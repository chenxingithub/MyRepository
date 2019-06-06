
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        大转盘抽奖记录
        <small>{{GameInfo.game_name}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><router-link to="/admin/user" tag="a"><i class="fa fa-user"></i> {{GameInfo.game_name}}</router-link></li>
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
                                    <el-input style="width: 50%;" @keyup.enter.native="handleFilter" class="filter-item" placeholder="奖品名称/抽奖人" v-model="listQuery.keyword">
                                    </el-input>
                                </div>
                                <div class="col-md-3">

                               <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
  <!-- <el-button class="filter-item" type="primary" @click="handleCreate" icon="edit">添加</el-button> -->
<!--<el-button class="filter-item" type="primary" icon="document" @click="handleDownload">导出</el-button> -->
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
                                        <th>ID</th>
                                        <th>冰鸟UID</th>
                                        <th style="word-break: break-word;">奖品名称</th>
<!--                                         <th>收货地址</th> -->
                                        <th>抽奖时间</th>
                                        <th>更新时间</th>
                                        <th>操作</th>
                                    </tr>
                                    <template>
                                    <tr v-for="item in items">
                                        <td>{{ item['id'] }}</td>
                                        <td>{{ item['bn_uid'] }}</td>
                                        <td >{{ item['prize_name'] }}</td>
<!--                                         <td >{{ item['receiving_address'] }}</td> -->
                                        <td >{{ item['created_at'] }}</td>
                                        <td >{{ item['updated_at'] }}</td>
                                        <td>
<!--                                             <el-button v-if="parseInt(item['is_grant'])==1" size="small" type="success" @click="handleModifyStatus(item,0)">已发放
</el-button> -->
                                            <el-button v-if="parseInt(item['is_grant'])==0" size="small" @click="handleModifyStatus(item,1)">未发放
                                            </el-button>
                                            <el-button size="small" type="danger" icon="el-icon-delete" @click="handleDelete(item)">删除</el-button>
                                            <!-- <el-button size="small" type="primary" icon="el-icon-edit" @click="handleUpdate(item)">编辑</el-button> -->
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
                            <el-form-item label="奖品图片">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="avatarUrl"
                                        :show-file-list="false"
                                        :headers="headers"
                                        :on-success="handleAvatarSuccess"
                                        :before-upload="beforeAvatarUpload">
                                    <img v-if="temp.img" :src="ImgPrefix+temp.img" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                </el-upload>
                            </el-form-item>
                            <el-form-item label="奖品名称" prop="name">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.name"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.name"></el-input>
                            </el-form-item>
                            <el-form-item label="中奖概率" prop="probability">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.probability"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.probability"></el-input>
                            </el-form-item>
                            <el-form-item label="总数量" prop="total_number">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.total_number"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.total_number"></el-input>
                            </el-form-item>
                            <el-form-item label="剩余数量" prop="surplus_number">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.surplus_number"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.surplus_number"></el-input>
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
            content:'2312312312',
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
            PositionType: [
                {
                    value: null,
                    label: '请选择',
                },
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
                img: '',
                name: '',
                probability: '',
                total_number: '',
                surplus_number:'',
                surplus_number:'',
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
                let url = '/jp-turntable-record';
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
                if(res.url){
                    this.temp.img = res.url;
                }
            },
            handleCreate(){
                this.dialogStatus = 'create'
                this.temp.id= ''
                this.temp.name= ''
                this.temp.img= ''
                this.temp.probability= ''
                this.temp.total_number= ''
                this.temp.surplus_number=''
                this.dialogFormVisible = true
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/user/excel?q='+this.listQuery.q)
            },
            handleUpdate(data) {
                this.dialogStatus = 'update';
                this.temp.id = data.id
                this.temp.name = data.name
                this.temp.img = data.img
                this.temp.probability= data.probability
                this.temp.total_number = data.total_number
                this.temp.surplus_number = data.surplus_number
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
                this.$confirm('此操作将永久删除该抽奖记录, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/jp-turntable-record/' + data.id)
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
                        this.$axios.put('/turntable-commodity/' + this.temp.id, {
                            name: this.temp.name,
                            img: this.temp.img,
                            probability: this.temp.probability,
                            total_number: this.temp.total_number,
                            surplus_number: this.temp.surplus_number,
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
                        this.$axios.post('/turntable-commodity', {
                            img: this.temp.img,
                            name: this.temp.name,
                            probability: this.temp.probability,
                            total_number: this.temp.total_number,
                            surplus_number: this.temp.surplus_number
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
            handleModifyStatus(data, is_grant) {
                this.$axios.put('/jp-turntable-record/' + data.id, {
                    is_grant: is_grant,
                    action: 'changeGrant'
                })
                    .then((response) => {
                        console.log(response);
                            if(response.status === 200){
                                this.$message({
                                    message: (response.data.message.length === 0?'发货状态切换成功..':response.data.message),
                                    type: 'success'
                                });
                                this.loadData();
                            }else{
                                this.$message({
                                    message: (response.data.message.length === 0?'操作失败':response.data.message),
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
                    1:'顶部',
                    2:'游戏特色',
                    3:'实景截图'
                };
                return statusMap[parseInt(status)]
            }
            
        },
}
</script>
