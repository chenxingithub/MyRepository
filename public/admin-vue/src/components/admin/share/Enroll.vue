<template>
    <section class="content-header">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ myTitleTag }}：{{ discussion_title }}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <el-input @keyup.enter.native="handleFilter" style="width: 200px;" class="filter-item" placeholder="姓名或邮箱" v-model="listQuery.q">
                                    </el-input>
                                    <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
                                    <el-button class="filter-item" type="primary" icon="document" @click="handleDownload">导出</el-button>
                                    <el-button type="primary" @click="handleBack">返回</el-button>
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
                                    <th>头像</th>
                                    <th>姓名</th>
                                    <th>邮箱</th>
                                    <th>报名时间</th>
                                    <th>操作</th>
                                </tr>
                                <template v-show="!listLoading">
                                    <tr v-for="item in items">
                                        <td>{{ item['user']['id'] }}</td>
                                        <td><img v-bind:src="item['user']['avatar']" width="45" alt=""></td>
                                        <td>{{ item['user']['name'] }}</td>
                                        <td>{{ item['user']['email'] }}</td>
                                        <td>
                                            {{ item['created_at'] }}
                                        </td>
                                        <td>
                                            <el-button size="small" type="danger" icon="delete" @click="handleDelete(item)">删除报名</el-button>
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
                myTitleTag: '分享标题',
                myName: '报名列表',
                items: [],
                total:null,
                listLoading: true,
                listQuery: {
                    page: 1,
                    limit: 10,
                    q: ''
                },
                discussion_title:''
            }
        },
        created() {
            this.loadData()
        },
        methods: {
            loadData() {
                this.listLoading = true;
                var url = '/api/share/'+this.$route.params.id+'/enroll';
                this.$axios.get(url, {
                    headers: this.$store.state.headers,
                    params: this.listQuery
                }).then(response => {
                    this.items = response.data.data
                    this.total = response.data.meta.pagination.total
                    this.discussion_title = response.data.meta.pagination.discussion_title
                    this.listLoading = false
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
                this.$confirm('此操作将删除该账号报名, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/api/share/' + this.$route.params.id+'/enroll', {
                        params:{user_id: data.user.id}
                    })
                        .then((response) => {
                            if(response.status === 204){
                                this.$message({
                                    showClose: true,
                                    message: (response.data.length === 0?response.data:'删除报名成功'),
                                    type: 'success'
                                });
                                this.loadData();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: (response.data.error.data === 0?response.data.error.data:'删除报名失败'),
                                    type: 'error'
                                });
                            }
                        }, (response) => {
                            this.$message({
                                showClose: true,
                                message: '删除报名失败',
                                type: 'error'
                            });
                        })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除报名操作'
                    });
                });
            },
            handleBack(){
                this.$router.back();
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/share/excel/'+this.$route.params.id+'?q='+this.listQuery.q)
            }
        }
    }
</script>