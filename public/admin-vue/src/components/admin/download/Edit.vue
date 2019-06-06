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
                                    <form class="form col-md-6 col-md-offset-3" role="form" @submit.prevent="edit">
                                        <div class="form-group">
                                            <label for="title">{{ $t('form.title') }}</label>
                                            <input type="text" class="form-control" id="title" :placeholder="$t('form.title')" v-model="discussion.title">
                                        </div>
                                        <div class="form-group">
                                            <label for="download">文件地址</label>
                                            <div class="input-group">
                                                <input type="text" id="download" name="download" v-model="discussion.download" class="form-control">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default popup_selector" type="button" data-inputid="download">选择文件!</button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="body">{{ $t('form.body') }}</label>
                                            <textarea class="form-control" id="body" :placeholder="$t('form.body')" v-model="discussion.body" cols="30" rows="10"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <el-button type="primary" @click="handleBack">返回</el-button>
                                            <button type="submit" class="btn btn-info pull-right">{{ $t('form.edit') }}</button>
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
                myName: '修改下载',
                discussion: {},
                editor:''
            }
        },
        mounted() {
            this.editor = CKEDITOR.replace('body');
        },
        created() {
            this.$axios.get('/api/download/' + this.$route.params.id + '/edit')
                .then((response) => {
                    this.discussion = response.data
                    if(this.discussion.body){
                        this.editor.setData(this.discussion.body)
                    }
                })
        },
        methods: {
            edit() {
                this.discussion.body = this.editor.getData()
                this.discussion.download = $("#download").val()
                this.$axios.put('/api/download/' + this.$route.params.id, this.discussion)
                    .then((response) => {
                        if(response.status === 204){
                            this.$message({
                                showClose: true,
                                message: (response.data.length === 0?'修改成功':response.data),
                                type: 'success'
                            });
                            this.$router.push('/admin/download')
                        }else{
                            this.$message({
                                showClose: true,
                                message: (response.data.error.data === 0?'修改失败':response.data.error.data),
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