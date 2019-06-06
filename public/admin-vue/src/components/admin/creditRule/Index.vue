<template>
  <section class="content-header">
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
                <div class="col-md-12">
                  <el-input @keyup.enter.native="handleFilter" style="width: 200px;" class="filter-item" placeholder="规则名称" v-model="listQuery.q">
                  </el-input>
                  <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
                  <el-button class="filter-item" style="margin-left: 10px;" type="primary" @click="handleCreate" icon="edit">添加</el-button>
                </div>
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
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                <tr>
                  <th style="width: 10px">ID</th>
                  <th style="word-break: break-word;width: 10%;">规则名称</th>
                  <th tyle="word-break: break-word;">积分唯一标识</th>
                  <th>积分数值</th>
                  <th style="word-break: break-word;width: 5%;">创建用户</th>
                  <th>创建时间</th>
                  <th>更新时间</th>
                  <th>状态</th>
                  <th>操作</th>
                </tr>
                <template v-show="!listLoading">
                  <tr v-for="item in items">
                    <td>{{ item['id'] }}</td>
                    <td><a @click="handleUpdate(item)">{{ item['name'] }}</a></td>
                    <td>{{ item['slug'] }}</td>
                    <td>{{ item['reward'] }}</td>
                    <td>{{ item['user']['name'] }} <el-tag>{{ item['user']['email'] }}</el-tag></td>
                    <td>{{ item['created_at'] }}</td>
                    <td>{{ item['updated_at'] }}</td>
                    <td>
                      <el-tag :type="item['status'] | statusFilter">{{ item['status'] | statusShow }}</el-tag>
                    </td>
                    <td>
                      <el-button v-if="item['status']!='1'" size="small" type="success" @click="handleModifyStatus(item,0)">发布
                      </el-button>
                      <el-button v-if="item['status']!='0'" size="small" @click="handleModifyStatus(item,1)">草稿
                      </el-button>
                      <el-button size="small" type="danger" icon="delete" @click="handleDelete(item)">删除</el-button>
                    </td>
                  </tr>
                </template>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix" v-show="!listLoading">
              <div class="pull-right">
                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="listQuery.page" :page-sizes="[10,20,30, 50]"
                               :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="total">
                </el-pagination>
              </div>
            </div>
          </div>
          <!-- /.box -->

          <el-dialog :title="textMap[dialogStatus]" v-model="dialogFormVisible" size="small">
            <el-form class="small-space" :model="temp" :rules="rules" ref="temp"  label-position="left" label-width="120px" style='width: 400px; margin-left:100px;'>
              <el-form-item label="规则名称" prop="name">
                <el-input v-if="dialogStatus=='update'" v-model="temp.name" disabled></el-input>
                <el-input v-else v-model="temp.name"></el-input>
              </el-form-item>
              <el-form-item label="积分数值" prop="reward">
                <el-input type="number" v-model.number="temp.reward"></el-input>
              </el-form-item>
              <el-form-item label="积分唯一标识" prop="slug">
                <el-input v-model="temp.slug"></el-input>
              </el-form-item>
              <el-form-item label="规则详情" prop="desc">
                <el-input type="textarea" v-model="temp.desc"></el-input>
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
    </section>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                myName: '积分规则列表',
                myCreateButtonText: '创建积分规则',
                items: [],
                total:null,
                listLoading: true,
                listQuery: {
                    page: 1,
                    limit: 10,
                    q: undefined
                },
                headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
                dialogFormVisible:false,
                temp: {
                    id: 0,
                    name: '',
                    reward: 0,
                    slug:'',
                    desc:''
                },
                dialogStatus: '',
                textMap: {
                    update: '编辑',
                    create: '创建'
                },
                rules: {
                    name: [
                        { required: true, message: '请输入规则名称', trigger: 'blur' },
                        { min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                    ],
                    reward: [
                        { required: true, type: 'number', message: '请输入正确的积分', trigger: 'blur' }
                    ],
                    slug: [
                        { required: true, message: '请输入积分唯一标识', trigger: 'blur' },
                        { min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                    ],
                    desc: [
                        { required: true, message: '请输入规则详情', trigger: 'blur' },
                    ]
                }
            }
        },
        created() {
            this.loadData()
        },
        filters: {
            statusFilter(status) {
                const statusMap = {
                    'true': 'success',
                    'false': 'gray',
                };
                return statusMap[!!status]
            },
            statusShow(status){
                const statusMap = {
                    'true':'发布',
                    'false': '草稿'
                };
                return statusMap[!!status]
            }
        },
        methods: {
            loadData() {
                this.listLoading = true;
                let url = '/api/creditRule';
                this.$axios.get(url, {
                    headers: this.$store.state.headers,
                    params: this.listQuery
                }).then(response => {
                    this.items = response.data.data
                    this.total = response.data.meta.pagination.total
                    this.listLoading = false;
                })
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
            handleDelete(data) {
                this.$confirm('此操作将永久删除改信息, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/api/creditRule/' + data.id)
                        .then((response) => {
                            if(response.status === 204){
                                this.$message({
                                    showClose: true,
                                    message: (response.data.length === 0?'删除成功':response.data),
                                    type: 'success'
                                });
                                this.loadData();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: (response.data.error.data.size ===0 ?'删除失败':response.data.error.data),
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
            handleModifyStatus(data, status) {
                this.$axios.put('/api/creditRule/' + data.id, {
                    status: !status,
                    action: 'changeStatus'
                })
                    .then((response) => {
                        if(response.status === 204){
                            this.$message({
                                showClose: true,
                                message: (response.data.length === 0?'操作成功':response.data),
                                type: 'success'
                            });
                            data.status = !status;
                        }else{
                            this.$message({
                                showClose: true,
                                message: (response.data.error.data.size ===0 ?'操作失败':response.data.error.data),
                                type: 'error'
                            });
                        }
                    })
            },
            handleUpdate(data) {
                this.dialogStatus = 'update';
                this.$axios.get('/api/creditRule/' + data.id)
                    .then((response) => {
                        if(response.status === 200){
                            this.temp.id = response.data.id
                            this.temp.name = response.data.name
                            this.temp.reward = response.data.reward
                            this.temp.slug = response.data.slug
                            this.temp.desc = response.data.desc
                            this.dialogFormVisible = true
                        }
                    })
            },
            handleCreate(){
                this.dialogStatus = 'create'
                this.temp.id = ''
                this.temp.name = ''
                this.temp.reward = 0
                this.temp.slug = ''
                this.temp.desc = ''
                this.dialogFormVisible = true
            },
            update(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.put('/api/creditRule/' + this.temp.id, {
                            reward: this.temp.reward,
                            slug: this.temp.slug,
                            desc: this.temp.desc,
                            slug : this.temp.slug
                        })
                            .then((response) => {
                                if(response.status === 204) {
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.length === 0 ? '操作成功' : response.data),
                                        type: 'success'
                                    });
                                    this.dialogFormVisible = false
                                    this.loadData();
                                }else{
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.error.data.size === 0?'操作失败':response.data.error.data),
                                        type: 'error'
                                    });
                                }
                            }, response => {
                                if (response.status === 422) {
                                    var msg = response.data.password.size === 0?'':response.data.password
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
                        this.$axios.post('/api/creditRule', {
                            reward: this.temp.reward,
                            desc: this.temp.desc,
                            name: this.temp.name,
                            slug : this.temp.slug
                        })
                            .then((response) => {
                                if(response.status === 204) {
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.length === 0 ? '操作成功' : response.data),
                                        type: 'success'
                                    });
                                    this.dialogFormVisible = false
                                    this.loadData();
                                }else{
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.error.data.size === 0?'操作失败':response.data.error.data),
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
                                }else{
                                    this.$message({
                                        showClose: true,
                                        message: '操作发生错误',
                                        type: 'error'
                                    });
                                }
                            })
                    } else {
                        return false;
                    }
                });
            }
        }

    }
</script>