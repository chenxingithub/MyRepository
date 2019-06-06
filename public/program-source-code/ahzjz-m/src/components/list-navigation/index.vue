<template>
    <div id="list-nav">
        <Header></Header>
        <div class="nav-content flex">
            <div :class="navSelected == 'comprehensive' ? 'nav-selected' : false" 
            @click="navChange('comprehensive', parentId)"
            >综合</div>

            <div :class="navSelected == 'activity' ? 'nav-selected' : false" 
            @click="navChange('activity', articleChildrenList[0].id)">{{ articleChildrenList[0].name }}</div>

            <div :class="navSelected == 'announcement' ? 'nav-selected' : false" 
            @click="navChange('announcement', articleChildrenList[1].id)">{{ articleChildrenList[1].name }}</div>
        </div>
        <div class="list-search flex-sb-c">
            <input type="text" placeholder="输入关键字" v-model="keyWorld">
            <a href="javascript:;" class="list-search-btn" @click="articleListSearch(keyWorld)">查找</a>
        </div>
        <div class="list-content">
            
            <a href="javascript:;" class="list-item" v-for="(item, index) in lists.data" :key="index">
                <router-link v-if="!item.jump_url" :to="{path: '/articledetails', query: {id: item.id}}">
                    <div class="list-title">
                        <span class="title-icon"></span>
                        <span>{{ item.title }}</span>
                    </div>
                    <div class="list-dec">
                        <p>
                        {{ item.subtitled }}
                        </p>
                    </div>
                    <div class="list-date">
                        <span>{{ item.updated_at.split(' ')[0] }}</span>
                    </div>
                </router-link>
                <a :href="item.jump_url" v-if="item.jump_url">
                    <div class="list-title">
                        <span class="title-icon"></span>
                        <span>{{ item.title }}</span>
                    </div>
                    <div class="list-dec">
                        <p>
                        {{ item.subtitled }}
                        </p>
                    </div>
                    <div class="list-date">
                        <span>{{ item.updated_at.split(' ')[0] }}</span>
                    </div>
                </a>
            </a>
        </div>
        <div class="load" ref="load">
            <span v-show="loadStatus == 'load'">上拉加载更多</span>
            <span v-show="loadStatus == 'noMore'">我是有底线的</span>
            <img v-show="loadStatus == 'loading'" src="../../assets/loading.gif" alt="gif">
        </div>
        <Footer-nav></Footer-nav>
    </div>
</template>
<script>
import FooterNav from '../footer/footer';
import Header from '../header/header';
import { throttle, debounce } from '../../utils/index.js';

import { 
    request_articleChildrenList,
    request_articleList,
    articleListFuzzy_search
 } from '../../service/ajax.js'

export default {
    name: 'listNav',
    components: {
        Header,
        FooterNav
    },
    data() {
        return {
            navSelected: 'comprehensive',
            type: this.$route.query.type || '',
            parentId: this.$route.query.id,
            navItem: [],
            articleChildrenList: [{name: ''},{name: ''}],
            page: 1,
            limit: 5,
            currentId: this.$route.query.id,
            lists: '',
            loadStatus: 'load',
            keyWorld: '',
            current_page: 0,
            last_page: 0
        };
    },
    methods: {
        navChange(selected, id) {
            this.navSelected = selected;
            let _this = this;
            this.currentId = id;
            
            this.loadStatus = 'load';
            this.page = 1;
            request_articleList(id, 1, this.limit)
                .then(function(response) {
                    _this.lists = response.data[id].data[0];
                    _this.current_page = response.data[id].data[0].current_page;
                    _this.last_page = response.data[id].data[0].last_page;
                    _this.page += 1;
                });
        },
        // switchNav(id) {
        //     request_articleList(id)
        //         .then(function(response) {
        //             console.log(response);
        //         });
        // },
        // loadMore(id) {
        //     let _this = this;
        //     let page = this.page = this.page + 1;
        //     request_articleList(id, page, this.limit)
        //         .then(function(response) {
        //             // console.log(response);
        //             _this.lists = [..._this.lists, ...response.data[id].data[0]];
        //         });
        // },
        scroll() {
            // console.log('list');
            if(this.loadStatus == 'noMore') {
                return false;
            }
            if(!this.$refs.load) {
                return false;
            }
            let element = this.$refs.load.getBoundingClientRect();
            
            if(this.last_page <= this.current_page) {
                this.loadStatus = 'noMore';
                return false;
            }
            if(window.screen.height > element.top + 100) {
                let _this = this;
                let id = this.currentId;


                this.loadStatus = 'loading';
                request_articleList(id, this.page, this.limit)
                    .then(function(response) {
                        
                        let data = response.data[id].data[0];
                        if(data.current_page < data.last_page) {
                            _this.loadStatus = 'load';
                        } else {
                            _this.loadStatus = 'noMore';
                        }
                        // console.log(response);
                        _this.lists.data = [..._this.lists.data, ...response.data[id].data[0].data];
                        _this.page += 1;
                        _this.current_page = response.data[id].data[0].current_page;
                        _this.last_page = response.data[id].data[0].last_page;
                        
                    });
                }
        },
        articleListSearch(keyWorld) {
            if(!keyWorld) {
                alert('搜索内容不能为空');
                return false;
            }
            let _this = this;
            articleListFuzzy_search(keyWorld, 1, 10)
                .then(function(response) {
                    _this.lists = response.data;
                    // console.log(response);
                });
        }
    },
    mounted: function() {
        // console.log(this.$route);
        let _this = this;
        let id = this.$route.query.id
        request_articleChildrenList(id)
            .then(function(response) {
                // console.log(response);
                _this.articleChildrenList = response.data.data;
            });
        request_articleList(id, this.page, this.limit)
                .then(function(response) {
                    // console.log(response);
                    _this.page += 1;
                    _this.lists = response.data[id].data[0];
                    _this.current_page = response.data[id].data[0].current_page;
                    _this.last_page = response.data[id].data[0].last_page;
                });
        
        window.addEventListener('scroll', throttle(_this.scroll,1000, 500, this),false);
    },
    beforeRouteLeave (to, from, next) {
        // 导航离开该组件的对应路由时调用
        // 可以访问组件实例 `this`
        // console.log(throttle);
        window.removeEventListener('scroll',throttle);
        next();
    }
}
</script>
<style lang="scss" scope>
$nav-height: 80px;
#list-nav{
    padding: 105px 65px 100px;
    background: #1f1625;
    min-height: 1334px;
    .nav-content{
        width: 100%;
        height: $nav-height;
        border-bottom: 1px solid #4c4356;/*no*/
        >div{
            width: 106px;
            height: $nav-height + 2px;
            line-height: $nav-height + 20px;
            color: #ffffff;
            text-align: center;
            margin-right: 30px;
            letter-spacing: 4px;
            font-size: 30px;
        }
        .nav-selected{
            color: #ff350f;
            border-bottom: 5px solid #ff350f;
        }
    }
    .list-search{
        width: 100%;
        height: 50px;
        margin: 19px auto 0;
        input{
            font-size: 24px;
            width: 480px;
            height: 50px;
            padding-left: 30px;
            background: #e0e0e5;
            border: 1px solid #c9b098;/*no*/
            border-radius: 0;
        }
        .list-search-btn{
            text-decoration: none;
            $btn-height: 50px;
            width: 134px;
            height: $btn-height;
            line-height: $btn-height;
            font-size: 24px;
            color: #fefefe;
            text-align: center;
            background: url('../../assets/search-btn.png') no-repeat;
            background-size: 100% 100%;
            background-color: #605686;
            letter-spacing: 5px;
        }
    }
    .list-content{
        margin-top: 10px;
        min-height: 900px;
        .list-item{
            text-decoration: none;
            display: block;
            padding-top: 36px;
            width: 100%;
            height: auto;
            border-bottom: 1px solid #4c4356;/*no*/
        }
        .list-title{
            color: #ffffff;
            width: 600px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            
            span{
                font-size: 30px;
            }
            .title-icon{
                display: inline-block;
                width: 27px;
                height: 30px;
                background: url('../../assets/list-item-dot-icon.png') no-repeat;
                background-size: 10px 10px;
                background-position: 4px center;
            }
        }
        .list-dec{
            word-break:break-all;
            word-wrap:break-word;
            text-align: initial;
            margin-top: 26px;
            padding-left: 30px;
            font-size: 26px;
            color: #a7a7b0;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 4;
            overflow: hidden;
        }
        .list-date{
            margin: 40px 0 16px;
            width: 100%;
            height: 22px;
            font-size: 20px;
            color: #a7a7b0;
            text-align: right;
            span{
                margin-right: 10px;
            }
        }
    }
    .load{
        color: #635970;
        font-size: 30px;
        width: 100%;
        height: 180px;
        line-height: 180px;
        text-align: center;
        .img{
            width: 330px;
            height: 104px;
        }
    }
}
</style>
