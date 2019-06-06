
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        列表
        <small>简历</small>
      </h1>
      <ol class="breadcrumb">
        <li><router-link to="/admin/user" tag="a"><i class="fa fa-user"></i> 简历</router-link></li>
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
                                <div class="col-md-4">
                                    时间筛选：
                                        <el-date-picker
                                          v-model="value4"
                                          type="datetimerange"
                                          format="yyyy-MM-dd HH:mm:ss"
                                          @change="handleTime"
                                          value-format="yyyy-MM-dd HH:mm:ss"
                                          :picker-options="pickerOptions2"
                                          range-separator="至"
                                          start-placeholder="开始日期"
                                          end-placeholder="结束日期"
                                          align="right">
                                        </el-date-picker>
                                </div>
                                <div class="col-md-3">
                                    应聘部门：
                                    <el-select v-model="listQuery.artisan_type_id" placeholder="请选择">
                                                <el-option
                                                  v-for="item in ArtisanType"
                                                  :key="item.id"
                                                  :label="item.name"
                                                  :value="item.id">
                                                </el-option>
                                    </el-select>
                                </div>

                                <div class="col-md-3">

                               <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
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
                                        <th>应聘岗位</th>
                                        <th>创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                    <template>
                                    <tr v-for="item in items">
                                        <td>{{ item['id'] }}</td>
                                        <td>{{ item['name'] }}</td>
                                        <td>{{ item['created_at'] }}</td>
                                        <td>
                                            <el-button size="small"  icon="edit" @click="handleUpdate(item)">简历下载</el-button>
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
                        <el-form-item label="游戏logo">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="imgUrl"
                                        :show-file-list="false"
                                        :headers="headers"
                                        :on-success="handleLogoSuccess"
                                        :before-upload="beforeAvatarUpload">
                                    <img v-if="temp.logo_url" :src="ImgPrefix+temp.logo_url" class="avatar media-object">
                                    <i v-else class="el-icon-plus avatar-uploader-icon media-object "></i>
                                </el-upload>
                            </el-form-item>
                            <el-form-item label="ios下载图">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="imgUrl"
                                        :show-file-list="false"
                                        :headers="headers"
                                        :on-success="handleIosSuccess"
                                        :before-upload="beforeAvatarUpload">
                                    <img v-if="temp.ios_dow_code_img" :src="ImgPrefix+temp.ios_dow_code_img" class="avatar media-object">
                                    <i v-else class="el-icon-plus avatar-uploader-icon media-object "></i>
                                </el-upload>
                            </el-form-item>
                            <el-form-item label="android下载图">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="imgUrl"
                                        :show-file-list="false"
                                        :headers="headers"
                                        :on-success="handleAndroidSuccess"
                                        :before-upload="beforeAvatarUpload">
                                    <img v-if="temp.android_dow_code_img" :src="ImgPrefix+temp.android_dow_code_img" class="avatar media-object">
                                    <i v-else class="el-icon-plus avatar-uploader-icon media-object "></i>
                                </el-upload>
                            </el-form-item>
                            <el-form-item label="游戏名称" prop="game_name">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.game_name"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.game_name"></el-input>
                            </el-form-item>
                            <el-form-item label="游戏标识" prop="idfa">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.idfa"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.idfa"></el-input>
                            </el-form-item>
                            <el-form-item label="游戏标签" prop="label">
                                  <el-select size="small" v-model="temp.label" placeholder="请选择">
                                      <el-option label="普通" value="0"></el-option>
                                      <el-option label="火爆" value="1"></el-option>
                                      <el-option label="最新" value="2"></el-option>
                                      <el-option label="推荐" value="3"></el-option>
                                  </el-select>
                            </el-form-item>
                            <el-form-item label="类型" prop="type">
                                  <el-select size="small" v-model="temp.type" placeholder="请选择">
                                      <el-option label="官网&公众号" value="1"></el-option>
                                      <el-option label="官网" value="2"></el-option>
                                      <el-option label="公众号" value="3"></el-option>
                                  </el-select>
                            </el-form-item>
                            <el-form-item label="官网链接" prop="official_url">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.official_url"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.official_url"></el-input>
                            </el-form-item>
                            <el-form-item label="视频链接" prop="video_url">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.video_url"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.video_url"></el-input>
                            </el-form-item>
                            <el-form-item label="游戏简述" prop="sketch">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.sketch"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.sketch"></el-input>
                            </el-form-item>
                            <el-form-item label="ios下载链接" prop="ios_download_url">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.ios_download_url"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.ios_download_url"></el-input>
                            </el-form-item>
                            <el-form-item label="android下载链接" prop="android_download_url">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.android_download_url"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.android_download_url"></el-input>
                            </el-form-item>
                            <el-form-item label="客服QQ(多个逗号分隔)" prop="ios_download_url">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.service_qq"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.service_qq"></el-input>
                            </el-form-item>
                            <el-form-item label="客服phone(多个逗号分隔)" prop="service_phone">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.service_phone"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.service_phone"></el-input>
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
/*import UE from '../../ue/ue.vue';*/
export default {
  data() {
    return {
            ImgPrefix:process.env.IMAGE_PREFIX,
            loading:false,
            config: {
              initialFrameWidth: null,
              initialFrameHeight: 350
            },
            ArtisanType: [
                {
                    id: null,
                    name: '请选择',
                }
            ],
    		myName: '简历',
    		loading: true,
    		items: [],
    		total:null,
            listQuery: {
                page: 1,
                limit: 10,
                startTime:'',
                endTime:'',
                artisan_type_id:''
            },
            positionType: [
                {
                    value: '',
                    label: '请选择',
                },
                {
                    value: 1,
                    label: '是',
                },
                {
                    value: 0,
                    label: '否',
                }
            ],
            pickerOptions2: {
              shortcuts: [{
                text: '最近一周',
                onClick(picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                  picker.$emit('pick', [start, end]);
                }
              }, {
                text: '最近一个月',
                onClick(picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                  picker.$emit('pick', [start, end]);
                }
              }, {
                text: '最近三个月',
                onClick(picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                  picker.$emit('pick', [start, end]);
                }
              }]
            },
            value4: '',
            textMap: {
                update: '编辑',
                create: '创建'
            },
            dialogFormVisible:false,
            dialogStatus: '',
            headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
            temp: {
                id: '',
                idfa: '',
                game_name: '',
                ios_dow_code_img:'',
                android_dow_code_img:'',
                official_url:'',
                video_url:'',
                ios_download_url:'',
                android_download_url:'',
                service_qq:'',
                service_phone:'',
                sketch:'',
                logo_url:'',
                label:'',
                type:'',
                sort:'',
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
                game_name: [
                    { required: true, message: '请输入游戏名称', trigger: 'blur' },
                    { min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                ],
                type: [
                    { required: true, message: '请选择游戏类型', trigger: 'change' }
                ],
                label: [
                    { required: true, message: '请选择游戏标识', trigger: 'change' }
                ],
                idfa: [
                    { required: true, message: '请输入游戏标识', trigger: 'blur' },
                    { min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                ],
                official_url: [
                    { required: true, message: '请输入官网链接', trigger: 'blur' },
                    { min: 1, max: 200, message: '长度在 1 到 200 个字符', trigger: 'blur' }
                ],
            },
            imgUrl:'/upload/image',
    }
  },
  /*components: {UE},*/
  methods: {
            loadData() {
            	this.loading = true
                let url = '/resume-list';
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
            handleTime(val){
                if (val) {
                    this.listQuery.startTime = val[0];
                    this.listQuery.endTime = val[1];
                }else{
                    this.listQuery.startTime = '';
                    this.listQuery.endTime = '';
                }
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
            handleIosSuccess(res, file) {
                if(res.url){
                    this.temp.ios_dow_code_img = res.url;
                }
            },
            handleLogoSuccess(res, file) {
                if(res.url){
                    this.temp.logo_url = res.url;
                }
            },
            handleAndroidSuccess(res, file) {
                if(res.url){
                    this.temp.android_dow_code_img = res.url;
                }
            },
            handleCreate(){
                this.dialogStatus = 'create'
                this.temp.id = ''
                this.temp.idfa = ''
                this.temp.game_name = ''
                this.temp.ios_dow_code_img = ''
                this.temp.android_dow_code_img = ''
                this.temp.official_url = ''
                this.temp.video_url = ''
                this.temp.ios_download_url = ''
                this.temp.android_download_url = ''
                this.temp.service_qq = ''
                this.temp.service_phone = ''
                this.temp.sort = ''
                this.temp.sketch = ''
                this.temp.logo_url = ''
                this.temp.label = ''
                this.temp.type = ''
                this.dialogFormVisible = true
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/user/excel?q='+this.listQuery.q)
            },
            handleUpdate(data) {
                window.open('/resume-download/'+data.id);
            },
            handleDelete(data) {
                this.$confirm('此操作将永久删除该游戏, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/games/delete/' + data.id)
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
                        this.$axios.put('/games/put/' + this.temp.id, {
                            idfa: this.temp.idfa,
                            game_name: this.temp.game_name,
                            ios_dow_code_img: this.temp.ios_dow_code_img,
                            android_dow_code_img: this.temp.android_dow_code_img,
                            official_url: this.temp.official_url,
                            video_url: this.temp.video_url,
                            ios_download_url: this.temp.ios_download_url,
                            android_download_url: this.temp.android_download_url,
                            service_phone: this.temp.service_phone,
                            service_qq: this.temp.service_qq,
                            sketch: this.temp.sketch,
                            logo_url: this.temp.logo_url,
                            label: this.temp.label,
                            type: this.temp.type,
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
                        this.$axios.post('/games/post', {
                            idfa: this.temp.idfa,
                            game_name: this.temp.game_name,
                            ios_dow_code_img: this.temp.ios_dow_code_img,
                            android_dow_code_img: this.temp.android_dow_code_img,
                            official_url: this.temp.official_url,
                            video_url: this.temp.video_url,
                            ios_download_url: this.temp.ios_download_url,
                            android_download_url: this.temp.android_download_url,
                            service_phone: this.temp.service_phone,
                            logo_url: this.temp.logo_url,
                            service_qq: this.temp.service_qq,
                            label: this.temp.label,
                            type: this.temp.type,
                            sketch: this.temp.sketch,
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
                this.$axios.put('/admin/wzdqqhd/focus/put/' + data.id, {
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
            handleModifySort(data, sort) {
                this.$axios.put('/games/put/' + data.id, {
                    sort: sort,
                    action: 'changeSort'
                })
                    .then((response) => {
                        console.log(response);
                            if(response.status === 200){
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'置顶成功！':response.data.message),
                                    type: 'success'
                                });
                                this.loadData();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'置顶失败！':response.data.message),
                                    type: 'error'
                                });
                            }
                    })
            },
        },
        created() {
            let url2 = '/artisan-type/get';
            this.$axios.get(url2, {
                headers: this.$store.state.headers,
                params: {
                    query_type: 1,
                    p_id: 32
                },
            }).then(response => {
                var data = this.ArtisanType;
                this.ArtisanType = data.concat(response.data);
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
            positionShow(status){
                const statusMap = {
                    1:'主页',
                    2:'新闻'
                };
                return statusMap[parseInt(status)]
            },
            labelShow(label){
                const labelMap = {
                    0:'普通',
                    1:'火爆',
                    2:'最新',
                    3:'推荐',
                };
                return labelMap[parseInt(label)]
            },
            typeShow(label){
                const labelMap = {
                    1:'官网&公众号',
                    2:'官网',
                    3:'公众号',
                };
                return labelMap[parseInt(label)]
            },
        
        },
}
</script>
