<template>
    <div v-if="myAuthCheck">
        <div class="btn-group" role="group" aria-label="...">
            <li v-if="myEnrolled" v-on:click="store" >
                <a href="javascript:void(0);"  title="">取消报名</a>
            </li>
            <li v-else v-on:click="store">
                <a href="javascript:void(0);"  title="">我要报名</a>
            </li>
            <!--<button type="button" v-if="myEnrolled" class="btn btn-primary"  v-on:click="store">取消报名</button>-->
            <!--<button type="button" v-else class="btn btn-success" v-on:click="store">我要报名</button>-->
            <!--<button type="button" class="btn btn-default" v-bind:data-original-title="text" v-html="myEnrollCount">0</button>-->
        </div>
    </div>

    <div v-else class="text-center">
        <li><a href="javascript:void(0);" onclick="loginShow()" title="">请先登录</a></li>
    </div>

</template>

<script>
    export default {
        props:['id','type','enrolled','authCheck','enrollCount'],
        data: function () {
            return {
                myEnrolled: !!this.enrolled,
                myAuthCheck: !!this.authCheck,
                isSuccess : 0,
//                myEnrollCount: this.enrollCount
            }
        },
//        computed: {
//            text: function () {
//                return this.myEnrollCount ? '已报名人数：'+this.myEnrollCount : '';
//            }
//        },
        methods: {
            store: function(){
                this.$http.post('/enroll',{'id':this.id,'type':this.type}).then(response=>{
                    if(response.data.enrolled){
                        $('#onBox').show();
                        $('#zhezhao').show();
                    }else if(!response.data.enrolled){
                        $('#unBox').show();
                        $('#zhezhao').show();
                    }else{
                        alert('操作失败!');
                    }
                    if(response.data != null) {
                        this.myEnrolled = response.data.enrolled;
//                        this.myEnrollCount = response.data.enroll_count;
                    }
                });
            }
        }
    }
</script>