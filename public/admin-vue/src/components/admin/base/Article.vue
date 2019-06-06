
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
        <li><router-link to="/admin/user" tag="a"><i class="fa fa-map-o"></i> {{GameInfo.game_name}}</router-link></li>
        <li class="active">文章列表</li>
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
                                    <el-input style="width: 50%;" @keyup.enter.native="handleFilter" class="filter-item" placeholder="文章标题" v-model="listQuery.keyword">
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
                                <div class="col-md-3">
                                    文章分类：
                                    </el-input>
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
                                    终端：
                                    </el-input>
                                    <el-select v-model="listQuery.terminal" placeholder="请选择">
                                      <el-option label="全部" value="0"></el-option>
                                      <el-option label="PC端" value="1"></el-option>
                                      <el-option label="移动端" value="2"></el-option>
                                  </el-select>
                                </div>
                            </div>
                                <div class="row" style="margin-top: 10px;">
                                <div class="col-md-3">

                               <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>

  <router-link :to="{ path: '/base/article_add/'+ $route.params.id }"><el-button class="filter-item" type="primary"  icon="edit">添加</el-button></router-link>
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
                                        <th>文章标题</th>
                                        <th>文章副标题</th>
                                        <th>简述</th>
                                        <th>外部跳转</th>
                                        <th>访问量</th>
                                        <th>所属文章分类</th>
                                        <th>终端</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    <template>
                                    <tr v-for="item in items">
                                        <td>{{ item['id'] }}</td>
                                        <td>{{ item['title'] }}</td>
                                        <td>{{ item['subtitled'] }}</td>
                                        <td>{{ item['sketch'] }}</td>
                                        <td>{{ item['jump_url'] }}</td>
                                        <td>{{ item['traffic_volume'] }}</td>
                                        <td>{{ item['name'] }}</td>
                                        <td >{{ item['terminal'] | terminalShow }}</td>
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
  <!--                                           <el-button size="small"  icon="edit" @click="handleUpdate(item)">查看内容</el-button> -->
  <router-link :to="{name:'ArticleEdit',query: { 
            game_id: listQuery.game_id, 
            artisan_type_id: item.artisan_type_id,
            bg_img: item.bg_img,
            content: item.content,
            created_at: item.created_at,
            fabulous_num: item.fabulous_num,
            icon: item.icon,
            id: item.id,
            jump_url: item.jump_url,
            traffic_volume: item.traffic_volume,
            name: item.name,
            sketch: item.sketch,
            sort: item.sort,
            status: item.status,
            subtitled: item.subtitled,
            terminal: item.terminal,
            label: item.label,
            enclosure_url: item.enclosure_url,
            title: item.title,
            updated_at: item.updated_at,
        }}"><el-button size="small" type="info" icon="edit" >修改</el-button></router-link>
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
                            <el-form-item label="文章标题" prop="title">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.title"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.title"></el-input>
                            </el-form-item>
<!--                             <el-form-item label="所属分类" prop="ArtisanType">
  <el-select v-model="temp.artisan_type_id" placeholder="请选择">
  <el-option label="区域二" value="beijing"></el-option>
        <el-option
          v-for="item in ArtisanType"
          :key="item.id"
          :label="item.name"
          :value="item.id">
        </el-option>
  </el-select>
</el-form-item> -->
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

                            <el-form-item label="开始时间" prop="created_at">
                              <el-date-picker
                                v-model="temp.created_at"
                                type="datetime"
                                @change="getSTimeStart"
                                format="yyyy-MM-dd HH:mm:ss"
                                value-format="yyyy-MM-dd HH:mm:ss"
                                placeholder="请选择开始时间">
                              </el-date-picker>
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
            GameInfo: '',
            config: {
              initialFrameWidth: null,
              initialFrameHeight: 350
            },
    		myName: '文章列表',
    		loading: true,
    		items: [],
    		total:null,
            listQuery: {
                page: 1,
                limit: 10,
                keyword: '',
                status:'',
                artisan_type_id:'',
                game_id:this.$route.params.id,
                terminal:'0'
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
            },
            avatarUrl:'/admin/upload/image?type=avatar',
    }
  },
  route: {
  canReuse() {
    return false
  }
},
  /*components: {UE},*/
  methods: {
            loadData() {
            	this.loading = true
                let url = '/artisan/get';
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
                    this.temp.picture = res.url;
                }
            },
            handleCreate(){
                this.dialogStatus = 'create'
                this.temp.id = ''
                this.temp.title = ''
                this.temp.artisan_type_id = ''
                this.temp.name = ''
                this.temp.status = ''
                this.temp.sort = ''
                this.temp.created_at = ''
                this.dialogFormVisible = true
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/user/excel?q='+this.listQuery.q)
            },
            handleUpdate(data) {
                this.dialogStatus = 'update';
                this.temp.id = data.id
                this.temp.name = data.name
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
                this.$confirm('此操作将永久删除该焦点图, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/artisan/delete/' + data.id)
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
                        this.$axios.put('/artisan-type/put/' + this.temp.id, {
                            name: this.temp.name,
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
                        this.$axios.post('/artisan-type/post', {
                            name: this.temp.name,
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
                this.$axios.put('/artisan/put/' + data.id, {
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
                this.$axios.put('/artisan/put/' + data.id, {
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
            let GamesInfoUrl = '/games/get/'+this.$route.params.id;
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
                    0:'使用',
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
                    1:'主页',
                    2:'新闻'
                };
                return statusMap[parseInt(status)]
            }
            
        },
}
</script>
