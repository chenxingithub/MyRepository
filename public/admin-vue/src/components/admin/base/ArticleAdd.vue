
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        文章列表
        <small>{{GameInfo.game_name}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><router-link :to="{ path: '/'+GameInfo.idfa+'/article/'+$route.params.id}" tag="a"><i class="fa fa-tv"></i>{{GameInfo.game_name}}</router-link></li>
        <li class="active">文章列表</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{myName}}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body" style="border-top: 3px #0ecef7 inset;">
                            <div class="row">
                                <div class="col-md-12">
                        <el-form class="small-space" :model="temp" :rules="rules" ref="temp"  label-position="left" label-width="100px" style='margin-left:50px;'>
                            <el-form-item label="文章标题" prop="title">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.title"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.title"></el-input>
                            </el-form-item>
                            <el-form-item label="文章副标题" prop="subtitled">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.subtitled"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.subtitled"></el-input>
                            </el-form-item>
                            <el-form-item label="简述" prop="sketch">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.sketch"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.sketch"></el-input>
                            </el-form-item>
                            <el-form-item label="跳转url" prop="jump_url">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.jump_url"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.jump_url"></el-input>
                            </el-form-item>
                            <el-form-item label="访问量" prop="traffic_volume">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.traffic_volume"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.traffic_volume"></el-input>
                            </el-form-item>

                            <el-form-item label="背景图">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="avatarUrl"
                                        :show-file-list="false"
                                        :headers="headers"
                                        :on-success="handleBgImgSuccess"
                                        :before-upload="beforeAvatarUpload">
                                    <img v-if="temp.bg_img" :src="ImgPrefix+temp.bg_img" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                </el-upload>
                            </el-form-item>
                            <el-form-item label="文章图标">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="avatarUrl"
                                        :show-file-list="false"
                                        :headers="headers"
                                        :on-success="handleIconSuccess"
                                        :before-upload="beforeAvatarUpload">
                                    <img v-if="temp.icon" :src="ImgPrefix+temp.icon" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                </el-upload>
                            </el-form-item>
                      <el-form-item label="文章分类" prop="artisan_type_id">
                        <el-select v-model="temp.artisan_type_id" placeholder="请选择">
                                    <el-option
                                      v-for="item in ArtisanType"
                                      :key="item.id"
                                      :label="item.name"
                                      :value="item.id">
                                    </el-option>
                        </el-select>
                      </el-form-item>
                       <el-form-item label="终端" prop="terminal">
                              <el-select v-model="temp.terminal" placeholder="请选择">
                                  <el-option label="全部" value="0"></el-option>
                                  <el-option label="PC端" value="1"></el-option>
                                  <el-option label="移动端" value="2"></el-option>
                              </el-select>
                        </el-form-item>

                            <el-form-item label="文章标签" prop="label">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.label"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.label"></el-input>
                            </el-form-item>

                            <el-form-item label="附件链接" prop="enclosure_url">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.enclosure_url"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.enclosure_url"></el-input>
                            </el-form-item>

                            <el-form-item label="创建时间" prop="created_at">
                              <el-date-picker
                                v-model="temp.created_at"
                                type="datetime"
                                @change="getSTimeStart"
                                format="yyyy-MM-dd HH:mm:ss"
                                value-format="yyyy-MM-dd HH:mm:ss"
                                placeholder="请选择创建时间">
                              </el-date-picker>
                            </el-form-item>

                        <el-form-item label="富文本" prop="password_confirmation">
                                        <div class="editor-container">
                                      <UE :defaultMsg=temp.content :config=config ref="ue"></UE>
                                    </div>
                            </el-form-item>
                        </el-form>
                        <span slot="footer" class="dialog-footer">
                            <el-button type="primary" @click="create('temp')">确 定</el-button>
                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
  </div>
</template>
<script>
import { mapState, mapActions } from 'vuex';
import UE from '../../ue/ue.vue';
export default {
  data() {
        var checkExpired_at = (rule, value, callback) => {
            if (!value) {
            callback(new Error('请选择创建时间'));
            }
            callback();
        };
    return {
            ImgPrefix:process.env.IMAGE_PREFIX,
            loading:false,
            GameInfo: '',
            config: {
              initialFrameWidth: null,
              initialFrameHeight: 350
            },
    		myName: '添加文章',
    		loading: true,
    		items: [],
    		total:null,
            ArtisanType: [
                {
                    id: null,
                    name: '请选择',
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
                title: '',
                artisan_type_id: '',
                name: '',
                status: '',
                sort: '',
                region: '',
                content: '我最帅！！！！！！！！',
                subtitled:'',
                sketch:'',
                jump_url:'',
                traffic_volume:'',
                bg_img:'',
                icon:'',
                terminal: '0',
                label: '',
                enclosure_url: '',
                created_at: '',
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
                    { required: true, message: '请输入文章标题', trigger: 'blur' },
                    { min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                ],
                artisan_type_id: [
                    { type:'number',required: true, message: '请选择活动区域', trigger: 'change' }
                ],
                terminal: [
                    { required: true, message: '请选择活动区域', trigger: 'change' }
                ],
                created_at: [
                    {   validator: checkExpired_at, trigger: 'change' }
                ],
            },
            avatarUrl:'/upload/image',
    }
  },
  components: {UE},
  methods: {
            loadData() {
            	this.loading = true
                let url2 = '/artisan-type/get';
                this.$axios.get(url2, {
                    headers: this.$store.state.headers,
                    params: {
                        query_type: 1,
                        game_id:this.$route.params.id,
                    },
                }).then(response => {
                    var data = this.ArtisanType;
                    this.ArtisanType = data.concat(response.data);
                    this.loading = false
                })
            },
            getSTimeStart(val){
                 this.temp.created_at = val;
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
            handleBgImgSuccess(res, file) {
                console.log(res);
                if(res.url){
                    this.temp.bg_img = res.url;
                }
            },
            handleIconSuccess(res, file) {
                console.log(res);
                if(res.url){
                    this.temp.icon = res.url;
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
                this.temp.created_at = ''
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
                        this.$axios.put('/admin/jpxxl/config', {
                            game_name: this.temp.game_name,
                            dow_code_img: this.temp.dow_code_img,
                            official_url: this.temp.official_url
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
                this.temp.content = this.$refs.ue.getUEContent();
                let content = this.$refs.ue.getUEContent();
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.post('/artisan/post', {
                            id: this.temp.id,
                            title: this.temp.title,
                            artisan_type_id: this.temp.artisan_type_id,
                            name: this.temp.name,
                            subtitled: this.temp.subtitled,
                            sketch: this.temp.sketch,
                            jump_url: this.temp.jump_url,
                            traffic_volume: this.temp.traffic_volume,
                            bg_img: this.temp.bg_img,
                            icon: this.temp.icon,
                            status: this.temp.status,
                            sort: this.temp.sort,
                            content:this.temp.content,
                            terminal:this.temp.terminal,
                            label:this.temp.label,
                            enclosure_url:this.temp.enclosure_url,
                            created_at:this.temp.created_at,
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
                        //获取游戏信息
            let GamesInfoUrl = '/games/get/'+this.$route.params.id;
            this.$axios.get(GamesInfoUrl, {
            }).then(response => {
                this.GameInfo = response.data;
                console.log(this.GameInfo);
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
                    1: '锁定'
                };
                return statusMap[parseInt(status)]
            }
        },
}
</script>
