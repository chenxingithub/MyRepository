<template>
    <a title="登录后才可以点赞" href="javascript:void(0);" v-bind:class="[Astyle?'js-pl-praise list-praise r':'']" data-id="52645">
        <i v-if="myAuthCheck" class="like"
           v-bind:class="[myLiked?'icon_zan_btn_on':'icon_zan_btn']"
           v-on:click="like"
           aria-hidden="true"
        ></i>
        <i v-else class="like icon_zan_btn" onclick="loginShow()" aria-hidden="true" data-toggle="tooltip" ></i>
        <span class="amount">{{ text }}</span>
    </a>
</template>

<script>
    export default {
        props:['id','type','liked','authCheck','likeCount','notAStyle'],
        mounted() {
            if(this.myAuthCheck && typeof(this.myLiked)==="undefined"){
                this.$http.post('/liked',{'id':this.id,'type':this.type}).then(response=>{
                    this.myLiked = response.data.liked
                })
            }
        },
        data() {
            return {
                myLiked: typeof(this.liked)==='undefined'?undefined:!!this.liked,
                myAuthCheck: !!this.authCheck,
                myLikeCount: this.likeCount,
                Astyle : !this.notAStyle,
            }
        },
        computed: {
            text(){
                return this.myLikeCount ? this.myLikeCount : ''
            }
        },
        methods: {
            like(){
                this.$http.post('/like',{'id':this.id,'type':this.type}).then(response=>{
                    this.myLiked = response.data.liked
                    this.myLikeCount = response.data.like_count
                })
            }
        }
    }
</script>

<style>
i.like{
    width:30px;
}
</style>