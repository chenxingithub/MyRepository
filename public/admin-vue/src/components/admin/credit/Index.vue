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
                  <el-input @keyup.enter.native="handleFilter" style="width: 200px;" class="filter-item" placeholder="详情" v-model="listQuery.q">
                  </el-input>
                  <el-select v-model="listQuery.rule" filterable placeholder="积分规则" clearable>
                    <el-option
                            v-for="item in rules"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                  </el-select>
                  <el-select v-model="listQuery.user" filterable placeholder="用户" clearable>
                    <el-option
                            v-for="item in users"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                  </el-select>
                  <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
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
                  <th style="word-break: break-word;width: 10%;">积分流水规则</th>
                  <th>获得积分</th>
                  <th style="word-break: break-word;width: 15%;">获得用户</th>
                  <th>获得时间</th>
                  <th style="word-break: break-word;width: 15%;">详情</th>
                  <th>操作</th>
                </tr>
                <template v-show="!listLoading">
                  <tr v-for="item in items">
                    <td>{{ item['id'] }}</td>
                    <td>{{ item['rule'] }}</td>
                    <td>{{ item['point'] }}</td>
                    <td>{{ item['user']['name'] }} <el-tag>{{ item['user']['email'] }}</el-tag></td>
                    <td>{{ item['created_at'] }}</td>
                    <td>{{ item['body'] }}</td>
                    <td>
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
        </div>
      </div>
    </section>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                myName: '积分流水列表',
                items: [],
                total:null,
                listLoading: true,
                users:[],
                rules:[],
                listQuery: {
                    page: 1,
                    limit: 10,
                    q: undefined,
                    user:'',
                    rule:''
                }
            }
        },
        created() {
            this.loadData()
        },
        methods: {
            loadData() {
                this.listLoading = true;
                let url = '/api/credit';
                this.$axios.get(url, {
                    headers: this.$store.state.headers,
                    params: this.listQuery
                }).then(response => {
                    this.items = response.data.data
                    this.total = response.data.meta.pagination.total
                    this.rules = response.data.meta.rules
                    this.users = response.data.meta.users
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
                    this.$axios.delete('/api/credit/' + data.id)
                        .then((response) => {
                            if (response.status === 204) {
                                this.$message({
                                    showClose: true,
                                    message: (response.data.length === 0 ? '删除成功' : response.data),
                                    type: 'success'
                                });
                                this.loadData();
                            } else {
                                this.$message({
                                    showClose: true,
                                    message: (response.data.error.data.size === 0 ? '删除失败' : response.data.error.data),
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
            }
        }
    }
</script>