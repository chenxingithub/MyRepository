
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        官网礼包配置
        <small>{{GameInfo.game_name}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><router-link to="/admin/user" tag="a"><i class="fa fa-user"></i> {{GameInfo.game_name}}</router-link></li>
        <li class="active">配置</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="row">
                        <el-form class="small-space" :model="temp" :rules="rules" ref="temp"  label-position="left" label-width="100px" style='width: 800px; margin-left:100px;'>
                            <el-form-item label="礼包id" prop="jump_url">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.gift_id"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.gift_id"></el-input>
                            </el-form-item>
                        </el-form>
                        <span slot="footer" class="dialog-footer"  style="margin-left:30rem;">
                            <el-button v-if="dialogStatus=='create'" type="primary" @click="create('temp')">确 定</el-button>
                            <el-button v-else type="primary" @click="update('temp')">确 定</el-button>
                        </span>
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
export default {
  data() {
            var validatePass = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请输入密码'));
                } else {
                    if (this.temp.password_confirmation !== '') {
                        this.$refs.temp.validateField('password_confirmation');
                    }
                    callback();
                }
            };
            var validatePass2 = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请再次输入密码'));
                } else if (value !== this.temp.password) {
                    callback(new Error('两次输入密码不一致!'));
                } else {
                    callback();
                }
            };
    return {
            ImgPrefix:process.env.IMAGE_PREFIX,
            GameInfo: '',
            gift_id:'',
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
                page: 1,
                limit: 10,
                keyword: '',
                position:'',
                game_id:this.$route.params.id,
                terminal:'0'
            },
            textMap: {
                update: '编辑',
                create: '创建'
            },
            dialogFormVisible:false,
            dialogStatus: '',
            headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
            temp: {
                id: '',
                gift_id: '',
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
                title: [
                    { required: true, message: '请输入轮播图标题', trigger: 'blur' },
                    { min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                ],
                  position: [
                    { type:'number',required: true, message: '请选择位置', trigger: 'change' }
                  ],
                terminal:[
                    { required: true, message: '请选择终端', trigger: 'change' }
                ]
            },
            avatarUrl:'/upload/image',
    }
  },
  methods: {
            loadData() {
            	this.loading = true
                let url = '/config/official-gift/get';
                this.$axios.get(url, {
                    headers: this.$store.state.headers,
                    params: {
                                game_id:this.$route.params.id,
                            }
                }).then(response => {
                    this.loading = false
                    if (!response.data) {
                        this.dialogStatus = 'create'
                        this.temp.gift_id = ''
                    }else{
                        this.dialogStatus = 'update'
                        this.temp.id = response.data.id
                        this.temp.gift_id = response.data.value
                    }
                    console.log(response.data.value);
                    console.log(this.temp.gift_id);
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
            handleAvatarSuccess(res, file) {
             	console.log(res);
                if(res.url){
                    this.temp.picture = res.url;
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
                this.$confirm('此操作将永久删除改用户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/focus/delete/' + data.id)
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
                        this.$axios.put('/config/official-gift/' + this.temp.id, {
                            value: this.temp.gift_id,
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
                        this.$axios.post('/config/official-gift/', {
                            value: this.temp.gift_id,
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
                this.$axios.put('/focus/put/' + data.id, {
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
                this.$axios.put('/focus/put/' + data.id, {
                    sort: data['sort'],
                    action: 'changeSort'
                })
                    .then((response) => {
                        console.log(response);
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
            let GamesInfoUrl = '/games/get/'+this.$route.params.id;
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
                    1:'分类一',
                    2:'分类二',
                    3:'分类三',
                    4:'分类四',
                    5:'分类五',
                    6:'分类六',
                    7:'分类七'
                };
                return statusMap[parseInt(status)]
            }
            
        },
}
</script>
