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
                                    <el-input @keyup.enter.native="handleFilter" style="width: 200px;" class="filter-item" placeholder="标题" v-model="listQuery.q">
                                    </el-input>
                                    <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
                                    <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="edit">
                                        <router-link to="/admin/video/create" style="color:#FFF">{{ myCreateButtonText }}</router-link>
                                    </el-button>
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
                                        <th style="word-break: break-word;width: 20%;">标题</th>
                                        <th>回复数</th>
                                        <th>点赞数</th>
                                        <th style="word-break: break-word;width: 15%;">创建用户</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    <template v-show="!listLoading">
                                    <tr v-for="item in items">
                                        <td>{{ item['id'] }}</td>
                                        <td>{{ item['title'] }}</td>
                                        <td>{{ item['reply_count'] }}</td>
                                        <td>{{ item['like_count'] }}</td>
                                        <td>{{ item['user']['name'] }} <el-tag>{{ item['user']['email'] }}</el-tag></td>
                                        <td>{{ item['created_at'] }}</td>
                                        <td>{{ item['updated_at'] }}</td>
                                        <td>
                                            <el-tag :type="item['status'] | statusFilter">{{ item['status'] | statusShow }}</el-tag>
                                        </td>
                                        <td>
                                            <el-button size="small" type="primary" icon="edit" @click="handleUpdate(item)">
                                                修改
                                            </el-button>
                                            <el-button v-if="item['status']!='1'" size="small" type="success" @click="handleModifyStatus(item,0)">发布
                                            </el-button>
                                            <el-button v-if="item['status']!='0'" size="small" @click="handleModifyStatus(item,1)">草稿
                                            </el-button>
                                            <el-button size="small" type="danger" icon="delete" @click="handleDelete(item)">删除</el-button>
                                            <el-button v-if="item['reply_count'] > 0" size="small" @click="editReply(item)" type="primary">管理评论</el-button>
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
                myName: '视频列表',
                myCreateButtonText: '创建视频',
                items: [],
                total:null,
                listLoading: true,
                listQuery: {
                    page: 1,
                    limit: 10,
                    q: undefined
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
            // 评论管理
            editReply(data) {
                this.$router.push('/admin/comment/' + data.id + '/editReply')
            },
            loadData() {
                this.listLoading = true;
                var url = '/api/video';
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
                    this.$axios.delete('/api/video/' + data.id)
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
                                    message: (response.data.error.data === 0?'删除失败':response.data.error.data),
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
            handleUpdate(data){
                this.$router.push('/admin/video/' + data.id + '/edit')
            },
            handleModifyStatus(data, status) {
                this.$axios.put('/api/video/' + data.id, {
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
                                message: (response.data.error.data === 0?'操作失败':response.data.error.data),
                                type: 'error'
                            });
                        }
                    })
            }
        }
    }
</script>