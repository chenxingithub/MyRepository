
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        敏感字过滤
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
                                    <el-input  @keyup.enter.native="handleFilter" class="filter-item" placeholder="敏感字" v-model="listQuery.keyword">
                                    </el-input>
                                </div>
                                <div class="col-md-3">
                               <el-button class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter" >搜索</el-button>
                               <el-button class="filter-item" type="primary" @click="handleCreate" icon="edit">添加</el-button>
<!-- <el-button class="filter-item" type="primary" icon="document" @click="handleDownload">导出</el-button> -->
                                <!-- <el-button class="filter-item" type="primary" icon="upload2" @click="handleUpload">导入</el-button> -->
                                </div>
                            </div>
                        </div>
                      <div class="table-responsive no-padding">
                        <div class="box">
                          <el-table
                            :data="items"
                            style="width: 100%">
                            <el-table-column
                              prop="id"
                              label="序号">
                            </el-table-column>

                            <el-table-column
                              prop="sensitive_word"
                              label="敏感字">
                            </el-table-column>

                            <el-table-column
                              prop="created_at"
                              label="时间">
                            </el-table-column>

                            <el-table-column
                              prop="keyword"
                              label="操作">
                              <template slot-scope="scope">
                                <el-button
                                  size="mini"
                                  @click="handleUpdate(scope.row)" icon="el-icon-edit">编辑</el-button>
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
                        <el-form class="small-space" :model="temp" :rules="rules" ref="temp"  label-position="left" label-width="100px" style='width: 800px; margin-left:100px;'>
                            <el-form-item label="敏感字" prop="sensitive_word">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.sensitive_word"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.sensitive_word"></el-input>
                            </el-form-item>
                        </el-form>
                        <span slot="footer" class="dialog-footer">
                            <el-button size="small" @click="dialogFormVisible = false">取 消</el-button>
                            <el-button size="small" v-if="dialogStatus=='create'" type="primary" @click="create('temp')">确 定</el-button>
                            <el-button size="small" v-else type="primary" @click="update('temp')">确 定</el-button>
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
export default {
  data() {
    return {
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
                sensitive_word: '',
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
                sensitive_word: [
                    { required: true, message: '请输入敏感字', trigger: 'blur' },
                ],
            },
            avatarUrl:'/upload/image',
    }
  },
  /*components: {UE},*/
  methods: {
            loadData() {
                this.loading = true
                let url = '/examineadmin/sensitive-word';
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
                this.temp.sensitive_word = '';
                this.dialogFormVisible = true
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/spirit/retrieval/excel?keyword='+this.listQuery.keyword+'&game_id='+this.$route.params.id)
            },
            handleUpdate(data) {
                this.dialogStatus = 'update';
                this.temp.sensitive_word = data.sensitive_word;
                this.temp.id = data.id;
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
                    this.$axios.delete('/examineadmin/sensitive-word/' + data.id)
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
                        this.$axios.put('/examineadmin/sensitive-word/' + this.temp.id, {
                            sensitive_word: this.temp.sensitive_word,
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
                        this.$axios.post('/examineadmin/sensitive-word', {
                            sensitive_word: this.temp.sensitive_word
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
            retrievalSignUpdate(data){
                this.loading = true
                this.$axios.put('/spirit/retrieval/' + data.id, {
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
                        this.$axios.post('/spirit/retrieval', {
                            to: data.from,
                            f_id: data.id,
                            game_id: this.$route.params.id,
                            retrieval_message: this.fastReply
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
