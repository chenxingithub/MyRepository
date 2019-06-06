
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        公众号
        <small>{{GameInfo.game_name}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><router-link to="/admin/user" tag="a"><i class="fa fa-map-o"></i> {{GameInfo.game_name}}</router-link></li>
        <li class="active">列表2</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">搜索选择</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">

                            </div>
                        </div>

                        <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ myName }}</h3>
                            <div class="box-tools">
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="table-responsive no-padding">
                        <div class="row">
                        <div class="col-md-12">
                        <el-form class="small-space" :model="temp" :rules="rules" ref="temp"  label-position="left" label-width="200px" style='width: 800px; margin-left:100px;'>
                            <el-form-item label="URL(服务器地址)" prop="server_url">
                                <el-input style="width: 50%;"  v-model="temp.server_url"></el-input>
                            </el-form-item>
                            <el-form-item label="Token" prop="token">
                                <el-input style="width: 50%;"  v-model="temp.token"></el-input>
                            </el-form-item>
                            <el-form-item label="公众号名称" prop="name">
                                <el-input style="width: 50%;"  v-model="temp.name"></el-input>
                            </el-form-item>
                            <el-form-item label="公众号原始id" prop="original_id">
                                <el-input style="width: 50%;"  v-model="temp.original_id"></el-input>
                            </el-form-item>
                            <el-form-item label="微信号" prop="wechat_num">
                                <el-input style="width: 50%;"  v-model="temp.wechat_num"></el-input>
                            </el-form-item>
                            <el-form-item label="头像地址">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="headPortrait"
                                        :show-file-list="false"
                                        :headers="headers"
                                        :on-success="handleHeadPortraitSuccess"
                                        :before-upload="beforeAvatarUpload">
                                    <img v-if="temp.head_portrait" :src="ImgPrefix+temp.head_portrait" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                </el-upload>
                            </el-form-item>
                            <el-form-item label="AppID" prop="app_id">
                                <el-input style="width: 50%;"  v-model="temp.app_id"></el-input>
                            </el-form-item>
                            <el-form-item label="AppSecret" prop="app_secret">
                                <el-input style="width: 50%;"  v-model="temp.app_secret"></el-input>
                            </el-form-item>
                            <el-form-item label="二维码">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="qrCode"
                                        :show-file-list="false"
                                        :headers="headers"
                                        :on-success="handleQrCodeSuccess"
                                        :before-upload="beforeAvatarUpload">
                                    <img v-if="temp.qr_code" :src="ImgPrefix+temp.qr_code" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                </el-upload>
                            </el-form-item>
                            <el-form-item label="微信号类型" prop="type">
                                  <el-select v-model="temp.type" placeholder="请选择">
                                      <el-option label="订阅号" value=1></el-option>
                                      <el-option label="认证订阅号" value="2"></el-option>
                                      <el-option label="服务号" value="3"></el-option>
                                      <el-option label="认证服务号" value="4"></el-option>
                                  </el-select>
                            </el-form-item>
                          <el-form-item label="微信接入状态" prop="access_state">
                                <el-radio-group v-model="temp.access_state">
                                  <el-radio label="0">等待接入</el-radio>
                                  <el-radio label="1">已接入</el-radio>
                                </el-radio-group>
                          </el-form-item>
                        </el-form>
                        </div>  
                        </div>
                           <div class="row">
                                <div class="col-md-4">
                                </div>
                               <div class="col-md-8">
                                <el-button @click="dialogFormVisible = false">取 消</el-button>
                                <el-button type="primary" @click="update('temp')">确 定</el-button>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
<div class="box-footer clearfix">
<!--                         <span slot="footer" class="dialog-footer">
    <el-button @click="dialogFormVisible = false">取 消</el-button>
    <el-button v-if="dialogStatus=='create'" type="primary" @click="create('temp')">确 定</el-button>
    <el-button v-else type="primary" @click="update('temp')">确 定</el-button>
</span> -->
<!--     <div class="pull-right">
    <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="listQuery.page" :page-sizes="[10,20,30, 50]"
                   :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="total">
    </el-pagination>
</div> -->
</div>
                    </div>
                    <!-- /.box -->
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
  </div>
</template>
<style>
  .el-upload__input {
  		display: none !important;;
  }
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #20a0ff;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }

</style>
<script>
import { mapState, mapActions } from 'vuex';
/*import UE from '../../ue/ue.vue';*/
export default {
  data() {
    return {
            ImgPrefix:process.env.IMAGE_PREFIX,
            GameInfo: '',
            loading:false,
            config: {
              initialFrameWidth: null,
              initialFrameHeight: 350
            },
    		myName: '焦点图列表',
    		loading: true,
    		items: [],
    		total:null,
            listQuery: {
                game_id:this.$route.params.id
            },
            PositionType: [
                {
                    value: null,
                    label: '请选择',
                },
                {
                    value: 1,
                    label: '主页',
                },
                {
                    value: 2,
                    label: '新闻页',
                }
            ],
            PositionTypeX: [
                {
                    value: 1,
                    label: '顶部',
                },
                {
                    value: 2,
                    label: '游戏特色',
                },
                {
                    value: 3,
                    label: '实景截图',
                }
            ],
            textMap: {
                update: '编辑',
                create: '创建'
            },
            dialogFormVisible:false,
            dialogStatus: '',
            headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
            temp: {
                id: '',
                server_url: '',
                token: '',
                name: '',
                original_id:'',
                wechat_num:'',
                app_secret:'',
                app_id:'',
                head_portrait:'',
                qr_code:'',
                type:'',
                access_state:'',
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isGIF = file.type === 'image/gif';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG && !isPNG && !isGIF) {
                    this.$message.error('上传头像图片只能是 JPG|PNG|GIF 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return (isJPG || isPNG || isGIF) && isLt2M;
            },
            rules: {
                server_url: [
                    { required: true, message: '请输入URL(服务器地址)', trigger: 'blur' }
                ],
                token: [
                    { required: true, message: '请输入Token', trigger: 'blur' }
                ],
                name: [
                    { required: true, message: '请输入公众号名称', trigger: 'blur' }
                ],
                original_id: [
                    { required: true, message: '请输入微信原始Id', trigger: 'blur' }
                ],
                wechat_num: [
                    { required: true, message: '请输入微信号', trigger: 'blur' }
                ],
                app_id: [
                    { required: true, message: '请输入微信AppID', trigger: 'blur' }
                ],
                app_secret: [
                    { required: true, message: '请输入微信AppSecret', trigger: 'blur' }
                ],
                type:[
                    { required: true, message: '请选择微信公众号类型', trigger: 'change' }
                ],
                access_state: [
                    { required: true, message: '请填写微信接入状态', trigger: 'blur' }
                ]
            },
            headPortrait:'/upload/image',
            qrCode:'/upload/image',
    }
  },
  /*components: {UE},*/
  methods: {
            loadData() {
            	this.loading = true
                let url = '/wechat/configure';
                this.$axios.get(url, {
                    headers: this.$store.state.headers,
                    params: this.listQuery
                }).then(response => {
                    console.log(response.data);
                    if (response.data) {
                        this.temp.id = response.data.id
                        this.temp.server_url = response.data.server_url
                        this.temp.token = response.data.token
                        this.temp.name = response.data.name
                        this.temp.original_id = response.data.original_id
                        this.temp.wechat_num = response.data.wechat_num
                        this.temp.app_secret = response.data.app_secret
                        this.temp.app_id = response.data.app_id
                        this.temp.head_portrait = response.data.head_portrait
                        this.temp.qr_code = response.data.qr_code
                        this.temp.type = response.data.type.toString()
                        this.temp.access_state = response.data.access_state.toString()
                    };
                    this.loading = false
                })
            },
          getUEContent() {
                let content = this.$refs.ue.getUEContent();
/*                this.$notify({
                  title: '获取成功，可在控制台查看！',
                  message: content,
                  type: 'success'
                });*/
                alert(content);
                console.log(content)
              },
            handleSizeChange(val) {
                this.listQuery.limit = val;
                this.loadData();
            },
            handleCurrentChange(val) {
                this.listQuery.page = val;
                this.loadData();
            },
            handleFilter() {
                this.loadData();
            },
            handleHeadPortraitSuccess(res, file) {
             	console.log(res);
                if(res.url){
                    this.temp.head_portrait = res.url;
                }
            },
            handleQrCodeSuccess(res, file) {
                console.log(res);
                if(res.url){
                    this.temp.qr_code = res.url;
                }
            },
            handleCreate(){
                this.dialogStatus = 'create'
                this.temp.id = 1
                this.temp.picture = ''
                this.temp.title = ''
                this.temp.jump_url= ''
                this.temp.position = 1
                this.temp.terminal = '0'
                this.dialogFormVisible = true
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/user/excel?q='+this.listQuery.q)
            },
            handleUpdate(data) {
                this.dialogStatus = 'update';
                this.temp.id = data.id
                this.temp.picture = data.picture
                this.temp.title = data.title
                this.temp.jump_url= data.jump_url
                this.temp.position = data.position
                this.temp.terminal = data.terminal.toString()
                this.dialogFormVisible = true
/*                this.$axios.get('/admin/user/' + data.id)
                    .then((response) => {
                        if(response.status === 200){
                            this.temp.id = response.data.id
                            this.temp.name = response.data.username
                            this.temp.nickname = response.data.name
                            this.temp.email = response.data.email
                            this.temp.avatar = response.data.picture
                            this.dialogFormVisible = true
                        }
                    })*/
            },
            handleDelete(data) {
                this.$confirm('此操作将永久删除该焦点图, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/admin/focus/delete/' + data.id)
                        .then((response) => {
                            if(response.status === 200){
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'删除成功':response.data.message),
                                    type: 'success'
                                });
                                this.loadData();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'删除失败':response.data.message),
                                    type: 'error'
                                });
                            }
                        }, (response) => {
                            this.$message({
                                showClose: true,
                                message: '删除失败',
                                type: 'error'
                            });
                        })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            handleUpload(){
                this.dialogUserVisible = true
            },
            update(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.put('/wechat/configure/' + this.$route.params.id, {
                            server_url: this.temp.server_url,
                            token: this.temp.token,
                            name: this.temp.name,
                            original_id: this.temp.original_id,
                            wechat_num: this.temp.wechat_num,
                            head_portrait: this.temp.head_portrait,
                            app_id: this.temp.app_id,
                            app_secret: this.temp.app_secret,
                            qr_code: this.temp.qr_code,
                            type: this.temp.type,
                            access_state: this.temp.access_state,
                            action: 'changeContent'
                        })
                            .then((response) => {
                                if(response.status === 200){
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0 ? '修改成功！' : response.data.message),
                                        type: 'success'
                                    });
                                    this.dialogFormVisible = false
                                    this.loadData();
                                }else{
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0?'修改失败！':response.data.message),
                                        type: 'error'
                                    });
                                }
                            }, response => {
                                if (response.status === 422) {
                                    let msg = response.data.password.size === 0?'':response.data.password
                                    this.$message({
                                        showClose: true,
                                        message: msg,
                                        type: 'error'
                                    });
                                }
                            })
                    } else {
                        return false;
                    }
                });
            },
            create(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.post('/admin/focus/post', {
                            picture: this.temp.picture,
                            title: this.temp.title,
                            jump_url: this.temp.jump_url,
                            position: this.temp.position,
                            terminal: this.temp.terminal,
                            game_id:this.$route.params.id,
                        })
                            .then((response) => {
                                if(response.status === 200){
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0 ? '操作成功' : response.data.message),
                                        type: 'success'
                                    });
                                    this.dialogFormVisible = false
                                    this.loadData();
                                }else{
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0?'操作失败':response.data.message),
                                        type: 'error'
                                    });
                                }
                            }, response => {
                                if (response.status === 422) {
                                    let msg = ''
                                    $.each(response.data, function(key, val) {
                                        msg += val
                                    });
                                    this.$message({
                                        showClose: true,
                                        message: msg,
                                        type: 'error'
                                    });
                                }
                            })
                    } else {
                        return false;
                    }
                });
            },
            handleModifyStatus(data, status) {
                this.$axios.put('/admin/focus/put/' + data.id, {
                    status: status,
                    action: 'changeStatus'
                })
                    .then((response) => {
                        console.log(response);
                            if(response.status === 200){
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'锁定/解锁，成功':response.data.message),
                                    type: 'success'
                                });
                                this.loadData();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'锁定失败':response.data.message),
                                    type: 'error'
                                });
                            }
                    })
            },
            handleModifySort(data) {
                this.$axios.put('/admin/focus/put/' + data.id, {
                    sort: data['sort'],
                    action: 'changeSort'
                })
                    .then((response) => {
                            if(response.status === 200){
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'修改排序成功！':response.data.message),
                                    type: 'success'
                                });
                                this.loadData();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'修改排序失败！':response.data.message),
                                    type: 'error'
                                });
                            }
                    })
            },
        },
        created() {
            //获取游戏信息
            let GamesInfoUrl = '/admin/games/get/'+this.$route.params.id;
            this.$axios.get(GamesInfoUrl, {
            }).then(response => {
                this.GameInfo = response.data;
            })
            this.loadData()
        },
        filters: {
            statusFilter(status) {
                const statusMap = {
                    0: 'success',
                    1: 'gray',
                };
                return statusMap[parseInt(status)]
            },
            statusShow(status){
                const statusMap = {
                    0:'正常',
                    1:'禁用'
                };
                return statusMap[parseInt(status)]
            },
            terminalShow(terminal){
                const statusMap = {
                    0:'全部',
                    1:'PC端',
                    2:'移动端'
                };
                return statusMap[parseInt(terminal)]
            },
            positionShow(status){
                const statusMap = {
                    1:'主页',
                    2:'新闻'
                };
                return statusMap[parseInt(status)]
            }
            
        },
}
</script>
