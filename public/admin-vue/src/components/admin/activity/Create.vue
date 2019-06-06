<template>
    <div>
        <section class="content" slot="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ myName }}</h3>
                        </div>
                            <div class="box-body">
                                <form class="form col-md-6 col-md-offset-3" role="form" @submit.prevent="create">
                                    <div class="form-group">
                                        <label for="title">{{ $t('form.title') }}</label>
                                        <input type="text" class="form-control" id="title" :placeholder="$t('form.title')" v-model="discussion.title">
                                    </div>
                                    <div class="form-group">
                                        <label for="body">{{ $t('form.body') }}</label>
                                        <textarea class="form-control" id="body" :placeholder="$t('form.body')" v-model="discussion.body" cols="30" rows="10"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <el-button type="primary" @click="handleBack">返回</el-button>
                                        <button type="submit" class="btn btn-info pull-right">{{ $t('form.create') }}</button>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                myName: '创建活动',
                discussion: {},
                editor:''
            }
        },
        mounted() {
            this.editor = CKEDITOR.replace('body');
        },
        methods: {
            create() {
                this.discussion.body = this.editor.getData()
                this.$axios.post('/api/activity', this.discussion)
                    .then((response) => {
                        if(response.status === 204){
                            this.$message({
                                showClose: true,
                                message: (response.data.length === 0?'创建成功':response.data),
                                type: 'success'
                            });
                            this.$router.push('/admin/activity')
                        }else{
                            this.$message({
                                showClose: true,
                                message: (response.data.error.data === 0?'创建失败':response.data.error.data),
                                type: 'error'
                            });
                        }
                    })
            },
            handleBack(){
                this.$router.back();
            }
        }
    }
</script>