
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        微信菜单管理
        <small>{{GameInfo.game_name}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><router-link to="/admin/user" tag="a"><i class="fa fa-map-o"></i> {{GameInfo.game_name}}</router-link></li>
        <li class="active">菜单列表</li>
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
                                        <th>菜单名称</th>
                                        <th>菜单类型</th>
                                        <th>菜单URL</th>
                                    </tr>
                                    <template v-for="(item,key) in items">
                                    <tr>
                                        <td>
                                            <div class="row">
                                            <div class="col-md-4">
                                            <el-input style="width: 50%;"
                                                size="small "
                                                placeholder="请输入菜单名称"
                                                v-model="item.name">
                                              </el-input>
                                            </div>
                                            <div class="col-md-2">
                                            <el-button size="small " icon="el-icon-plus" type="primary" @click="AddSecond(key)">添加</el-button>
                                            </div>
                                            <div class="col-md-2">
                                            <el-button size="small " icon="el-icon-delete" type="danger" @click="Delete(items,key)">删除</el-button>
                                            </div>
                                            </div>
                                        </td>
                                        <td>  
                                        <el-select size="small " v-model="item.type" placeholder="请选择">
                                            <el-option
                                              v-for="MenuType in MenuTypes"
                                              :key="MenuType.value"
                                              :label="MenuType.label"
                                              :value=MenuType.value>
                                            </el-option>
                                        </el-select>
                                        </td>
                                        <td>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <el-input style="width: 50%;"
                                                size="small "
                                                placeholder="请输入菜单Url或关键字"
                                                v-model="item.url">
                                              </el-input>
                                            </div>
                                        </div>
                                        </td>
                                    </tr>
                                    <!-- 判断是否有子级 -->
                                        <template v-if="item.son">
                                            <tr v-for="(sonitem,sonkey) in item.son">
                                                <td>
                                                    <div class="row">
                                                    <div class="col-md-2">
                                                    </div>
                                                    <div class="col-md-4">
                                                    <el-input style="width: 50%;"
                                                        size="small "
                                                        placeholder="请输入菜单名称"
                                                        v-model="sonitem.name">
                                                      </el-input>
                                                    </div>
                                                    <div class="col-md-2">
                                                    <el-button size="small " icon="el-icon-delete" type="danger" @click="Delete(item.son,sonkey)">删除</el-button>
                                                    </div>
                                                    </div>
                                                </td>
                                                <td>  
                                                <el-select size="small " v-model="sonitem.type" placeholder="请选择">
                                                    <el-option
                                                      v-for="MenuType in MenuTypes"
                                                      :key="MenuType.value"
                                                      :label="MenuType.label"
                                                      :value=MenuType.value>
                                                    </el-option>
                                                </el-select>
                                                </td>
                                                <td>                                       
                                                <div class="row">
                                                <div class="col-md-10">
                                                    <el-input style="width: 50%;"
                                                    size="small "
                                                    placeholder="请输入菜单Url或关键字"
                                                    v-model="sonitem.url">
                                                  </el-input>
                                                </div>
                                            </div></td>
                                            </tr>
                                        </template>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <div class="row">
                                <div class="col-md-1">
    <el-button size="small " icon="el-icon-plus" type="primary" @click="AddFirst()">添加</el-button>
                                </div>
                                <div class="col-md-1">
    <el-button size="small " icon="el-icon-edit" type="success" @click="handleUpdate()">提交</el-button>
                                </div>
                            </div>
</div> 
                    </div>
                    <!-- /.box -->
                    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible"  size="small">
                        <el-form class="small-space" :model="temp" :rules="rules" ref="temp"  label-position="left" label-width="100px" style='width: 800px; margin-left:100px;'>
                            <el-form-item label="标题" prop="title">
                                <el-input style="width: 50%;" v-if="dialogStatus=='update'" v-model="temp.title"></el-input>
                                <el-input style="width: 50%;" v-else v-model="temp.title"></el-input>
                            </el-form-item>
                            <el-form-item label="图片">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="ImgUrl"
                                        :show-file-list="false"
                                        :headers="headers"
                                        :on-success="handleImgUrlSuccess"
                                        :before-upload="beforeAvatarUpload">
                                    <img v-if="temp.img_url" :src="ImgPrefix+temp.img_url" class="avatar media-object img-circle">
                                    <i v-else class="el-icon-plus avatar-uploader-icon media-object img-circle"></i>
                                </el-upload>
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
<script>
import { mapState, mapActions } from 'vuex';
/*import UE from '../../ue/ue.vue';*/
export default {
  data() {
         var checkExpired_at = (rule, value, callback) => {
            if (!value) {
            callback(new Error('请选择过期时间'));
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
            myName: '微信菜单',
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
            MenuTypes: [{
              value: 1,
              label: '链接'
            }, {
              value: 2,
              label: '触发关键字'
            }],
            value: '1',
            textMap: {
                update: '编辑',
                create: '创建'
            },
            dialogFormVisible:false,
            dialogStatus: '',
            headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
            temp: {
                id: '',
                img_url: '',
                title: '',
                day_num: ''
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
            },
            ImgUrl:'/upload/image',
    }
  },
  /*components: {UE},*/
  methods: {
            loadData() {
                this.loading = true
                let url = '/wechat/menu';
                this.$axios.get(url, {
                    headers: this.$store.state.headers,
                    params: this.listQuery
                }).then(response => {
                    this.items = response.data;
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
            handleImgUrlSuccess(res, file) {
                console.log(res);
                if(res.url){
                    this.temp.img_url = res.url;
                }
            },
            getSTime(val){
                 this.temp.expired_at = val;
                 console.log(this.temp.expired_at);
            },
            handleCreate(){
                this.$confirm('此操作将会更新公众号的菜单, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/week-sign-gift/delete/' + data.id)
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
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/user/excel?q='+this.listQuery.q)
            },
            handleUpdate() {
                this.$confirm('此操作将会更新公众号的菜单, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.loading = true;
                    this.$axios.put('/wechat/menu', {
                            menu_data:this.items,
                            game_id: this.$route.params.id,
                            action: 'changeContent'
                        })
                        .then((response) => {
                            if(response.status === 200){
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'更新成功':response.data.message),
                                    type: 'success'
                                });
                                this.loadData();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'更新失败':response.data.message),
                                    type: 'error'
                                });
                                this.loadData();
                            }
                        }, (response) => {
                            this.$message({
                                showClose: true,
                                message: '更新失败',
                                type: 'error'
                            });
                        })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消更新'
                    });
                });
            },
            handleDelete(data) {
                this.$confirm('此操作将永久删除该礼包类型, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/week-sign-gift/delete/' + data.id)
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
                        this.$axios.put('/week-sign-gift/put/' + this.temp.id, {
                            img_url: this.temp.img_url,
                            title: this.temp.title,
                            day_num: this.temp.day_num,
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
            AddFirst(){
                if (this.items.length>=3) {
                    this.$message({
                              showClose: true,
                              message: '亲，一级菜单不能超过3个呦！',
                              type: 'warning'
                            });
                    return false;
                }
                this.items.push({
                              name: '',
                              type: 1,
                              url: '',
                              parent_id: 0,
                              son: []
                        });
            },
            AddSecond(key){
                if (this.items[key]['son'].length>=5) {
                    this.$message({
                              showClose: true,
                              message: '亲，一级菜单下二级菜单不能超过5个呦！',
                              type: 'warning',
                            });
                    return false;
                }
                this.items[key]['son'].push({
                              name: '',
                              type: 1,
                              url: '',
                              parent_id: this.items[key]['id'],
                              son: []
                        });
            },
            Delete(data,key){
                data.splice(key,1);
                console.log(this.items);
            },
            handleModifyStatus(data, status) {
                this.$axios.put('/admin/cdk-type/put/' + data.id, {
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
