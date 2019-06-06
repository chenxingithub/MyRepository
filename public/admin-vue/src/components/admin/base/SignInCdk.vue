
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        签到礼品码列表
        <small>{{GameInfo.game_name}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><router-link to="/admin/user" tag="a"><i class="fa fa-map-o"></i> {{GameInfo.game_name}}</router-link></li>
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
                                    <el-input style="width: 50%;" @keyup.enter.native="handleFilter" class="filter-item" placeholder="礼包码" v-model="listQuery.keyword">
                                    </el-input>
                                </div>
                                <div class="col-md-3">
                                    天数：
                                <el-select v-model="listQuery.cdk_day_id" placeholder="请选择签到天数">
                                  <el-option label="第一天" :value="1"></el-option>
                                  <el-option label="第二天" :value="2"></el-option>
                                  <el-option label="第三天" :value="3"></el-option>
                                  <el-option label="第四天" :value="4"></el-option>
                                  <el-option label="第五天" :value="5"></el-option>
                                  <el-option label="第六天" :value="6"></el-option>
                                  <el-option label="第七天" :value="7"></el-option>
                                  <el-option label="每周" :value="8"></el-option>
                                </el-select>
                                </div>
                                <div class="col-md-3">

                               <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
  <el-button class="filter-item" type="primary" @click="handleCreate" icon="edit">添加</el-button>
  <el-button class="filter-item" type="danger" icon="delete" @click="handleBatchDelete">批量删除</el-button>
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
                                        <th style="width: 50px;"><input type="checkbox"  style="display: inherit;" class="checkbox" @click="selectAll" /></th>
                                        <th style="width: 10px">ID</th>
                                        <th>礼包类型</th>
                                        <th>cdk码</th>
                                        <th>领取状态</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>操作</th>
                                    </tr>
                                    <template>
                                    <tr v-for="item in items">
                                        <td><input type="checkbox" :value="item['id']" v-model="selectArr"></td>
                                        <td>{{ item['id'] }}</td>
                                        <td>{{ item['title'] }}</td>
                                        <td>{{ item['code'] }}</td>
                                        <td>
                                            <el-tag :type="item['status'] | statusFilter">{{ item['status'] | statusShow }}</el-tag>
                                        </td>
                                        <td>{{ item['created_at'] }}</td>
                                        <td>{{ item['updated_at'] }}</td>
                                        <td>

<!--                                             <el-button v-if="parseInt(item['status'])==1" size="small" type="success" @click="handleModifyStatus(item,0)">正常
</el-button>
<el-button v-if="parseInt(item['status'])==0" size="small" @click="handleModifyStatus(item,1)">禁用
</el-button> -->

                                            <el-button v-if="parseInt(item['sort'])==0" size="small" type="success" @click="handleModifySort(item,1)">置顶
                                            </el-button>
                                            <el-button v-if="parseInt(item['sort'])==1" size="small" @click="handleModifySort(item,0)">取消置顶
                                            </el-button>

<!--                                             <el-button size="small" type="info" icon="edit" @click="handleUpdate(item)">修改</el-button> -->
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
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="listQuery.page" :page-sizes="[10,20,30, 50,1000]"
                                               :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="total">
                                </el-pagination>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible"  size="small">
                        <el-form class="small-space" :model="temp" :rules="rules" ref="temp"  label-position="left" label-width="100px" style='width: 800px; margin-left:100px;'>
                          <el-form-item label="天数" prop="cdk_day_id">
                            <el-select v-model="temp.cdk_day_id" placeholder="请选择天数" >
                                  <el-option label="第一天" value="1"></el-option>
                                  <el-option label="第二天" value="2"></el-option>
                                  <el-option label="第三天" value="3"></el-option>
                                  <el-option label="第四天" value="4"></el-option>
                                  <el-option label="第五天" value="5"></el-option>
                                  <el-option label="第六天" value="6"></el-option>
                                  <el-option label="第七天" value="7"></el-option>
                                  <el-option label="每周" value="8"></el-option>
                            </el-select>
                            </el-form-item>
                            <el-form-item label="导入cdk码">
                              <el-upload
                                  class="upload-demo"
                                  drag
                                  :headers="headers"
                                  :on-success="handleCdkSuccess"
                                  :action="CdkUrl"
                                  :before-upload="beforeCdkUpload"
                                  multiple>
                                  <i class="el-icon-upload"></i>
                                  <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                                  <div class="el-upload__tip" slot="tip">只能上传xlsx文件</div>
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
            selectArr: [],
            config: {
              initialFrameWidth: null,
              initialFrameHeight: 350
            },
            myName: '礼包码列表',
            loading: true,
            items: [],
            total:null,
            listQuery: {
                page: 1,
                limit: 10,
                keyword: '',
                status:'',
                cdk_day_id:'',
                game_id:this.$route.params.id,
                terminal:'0'
            },
            CdkType: [
                {
                    id: null,
                    cdk_type_title: '请选择',
                }
            ],
            StatusType: [
                {
                    value: null,
                    label: '请选择',
                },
                {
                    value: 0,
                    label: '未领取',
                },
                {
                    value: 1,
                    label: '已领取',
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
                cdk_day_id: '',
                cdk_list: '',
            },
            beforeCdkUpload(file) {
                const isXLSX = file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                const isXLS = file.type === 'application/vnd.ms-excel';

                if (!isXLSX && !isXLS) {
                    this.$message.error('亲只能上传xlsx或xls文件喔！');
                }
                return (isXLSX || isXLS);
            },
            rules: {
                cdk_type_id: [
                    { type:'number',required: true, message: '请选择礼品类型', trigger: 'change' }
                ],
            },
            CdkUrl:'/upload/xlsx',
    }
  },
  /*components: {UE},*/
  methods: {
            loadData() {
                this.loading = true
                let url = '/sign-in-cdk';
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
            handleCdkSuccess(res, file) {
                if(res.cdk_list){
                    this.temp.cdk_list = res.cdk_list;
                }
            },
            selectAll(event) {
                var _this = this;
                console.log(event.currentTarget)
                if (!event.currentTarget.checked) {
                    this.selectArr = [];
                } else { //实现全选
                    _this.selectArr = [];
                    _this.items.forEach(function(item, i) {
                        _this.selectArr.push(item['id']);
                    });
                }
            },
            getSTime(val){
                 this.temp.expired_at = val;
                 console.log(this.temp.expired_at);
            },
            handleCreate(){
                this.dialogStatus = 'create'
                this.temp.id = ''
                this.temp.cdk_day_id = ''
                this.temp.cdk_list = ''
                this.dialogFormVisible = true
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/user/excel?q='+this.listQuery.q)
            },
            handleUpdate(data) {
                this.dialogStatus = 'update';
                this.temp.id = data.id
                this.temp.title = data.cdk_type_title
                this.temp.content = data.cdk_type_content
                this.temp.expired_at = data.expired_at
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
                this.$confirm('此操作将永久删除该cdk码, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/sign-in-cdk/' + data.id)
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
            handleBatchDelete() {
                this.$confirm('此操作将永久批量删除该cdk码, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.put('/sign-in-cdk/delete-batch', {
                            selectArr: this.selectArr,
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
                this.dialogUserVisible = true
            },
            update(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.put('/admin/cdk-type/put/' + this.temp.id, {
                            cdk_type_title: this.temp.title,
                            cdk_type_content: this.temp.content,
                            expired_at: this.temp.expired_at,
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
                        this.loading = true;
                        this.$axios.post('/sign-in-cdk', {
                            cdk_day_id: this.temp.cdk_day_id,
                            cdk_list: this.temp.cdk_list,
                            game_id:this.$route.params.id,
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
                },
            }).then(response => {
                var data = this.CdkType;
                this.CdkType = data.concat(response.data);
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
                    0:'未领取',
                    1:'已领取'
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
