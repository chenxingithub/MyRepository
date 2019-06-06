<?php
namespace app\common\controller;
use think\Request;
/**
 *
 * @author: dryangkun
 * @date: 2017/9/22 下午12:06
 */
class AucInterceptor {

    const CONF_SYS_ID = 'sys_id';
    const CONF_SYS_KEY = 'sys_key';
    const CONF_OPEN = 'open'; //如果系统是对外开放，则只能够访问open = 1的菜单列表
    const CONF_OPEN_LOGIN_URL = 'open_login_url'; //对外开放的系统的登录url
    const CONF_LOGIN_EXPIRE = 'login_expire'; //登录过期时间，单位秒，默认6个小时


    const CONF_DB_HOST = 'db_host';
    const CONF_DB_PORT = 'db_port';
    const CONF_DB_USER = 'db_user';
    const CONF_DB_PASS = 'db_pass';


    private static $envs = [
        'prod' => [
            'db_host' => '10.30.254.87',
            'db_user' => 'bnauc_read',
            'db_pass' => 'CCfSerQGxpl49',
        ],
        'dev' => [
            'db_host' => 'localhost',
            'db_user' => 'bn_aux',
            'db_pass' => 'uYOu3cboFCKr',
        ],
    ];

    private static $obj;

    /**
     * @param array $conf
     * @param callable $authorizer func($path, $params) => ($path, $params)
     * @param string $env
     * @param bool $debug
     * @return AucInterceptor
     */
    public static function init(array $conf, callable $authorizer, $env = 'prod', $debug = false) {
        if (empty($env)) {
            $env = 'prod';
        }
        if (!isset($conf[self::CONF_DB_HOST]) || !isset($conf[self::CONF_DB_USER])) {
            foreach (self::$envs[$env] as $k => $v) {
                $conf[$k] = $v;
            }
        }
        self::$obj = new AucInterceptor($conf, $authorizer, $debug);
        return self::$obj;
    }

    /**
     * @return AucInterceptor
     */
    public static function get() {
        return self::$obj;
    }

    private $conf_sys_id;
    private $conf_sys_key;
    private $conf_open;
    private $conf_open_login_url;
    private $conf_login_expire = 21600;
    private $conf_db_host;
    private $conf_db_port = 3306;
    private $conf_db_user;
    private $conf_db_pass;

    private $debug = false;
    private $authorizer;
    private $aucLoginUrl;
    private $aucSessKey = '__bnauc';

    /**
     * @var mysqli
     */
    private $db = null;

    private function __construct(array $conf, callable $authorizer, $debug) {
        foreach ($this as $k => &$v) {
            $k = substr($k, 5);
            if (isset($conf[$k])) {
                $v = $conf[$k];
            }
        }
        unset($v);
        $this->debug = $debug;
        $this->authorizer = $authorizer;
        $this->aucLoginUrl = $debug == false ? 'http://auc788.ibingniao.com' : 'http://devauc788.ibingniao.com';

        $this->conf_sys_id = (int)$this->conf_sys_id;
        if (empty($this->conf_sys_id) || empty($this->conf_sys_key)) {
            throw new InvalidArgumentException(sprintf('conf[%s/%s] is empty', self::CONF_SYS_ID, self::CONF_SYS_KEY));
        }
        if (empty($this->conf_db_host) || empty($this->conf_db_user)) {
            throw new InvalidArgumentException(sprintf('conf[%s/%s] is empty', self::CONF_DB_HOST, self::CONF_DB_USER));
        }
        //如果没有启动session，则session_start()
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function __destruct() {
        if ($this->db !== null) {
            mysqli_close($this->db);
        }
    }

    private function db() {
        if ($this->db === null) {
            $db = mysqli_connect($this->conf_db_host, $this->conf_db_user, $this->conf_db_pass, 'bingniao_auc', $this->conf_db_port);
            if ($db === false) {
                throw new Exception('连接auc-mysql失败, err = [' . mysqli_connect_errno() . ']' . mysqli_connect_error());
            }
            mysqli_set_charset($db, 'utf8');
            $this->db = $db;
        }
        return $this->db;
    }

    private function dbEscape($str) {
        $this->db();
        return mysqli_real_escape_string($this->db, $str);
    }

    /**
     * @param $sql
     * @return bool|array
     * @throws Exception
     */
    private function dbQuery($sql) {
        $this->db();
        $res = mysqli_query($this->db, $sql);
        if ($res === false) {
            throw new Exception(sprintf(
                '执行auc-sql失败, sql = %s, err = [%d]%s',
                str_replace("\n", ' ', $sql), mysqli_errno($this->db), mysqli_error($this->db)));
        } elseif (is_object($res)) {
            $ret = [];
            while ($v = mysqli_fetch_assoc($res)) {
                $ret[] = $v;
            }
            mysqli_free_result($res);
            return $ret;
        }
        return true;
    }

    private function getCurrentUrl() {
        static $url = null;
        if ($url === null) {
            $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
            $port = $_SERVER["SERVER_PORT"] == 80 ? '' : ":{$_SERVER["SERVER_PORT"]}";
            $url = "{$protocol}://{$_SERVER['SERVER_NAME']}{$port}{$_SERVER["REQUEST_URI"]}";
        }
        return $url;
    }

    private function quit($msg) {
        die(json_encode(['msg' => '该子页面/子操作没有权限', 'ret' => 9], JSON_UNESCAPED_UNICODE));
    }

    private function redirect($url, array $params = null) {
        if (!empty($params)) {
            $str = http_build_query($params);
            $url .= (strpos($url, '?') === false ? '?' : '&') . $str;
        }
        header("Location: {$url}");
        exit;
    }

    private function getCurrentUserMenus() {
        $menus = $_SESSION[$this->aucSessKey]['user_menus'];
        if (!empty($menus)) {
            return $menus;
        }

        $user_id = (int)$_SESSION[$this->aucSessKey]['user_id'];
        $menus = [];

        $open_invals = $this->conf_open ? '1' : '0';
        $sqls = [];
        //查用户对应角色授权的菜单，并且不在用户菜单黑名单中
        $sqls[] = "
            select 
              c.menu_id, c.menu_name, c.menu_url, c.visible, 
              d.menu_name as top_menu_name, d.top_menu_id 
            from user_role as a 
            join role_menu as b on(a.role_id = b.role_id) 
            join menu as c on(b.menu_id = c.menu_id)
            join top_menu as d on(d.system_id = c.system_id and d.top_menu_id=c.top_menu_id)
            where a.user_id = {$user_id} 
            and c.system_id = {$this->conf_sys_id}
            and c.menu_id not in(
                select menu_id from user_menu where user_id = {$user_id} and status = 0
            )
            and c.open in({$open_invals})
            order by d.sort asc, d.top_menu_id, c.sort asc, c.menu_id
        ";
        //查用户菜单白名单
        $sqls[] = "
            select 
              b.menu_id, b.menu_name, b.menu_url, b.visible, 
              c.menu_name as top_menu_name, c.top_menu_id 
            from user_menu as a 
            join menu as b on(a.menu_id = b.menu_id) 
            join top_menu as c on(b.top_menu_id = c.top_menu_id) 
            where a.user_id = {$user_id}
            and b.system_id = {$this->conf_sys_id} 
            and a.status = 1 
            and b.open in({$open_invals})
            order by c.sort asc, c.top_menu_id, b.sort asc, b.menu_id
        ";
        foreach ($sqls as $sql) {
            foreach ($this->dbQuery($sql) as $v) {
                $visible = !empty($v['visible']);
                $urls = explode("\n", trim($v['menu_url']));
                if (empty($urls) || empty($urls[0])) {
                    continue;
                }

                $v1 = &$menus[$v['top_menu_id']];
                if ($v1 === null) {
                    $v1 = [
                        'name' => $v['top_menu_name'],
                        'visible' => $visible,
                        'subs' => [
                            $v['menu_id'] => [
                                'name' => $v['menu_name'],
                                'url' => $urls,
                                'visible' => $visible,
                            ]
                        ]
                    ];
                } else {
                    if ($visible) {
                        $v1['visible'] = true;
                    }
                    $v1['subs'][$v['menu_id']] = [
                        'name' => $v['menu_name'],
                        'url' => $urls,
                        'visible' => $visible,
                    ];
                }
                unset($v1);
            }
        }
        //查系统均可访问的urls
        $sql2 = "select system_menu from system where system_id = {$this->conf_sys_id}";
        $system_menu = explode("\n", $this->dbQuery($sql2)[0]['system_menu']);
        if (!empty($system_menu) && !empty($system_menu[0])) {
            $menus['__common_menu'] = [
                'visible' => false,
                'subs' => [
                    '0' => [
                        'url' => $system_menu,
                        'visible' => false,
                    ]
                ],
            ];
        }

        return $_SESSION[$this->aucSessKey]['user_menus'] = $menus;
    }

    public function start() {
        if (empty($this->conf_open)) {
            $action = $_GET['bnauc'];
            switch ($action) {
                case 'logout':
                    //从子系统登出
                    unset($_SESSION[$this->aucSessKey]);
                    $this->redirect($this->aucLoginUrl . '/admin/index/logout');
                    break;
                case 'login':
                    //从auc登录跳转回子系统
                    $sign = $_GET['sign'];
                    $time = $_GET['time'];
                    $user_id = $_GET['user'];
                    $redirect = $_GET['redirect'];
                    if (time() - $time > 300) {
                        $this->quit('登录子系统校验超时');
                    }
                    if ($sign !== md5("{$this->conf_sys_id}#{$this->conf_sys_key}#{$time}#{$user_id}")) {
                        $this->quit('登录子系统校验失败');
                    }
                    $_SESSION[$this->aucSessKey] = [
                        'user_id' => $user_id,
                        'login_expire' => $this->conf_login_expire > 0 ? time() + $this->conf_login_expire : -1,
                    ];
                    self::get()->getCurrentUserInfo();
                    $this->redirect($redirect);
                    break;
            }
            //记录首次url，登录后用于跳转
            if (!$_SESSION['redirect']) {
                $_SESSION['redirect'] = [
                    'app_id' => $this->conf_sys_id,
                    'url' => $this->getCurrentUrl()
                ];
            }

            if (!$this->isLogined()) {
                $this->redirect($this->aucLoginUrl, [
                    'redirect' => $this->getCurrentUrl(),
                    'app_id' => $this->conf_sys_id,
                ]);
            }
        } else {
            if (!$this->isLogined()) {
                $doRedirect = false;
                foreach ($this->conf_open_login_url as $v) {
                    if (strpos($_SERVER["REQUEST_URI"], $v) === 0) {
                        $doRedirect = true;
                    }
                }
                if (!$doRedirect) {
                    $this->redirect($this->conf_open_login_url[0]);
                }
            }
        }

        if ($this->debug) { //调试模式不进行权限校验
            return;
        }

        list($currPath) = explode('?', $_SERVER['REQUEST_URI'], 2);
        $currPath = '/' . trim($currPath, '/');

        list($currPath, $currQParams) = call_user_func($this->authorizer, $currPath, $_GET);
        if (!is_array($currQParams)) {
            $currQParams = [];
        }

            $ct = strtolower(Request::instance()->controller());
            $ac = strtolower(Request::instance()->action());
            $currPath = "/{$ct}/{$ac}";

        $menus = $this->getCurrentUserMenus();
        $found = false;
        foreach ($menus as $v) {
            foreach ($v['subs'] as $v1) {
                foreach ($v1['url'] as $url) {
                    if (empty($url)) {
                        continue;
                    }

                    if (strpos($url, 'http') === false) {
                        list($path, $qStr) = explode('?', $url, 2);
                    } else {
                        $tmp = parse_url($url);
                        $path = $tmp['path'];
                        $qStr = $tmp['query'];
                        unset($tmp);
                    }

                    $path = '/' . trim($path, '/');
                    if (!empty($qStr)) {
                        parse_str($qStr, $qArr);
                    } else {
                        $qArr = [];
                    }

                    list($path, $qParams) = call_user_func($this->authorizer, $path, $qArr);
                    if (!is_array($qParams)) {
                        $qParams = [];
                    }
                    //if ($path === $currPath && $qParams == $currQParams) {
                    if ($path === $currPath) {
                        $found = true;
                        break;
                    }
                }
            }
        }
        if (!$found) {
            $this->quit('没有权限');
        }
    }

    public function isLogined() {
        if (($data = $_SESSION[$this->aucSessKey]) !== null) {
            $login_expire = (int)$data['login_expire'];
            if ($login_expire === -1 || $login_expire >= time()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array|bool [user_id, user_name, nickname, phone, email, group_name]
     */
    public function getCurrentUserInfo() {
        if (!$this->isLogined()) {
            return false;
        }
        if (isset($_SESSION[$this->aucSessKey]['user_info'])) {
            return $_SESSION[$this->aucSessKey]['user_info'];
        }

        $user_id = (int)$_SESSION[$this->aucSessKey]['user_id'];
        $sql = "
            select a.user_id, a.user_name, a.nickname, a.phone, a.email, b.group_name 
            from `user` as a 
            join `group` as b on(a.group_id = b.group_id) 
            where a.user_id = {$user_id}
        ";
        $sql1 = "
            select role_id
            from `user_role`
            where user_id = {$user_id}
        ";
        $userInfo = $this->dbQuery($sql)[0];
        $userInfo['roles'] = [];
        foreach ($this->dbQuery($sql1) as $v) {
            $userInfo['roles'][] = $v['role_id'];
        }
        return $_SESSION[$this->aucSessKey]['user_info'] = $userInfo;
    }

    public function getCurrentMenus() {
        if (!$this->isLogined()) {
            return false;
        }
        $menus = $this->getCurrentUserMenus();
        unset($menus['']);
        return $menus;
    }

    /**
     * 对外开放的系统内部调用登录方法
     * @param $user_name
     * @param $password
     * @return bool
     */
    public function sysOpenLogin($user_name, $password) {
        if (!empty($user_name) && !empty($password)) {
            $user_name = $this->dbEscape($user_name);
            $sql = "select user_id, user_name, password from `user` where user_name = '{$user_name}' and status = 1";
            $data = $this->dbQuery($sql)[0];
            $md_password = password_verify(md5($password), $data['password']);
            if ($md_password) {
                $_SESSION[$this->aucSessKey] = [
                    'user_id' => $data['user_id'],
                    'login_expire' => $this->conf_login_expire > 0 ? time() + $this->conf_login_expire : -1,
                ];
                return true;
            }
        }
        return false;
    }

    /**
     * 对外开发系统内部调用登出方法
     */
    public function sysOpenLogout() {
        unset($_SESSION[$this->aucSessKey]);
    }

    /**
     * 对外开发系统获取用户信息 获取手机号
     * @param $user_name
     * @param $password
     * @return bool|mixed
     */
    public function sysOpenGetUser($user_name, $password) {
        if (!empty($user_name) && !empty($password)) {
            $user_name = $this->dbEscape($user_name);
            $sql = "select user_name, password, phone, email from `user` where user_name = '{$user_name}' and status = 1";
            $data = $this->dbQuery($sql)[0];
            $md_password = password_verify(md5($password), $data['password']);
            if ($md_password) {
                return $data;
            }
        }

        return false;
    }

    /**
     * 获取用户信息列表，不需要登录
     * @param null|string $group_id 部门id
     * @return array [[user_id, user_name, nickname, phone, email, group_name]]
     */
    public function getUsersAll($group_id = null) {
        $group_str = $group_id > 0 ? ' and a.group_id = ' . intval($group_id) : '';
        $sql = "
            select a.user_id, a.user_name, a.nickname, a.phone, a.email, b.group_name 
            from `user` as a 
            join `group` as b on(a.group_id = b.group_id) 
            where a.status = 1{$group_str}
        ";
        $data = $this->dbQuery($sql);
        $users = [];
        foreach ($data as $v) {
            $users[$v['user_id']] = $v;
        }
        return $users;
    }

    /**
     * 根据条件获取单个用户信息
     * @param array $condition 查询条件['user_id', 'user_name', 'nickname', 'phone', 'email']
     * @return null|array [[user_id, user_name, nickname, phone, group_name]]
     */
    public function getUser($condition) {
        $col = ['user_id', 'user_name', 'nickname', 'phone', 'email'];
        $where_str = '';
        foreach ($condition as $k => $v) {
            if (in_array($k, $col)) {
                $where_str .= " and a.{$k} = '{$v}'";
            }
        }
        if (empty($where_str)) {
            return null;
        }
        $sql = "
            select a.user_id, a.user_name, a.nickname, a.phone, a.email, b.group_name 
            from `user` as a 
            join `group` as b on(a.group_id = b.group_id) 
            where a.status = 1{$where_str} limit 1
        ";
        $data = $this->dbQuery($sql)[0];
        if (empty($data)) {
            return null;
        }
        $sql = "
            select role_id
            from `user_role`
            where user_id = {$data['user_id']}
        ";
        $data['roles'] = [];
        foreach ($this->dbQuery($sql) as $v) {
            $data['roles'][] = $v['role_id'];
        }
        return $data;
    }

    /**
     * @param string $keyword 搜索字符串，不需要登录
     * @param int $limit
     * @return array [[user_id, user_name, nickname, phone, group_name]]
     */
    public function searchUsers($keyword, $limit = 20) {
        $keyword = $this->dbEscape($keyword);
        $sql = "
            select a.user_id, a.user_name, a.nickname, a.phone, a.group_name 
            from `user` as a 
            join `group` as b on(a.group_id = b.group_id) 
            where a.status = 1 
            and (a.user_name like '%{$keyword}%' or a.phone like '%{$keyword}%' or a.nickname like '%{$keyword}%')
            limit {$limit}
        ";
        return $this->dbQuery($sql);
    }
}