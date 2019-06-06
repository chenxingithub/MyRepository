<template>
    <div id="home-con-pt2">
        <div class="con-pt1-l">
            <div class="bn-wx-code"></div>
        </div>
        <div class="con-pt1-r border-box" v-if="newsList">
            <div class="news-info-tit">
                <span>{{ newsList.name[0] }}</span>
                <router-link to="/newsInfo?listId=79" class="list-more">更多</router-link>
            </div>
            <div class="news-info-con">
                <div class="news-info-img">
                    <!-- <img src="../../assets/news-info-icon.png" alt="news"> -->
                </div>
                <div class="news-info-list">
                    <ul>
                        <li v-for="(item, index) in newsList.data[0].data" :key="index">
                            <router-link :to="{path: '/article', query: {id: item.id}}">
                                <p class="list-text">{{ item.title }}</p>
                                <span>{{ item.updated_at.split(' ')[0] }}</span>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'homeContentPart2',
    props: ['newsList'],
}
</script>
<style lang="scss">
#home-con-pt2{
    width: 1200px;
    height: 267px;
    margin: 19px auto 0;

    .con-pt1-l{
        float: left;
        width: 310px;
        height: 265px;
        background: url('../../assets/310game-gzh.jpg') no-repeat;
        background-size: 100% 100%;
        border: 1px solid #ebebeb;
        position: relative;
        overflow: hidden;
        .bn-wx-code{
            width: 310px;
            height: 265px;
            background: url('../../assets/bnsy-wx.png') no-repeat;
            background-size: 130px auto;
            background-position: center;
            background-color: rgba(49,170,226, .86);
            position: absolute;
            top: -100%;
            left: 0;
            opacity: 0;
            filter: alpha(opacity=1);
            transition: all ease-out 0.4s;
        }
    }
    .con-pt1-l:hover{
        .bn-wx-code{
            top: 0;
            opacity: 1;
            filter: alpha(opacity=100);
            transition: all ease-in 0.4s;
        }
    }
    .con-pt1-r{
        float: right;
        width: 870px;
        height: 267px;
        border: 1px solid #ebebeb;
        padding: 0 20px 0 20px;
        background: #ffffff;
        .news-info-tit{
            margin: 23px 0 18px 0;
            position: relative;
            span{
                margin-left: 18px;
                font-size: 18px;
            }
            span:before{
                height: 20px;
                width: 5px;
                content: " ";
                background: #31aae2;
                position: absolute;
                top: 1px;
                left: 0px;
            }
            .list-more{
              float: right;
              font-size: 14px;
              color: #aeaeae;
            }
            .list-more:hover{
              color: #31aae2;
            }
        }
        .news-info-con{
            .news-info-img{
                width: 263px;
                height: 174px;
                float: left;
                background: url('../../assets/news-info-icon.png') no-repeat;
                background-size: 263px auto;
            }
            .news-info-list{
                font-size: 14px;
                float: right;
                width: 550px;
                overflow: hidden;
                li{
                    list-style: none;
                    height: 32px;
                }
                a{
                    p{
                        overflow: hidden;
                        white-space: nowrap;
                        text-overflow: ellipsis;
                        color: #000000;
                        transition: color ease-out 0.2s;
                    }
                }
                a:hover{
                    p{
                        color: #31aae2;
                        transition: color ease-in 0.2s;
                    }
                }
                p{
                    float: left;
                    width: 410px;
                    overflow: hidden;
                }
                span{
                    float: right;
                    color: #aeaeae;
                }
            }
        }
    }
}
</style>
