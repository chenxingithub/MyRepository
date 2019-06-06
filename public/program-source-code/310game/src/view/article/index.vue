<template>
    <div id="article-dec">
        <head-nav></head-nav>
        <div class="article-head">
            <div class="article-head-bg">
                <!-- 预留放视屏， 暂时图片  -->
            </div>
        </div>
        <div class="article-con" v-if="article">
            <div class="article-tit">
                <div class="article-tit-box">
                    <p>{{ article.title }}</p>
                    <span>{{ article.updated_at.split(' ')[0] }}</span>
                </div>
            </div>
            <div class="article-text" v-if="article">
                <div v-html="article.content"></div>
            </div>
        </div>
        <friendly-link></friendly-link>
        <foot></foot>
    </div>
</template>
<script>
import headNav from '../../components/head/index.vue'
import foot from '../../components/foot/index'
import { request_article } from '../../api/index.js'
import friendlyLink from '../../components/friendlyLink/index'

export default {
    name: 'articleDec',
    components: {
      friendlyLink,
      headNav,
      foot
    },
    data() {
        return {
            article: null
        }
    },
    created() {
        request_article(this.$route.query.id).then(resolve => {

            this.article = resolve.data.data;
        });
    }
}
</script>
<style lang="scss">
#article-dec{
    .article-head{
        width: 100%;
        min-width: 1200px;
        height: 526px;
        .article-head-bg{
            width: 100%;
            height: 526px;
            min-width: 1200px;
            margin: 0 auto;
            background: url('../../assets/article-head-bg.png') no-repeat;
            background-size: cover;
            background-position: center top;
        }
    }
    .article-con{
        width: 100%;
        margin: 0 auto;
        .article-tit{
            border-bottom: 1px solid #dfdfdf;
            width: 100%;
            .article-tit-box{
                width: 1200px;
                padding: 85px 0;
                text-align: center;
                margin: 0 auto;
            }
            p{
                font-size: 32px;
                font-weight: 800;
                margin-bottom: 32px;
            }
            span{
                color: #7b7b7b;
            }
        }
        .article-text{
            width: 1010px;
            padding: 0 95px;
            margin: 0 auto;
            padding: 36px 0 125px;
        }
    }
}
</style>
