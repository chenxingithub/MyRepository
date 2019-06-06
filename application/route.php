<?php
// +----------------------------------------------------------------------
// | Description: 基础框架路由配置文件
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honghaiweb.com>
// +----------------------------------------------------------------------

use think\Route;



Route::domain( ( APP_STATUS == 'dev' ? 'devadmin' : 'admin223') ,function(){
	//基本配置
	Route::get('jpxxl/config','admin/Jpxxl/ConfigGet');	// 基本配置查询
	Route::put('jpxxl/config', 'admin/Jpxxl/ConfigPut'); // 基本配置修改

	//数据统计
	Route::get('statistics','admin/Statistics/StatisticsGet');	// 基本配置查询
	//焦点图
	Route::get('focus/get', 'admin/Focus/FocusGet'); //查询
	Route::put('focus/put/:id', 'admin/Focus/FocusPut'); // 修改
	Route::delete('focus/delete/:id', 'admin/Focus/FocusDelete'); // 删除
	Route::post('focus/post', 'admin/Focus/FocusPost'); // 添加

	//svip配置
	Route::get('svip/get', 'admin/Svip/SvipConfigGet'); //查询
	Route::put('focus/put/:id', 'admin/Focus/FocusPut'); // 修改
	Route::delete('focus/delete/:id', 'admin/Focus/FocusDelete'); // 删除
	Route::post('focus/post', 'admin/Focus/FocusPost'); // 添加

	//文章分类
    Route::get('artisan-type/get', 'admin/ArtisanType/ArtisanTypeGet'); // 查询
    Route::put('artisan-type/put/:id', 'admin/ArtisanType/ArtisanTypePut'); // 修改
    Route::post('artisan-type/post', 'admin/ArtisanType/ArtisanTypePost'); // 添加
    Route::delete('artisan-type/delete/:id', 'admin/ArtisanType/ArtisanTypeDelete'); //删除

    //文章列表管理模块
        //文章分类
    Route::get('artisan/get', 'admin/Artisan/ArtisanGet')/*->name('jpxxl.config.get')*/; // 查询
    Route::get('artisan/get/:id', 'admin/Artisan/ArtisanSingle')/*->name('jpxxl.config.get')*/; // 单个查询
    Route::put('artisan/put/:id', 'admin/Artisan/ArtisanPut')/*->name('jpxxl.config.get')*/; // 修改
    Route::post('artisan/post', 'admin/Artisan/ArtisanPost')/*->name('user.post')*/; // 添加
    Route::delete('artisan/delete/:id', 'admin/Artisan/ArtisanDelete')/*->name('user.delete')*/;

	//礼包分类
    Route::get('cdk-type/get', 'admin/CdkType/CdkTypeGet'); // 查询
    Route::put('cdk-type/put/:id', 'admin/CdkType/CdkTypePut'); // 修改
    Route::post('cdk-type/post', 'admin/CdkType/CdkTypePost'); // 添加
    Route::delete('cdk-type/delete/:id', 'admin/CdkType/CdkTypeDelete'); //删除

    //ckd列表管理模块
    Route::get('cdk-list/get', 'admin/CdkList/CdkListGet')/*->name('jpxxl.config.get')*/; // 查询
    Route::put('cdk-list/put/:id', 'admin/CdkList/CdkTypePut')/*->name('jpxxl.config.get')*/; // 修改
    Route::post('cdk-list/post', 'admin/CdkList/CdkListPost')/*->name('user.post')*/; // 添加
    Route::put('cdk-list/delete-batch', 'admin/CdkList/CdkListBatchDelete')/*->name('user.delete')*/;
    //批量删除
    Route::delete('cdk-list/delete/:id', 'admin/CdkList/CdkListDelete')/*->name('user.delete')*/;
    Route::get('cdk-list/excel', 'admin/CdkList/ExcelGet'); //Excel下载

	//周签到礼品列表模块
    Route::get('week-sign-gift/get', 'admin/WeekSignGift/WeekSignGiftGet'); // 查询
    Route::post('week-sign-gift', 'admin/WeekSignGift/Post'); // 添加
    Route::put('week-sign-gift/put/:id', 'admin/WeekSignGift/WeekSignGiftPut'); // 修改
    Route::delete('week-sign-gift/:id', 'admin/WeekSignGift/Delete'); //删除

	//日签到礼品列表模块
    Route::get('day-sign-gift/get', 'admin/DaySignGift/DaySignGiftGet'); // 查询
    Route::post('day-sign-gift', 'admin/DaySignGift/Post'); // 添加
    Route::put('day-sign-gift/put/:id', 'admin/DaySignGift/DaySignGiftPut'); // 修改
    Route::delete('day-sign-gift/:id', 'admin/DaySignGift/Delete'); //删除

    //签到cdk模块
    Route::get('sign-in-cdk', 'admin/SignInCdk/Get'); // 查询
    Route::post('sign-in-cdk', 'admin/SignInCdk/Post'); // 添加
    Route::put('sign-in-cdk/delete-batch', 'admin/SignInCdk/BatchDelete')/*->name('user.delete')*/;
    //批量删除
    Route::delete('sign-in-cdk/:id', 'admin/SignInCdk/Delete')/*->name('user.delete')*/;

	//游戏列表系列路由
	Route::get('games/get/:id', 'admin/Games/GamesSingleGet'); //查询单个游戏信息
	Route::get('games/get', 'admin/Games/GamesGet'); //查询
	Route::put('games/put/:id', 'admin/Games/GamesPut'); // 修改
	Route::delete('games/delete/:id', 'admin/Games/GamesDelete'); // 删除
	Route::post('games/post', 'admin/Games/GamesPost'); // 添加

	//权限控制系列路由
	Route::get('jurisdiction', 'admin/Jurisdiction/JurisdictionGet'); //查询
	Route::post('jurisdiction', 'admin/Jurisdiction/JurisdictionPost'); // 添加
	Route::put('jurisdiction/:id', 'admin/Jurisdiction/JurisdictionPut'); // 修改
	Route::delete('jurisdiction/:id', 'admin/Jurisdiction/JurisdictionDelete'); // 删除
    //微信模块 开始
		//微信基础配置
    Route::get('wechat/configure', 'admin/WechatConfigure/GetConfigure')/*->name('jpxxl.config.get')*/; // 查询
    Route::put('wechat/configure/:id', 'admin/WechatConfigure/PutConfigure')/*->name('jpxxl.config.get')*/; // 修改
    	//微信菜单
    Route::get('wechat/menu', 'admin/WechatMenu/GetMenu');// 查询
    Route::put('wechat/menu', 'admin/WechatMenu/PutMenu');// 修改
    	//微信关键字回复
    Route::get('wechat/keyword', 'admin/WechatKeyword/GetKeyword');// 关键字查询
    Route::put('wechat/keyword-reply/:id', 'admin/WechatKeyword/KeywordReplyPut'); // 修改
    Route::post('wechat/keyword-reply', 'admin/WechatKeyword/KeywordReplyPost'); // 添加
    Route::delete('wechat/keyword-reply', 'admin/WechatKeyword/KeywordReplyDelete'); //删除
    Route::get('wechat/reply/:id', 'admin/WechatKeyword/GetReply');// 回复查询

    //微信模块 结束

    //简历库
    Route::get('resume-list', 'admin/ResumeList/Get'); //查询
    Route::get('resume-download/:id', 'admin/ResumeList/Download'); //查询

    //游戏精灵
    	//精灵关键字分类
    Route::get('spirit-type', 'admin/SpiritType/Get'); //查询
    Route::post('spirit-type', 'admin/SpiritType/Post'); //添加
    Route::put('spirit-type/:id', 'admin/SpiritType/Put'); // 修改
    Route::delete('spirit-type/:id', 'admin/SpiritType/Delete'); //删除

    	//精灵关键字
    Route::get('spirit/keyword', 'admin/SpiritKeyword/Get');// 关键字查询
    Route::post('spirit/keyword', 'admin/SpiritKeyword/Post'); //添加
    Route::post('spirit/keyword-import', 'admin/SpiritKeyword/Import'); //批量导入
    Route::put('spirit/keyword', 'admin/SpiritKeyword/Put'); // 修改
    Route::delete('spirit/keyword', 'admin/SpiritKeyword/Delete'); //删除

        //游戏精灵板块管理
    Route::get('spirit/plate', 'admin/SpiritPlate/Get'); //查询
    Route::post('spirit/plate', 'admin/SpiritPlate/Post'); //添加
    Route::put('spirit/plate/:id', 'admin/SpiritPlate/Put'); // 修改
    Route::delete('spirit/plate/:id', 'admin/SpiritPlate/Delete'); //删除
    	//游戏精灵关键字组件
    Route::get('plate/keyword-assembly', 'admin/SpiritPlate/KeywordAssemblyGet'); //查询
    Route::post('plate/keyword-assembly', 'admin/SpiritPlate/KeywordAssemblyPost'); //添加
    Route::delete('plate/keyword-assembly/:id', 'admin/SpiritPlate/KeywordAssemblyDelete'); //删除
    Route::put('plate/keyword-assembly/:id', 'admin/SpiritPlate/KeywordAssemblyPut'); // 修改

    	//游戏精灵基础配置
    Route::get('spirit/configure/single', 'admin/SpiritConfigure/SingleGet'); //单个查询
    Route::delete('spirit/channel-configure/:id', 'admin/SpiritConfigure/ChannelConfigureDelete'); //删除
    Route::put('spirit/configure/:id', 'admin/SpiritConfigure/Put'); // 修改


        	//游戏精灵反馈
    Route::get('spirit/feedback', 'admin/SpiritFeedback/Get'); //查询
    Route::delete('spirit/feedback/:id', 'admin/SpiritFeedback/Delete'); //删除
    Route::post('spirit/feedback', 'admin/SpiritFeedback/Post'); //添加
    Route::put('spirit/feedback/:id', 'admin/SpiritFeedback/Put'); // 修改
    Route::get('spirit/feedback-dialogue', 'admin/SpiritFeedback/DialogueGet'); //反馈对话查询
    Route::get('spirit/feedback/excel', 'admin/SpiritFeedback/ExcelGet'); //Excel下载
    
        	//游戏精灵检索
    Route::get('spirit/retrieval', 'admin/SpiritRetrieval/Get'); //查询
    Route::delete('spirit/retrieval/:id', 'admin/SpiritRetrieval/Delete'); //删除
    Route::post('spirit/retrieval', 'admin/SpiritRetrieval/Post'); //添加
    Route::put('spirit/retrieval/:id', 'admin/SpiritRetrieval/Put'); // 修改
    Route::get('spirit/retrieval-dialogue', 'admin/SpiritRetrieval/DialogueGet'); //检索对话查询
    Route::get('spirit/retrieval/excel', 'admin/SpiritRetrieval/ExcelGet'); //Excel下载

    		//冰鸟转盘
    Route::get('turntable-commodity', 'admin/TurntableCommodity/Get'); //查询
    Route::post('turntable-commodity', 'admin/TurntableCommodity/Post'); //添加
    Route::delete('turntable-commodity/:id', 'admin/TurntableCommodity/Delete'); //删除
    Route::put('turntable-commodity/:id', 'admin/TurntableCommodity/Put'); // 修改
    Route::get('turntable-record', 'admin/TurntableRecord/Get'); //查询抽奖记录
    Route::put('turntable-record/:id', 'admin/TurntableRecord/Put'); // 修改查询抽奖记录
    Route::delete('turntable-record/:id', 'admin/TurntableRecord/Delete'); //删除查询抽奖记录
    Route::get('turntable-user', 'admin/TurntableUser/Get'); //查询抽奖用户列表
    Route::put('turntable-user/:id', 'admin/TurntableUser/Put'); // 修改抽奖用户列表
    Route::post('turntable-user', 'admin/TurntableUser/Post'); //抽奖用户列表添加
    Route::delete('turntable-user/:id', 'admin/TurntableUser/Delete'); //删除抽奖用户列表

        		//九品-转盘
    Route::get('jp-turntable-commodity', 'admin/JpTurntableCommodity/Get'); //查询
    Route::post('jp-turntable-commodity', 'admin/JpTurntableCommodity/Post'); //添加
    Route::delete('jp-turntable-commodity/:id', 'admin/JpTurntableCommodity/Delete'); //删除
    Route::put('jp-turntable-commodity/:id', 'admin/JpTurntableCommodity/Put'); // 修改
    Route::get('jp-turntable-record', 'admin/JpTurntableRecord/Get'); //查询抽奖记录
    Route::put('jp-turntable-record/:id', 'admin/JpTurntableRecord/Put'); // 修改查询抽奖记录
    Route::delete('jp-turntable-record/:id', 'admin/JpTurntableRecord/Delete'); //删除查询抽奖记录

    		   // 九品转盘 - 用户奖励
	Route::get('jp-exchange-record', 'admin/ExchangeRecord/Get'); // 查询
	Route::delete('jp-exchange-record/:id', 'admin/ExchangeRecord/Delete'); //删除
	Route::put('jp-exchange-record/:id', 'admin/ExchangeRecord/Put'); // 修改

				// 开服表
	Route::get('openservice', 'admin/OpenService/Get'); // 查询
	Route::post('openservice', 'admin/OpenService/Post'); // 添加
	Route::delete('openservice/:id', 'admin/OpenService/Delete'); //删除
	Route::put('openservice/:id', 'admin/OpenService/Put'); // 修改

				//官网礼包配置
	Route::get('config/official-gift/get', 'admin/Config/OfficialGiftGet'); // 查询
	Route::post('config/official-gift', 'admin/Config/OfficialGiftPost'); // 添加
	Route::put('config/official-gift/:id', 'admin/Config/OfficialGiftPut'); // 修改

	// 冰鸟游戏盒子
		//焦点图
	Route::get('wechat-app/focus', 'admin/WechatApp/FocusGet'); // 查询
	Route::post('wechat-app/focus', 'admin/WechatApp/FocusPost'); // 添加
	Route::delete('wechat-app/focus/:id', 'admin/WechatApp/FocusDelete'); //删除
	Route::put('wechat-app/focus/:id', 'admin/WechatApp/FocusPut'); // 修改
	
		//游戏列表
	Route::get('wechat-app/games', 'admin/WechatApp/GamesGet'); // 查询
	Route::post('wechat-app/games', 'admin/WechatApp/GamesPost'); // 添加
	Route::delete('wechat-app/games/:id', 'admin/WechatApp/GamesDelete'); //删除
	Route::put('wechat-app/games/:id', 'admin/WechatApp/GamesPut'); // 修改

    //上传文件接口
	Route::post('upload/image', 'admin/Upload/UploadImage'); // 图片上传;
	Route::post('upload/xlsx', 'admin/Upload/UploadXlsx'); // 图片上传;


##################################我是分隔线########################################
	
	// 【基础】登录视图
	Route::get('login','admin/base/loginView');
	// 【基础】登录
	Route::post('login','admin/base/login');
	// 【基础】记住登录
	Route::post('base/relogin','admin/base/relogin');
	// 【基础】修改密码
	Route::post('base/setInfo','admin/base/setInfo');
	// 【基础】退出登录
	Route::post('base/logout','admin/base/logout');
	// 【基础】获取配置
	Route::post('base/getConfigs','dmin/base/getConfigs');
	// 【基础】获取验证码
	Route::get('base/getVerify','admin/base/getVerify');
	// 【基础】上传图片
	Route::post('upload','admin/upload/index');
	// 保存系统配置
	Route::post('systemConfigs','admin/systemConfigs/save');
	// 【规则】批量删除
	Route::post('rules/deletes','admin/rules/deletes');
	// 【规则】批量启用/禁用
	Route::post('rules/enables','admin/rules/enables');
	// 【用户组】批量删除
	Route::post('groups/deletes','admin/groups/deletes');
	// 【用户组】批量启用/禁用
	Route::post('groups/enables','admin/groups/enables');
	// 【用户】批量删除
	Route::post('users/deletes','admin/users/deletes');
	// 【用户】批量启用/禁用
	Route::post('users/enables','admin/users/enables');
	// 【菜单】批量删除
	Route::post('menus/deletes','admin/menus/deletes');
	// 【菜单】批量启用/禁用
	Route::post('menus/enables','admin/menus/enables');
	// 【组织架构】批量删除
	Route::post('structures/deletes','admin/structures/deletes');
	// 【组织架构】批量启用/禁用
	Route::post('structures/enables','admin/structures/enables');
	// 【部门】批量删除
	Route::post('posts/deletes','admin/posts/deletes');
	// 【部门】批量启用/禁用
	Route::post('posts/enables','admin/posts/enables');

    Route::miss('Auth/Index');
});
##################################我是分隔线########################################
//接口篇路由
Route::group('yh-cms-api',function(){
	//焦点图
	Route::get('focus', 'api/Focus/FocusGet'); //焦点图查询
	Route::get('Kki', 'api/Focus/Kki'); //焦点图查询
	//游戏信息
	Route::get('game-info', 'api/Game/GameInfo');//游戏信息
	Route::get('game-list', 'api/Game/GameList');//游戏列表
	//文章
	Route::get('artisan-list', 'api/Artisan/ArtisanList');//文章列表
	Route::put('artisan-list/:id', 'api/Artisan/ArtisanListPut');//文章列表-修改
	Route::get('artisan-info', 'api/Artisan/ArtisanInfo');//根据分类id获取文章列表信息
	Route::get('artisan-single/:id', 'api/Artisan/ArtisanSingle');//根据文章id获取对应文章信息

	Route::get('artisan-type-info', 'api/ArtisanType/ArtisanTypeInfo');//文章分类信息查询
	Route::get('artisan-type-children/:id', 'api/ArtisanType/ArtisanTypeChildren');//查询文章分类子分类
	Route::get('open-service', 'api/OpenService/List');//查询开服表

	Route::get('cdk-type-list', 'api/CdkType/CdkTypeGet');//cdk分类列表查询

	Route::get('cdk-list-receive/:id', 'api/CdkList/CdkListReceive');//cdk码领取接口

	Route::any('wechat-serve/:id', 'api/Wechat/Serve'); //微信服务接入

	Route::any('wechat-serve2/:id', 'api/Wechat/Serve2'); //微信服务接入(测试)

	Route::get('wechat-cdk-view', 'api/WechatActivity/CdkView'); //微信服务接入

	Route::post('resume', 'api/ResumeList/Post'); //简历投递

	Route::get('spirit-plate', 'api/SpiritPlate/Get'); //精灵板块列表查询

	Route::get('spirit-configure', 'api/SpiritConfigure/Get'); //精灵基础信息查询

	Route::get('spirit-plate-assembly', 'api/SpiritPlateAssembly/Get'); //精灵规则组件查询

	Route::get('spirt-keyword', 'api/SpiritKeyword/Get'); //精灵规则组件查询

	Route::post('spirt-feedback', 'api/SpiritFeedbacks/Post'); //精灵反馈提交

	Route::get('spirt-feedback-reply', 'api/SpiritFeedbacks/ReplyGet'); //精灵反馈回复查询

	Route::get('spirt-feedback-read', 'api/SpiritFeedbacks/ReadGet'); //精灵反馈查询回复读取

	Route::post('spirt-feedback-read', 'api/SpiritFeedbacks/ReadPost'); //查询回复处理

	Route::post('user-invoice', 'api/UserInvoice/Post'); //用户发票申请

	Route::post('user/login', 'api/User/Login');//cdk分类列表查询

	Route::post('user/binding-phone', 'api/User/BindingPhone');//用户绑定手机号

	Route::post('customer-service/appeal', 'api/CustomerService/AppealPost');//客服申诉提交

	Route::post('customer-service/invoice', 'api/CustomerService/InvoicePost');//客服发票提交

	Route::get('customer-service/appeal', 'api/CustomerService/AppealGet');//客服申诉查询

	Route::get('customer-service/username-appeal', 'api/CustomerService/UserNameGet');//客服username申诉查询

	Route::get('customer-service-article-type', 'api/CustomerServiceArticleType/Get');//客服文章分类列表查询

	Route::get('customer-service-article-type-subgrade/:id', 'api/CustomerServiceArticleType/SubgradeGet');//客服文章分类下的一级文章查询

	Route::get('customer-service-article/:id', 'api/CustomerServiceArticle/Get');//客服文章查询

	Route::get('turntable-commodity', 'api/TurntableCommodity/Get');//冰鸟大转盘奖品查询

	Route::post('turntable-commodity-select', 'api/TurntableCommodity/SelectPost');//冰鸟大转盘奖品获取

	Route::post('turntable-commodity-login', 'api/TurntableCommodity/Login');//冰鸟大转盘用户登录

	Route::get('turntable-record', 'api/TurntableCommodity/Record');//冰鸟大转盘用户抽奖记录

	Route::get('turntable-user-info', 'api/TurntableCommodity/UserInfo');//冰鸟大转盘用户信息

	Route::get('system-config-gift', 'api/Config/Gift');//官网配置
	
	
	//微信相关路由
	Route::get('wechat-login', 'api/WechatActivity/Login');//微信授权获取token

	Route::get('wechat-share-config', 'api/WechatActivity/ShareConfig');//CMS微信分享配置

	Route::get('day-sign-gift', 'api/SignIn/DaySignGift');//日签到奖品列表

	Route::get('week-sign-gift', 'api/SignIn/WeekSignGift');//周签到奖品列表

	Route::post('day-sign-in', 'api/SignIn/DaySign');//日签到

	Route::post('week-sign-in', 'api/SignIn/WeekSign');//周签到

	Route::get('day-sign-in-record', 'api/SignIn/DayRecord');//日签到记录

	Route::post('upload/image', 'api/Upload/UploadImage'); // 图片上传;
	Route::get('getverify', 'api/Test/getVerify'); // 图片上传;
	Route::get('verify', 'api/Test/verify'); // 图片上传;
	Route::post('send', 'api/Test/send'); // 图片上传;
	Route::post('test', 'api/Test/test'); // 图片上传;

	// Route::get('jp-turntable/user', 'api/JpTurntable/User'); // 九品-获取用户抽奖次数与任务完成度

	// Route::post('jp-turntable/select-post', 'api/JpTurntable/SelectPost'); // 九品-抽奖
	
	// Route::get('jp-turntable/commodity', 'api/JpTurntable/Get'); // 九品-商品列表

	// Route::get('jp-turntable/exchange-record', 'api/JpTurntable/ExchangeRecordGet'); // 九品-奖励查询

	// Route::post('jp-turntable/integral-exchange', 'api/JpTurntable/IntegralExchangePost'); // 九品-积分兑换

	// Route::post('jp-turntable/receipt-information', 'api/JpTurntable/ReceiptInformation'); // 九品-收货地址

	// Route::get('jp-turntable/wchat-share-configure', 'api/JpTurntable/WechatShareConfigure'); // 九品-分享配置

	// Route::post('jp-turntable/wchat-share', 'api/JpTurntable/WechatShare'); // 九品-分享
	
});

	//冰鸟游戏盒子小程序api

Route::group('wechat-app-api',function(){
	//焦点图
	Route::get('initial', 'api/WechatApp/InitialGet'); //焦点图查询
	//游戏列表
	Route::get('games', 'api/WechatApp/GamesGet'); //游戏列表查询
	//游戏列表推荐
	Route::get('recommend-games', 'api/WechatApp/RecommendGamesGet'); //游戏列表查询
});


//审核篇路由 

Route::group('examineadmin',function(){
	// 【基础】登录视图
	Route::get('login','examineadmin/base/loginView');
	// 【基础】登录
	Route::post('login','examineadmin/base/login');
	// 【基础】登出
	Route::post('logout','examineadmin/base/logout');
	//敏感字过滤
	Route::get('sensitive-word','examineadmin/SensitiveWord/Get');//查询

	Route::post('sensitive-word','examineadmin/SensitiveWord/Post');//添加

	Route::delete('sensitive-word/:id', 'examineadmin/SensitiveWord/Delete'); //删除

	Route::put('sensitive-word/:id', 'examineadmin/SensitiveWord/Put'); // 修改

	Route::miss('examineadmin/Auth/Index');
});

 Route::miss('api/Auth/Index');