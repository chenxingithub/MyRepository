
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        用户列表
        <small>用户管理</small>
      </h1>
      <ol class="breadcrumb">
        <li><router-link to="/admin/user" tag="a"><i class="fa fa-user"></i> 用户</router-link></li>
        <li class="active">列表</li>
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
                                <div class="col-md-3">
                            	 <!-- @keyup.enter.native="handleFilter"  监听回车事件执行handleFilter方法 -->
                                    <el-input @keyup.enter.native="handleFilter" class="filter-item" placeholder="用户名、手机号码、昵称" v-model="listQuery.keyword">
                                    </el-input>
                                </div>
                                <div class="col-md-9">
                                <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
                                <el-button class="filter-item" type="primary" @click="handleCreate" icon="edit">添加</el-button>
                                <el-button class="filter-item" type="primary" icon="document" @click="handleDownload">导出</el-button>
                                <!-- <el-button class="filter-item" type="primary" icon="upload2" @click="handleUpload">导入</el-button> -->
                                </div>
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
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th style="width: 5%;">头像</th>
                                        <th style="word-break: break-word;">用户名</th>
                                        <th>邮箱</th>
                                        <th>昵称</th>
                                        <th>手机号码</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    <template>
                                    <tr v-for="item in items">
                                        <td>{{ item['id'] }}</td>
                                        <td><span class="link-type" @click="handleUpdate(item)"><img :src="item['picture']" style="width: 46px; height: 46px;" class="media-object img-circle"></span></td>
                                        <td>{{ item['username'] }}</td>
                                        <td>{{ item['email'] }}</td>
                                        <td>{{ item['name'] }}</td>
                                        <td>{{ item['phone'] }}</td>
                                        <td>{{ item['created_at'] }}</td>
                                        <td>{{ item['updated_at'] }}</td>
                                        <td>
                                            <el-tag :type="item['status'] | statusFilter">{{ item['status'] | statusShow }}</el-tag>
                                        </td>
                                        <td>
                                            <el-button v-if="parseInt(item['status'])==1" size="small" type="success" @click="handleModifyStatus(item,0)">正常
                                            </el-button>
                                            <el-button v-if="parseInt(item['status'])==0" size="small" @click="handleModifyStatus(item,1)">锁定
                                            </el-button>
                                            <el-button size="small" type="danger" icon="delete" @click="handleDelete(item)">删除</el-button>
                                        </td>
                                    </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <div class="pull-right">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="listQuery.page" :page-sizes="[10,20,30, 50]"
                                               :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="total">
                                </el-pagination>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible"  size="small">
                        <el-form class="small-space" :model="temp" :rules="rules" ref="temp"  label-position="left" label-width="100px" style='width: 800px; margin-left:100px;'>
                            <el-form-item label="头像">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="avatarUrl"
                                        :show-file-list="false"
                                        :headers="headers"
                                        :on-success="handleAvatarSuccess"
                                        :before-upload="beforeAvatarUpload">
                                    <img v-if="temp.avatar" :src="temp.avatar" class="avatar media-object img-circle">
                                    <i v-else class="el-icon-plus avatar-uploader-icon media-object img-circle"></i>
                                </el-upload>
                            </el-form-item>
                            <el-form-item label="用户名" prop="name">
                                <el-input v-if="dialogStatus=='update'" v-model="temp.name" disabled></el-input>
                                <el-input v-else v-model="temp.name"></el-input>
                            </el-form-item>
                            <el-form-item label="邮箱" prop="email">
                                <el-input type="email" v-if="dialogStatus=='update'" v-model="temp.email" disabled></el-input>
                                <el-input type="email" v-else v-model="temp.email"></el-input>
                            </el-form-item>
                            <el-form-item label="昵称" prop="nickname">
                                <el-input v-if="dialogStatus=='update'" v-model="temp.nickname" disabled></el-input>
                                <el-input v-else v-model="temp.nickname"></el-input>
                            </el-form-item>
                            <el-form-item label="密码" prop="password">
                                <el-input type="password" v-model="temp.password"></el-input>
                            </el-form-item>
                            <el-form-item label="确认密码" prop="password_confirmation">
                                <el-input type="password" v-model="temp.password_confirmation"></el-input>
                            </el-form-item>
                            <el-form-item label="富文本" prop="password_confirmation">
                                     <button @click="getUEContent()">获取内容</button>
                                        <div class="editor-container">
                                      <UE :defaultMsg=defaultMsg :config=config ref="ue"></UE>
                                    </div>
                            </el-form-item>
                        </el-form>
                        <span slot="footer" class="dialog-footer">
                            <el-button @click="dialogFormVisible = false">取 消</el-button>
                            <el-button v-if="dialogStatus=='create'" type="primary" @click="create('temp')">确 定</el-button>
                            <el-button v-else type="primary" @click="update('temp')">确 定</el-button>
                        </span>
                    </el-dialog >
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
import UE from '../../ue/ue.vue';
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
            defaultMsg: '这里是UE测试',
            loading:false,
            config: {
              initialFrameWidth: null,
              initialFrameHeight: 350
            },
    		myName: '用户列表',
    		loading: true,
    		items: [],
    		total:null,
            listQuery: {
                page: 1,
                limit: 10,
                keyword: ''
            },
            textMap: {
                update: '编辑',
                create: '创建'
            },
            dialogFormVisible:false,
            dialogStatus: '',
            headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
            temp: {
                id: 0,
                name: '',
                nickname: '',
                email:'',
                avatar:'',
                password:'',
                password_confirmation:''
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
                name: [
                    { required: true, message: '请输入用户名称', trigger: 'blur' },
                    { min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                ],
                email: [
                    { required: true, message: '请输入用户邮箱', trigger: 'blur' },
                    { type: 'email', message: '请输入正确的邮箱地址', trigger: 'blur,change' }
                ],
                nickname: [
                    { required: true, message: '请输入用户昵称', trigger: 'blur' },
                    { min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: '请输入用户密码', trigger: 'blur' },
                    { validator: validatePass, trigger: 'blur' }
                ],
                password_confirmation: [
                    { required: true, message: '请输入用户确认密码', trigger: 'blur' },
                    { validator: validatePass2, trigger: 'blur' }
                ]
            },
            avatarUrl:'/admin/upload/image?type=avatar',
    }
  },
  components: {UE},
  methods: {
            loadData() {
            	this.loading = true
                let url = '/admin/user/get';
                this.$axios.get(url, {
                    headers: this.$store.state.headers,
                    params: this.listQuery
                }).then(response => {
                    this.items = response.data.data
                    this.total = response.data.total
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
            handleAvatarSuccess(res, file) {
             	console.log(res);
                if(res.url){
                    this.temp.avatar = res.url;
                }
            },
            handleCreate(){
                this.dialogStatus = 'create'
                this.temp.id = ''
                this.temp.name = ''
                this.temp.nickname = ''
                this.temp.email = ''
                this.temp.avatar = ''
                this.temp.password = ''
                this.temp.password_confirmation = ''
                this.dialogFormVisible = true
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/user/excel?q='+this.listQuery.q)
            },
            handleUpdate(data) {
                this.dialogStatus = 'update';
                this.$axios.get('/admin/user/' + data.id)
                    .then((response) => {
                        if(response.status === 200){
                            this.temp.id = response.data.id
                            this.temp.name = response.data.username
                            this.temp.nickname = response.data.name
                            this.temp.email = response.data.email
                            this.temp.avatar = response.data.picture
                            this.dialogFormVisible = true
                        }
                    })
            },
            handleDelete(data) {
                this.$confirm('此操作将永久删除改用户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/admin/user/' + data.id)
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
                        this.$axios.put('/admin/user/' + this.temp.id, {
                            password: this.temp.password,
                            password_confirmation: this.temp.password_confirmation,
                            avatar: this.temp.avatar
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
                        this.$axios.post('/admin/user', {
                            name: this.temp.name,
                            nickname: this.temp.nickname,
                            email: this.temp.email,
                            avatar: this.temp.avatar,
                            password: this.temp.password,
                            password_confirmation: this.temp.password_confirmation,
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
                this.$axios.put('/admin/user/' + data.id, {
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
        },
        created() {
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
                    1: '锁定'
                };
                return statusMap[parseInt(status)]
            }
        },
}
</script>
