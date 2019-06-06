
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        精灵用户反馈
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
                                    <el-input  @keyup.enter.native="handleFilter" class="filter-item" placeholder="用户名/用户id" v-model="listQuery.keyword">
                                    </el-input>
                                </div>
                                <div class="col-md-3">
                               <el-button class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter" >搜索</el-button>
                               <el-button class="filter-item" type="primary" icon="document" @click="handleDownload">导出</el-button>
                                <!-- <el-button class="filter-item" type="primary" icon="upload2" @click="handleUpload">导入</el-button> -->
                                </div>
                            </div>
                        </div>
                      <div class="table-responsive no-padding">
                        <div class="box">
                          <el-table
                            :data="items"
                            stripe
                            style="width: 100%">
                            <el-table-column type="expand">
                              <template slot-scope="props">
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
                                      v-model="fastReply"
                                      ref="myQuillEditor"
                                      :options="editorOption"
                              >
                              </quill-editor>
                              </el-row>

                              <span slot="footer" class="dialog-footer" style="float: right;margin-top: 3rem;">
                                  <el-button size="small" type="primary" @click="fastReplycreate(props.row)">快速回复</el-button>
                              </span>
                              </template>
                            </el-table-column>


                            <el-table-column
                              prop="service_area"
                              label="区服"
                              width="180">
                            </el-table-column>

                            <el-table-column
                              prop="from"
                              label="玩家id"
                              width="180">
                            </el-table-column>

                            <el-table-column
                              prop="role_name"
                              label="用户名">
                            </el-table-column>

                            <el-table-column
                              prop="vip"
                              label="VIP">
                            </el-table-column>

                            <el-table-column
                              prop="role_rank"
                              label="等级"
                              width="100">
                            </el-table-column>

                            <el-table-column
                              prop="created_at"
                              label="时间">
                            </el-table-column>

                            <el-table-column
                              prop="feedback_message"
                              label="反馈信息"
                              
                              >
                            </el-table-column>

                            <el-table-column
                              label="是否标记"
                              prop="is_sign"
                              :filters="[{ text: '已标记', value: 1 }, { text: '未标记', value: 0 }]"
                              :filter-method="filterIs_sign"
                              >
                              <template slot-scope="scope">
                                <el-switch
                                  v-model="scope.row.is_sign"
                                  active-color="#13ce66"
                                  inactive-color="#ff4949"
                                  :active-value="1"
                                  :inactive-value="0"
                                  @change="feedbackSignUpdate(scope.row)"
                                  >
                                </el-switch>
                              </template>
                            </el-table-column>

                            <el-table-column
                              prop="keyword"
                              label="操作">
                              <template slot-scope="scope">
                                <el-button
                                  size="mini"
                                  @click="handleUpdate(scope.row)" icon="el-icon-edit">回复</el-button>
                                <el-button
                                  size="mini"
                                  type="danger"
                                  @click="handleDelete(scope.row)" icon="el-icon-delete">删除</el-button>
                              </template>
                            </el-table-column>
                          </el-table>
                    </div>
                    </div>
                   <!-- /.box-body -->
                      <div class="box-footer clearfix">
                          <div class="pull-right">
                              <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="listQuery.page" :page-sizes="[10,20,30, 50]"
                                             :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="total">
                              </el-pagination>
                          </div>
                      </div>
                    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible"  size="small">

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
                             <el-row v-loading="quillUpdateImg" style="margin-bottom: 2rem;">
                              <quill-editor
                                      v-model="Reply"
                                      ref="myQuillEditor"
                                      :options="editorOption"
                              >
                              </quill-editor>
                              </el-row>
                                  <p style="text-align: right;">
                                    <el-button size="small" @click="dialogFormVisible = false">取 消</el-button>
                                    <el-button size="small" type="primary" @click="update()">发表回复</el-button>
                                  </p>
                              <el-row style="margin-bottom: 1rem;">
                                  <el-col :span="24"><span>最近20条聊天记录</span></el-col>
                              </el-row>

                              <el-card class="box-card" v-for="(dialoguevalue, dialogueindex) in dialogue" :key="dialogueKey" style="margin-top: 1.5rem;">
                               <el-row>
                                  <el-col :span="24"><span>用户名:</span>{{dialoguevalue.role_name}}</el-col>
                              </el-row>
                              <el-row>
                                  <el-col :span="24"><span>内容：</span></el-col>
                              </el-row>
                              <el-row>
                                  <el-col :span="23" :offset="1" v-html="dialoguevalue.feedback_message"></el-col>
                              </el-row>
                              </el-card>
                    </el-dialog >
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
  </div>
</template>
<style>
  .el-table td:first-child .cell, .el-table th:first-child .cell {
    padding-left: 10px;
  }
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
  .text {
    font-size: 14px;
  }

  .item {
    margin-bottom: 18px;
  }

  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
  .clearfix:after {
    clear: both
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
                game_id:this.$route.params.id
            },
            textMap: {
                update: '对话',
                create: '创建'
            },
            fastReply:'',
            Reply:'',
            dialogue:'',
            dialogFormVisible:false,
            dialogStatus: '',
            headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
            temp: {
                id: '',
                from: '',
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
            avatarUrl:'/upload/image',
    }
  },
  /*components: {UE},*/
  methods: {
            loadData() {
                this.loading = true
                let url = '/spirit/feedback';
                this.$axios.get(url, {
                    headers: this.$store.state.headers,
                    params: this.listQuery
                }).then(response => {
                    this.items = response.data.data
                    this.total = response.data.total
                    this.loading = false
                })
            },
          filterIs_sign(value, row) {
            return row.is_sign === value;
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
                window.open(window.location.protocol+'//'+window.location.host+'/spirit/feedback/excel?keyword='+this.listQuery.keyword+'&game_id='+this.$route.params.id)
            },
            handleUpdate(data) {
                this.dialogStatus = 'update';
                this.Reply = '';
                this.$axios.get('/spirit/feedback-dialogue', {
                  headers: this.$store.state.headers,
                  params: {
                              game_id: this.$route.params.id,
                              Interlocutor: data.from
                          }
                }).then((response) => {
                  this.dialogue = response.data;
                  this.temp.from = data.from;
                  this.temp.id = data.id;
                })
                console.log(this.temp);
                this.dialogFormVisible = true
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
                    this.$axios.delete('/spirit/feedback/' + data.id)
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
       
            // 富文本图片上传失败
            uploadError() {
                // loading动画消失
                this.quillUpdateImg = false
                this.$message.error('图片插入失败')
            },
            update(){

              this.$axios.post('/spirit/feedback', {
                  to: this.temp.from,
                  f_id: this.temp.id,
                  game_id: this.$route.params.id,
                  feedback_message: this.Reply
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
            feedbackSignUpdate(data){
                this.loading = true
                this.$axios.put('/spirit/feedback/' + data.id, {
                    is_sign: data.is_sign,
                    action: 'changeSign'
                })
                  .then((response) => {
                      if(response.status === 200){
                          this.$message({
                              showClose: true,
                              message: (response.data.message.length === 0 ? '修改成功！' : response.data.message),
                              type: 'success'
                          });
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
            },
            fastReplycreate(data){
                        this.$axios.post('/spirit/feedback', {
                            to: data.from,
                            f_id: data.id,
                            game_id: this.$route.params.id,
                            feedback_message: this.fastReply
                        })
                            .then((response) => {
                                if(response.status === 200){
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0 ? '操作成功' : response.data.message),
                                        type: 'success'
                                    });
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
