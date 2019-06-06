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
                                    <el-input @keyup.enter.native="handleFilter" style="width: 200px;" class="filter-item" placeholder="内容" v-model="listQuery.q">
                                    </el-input>
                                    <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
                                    <el-button class="filter-item" type="primary" icon="search" @click="getBack()" >返回</el-button>
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
                                    <th style="word-break: break-word;width: 20%;">内容</th>
                                    <th>点赞数</th>
                                    <th style="word-break: break-word;width: 15%;">发表用户</th>
                                    <th>创建时间</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                <template v-show="!listLoading">
                                    <tr v-for="item in items">
                                        <td>{{ item['id'] }}</td>
                                        <td>{{ item['body'] }}</td>
                                        <td>{{ item['like_count'] }}</td>
                                        <td>{{ item['user']['name'] }} <el-tag>{{ item['user']['email'] }}</el-tag></td>
                                        <td>{{ item['created_at'] }}</td>
                                        <td>
                                            <el-tag :type="item['status'] | statusFilter">{{ item['status'] | statusShow }}</el-tag>
                                        </td>
                                        <td>
                                            <el-button v-if="item['status']!='1'" size="small" type="success" @click="handleModifyStatus(item,0)">展示
                                            </el-button>
                                            <el-button v-if="item['status']!='0'" size="small" @click="handleModifyStatus(item,1)">锁定
                                            </el-button>
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
                myName: '评论列表',
                items: [],
                total:null,
                listLoading: true,
                listQuery: {
                    page: 1,
                    limit: 10,
                    q: undefined,
                }
            }
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
                    'true':'展示',
                    'false': '锁定'
                };
                return statusMap[!!status]
            }
        },
        created() {
            this.$axios.get('/api/comment/' + this.$route.params.id + '/editReply')
                .then((response) => {
                    this.items = response.data.data;
                    this.total = response.data.meta.pagination.total;
                    this.listLoading = false;
                })
        },
        methods: {
            getBack() {
                history.go(-1);
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
            loadData() {
                this.$axios.get('/api/comment/' + this.$route.params.id + '/editReply', {
                    headers: this.$store.state.headers,
                    params: this.listQuery
                })
                    .then((response) => {
                        this.items = response.data.data;
                        this.total = response.data.meta.pagination.total;
                        this.listLoading = false;
                    })
            },
            handleModifyStatus(data, status) {
                this.$axios.put('/api/comment/' + data.id + '/editReply', {
                    status: !status
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
                                message: (response.data.error.data === 0?'操作失败':response.data.error.data),
                                type: 'error'
                            });
                        }
                    })
            }
        }
    }
</script>