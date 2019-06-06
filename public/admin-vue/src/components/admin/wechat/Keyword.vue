
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        关键字回复
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
                                    <el-input style="width: 50%;" @keyup.enter.native="handleFilter" class="filter-item" placeholder="关键字" v-model="listQuery.keyword">
                                    </el-input>
                                </div>
                                <div class="col-md-3">
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
                                        <th>关键字</th>
                                        <th>回复类型</th>
                                        <th>是否模糊查询</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>操作</th>
                                    </tr>
                                    <template>
                                    <tr v-for="item in items">
                                        <td>{{ item['id'] }}</td>
                                        <td>{{ item['keyword'] }}</td>
                                        <td>{{ item['type'] | typeShow }}</td>
                                        <td><el-tag :type="item['is_vague'] | statusFilter">{{ item['is_vague'] | is_vagueShow }}</el-tag></td>
                                        <td>{{ item['created_at'] }}</td>
                                        <td>{{ item['updated_at'] }}</td>
                                        <td>
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
                            <el-form-item label="关键字" prop="keyword">
                                <el-input style="width: 50%;" size="small" v-if="dialogStatus=='update'" v-model="temp.keyword"></el-input>
                                <el-input style="width: 50%;" size="small" v-else v-model="temp.keyword"></el-input>
                            </el-form-item>
                            <el-form-item label="上下文" prop="is_context">
                                  <el-switch
                                    active-text="开"
                                    inactive-text="关"
                                    v-model="temp.is_context"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949"
                                    active-value="1"
                                    inactive-value="0">
                                  </el-switch>
                            </el-form-item>
                            
                            <el-form-item label="盖楼模式" prop="is_cover_building">
                                  <el-switch
                                    active-text="开"
                                    inactive-text="关"
                                    v-model="temp.is_cover_building"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949"
                                    active-value="1"
                                    inactive-value="0">
                                  </el-switch>
                            </el-form-item>

                            <el-form-item label="上一句问题" prop="front_problem" v-if="temp.is_context == '1'">
                                <el-input style="width: 50%;" size="small" v-if="dialogStatus=='update'" v-model="temp.front_problem"></el-input>
                                <el-input style="width: 50%;" size="small" v-else v-model="temp.front_problem"></el-input>
                            </el-form-item>
                            <el-form-item label="上一句回复" prop="front_reply" v-if="temp.is_context == '1'">
                                <el-input style="width: 50%;" size="small" v-if="dialogStatus=='update'" v-model="temp.front_reply"></el-input>
                                <el-input style="width: 50%;" size="small" v-else v-model="temp.front_reply"></el-input>
                            </el-form-item>
                             <el-form-item label="模糊匹配" prop="is_vague">
                                   <el-select size="small" v-model="temp.is_vague" placeholder="请选择">
                                       <el-option label="是" value="1"></el-option>
                                       <el-option label="否" value="0"></el-option>
                                   </el-select>
                             </el-form-item>
                            
                            <template v-if="temp.is_cover_building == '1'">    
                                <el-form-item label="楼层" v-for="(flooritem,floorkey) in temp.floor" :key="floorkey">
                                    <el-input style="width: 50%;" size="small" v-if="dialogStatus=='update'" v-model="temp.floor[floorkey]"></el-input>
                                    <el-input style="width: 50%;" size="small" v-else v-model="temp.floor[floorkey]"></el-input>
                                    <el-button v-if="floorkey <= 0" size="small " icon="el-icon-plus" type="primary" @click="AddSecond()">添加</el-button>
                                    <el-button v-if="floorkey > 0" size="small " icon="el-icon-delete" type="danger" @click="DeleteSecond(floorkey)">删除</el-button>
                                </el-form-item>
                            </template>
                            <el-form-item label="回复类型" prop="type" v-if="temp.is_cover_building != '1'">
                                  <el-select size="small" v-model="temp.type" placeholder="请选择">
                                      <el-option label="文本" value="1"></el-option>
                                      <el-option label="图文" value="2"></el-option>
                                      <el-option label="礼包码" value="3"></el-option>
                                  </el-select>
                            </el-form-item>
                            <el-form-item label="回复文本" prop="type" v-if="temp.type == '1'">
                                <el-input
                                  style="width: 50%;"
                                  type="textarea"
                                  :autosize="{ minRows: 2, maxRows: 4}"
                                  placeholder="请输入内容"
                                  v-model="temp.text_content">
                                </el-input>
                            </el-form-item>

                            <el-form-item label="选择礼包" prop="cdk_type_id" v-else-if="temp.type == '3'">
                                <el-select v-model="temp.cdk_type_id" placeholder="请选择">
                                            <el-option
                                              v-for="item in CdkType"
                                              :key="item.id"
                                              :label="item.cdk_type_title"
                                              :value="item.id">
                                            </el-option>
                                </el-select>
                            </el-form-item>

                            <el-collapse v-else-if="temp.type == '2'" v-model="activeName" accordion>
                            <template v-for="(replyitem,key) in temp.reply">
                            <el-collapse-item  :name="key">
                            <template slot="title">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>图文{{ key+1 }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>点击展开</span>
                                    </div>
                                </div>
                            </template>
                                <el-form-item label="图文标题" prop="title">
                                    <el-input size="small" v-if="dialogStatus=='update'" v-model="replyitem.title" style="width: 50%;"></el-input>
                                    <el-input size="small" v-else v-model="replyitem.title"></el-input>
                                </el-form-item>
                                <el-form-item label="图文描述" prop="describe">
                                    <el-input style="width: 50%;" size="small" v-if="dialogStatus=='update'" v-model="replyitem.describe"></el-input>
                                    <el-input style="width: 50%;" size="small" v-else v-model="replyitem.describe"></el-input>
                                </el-form-item>
                                <el-form-item label="跳转Url" prop="url">
                                    <el-input style="width: 50%;" size="small" v-if="dialogStatus=='update'" v-model="replyitem.url" ></el-input>
                                    <el-input style="width: 50%;" size="small" v-else v-model="replyitem.url"></el-input>
                                </el-form-item>
                                <el-form-item label="图文图片" >
                                    <el-upload
                                            class="avatar-uploader"
                                            :action="avatarUrl"
                                            :headers="headers"
                                            :on-success="handleAvatarSuccess"
                                            :before-upload="beforeAvatarUpload">
                                        <img v-if="replyitem.img_url" :src="ImgPrefix+replyitem.img_url" class="avatar">
                                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                    </el-upload>
                                </el-form-item>
                                <el-button size="small" icon="el-icon-delete" type="danger" @click="replyDelete(temp.reply,key)">删除</el-button>
                            </el-collapse-item>

                            </template>
                            </el-collapse>

                            <el-form-item label="回复关键字" v-if="temp.is_cover_building == '1'" v-for="(sub_keyworditem,sub_keywordkey) in temp.sub_keyword" :key="sub_keywordkey">
                                <el-input style="width: 50%;" size="small" v-if="dialogStatus=='update'" v-model="temp.sub_keyword[sub_keywordkey]"></el-input>
                                <el-input style="width: 50%;" size="small" v-else v-model="temp.sub_keyword[sub_keywordkey]"></el-input>
                            </el-form-item>
                        </el-form>
                        <span slot="footer" class="dialog-footer">
                            <el-button size="small" v-if="temp.type == '2'" type="success" icon="el-icon-edit" @click="AddReply(temp.id)">添加图文</el-button>
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
            CdkType: [
                {
                    id: 0,
                    cdk_type_title: '请选择',
                }
            ],
            dialogFormVisible:false,
            dialogStatus: '',
            headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
            temp: {
                id: '',
                keyword: '',
                front_problem: '',
                floor: [""],
                sub_keyword: [""],
                front_reply: '',
                is_vague: '',
                is_context: '',
                is_cover_building: '',
                type: '',
                text_content: '',
                reply:[],
                cdk_type_id:'',
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
                keyword: [
                    { required: true, message: '请输入关键字', trigger: 'blur' },
                    { min: 1, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                ],
                is_vague: [
                    { required: true, message: '请选择是否模糊匹配', trigger: 'change' }
                ],
                cdk_type_id: [
                    {type:'number',  required: true, message: '请选择公众号礼包', trigger: 'change' }
                ],
                type: [
                    {required: true, message: '请选择是回复类型', trigger: 'change' }
                ],
                sub_keyword: [
                    { required: true, message: '请输入回复关键字', trigger: 'blur' },
                ],
                floor: [
                    { required: true, message: '请输入楼层', trigger: 'blur' },
                ],
            },
            avatarUrl:'/upload/image',
    }
  },
  /*components: {UE},*/
  methods: {
            DeleteSecond(key){
                this.temp.floor.splice(key,1);
                this.temp.sub_keyword.splice(key,1);
            },
            AddSecond(){
                this.temp.floor.push("");
                this.temp.sub_keyword.push("");
            },
            loadData() {
                this.loading = true
                let url = '/wechat/keyword';
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
                this.temp.reply = [];
                this.temp.text_content = '';
                this.temp.id = '';
                this.temp.keyword = '';
                this.temp.front_problem = '';
                this.temp.floor = [''];
                this.temp.sub_keyword = [''];
                this.temp.front_reply = '';
                this.temp.type = '';
                this.temp.is_vague = '';
                this.temp.is_context = 0;
                this.temp.is_cover_building = 0;
                this.temp.cdk_type_id = 0;
                this.dialogFormVisible = true
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/user/excel?q='+this.listQuery.q)
            },
            handleUpdate(data) {
                this.loading = true
                this.$axios.get('/wechat/reply/' + data.id)
                    .then((response) => {
                        if(response.status === 200){
                            this.dialogStatus = 'update';
                            this.temp.reply = response.data
                            this.temp.text_content = response.data[0].text_content
                            this.temp.id = data.id;
                            this.temp.keyword = data.keyword;
                            this.temp.front_problem = data.front_problem;
                            this.temp.front_reply = data.front_reply;
                            this.temp.floor = data.floor?data.floor.split("|"):data.floor;
                            let sub_keyword = [];
                            for (var i = response.data.length - 1; i >= 0; i--) {
                                sub_keyword[i] = response.data[i]['sub_keyword'];  
                            };
                            console.log(sub_keyword);
                            this.temp.sub_keyword = sub_keyword
                            this.temp.type = data.type.toString();
                            this.temp.is_vague = data.is_vague.toString();
                            this.temp.is_context = data.is_context.toString();
                            this.temp.is_cover_building = data.is_cover_building.toString();
                            this.temp.cdk_type_id = response.data[0].cdk_type_id;
                            this.dialogFormVisible = true
                            this.loading = false
                            console.log(this.temp.cdk_type_id);
                        }
                    })
            },
            handleDelete(data) {
                this.$confirm('此操作将永久删除改用户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/wechat/keyword-reply/' + data.id)
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
                        this.$axios.put('/wechat/keyword-reply/' + this.temp.id, {
                            reply: this.temp.reply,
                            text_content: this.temp.text_content,
                            keyword: this.temp.keyword,
                            front_problem: this.temp.front_problem,
                            front_reply: this.temp.front_reply,
                            type: this.temp.type,
                            is_vague: this.temp.is_vague,
                            is_context: this.temp.is_context,
                            cdk_type_id: this.temp.cdk_type_id,
                            floor: this.temp.floor,
                            sub_keyword: this.temp.sub_keyword,
                            is_cover_building: this.temp.is_cover_building,
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
                        if (this.temp.is_cover_building == 1) {
                            this.temp.type = 4;
                        };
                        this.$axios.post('/wechat/keyword-reply', {
                            reply: this.temp.reply,
                            text_content: this.temp.text_content,
                            keyword: this.temp.keyword,
                            front_problem: this.temp.front_problem,
                            front_reply: this.temp.front_reply,
                            type: this.temp.type,
                            is_vague: this.temp.is_vague,
                            is_context: this.temp.is_context,
                            game_id:this.$route.params.id,
                            cdk_type_id: this.temp.cdk_type_id,
                            floor: this.temp.floor,
                            sub_keyword: this.temp.sub_keyword,
                            is_cover_building: this.temp.is_cover_building,
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
            let url2 = '/cdk-type/get';
            this.$axios.get(url2, {
                headers: this.$store.state.headers,
                params: {
                    query_type: 1,
                    game_id:this.$route.params.id,
                    type:2,
                },
            }).then(response => {
                var data = this.CdkType;
                this.CdkType = data.concat(response.data);
                console.log(this.CdkType);
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
            typeShow(type){
                const typeMap = {
                    1:'文本',
                    2:'图文',
                    3:'礼包码',
                    4:'关键字'
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
