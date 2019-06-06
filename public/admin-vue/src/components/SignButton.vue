<template>
    <li v-if="myAuthCheck"  data-placement="bottom" data-toggle="tooltip" v-bind:data-original-title="title"
    v-html="'<a href=\'javascript:void(0);\'>'+text+'</a>'" v-bind:disabled="mySigned" v-on:click="sign"><a href=""></a></li>
    <li v-else  data-placement="bottom" data-toggle="tooltip"  title="请先登录">
        <a href="javascript:void(0);" onclick="loginShow()">签到</a></li>
</template>

<script>
    export default{
        props:['authCheck','signed'],
        data(){
            return{
                mySigned: !!this.signed,
                myAuthCheck: !!this.authCheck,
                title:'点击签到'
            }
        },
        computed: {
            text(){
                return this.mySigned ? '已签到' : '签到'
            }
        },
        methods: {
            sign(){
                this.$http.post('/sign').then(response=>{
                    if(response.data.ret == true){
                        this.mySigned = response.data.signed;
                        this.title = '已签到';
                        alert('签到成功，已连续签到' + response.data.continuity_count+'次');
                    }
                })
            }
        }
    }
</script>
