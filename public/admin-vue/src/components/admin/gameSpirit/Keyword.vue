
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        精灵关键字回复
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
                                <div class="col-md-2">
                                 <!-- @keyup.enter.native="handleFilter"  监听回车事件执行handleFilter方法 -->
                                    <el-input  @keyup.enter.native="handleFilter" class="filter-item" placeholder="规则名" v-model="listQuery.keyword">
                                    </el-input>
                                </div>
                                <div class="col-md-3">
                               <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
  <el-button class="filter-item" type="primary" @click="handleCreate" icon="edit">添加</el-button>
<!--<el-button class="filter-item" type="primary" icon="document" @click="handleDownload">导出</el-button> -->
                                <el-button class="filter-item" type="primary" icon="upload2" @click="handleUpload">导入</el-button>
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
                                        <th>规则名</th>
                                        <th>规则分类</th>
                                        <th>是否模糊查询</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    <template>
                                    <tr v-for="item in items">
                                        <td>{{ item['rule'] }}</td>
                                        <td v-if="item.name" >{{ item['name'] }}</td>
                                        <td v-else >未定义分类</td>
                                        <td><el-tag :type="item['is_vague'] | statusFilter">{{ item['is_vague'] | is_vagueShow }}</el-tag></td>
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
                                            <el-button size="small" type="success" icon="el-icon-edit" @click="handleUpdate(item)">修改</el-button>
                                            <el-button size="small" icon="el-icon-delete" type="danger" @click="handleDelete(item)">删除</el-button>
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
                              <el-form-item label="规则名" prop="rule">
                                 <el-input style="width: 80%;" size="small" v-if="dialogStatus=='update'" v-model="temp.rule"></el-input>
                                <el-input style="width: 80%;" size="small" v-else v-model="temp.rule"></el-input>
                              </el-form-item>
                            <template v-for="(keyword_item,key) in temp.keyword_arr">
                                <el-form-item label="关键字" prop="keyword_arr">
                                    <el-input style="width: 80%;" size="small" v-if="dialogStatus=='update'" v-model="temp.keyword_arr[key]">
                                            <el-select v-model="temp.vague_arr[key]" slot="prepend" placeholder="请选择">
                                              <el-option label="全匹配" value="0"></el-option>
                                              <el-option label="半匹配" value="1"></el-option>
                                            </el-select>
                                            <el-button  v-if="key=='0'" slot="append" size="small" icon="el-icon-edit" type="danger" @click="Createkeyword(key)">添加</el-button>
                                            <el-button  v-else slot="append" size="small" icon="el-icon-delete" type="danger" @click="Deletekeyword(key)">删除</el-button>
                                    </el-input>
                                    <el-input style="width: 80%;" size="small" v-else v-model="temp.keyword_arr[key]">
                                            <el-select v-model="temp.vague_arr[key]" slot="prepend" placeholder="请选择">
                                              <el-option label="全匹配" value="0"></el-option>
                                              <el-option label="半匹配" value="1"></el-option>
                                            </el-select>
                                            <el-button  v-if="key=='0'" slot="append" size="small" icon="el-icon-edit" type="danger" @click="Createkeyword(key)">添加</el-button>
                                            <el-button  v-else slot="append" size="small" icon="el-icon-delete" type="danger" @click="Deletekeyword(key)">删除</el-button>
                                    </el-input>
                                </el-form-item>
                             </template>
                      <el-form-item label="规则分类" prop="spirit_type_id">
                        <el-select size="small" v-model="temp.spirit_type_id" placeholder="请选择">
                                    <el-option
                                      v-for="item in SpiritType"
                                      :key="item.id"
                                      :label="item.name"
                                      :value="item.id">
                                    </el-option>
                        </el-select>
                      </el-form-item>
                            <el-form-item label="回复内容">
                                      <el-button size="small" type="primary" @click="labelInsert('temp')">插入标签</el-button>
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
        v-model="temp.content"
        ref="myQuillEditor"
        :options="editorOption"
>
</quill-editor>
                                    </el-row>
                            </el-form-item>
                        </el-form>
                        <span slot="footer" class="dialog-footer">
                            <el-button size="small" @click="dialogFormVisible = false">取 消</el-button>
                            <el-button size="small" v-if="dialogStatus=='create'" type="primary" @click="create('temp')">确 定</el-button>
                            <el-button size="small" v-else type="primary" @click="update('temp')">确 定</el-button>
                        </span>
                    </el-dialog >

                    <el-dialog title="批量导入" :visible.sync="dialogImportVisible"  size="small">
                                <el-upload
                                  class="upload-demo"
                                  drag
                                  :headers="headers"
                                  :on-success="handleImportSuccess"
                                  :action="ImportUrl"
                                  :before-upload="beforeImportUpload"
                                  multiple>
                                  <i class="el-icon-upload"></i>
                                  <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                                  <div class="el-upload__tip" slot="tip">只能上传xlsx文件</div>
                                </el-upload>
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
  .el-select .el-input {
    width: 130px;
  }
  .input-with-select .el-input-group__prepend {
    background-color: #fff;
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
            var validateKeyword = (rule, value, callback) => {
                for (var k = 0, length = value.length; k < length; k++) {
                        if (!value[k]) {
                            callback(new Error('请输入关键字'));
                        }
                }
                callback();
            };
    return {
            imageUrl: '/upload/image',
            quillUpdateImg: false, // 根据图片上传状态来确定是否显示loading动画，刚开始是false,不显示
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
            activeName: '1',
            GameInfo: '',
            loading:false,
            config: {
              initialFrameWidth: null,
              initialFrameHeight: 350
            },
            myName: '回复列表',
            loading: true,
            items: [],
            total:null,
            listQuery: {
                page: 1,
                limit: 10,
                keyword: '',
                game_id:this.$route.params.id,
                terminal:'0'
            },
            textMap: {
                update: '编辑',
                create: '创建'
            },
            SpiritType: [
                {
                    id: 0,
                    name: '请选择',
                }
            ],
            dialogFormVisible:false,
            dialogImportVisible:false,
            dialogStatus: '',
            headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
            temp: {
                id: '',
                keyword: '',
                keyword_arr: [],
                vague_arr: [],
                keyword_id_arr: [],
                is_vague: '',
                content: '',
                spirit_type_id:'',
                rule:'',
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
                keyword_arr: [
                    { validator: validateKeyword, trigger: 'blur' }
                ],
                cdk_type_id: [
                    {type:'number',  required: true, message: '请选择公众号礼包', trigger: 'change' }
                ],
                rule: [
                    { required: true, message: '请输入用户名称', trigger: 'blur' },
                    { min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                ],
                type: [
                    {required: true, message: '请选择是回复类型', trigger: 'change' }
                ],
            },
            beforeImportUpload(file) {
                const isXLSX = file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                const isXLS = file.type === 'application/vnd.ms-excel';

                if (!isXLSX && !isXLS) {
                    this.$message.error('亲只能上传xlsx或xls文件喔！');
                }
                return (isXLSX || isXLS);
            },
            avatarUrl:'/upload/image',
            ImportUrl:'/spirit/keyword-import',
    }
  },
  /*components: {UE},*/
  methods: {
            loadData() {
                this.loading = true
                let url = '/spirit/keyword';
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
                    this.temp.reply[this.activeName].img_url = res.url;
                }
            },
            handleCreate(){
                this.dialogStatus = 'create'
                this.temp.content = '';
                this.temp.rule = '',
                this.temp.id = '';
                this.temp.spirit_type_id = 0;
                this.temp.keyword_arr = [''];
                this.temp.vague_arr =  ['0'];
                this.dialogFormVisible = true
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/user/excel?q='+this.listQuery.q)
            },
            handleUpdate(data) {
                this.dialogStatus = 'update';
                this.temp.content =  data.content;
                this.temp.id =  data.id;
                this.temp.keyword =  data.keyword;
                this.temp.rule =  data.rule;
                this.temp.keyword_arr =  data.keyword_arr.split(",");
                this.temp.keyword_id_arr =  data.keyword_id_arr;
                this.temp.vague_arr =  data.vague_arr.split(",");
                this.temp.spirit_type_id =  data.type_id;
                this.dialogFormVisible = true
                console.log(this.temp);
            },
            handleModifyStatus(data, status) {
                this.$axios.put('/spirit/keyword', {
                    keyword_id_arr: data.keyword_id_arr,
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
            handleDelete(data) {
                this.$confirm('此操作将永久删除改规则, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/spirit/keyword', {
                            params: {
                                keyword_id_arr: data.keyword_id_arr
                            }
                        })
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
                this.dialogImportVisible = true
            },

 // 富文本图片上传前
            beforeUpload() {
                // 显示loading动画
                this.quillUpdateImg = true
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
                    quill.insertEmbed(length, 'image', 'https://cdn3.ibingniao.com/'+res.url)
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

            labelInsert() {
                let quill = this.$refs.myQuillEditor.quill;

                let length = quill.getSelection().index;

                // 插入图片  res.info为服务器返回的图片地址
                quill.insertEmbed(length, 'text', '{::}');
                // 调整光标到最后
                quill.setSelection(length + 2);
            },
            update(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.put('/spirit/keyword', {
                            keyword_arr: this.temp.keyword_arr,
                            vague_arr: this.temp.vague_arr,
                            keyword_id_arr: this.temp.keyword_id_arr,
                            content: this.temp.content,
                            rule: this.temp.rule,
                            game_id: this.$route.params.id,
                            spirit_type_id: this.temp.spirit_type_id,
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
            handleImportSuccess(res, file) {
                if(res.message == '添加成功！'){
                    this.$message({
                        showClose: true,
                        message: '添加成功！',
                        type: 'success'
                    });
                    this.dialogImportVisible = false
                    this.loadData();
                }else{
                    this.$message({
                        showClose: true,
                        message: '添加失败！',
                        type: 'error'
                    });
                }
            },
            Deletekeyword(key){
                this.temp.keyword_arr.splice(key,1);
                this.temp.vague_arr.splice(key,1);
                console.log(this.temp.keyword_arr);
                console.log(this.temp.vague_arr);
            },
            Createkeyword(key){
                this.temp.vague_arr.push("0");
                this.temp.keyword_arr.push("");
                console.log(this.temp.keyword_arr);
            },
            create(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.post('/spirit/keyword', {
                            keyword_arr: this.temp.keyword_arr,
                            vague_arr: this.temp.vague_arr,
                            rule: this.temp.rule,
                            content: this.temp.content,
                            game_id: this.$route.params.id,
                            spirit_type_id: this.temp.spirit_type_id
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
            replyDelete(data,key){
                data.splice(key,1);
            },
            AddReply(keyword_id){
                if (this.temp.reply.length>=8) {
                    this.$message({
                              showClose: true,
                              message: '亲，图文列表数量不能超过8个呦！',
                              type: 'warning',
                            });
                    return false;
                }
                this.temp.reply.push({
                              id: '',
                              keyword_id: keyword_id,
                              title: '',
                              describe: '',
                              img_url: '',
                              text_content: '',
                        });
            },
        },
        created() {
            //获取游戏信息
            let GamesInfoUrl = '/games/get/'+this.$route.params.id;
            this.$axios.get(GamesInfoUrl, {
            }).then(response => {
                this.GameInfo = response.data;
            })
            let url2 = '/spirit-type';
            this.$axios.get(url2, {
                headers: this.$store.state.headers,
                params: {
                    query_type: 1,
                    game_id:this.$route.params.id
                },
            }).then(response => {
                var data = this.SpiritType;
                this.SpiritType = data.concat(response.data);
                console.log(this.SpiritType);
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
                    1:'文本',
                    2:'图文',
                    3:'礼包码'
                };
                return typeMap[parseInt(type)]
            },
            is_vagueShow(is_vague){
                const is_vagueMap = {
                    0:'否',
                    1:'是'
                };
                return is_vagueMap[parseInt(is_vague)]
            },
            
        },
}
</script>
