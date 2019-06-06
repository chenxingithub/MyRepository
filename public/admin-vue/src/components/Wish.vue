<template>
    <div v-if="myAuthCheck">
        <!-- 模态框（Modal） -->
        <div class="modal fade" v-bind:id="type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title text-center" id="myModalLabel">
                            填写心愿
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label for="title">推荐课题:</label> <input name="title" v-model="title" type="text" id="title" class="form-control" required></div>
                        <div class="form-group"><label for="body">内容简介:</label> <textarea name="body" v-model="body" cols="30" rows="10" id="body" class="form-control" required></textarea></div>
                        <div class="form-group"><label for="lecturer">推荐讲师:</label> <input name="lecturer" v-model="lecturer" type="text" id="lecturer" class="form-control"></div>
                        <div class="form-group" v-if="lecturerTypeCheck">
                            <label class="checkbox-inline">
                                <input type="radio" name="lecturer_type" id="lecturer_type1" value="0" v-model="lecturerType">内部讲师
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="lecturer_type" id="lecturer_type2" value="1" v-model="lecturerType">外部讲师
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                        </button>
                        <button type="button" class="btn btn-primary" v-on:click="store">
                            提交
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </div>
        <div class="text-center">
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-success" data-toggle="modal" v-bind:data-target="modalId">{{ description }}</button>
            </div>
        </div>
    </div>
    <div v-else class="text-center">
        <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-primary" data-placement="bottom" data-toggle="tooltip" data-original-title="请先登录">{{ description }}</button>
        </div>
    </div>
</template>
<script>
    export default {
        props:['type','authCheck','description'],
        data: function () {
            return {
                myAuthCheck: !!this.authCheck,
                title:'',
                body:'',
                lecturer:'',
                lecturerType:0
            }
        },
        computed: {
            lecturerTypeCheck: function () {
                return this.type == 'recommend' ? true : false;
            },
            modalId:function(){
                return '#'+this.type;
            }
        },
        methods: {
            store: function(){
                if(this.title === ''){
                    $(this.modalId).modal('hide');
                    toastr.success('课题不能为空!');
                    return false;
                }
                if(this.body === ''){
                    $(this.modalId).modal('hide');
                    toastr.success('内容简介不能为空!');
                    return false;
                }
                this.$http.post('/wish',{'type':this.type, 'title':this.title, 'body': this.body, 'lecturer':this.lecturer, 'lecturerType':this.lecturerType}).then(response=>{
                    if(response.data.ret ===0 ){
                        this.title = '';
                        this.body = '';
                        this.lecturer = '';
                        this.lecturerType = 0;
                        $(this.modalId).modal('hide');
                        toastr.success('心愿提交成功!');
                    }else if(!response.data.enrolled){
                        toastr.success('心愿提交失败!');
                    }else{
                        toastr.success('操作失败!');
                    }
                });
            }
        }
    }
</script>