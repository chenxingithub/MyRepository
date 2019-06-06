<template>
    <div class="course-subcontainer clearfix">
        <div class="course-left">
            <ul class="course-menu course-code-menu clearfix js-course-menu" data-ower="all" data-sort="last" style="position: absolute; left: 0px;">
                <li class="course-menu-item"><a class="active" href="#" id="plMenu">评论</a></li>
                <!--<li class="course-menu-item"><a href="#" id="qaMenu">问答</a></li>
                <li class="course-menu-item"><a href="#" id="noteMenu">笔记</a></li>
                <li class="course-menu-item"><a href="#" id="mateMenu">同学代码</a></li>-->
            </ul>
            <div id="disArea" class="lists-container list-wrap">
                <form class="form-horizontal" style="margin-top: 50px;" @submit.prevent="comment" v-if="myAuthCheck">
                <div id="pl-content" class="list-tab-con">
                    <div class="publish clearfix" id="discus-publish">
                        <div class="publish-wrap publish-wrap-pl">
                            <div class="pl-input-wrap clearfix">
                                <a href="#" class="user-head l">
                                    <img :src="avatar" alt="知识">
                                </a>
                                <div id="js-pl-input-fake" class="pl-input-inner l">
                                    <textarea v-model="body" maxlength="300" id="js-pl-textarea" class="js-placeholder" placeholder="扯淡、吐槽、表扬、鼓励……想说啥就说啥！"></textarea>
                                    <span class="num-limit"><span id="js-pl-limit" >{{ body.length }}</span>/300</span>
                                </div>
                                <div class="pl-input-btm input-btm clearfix">
                                    <div class="verify-code l"></div>
                                    <input type="button" class="r course-btn" v-if="!myAuthCheck" value="登录参与平均">
                                    <input type="submit" id="js-pl-submit" v-else="" class="r course-btn" value="发表评论">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <div id="plLoadListData">
                    <div class="pl-container">
                        <ul>
                            <li class="pl-list clearfix" v-for="comment in comments" >
                                <div class="pl-list-avator">
                                    <a href="#" target="_blank">
                                        <img width="40" height="40" v-bind:src="comment.avatar" title="shadowpj">
                                    </a>
                                </div>
                                <div class="pl-list-main">
                                    <div class="pl-list-nick">
                                        <a href="#" target="_blank">{{ comment.name }}</a>
                                    </div>
                                    <div class="pl-list-content" v-html="comment.body_html">
                                    </div>
                                    <div class="pl-list-btm clearfix">
                                        <span class="pl-list-time l">时间: {{ comment.created_at }}</span>
                                            <like-button v-bind:like-count="comment.like_count" v-bind:liked="comment.liked" v-bind:id="comment.id" v-bind:auth-check="authCheck" type="Comment"></like-button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- 页数 -->
        <div class="col-md-12 media-bottom text-right" v-html="pageLink">
        </div>
        <!-- 页数 end -->
    </div>
</template>
<script>
    export default{
        props:[
            'title',
            'type',
            'id',
            'commentsCount',
            'page',
            'name',
            'avatar',
            'authCheck'
        ],
        data() {
            return {
                comments: [],
                pageLink: '',
                body: '',
                commentsChangeCount:0,
                myAuthCheck:!!this.authCheck,
            }
        },
        mounted() {
            var url = '/comment'
            this.commentsChangeCount=this.commentsCount
            this.$http.get(url, {
                params: {
                    type: this.type,
                    id: this.id,
                    page:this.page
                }
            }).then((response) => {
                if(response.data.ret){
                    if(response.data.data != null){
                        response.data.data.forEach((data) => {
                            data.body_html = marked(data.body)
                            return data
                        })
                        this.comments = response.data.data,
                        this.pageLink = decodeURIComponent(response.data.pageLink)
                    }
                }
            })
        },
        methods: {
            comment() {
                if( this.body.length > 300 ) {
                    alert('超出最大长度限制');
                    return false;
                }
                const data = {
                    id: this.id,
                    type: this.type,
                    body: this.body
                }

                this.$http.post('/comment', data)
                    .then((response) => {
                        let comment = null
                        comment = response.data.data
                        comment.body_html = marked(comment.body)
                        this.comments.push(comment)
                        this.body = ''
                        this.commentsChangeCount+=1
                        toastr.success('发表评论成功!')
                    })
            },
            reply(name) {
                $('#body').focus()
                this.body = '@' + name + ' '
            }
        }
    }
</script>
<style>
.comment .img-circle{
    width: 46px;
    height: 46px
}

.comment .operate{
    font-size:12px;
}

.comment .operate a{
    margin-right:5px;
    text-decoration:none;
}

@media screen and (max-width: 767px) {
    .comment .media-left {
        display: none;
    }
    .own-avatar {
        display: none;
    }
}
</style>
