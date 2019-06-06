
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        礼包分类
        <small>{{GameInfo.game_name}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><router-link to="/admin/user" tag="a"><i class="fa fa-map-o"></i> {{GameInfo.game_name}}</router-link></li>
        <li class="active">礼包分类</li>
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
                                    <el-input style="width: 50%;" @keyup.enter.native="handleFilter" class="filter-item" placeholder="礼包标题/礼包内容" v-model="listQuery.keyword">
                                    </el-input>
                                </div>
                                <div class="col-md-3">
                                    状态：
                                    </el-input>
                                    <el-select v-model="listQuery.status" placeholder="请选择">
                                    <el-option
                                      v-for="item in StatusType"
                                      :key="item.value"
                                      :label="item.label"
                                      :value="item.value">
                                    </el-option>
                                  </el-select>
                                </div>
                                <div class="col-md-6">

                               <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
  <el-button class="filter-item" type="primary" @click="handleCreate" icon="edit">添加</el-button>
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
                                        <th style="width: 10px">ID</th>
                                        <th>礼包标题</th>
                                        <th>礼包内容</th>
                                        <th>礼包类型</th>
                                        <th>总数</th>
                                        <th>使用</th>
                                        <th>剩余</th>
                                        <th>过期时间</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    <template>
                                    <tr v-for="item in items">
                                        <td>{{ item['id'] }}</td>
                                        <td>{{ item['cdk_type_title'] }}</td>
                                        <td>{{ item['cdk_type_content'] }}</td>
                                        <td>
                                            <el-tag :type="item['type'] | statusFilter">{{ item['type'] | typeShow }}</el-tag>
                                        <td>{{ item['total_num'] }}</td>
                                        <td>{{ item['use_num'] }}</td>
                                        <td>{{ item['surplus_num'] }}</td>
                                        <td>{{ item['expired_at'] }}</td>
                                        <td>{{ item['created_at'] }}</td>
                                        <td>{{ item['updated_at'] }}</td>
                                        <td>
                                            <el-tag :type="item['status'] | statusFilter">{{ item['status'] | statusShow }}</el-tag>
                                        </td>
                                        <td>

                                            <el-button v-if="parseInt(item['status'])==1" size="small" type="success" @click="handleModifyStatus(item,0)">正常
                                            </el-button>
                                            <el-button v-if="parseInt(item['status'])==0" size="small" @click="handleModifyStatus(item,1)">禁用
                                            </el-button>

                                            <el-button v-if="parseInt(item['sort'])==0" size="small" type="success" @click="handleModifySort(item,1)">置顶
                                            </el-button>
                                            <el-button v-if="parseInt(item['sort'])==1" size="small" @click="handleModifySort(item,0)">取消置顶
                                            </el-button>

                                            <el-button size="small" type="info" icon="edit" @click="handleUpdate(item)">修改</el-button>
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
                        <el-form class="small-space" :model="temp" :rules="rules" ref="temp"  label-position="left" label-width="100px" style='width: 800px; margin-left:50px;'>
                            <el-form-item label="分类标题" prop="title">
                                <el-input v-if="dialogStatus=='update'" v-model="temp.title" style="width:50%;"></el-input>
                                <el-input v-else v-model="temp.title" style="width:50%;"></el-input>
                            </el-form-item>
                            <el-form-item label="分类内容" prop="content">
                                <el-input v-if="dialogStatus=='update'" v-model="temp.content" style="width:50%;"></el-input>
                                <el-input v-else v-model="temp.content" style="width:50%;"></el-input>
                            </el-form-item>
                            <el-form-item label="礼包类型" prop="type">
                                  <el-select size="small" v-model="temp.type" placeholder="请选择">
                                      <el-option label="官网" value="1"></el-option>
                                      <el-option label="公众号" value="2"></el-option>
                                  </el-select>
                            </el-form-item>
                            <el-form-item label="开始时间" prop="start_at">
                              <el-date-picker
                                v-model="temp.start_at"
                                type="datetime"
                                @change="getSTimeStart"
                                format="yyyy-MM-dd HH:mm:ss"
                                value-format="yyyy-MM-dd HH:mm:ss"
                                placeholder="请选择开始时间">
                              </el-date-picker>
                            </el-form-item>
                            <el-form-item label="过期时间" prop="expired_at">
                            <el-date-picker
                              v-model="temp.expired_at"
                              type="datetime"
                              @change="getSTimeExpired"
                              format="yyyy-MM-dd HH:mm:ss"
                              value-format="yyyy-MM-dd HH:mm:ss"
                              placeholder="请选择过期时间">
                            </el-date-picker>
                            </el-form-item>

                            <el-form-item label="使用说明" v-if="temp.type==1">
                                <el-input v-if="dialogStatus=='update'" v-model="temp.usage_method" style="width:50%;"></el-input>
                                <el-input v-else v-model="temp.usage_method" style="width:50%;"></el-input>
                            </el-form-item>

                            <el-form-item label="礼包说明" v-if="temp.type==1">
                                <el-input v-if="dialogStatus=='update'" v-model="temp.instructions" style="width:50%;"></el-input>
                                <el-input v-else v-model="temp.instructions" style="width:50%;"></el-input>
                            </el-form-item>

                            <el-form-item label="头图" v-if="temp.type==2">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="headUrl"
                                        :show-file-list="false"
                                        :headers="headers"
                                        :on-success="handleHeadSuccess"
                                        :before-upload="beforeAvatarUpload">
                                    <img v-if="temp.head_img" :src="ImgPrefix+temp.head_img" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                </el-upload>
                            </el-form-item>

                            <el-form-item label="背景颜色" v-if="temp.type==2">
                                <el-color-picker v-model="temp.bg_color"></el-color-picker>
                            </el-form-item>

                            <el-form-item label="中奖概率" v-if="temp.type==2">
                                <el-input v-if="dialogStatus=='update'" v-model="temp.probability" style="width:50%;" placeholder="请输入1-100的整数"></el-input>
                                <el-input v-else v-model="temp.probability" style="width:50%;" placeholder="请输入1-100的整数"></el-input>
                            </el-form-item>

                            <el-form-item label="使用说明" v-if="temp.type==2">
                                    <!-- 图片上传组件辅助-->
                                    <el-upload
                                            class="avatar-uploader vue-quill-editor-img"
                                            :action="imageUrl"
                                            :headers="headers"
                                            :show-file-list="false"
                                            :on-success="uploadSuccess"
                                            :on-error="uploadError"
                                            :before-upload="beforeUpload">
                                    </el-upload>
                                    <!--富文本编辑器组件-->
                                   <el-row v-loading="quillUpdateImg">
                                    <quill-editor
                                            v-model="temp.instructions"
                                            ref="myQuillEditor"
                                            :options="editorOption"
                                    >
                                    </quill-editor>
                                    </el-row>
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
/*import Tinymce from '../Tinymce/index.vue';*/
// 工具栏配置
const toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  ['blockquote', 'code-block'],

  [{'header': 1}, {'header': 2}],               // custom button values
  [{'list': 'ordered'}, {'list': 'bullet'}],
  [{'script': 'sub'}, {'script': 'super'}],      // superscript/subscript
  [{'indent': '-1'}, {'indent': '+1'}],          // outdent/indent
  [{'direction': 'rtl'}],                         // text direction

  [{'size': ['small', false, 'large', 'huge']}],  // custom dropdown
  [{'header': [1, 2, 3, 4, 5, 6, false]}],

  [{'color': []}, {'background': []}],          // dropdown with defaults from theme
  [{'font': []}],
  [{'align': []}],
  ['link', 'image', 'video'],
  ['clean']                                         // remove formatting button
]
export default {
  data() {
         var checkExpired_at = (rule, value, callback) => {
            if (!value) {
            callback(new Error('请选择过期时间'));
            }
            callback();
        };
    return {
            imageUrl: '/upload/image',
            quillUpdateImg: false, // 根据图片上传状态来确定是否显示loading动画，刚开始是false,不显示
            quillUpdateImg2: false, // 根据图片上传状态来确定是否显示loading动画，刚开始是false,不显示
            headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
            editorOption: {
                      placeholder: '',
                      theme: 'snow',  // or 'bubble'
                      modules: {
                        toolbar: {
                          container: toolbarOptions,  // 工具栏
                          handlers: {
                            'image': function (value) {
                              if (value) {
                                document.querySelector('.vue-quill-editor-img input').click();
                              } else {
                                this.quill.format('image', false);
                              }
                            }
                          }
                        }
                      }
            },
            ImgPrefix:process.env.IMAGE_PREFIX,
            loading:false,
            GameInfo: '',
            config: {
              initialFrameWidth: null,
              initialFrameHeight: 350
            },
    		myName: '礼包分类',
            ArtisanType: [
                {
                    id: 0,
                    name: '请选择',
                }
            ],
    		loading: true,
    		items: [],
    		total:null,
            listQuery: {
                page: 1,
                limit: 10,
                keyword: '',
                status:'',
                game_id:this.$route.params.id,
            },
            StatusType: [
                {
                    value: null,
                    label: '请选择',
                },
                {
                    value: 0,
                    label: '使用',
                },
                {
                    value: 1,
                    label: '禁用',
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
                content: '',
                expired_at: '',
                start_at: '',
                type: '',
                head_img: '',
                bg_color: '',
                instructions: '',
                usage_method: '',
                probability: ''
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
                    { required: true, message: '请输入礼包分类标题', trigger: 'blur' },
                    { min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                ],
                expired_at: [
                    {   validator: checkExpired_at, trigger: 'change' }
                ],
                start_at: [
                    {   validator: checkExpired_at, trigger: 'change' }
                ],
            },
            headUrl:'/upload/image',
    }
  },
/*  components: {
            'Tinymce': Tinymce,
  },*/
  methods: {
            loadData() {
            	this.loading = true
                let url = '/cdk-type/get';
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
            handleHeadSuccess(res, file) {
             	console.log(res);
                if(res.url){
                    this.temp.head_img = res.url;
                }
            },
            getSTimeExpired(val){
                 this.temp.expired_at = val;
            },
            getSTimeStart(val){
                 this.temp.start_at = val;
            },
            handleCreate(){
                this.dialogStatus = 'create'
                this.temp.id = ''
                this.temp.title = ''
                this.temp.content = ''
                this.temp.expired_at = ''
                this.temp.start_at = ''
                this.temp.type = ''
                this.temp.head_img = ''
                this.temp.bg_color = ''
                this.temp.instructions = ''
                this.temp.usage_method = ''
                this.temp.probability = ''
                this.dialogFormVisible = true
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/user/excel?q='+this.listQuery.q)
            },
            handleUpdate(data) {
                console.log(data);
                this.dialogStatus = 'update';
                this.temp.id = data.id
                this.temp.title = data.cdk_type_title
                this.temp.content = data.cdk_type_content
                this.temp.expired_at = data.expired_at
                this.temp.start_at = data.start_at
                this.temp.type = data.type.toString()
                this.temp.head_img = data.head_img
                this.temp.bg_color = data.bg_color
                this.temp.instructions = data.instructions
                this.temp.usage_method = data.usage_method
                this.temp.probability = data.probability
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
                this.$confirm('此操作将永久删除该礼包类型, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/cdk-type/delete/' + data.id)
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
 // 富文本图片上传前
            beforeUpload() {
                // 显示loading动画
                this.quillUpdateImg = true
            },
 // 富文本图片上传前
            beforeUpload2() {
                // 显示loading动画
                this.quillUpdateImg2 = true
            }, 
            uploadSuccess(res, file) {
                // res为图片服务器返回的数据
                // 获取富文本组件实例
                let quill = this.$refs.myQuillEditor.quill
                // 如果上传成功
                if (res.message === 'OK' && res.url !== null) {
                    // 获取光标所在位置
                    let length = quill.getSelection().index;
                    // 插入图片  res.info为服务器返回的图片地址
                    quill.insertEmbed(length, 'image', '/'+res.url)
                    // 调整光标到最后
                    quill.setSelection(length + 1)
                } else {
                    this.$message.error('图片插入失败')
                }
                // loading动画消失
                this.quillUpdateImg = false
            },
            uploadSuccess2(res, file) {
                // res为图片服务器返回的数据
                // 获取富文本组件实例
                let quill = this.$refs.myQuillEditor2.quill
                // 如果上传成功
                if (res.message === 'OK' && res.url !== null) {
                    // 获取光标所在位置
                    let length = quill.getSelection().index;
                    // 插入图片  res.info为服务器返回的图片地址
                    quill.insertEmbed(length, 'image', '/'+res.url)
                    // 调整光标到最后
                    quill.setSelection(length + 1)
                } else {
                    this.$message.error('图片插入失败')
                }
                // loading动画消失
                this.quillUpdateImg = false
            },
            // 富文本图片上传失败
            uploadError() {
                // loading动画消失
                this.quillUpdateImg = false
                this.$message.error('图片插入失败')
            },
            // 富文本图片上传失败
            uploadError2() {
                // loading动画消失
                this.quillUpdateImg = false
                this.$message.error('图片插入失败')
            },
            update(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.put('/cdk-type/put/' + this.temp.id, {
                            cdk_type_title: this.temp.title,
                            cdk_type_content: this.temp.content,
                            expired_at: this.temp.expired_at,
                            start_at: this.temp.start_at,
                            type: this.temp.type,
                            head_img: this.temp.head_img,
                            bg_color: this.temp.bg_color,
                            instructions: this.temp.instructions,
                            probability: this.temp.probability,
                            usage_method: this.temp.usage_method,
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
                        this.$axios.post('/cdk-type/post', {
                            cdk_type_title: this.temp.title,
                            cdk_type_content: this.temp.content,
                            expired_at: this.temp.expired_at,
                            start_at: this.temp.start_at,
                            game_id:this.$route.params.id,
                            type: this.temp.type,
                            head_img: this.temp.head_img,
                            bg_color: this.temp.bg_color,
                            instructions: this.temp.instructions,
                            probability: this.temp.probability,
                            usage_method: this.temp.usage_method,
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
                this.$axios.put('/cdk-type/put/' + data.id, {
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
                this.$axios.put('/admin/focus/put/' + data.id, {
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
            //获取游戏信息
            let GamesInfoUrl = '/admin/games/get/'+this.$route.params.id;
            this.$axios.get(GamesInfoUrl, {
            }).then(response => {
                this.GameInfo = response.data;
            })
            let url2 = '/artisan-type/get';
            this.$axios.get(url2, {
                headers: this.$store.state.headers,
                params: {
                    query_type: 1,
                    game_id:this.$route.params.id,
                },
            }).then(response => {
                var data = this.ArtisanType;
                console.log(data);
                this.ArtisanType = data.concat(response.data);
                console.log(this.ArtisanType);
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
                    0:'使用',
                    1:'禁用'
                };
                return statusMap[parseInt(status)]
            },
            typeShow(type){
                const typeMap = {
                    1:'官网',
                    2:'公众号'
                };
                return typeMap[parseInt(type)]
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
