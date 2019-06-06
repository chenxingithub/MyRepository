<template>
    <div class="openService">
        <Header></Header>
        <div class="openservice-head"></div>
        <div class="openservice-list">
            <div class="list-nav flex-sb-c">
                <!-- <a href="javascript:;" :class="{'android-btn': true, 'selected': selected == 'android' ? true : false}" @click="clickSelected('android')"></a>
                <a href="javascript:;" :class="{'ios-btn': true, 'selected': selected == 'ios' ? true : false}" @click="clickSelected('ios')"></a> -->
            </div>
            <div class="android-item" v-show="selected == 'android' ? true : false">
                <table>
                    <tr>
                        <td>日期</td>
                        <td>时间</td>
                        <td>服务器</td>
                    </tr>
                    <tr v-for="(item, index) in androidList" :key="index">
                        <td>{{ dateFormat(item.open_service_time) }}</td>
                        <td>{{ timeFormat(item.open_service_time) }}</td>
                        <td>{{ item.open_service_name }}</td>
                    </tr>
                    <!-- <tr>
                        <td>日期</td>
                        <td>时间</td>
                        <td>服务器</td>
                    </tr>
                    <tr>
                        <td>12月8日</td>
                        <td>10:00</td>
                        <td>暗黑1服</td>
                    </tr>
                    <tr>
                        <td>12月15日</td>
                        <td>10:00</td>
                        <td>暗黑2服</td>
                    </tr>
                    <tr>
                        <td>12月22日</td>
                        <td>10:00</td>
                        <td>暗黑3服</td>
                    </tr>
                    <tr>
                    <tr>
                        <td>1月16日</td>
                        <td>10:00</td>
                        <td>暗黑4服</td>
                    </tr>
                    <tr>
                        <td>1月30日</td>
                        <td>10:00</td>
                        <td>暗黑5服</td>
                    </tr>
                    <tr>
                        <td>2月12日</td>
                        <td>10:00</td>
                        <td>暗黑6服</td>
                    </tr>
                    <tr>
                        <td>3月8日</td>
                        <td>10:00</td>
                        <td>暗黑7服</td>
                    </tr>
                    <tr>
                        <td>3月9日</td>
                        <td>10:00</td>
                        <td>暗黑8服</td>
                    </tr>
                    <tr>
                        <td>3月10日</td>
                        <td>10:00</td>
                        <td>暗黑9服</td>
                    </tr>
                    <tr>
                        <td>3月23日</td>
                        <td>10:00</td>
                        <td>暗黑10服</td>
                    </tr>
                    <tr>
                        <td>3月23日</td>
                        <td>16:30</td>
                        <td>暗黑11服</td>
                    </tr>
                    <tr>
                        <td>4月12日</td>
                        <td>10:00</td>
                        <td>暗黑12服</td>
                    </tr>
                    <tr>
                        <td>4月12日</td>
                        <td>16:30</td>
                        <td>暗黑13服</td>
                    </tr>
                    <tr>
                        <td>4月13日</td>
                        <td>10:00</td>
                        <td>暗黑14服</td>
                    </tr>
                    <tr>
                        <td>4月14日</td>
                        <td>10:00</td>
                        <td>暗黑15服</td>
                    </tr>
                    <tr>
                        <td>4月14日</td>
                        <td>16:30</td>
                        <td>暗黑16服</td>
                    </tr>
                    <tr>
                        <td>4月15日</td>
                        <td>10:00</td>
                        <td>暗黑17服</td>
                    </tr>
                    <tr>
                        <td>4月15日</td>
                        <td>16:30</td>
                        <td>暗黑18服</td>
                    </tr>
                    <tr>
                        <td>4月16日</td>
                        <td>16:00</td>
                        <td>暗黑19服</td>
                    </tr>
                    <tr>
                        <td>4月17日</td>
                        <td>10:00</td>
                        <td>暗黑20服</td>
                    </tr>
                    <tr>
                        <td>4月18日</td>
                        <td>16:00</td>
                        <td>暗黑21服</td>
                    </tr>
                    <tr>
                        <td>4月18日</td>
                        <td>16:30</td>
                        <td>暗黑22服</td>
                    </tr>
                    <tr>
                        <td>4月19日</td>
                        <td>10:00</td>
                        <td>暗黑23服</td>
                    </tr>
                    <tr>
                        <td>4月19日</td>
                        <td>16:30</td>
                        <td>暗黑24服</td>
                    </tr>
                    <tr>
                        <td>4月20日</td>
                        <td>10:00</td>
                        <td>暗黑25服</td>
                    </tr>
                    <tr>
                        <td>4月20日</td>
                        <td>16:30</td>
                        <td>暗黑26服</td>
                    </tr>
                    <tr>
                        <td>4月21日</td>
                        <td>10:00</td>
                        <td>暗黑27服</td>
                    </tr>
                    <tr>
                        <td>4月21日</td>
                        <td>16:30</td>
                        <td>暗黑28服</td>
                    </tr>
                    <tr>
                        <td>4月22日</td>
                        <td>10:00</td>
                        <td>暗黑29服</td>
                    </tr>
                    <tr>
                        <td>4月22日</td>
                        <td>16:30</td>
                        <td>暗黑30服</td>
                    </tr>
                    <tr>
                        <td>4月23日</td>
                        <td>10:00</td>
                        <td>暗黑31服</td>
                    </tr>
                    <tr>
                        <td>4月23日</td>
                        <td>16:30</td>
                        <td>暗黑32服</td>
                    </tr>
                    <tr>
                        <td>4月24日</td>
                        <td>10:00</td>
                        <td>暗黑33服</td>
                    </tr>
                    <tr>
                        <td>4月24日</td>
                        <td>16:30</td>
                        <td>暗黑34服</td>
                    </tr>
                    <tr>
                        <td>4月25日</td>
                        <td>10:00</td>
                        <td>暗黑35服</td>
                    </tr>
                    <tr>
                        <td>4月25日</td>
                        <td>16:30</td>
                        <td>暗黑36服</td>
                    </tr>
                    <tr>
                        <td>4月26日</td>
                        <td>10:00</td>
                        <td>暗黑37服</td>
                    </tr>
                    <tr>
                        <td>4月26日</td>
                        <td>16:30</td>
                        <td>暗黑38服</td>
                    </tr>
                    <tr>
                        <td>4月27日</td>
                        <td>10:00</td>
                        <td>暗黑39服</td>
                    </tr>
                    <tr>
                        <td>4月28日</td>
                        <td>10:00</td>
                        <td>暗黑40服</td>
                    </tr>
                    <tr>
                        <td>4月29日</td>
                        <td>10:00</td>
                        <td>暗黑41服</td>
                    </tr>
                    <tr>
                        <td>4月30日</td>
                        <td>10:00</td>
                        <td>暗黑42服</td>
                    </tr>
                    <tr>
                        <td>5月1日</td>
                        <td>10:00</td>
                        <td>暗黑43服</td>
                    </tr>
                    <tr>
                        <td>5月2日</td>
                        <td>10:00</td>
                        <td>暗黑44服</td>
                    </tr>
                    <tr>
                        <td>5月3日</td>
                        <td>10:00</td>
                        <td>暗黑45服</td>
                    </tr> -->
                </table>
            </div>
            <div class="ios-item" v-show="selected == 'ios' ? true : false">
                <table>
                    <tr>
                        <td>日期</td>
                        <td>时间</td>
                        <td>服务器</td>
                    </tr>
                    <tr v-for="(item, index) in iosList" :key="index">
                        <td>{{ dateFormat(item.open_service_time) }}</td>
                        <td>{{ timeFormat(item.open_service_time) }}</td>
                        <td>{{ item.open_service_name }}</td>
                    </tr>

                    <!-- <tr>
                        <td>12月8日</td>
                        <td>10:00</td>
                        <td>暗黑1服</td>
                    </tr>
                    <tr>
                        <td>12月15日</td>
                        <td>10:00</td>
                        <td>暗黑2服</td>
                    </tr>
                    <tr>
                        <td>12月22日</td>
                        <td>10:00</td>
                        <td>暗黑3服</td>
                    </tr>
                    <tr>
                    <tr>
                        <td>1月16日</td>
                        <td>10:00</td>
                        <td>暗黑4服</td>
                    </tr>
                    <tr>
                        <td>1月30日</td>
                        <td>10:00</td>
                        <td>暗黑5服</td>
                    </tr>
                    <tr>
                        <td>2月12日</td>
                        <td>10:00</td>
                        <td>暗黑6服</td>
                    </tr>
                    <tr>
                        <td>3月8日</td>
                        <td>10:00</td>
                        <td>暗黑7服</td>
                    </tr>
                    <tr>
                        <td>3月9日</td>
                        <td>10:00</td>
                        <td>暗黑8服</td>
                    </tr>
                    <tr>
                        <td>3月10日</td>
                        <td>10:00</td>
                        <td>暗黑9服</td>
                    </tr>
                    <tr>
                        <td>3月23日</td>
                        <td>10:00</td>
                        <td>暗黑10服</td>
                    </tr>
                    <tr>
                        <td>3月23日</td>
                        <td>18:00</td>
                        <td>暗黑11服</td>
                    </tr>
                    <tr>
                        <td>4月12日</td>
                        <td>10:00</td>
                        <td>暗黑12服</td>
                    </tr>
                    <tr>
                        <td>4月12日</td>
                        <td>16:30</td>
                        <td>暗黑13服</td>
                    </tr>
                    <tr>
                        <td>4月13日</td>
                        <td>8:00</td>
                        <td>暗黑14服</td>
                    </tr>
                    <tr>
                        <td>4月14日</td>
                        <td>10:00</td>
                        <td>暗黑15服</td>
                    </tr>
                    <tr>
                        <td>4月14日</td>
                        <td>15:30</td>
                        <td>暗黑16服</td>
                    </tr>
                    <tr>
                        <td>4月15日</td>
                        <td>10:00</td>
                        <td>暗黑17服</td>
                    </tr>
                    <tr>
                        <td>4月15日</td>
                        <td>17:30</td>
                        <td>暗黑18服</td>
                    </tr>
                    <tr>
                        <td>4月16日</td>
                        <td>10:00</td>
                        <td>暗黑19服</td>
                    </tr>
                    <tr>
                        <td>4月17日</td>
                        <td>10:00</td>
                        <td>暗黑20服</td>
                    </tr>
                    <tr>
                        <td>4月18日</td>
                        <td>10:00</td>
                        <td>暗黑21服</td>
                    </tr>
                    <tr>
                        <td>4月18日</td>
                        <td>18:55</td>
                        <td>暗黑22服</td>
                    </tr>
                    <tr>
                        <td>4月19日</td>
                        <td>10:00</td>
                        <td>暗黑23服</td>
                    </tr>
                    <tr>
                        <td>4月19日</td>
                        <td>19:00</td>
                        <td>暗黑24服</td>
                    </tr>
                    <tr>
                        <td>4月20日</td>
                        <td>10:00</td>
                        <td>暗黑25服</td>
                    </tr>
                    <tr>
                        <td>4月20日</td>
                        <td>19:00</td>
                        <td>暗黑26服</td>
                    </tr>
                    <tr>
                        <td>4月21日</td>
                        <td>10:00</td>
                        <td>暗黑27服</td>
                    </tr>
                    <tr>
                        <td>4月21日</td>
                        <td>17:00</td>
                        <td>暗黑28服</td>
                    </tr>
                    <tr>
                        <td>4月22日</td>
                        <td>10:00</td>
                        <td>暗黑29服</td>
                    </tr>
                    <tr>
                        <td>4月22日</td>
                        <td>17:00</td>
                        <td>暗黑30服</td>
                    </tr>
                    <tr>
                        <td>4月23日</td>
                        <td>10:00</td>
                        <td>暗黑31服</td>
                    </tr>
                    <tr>
                        <td>4月23日</td>
                        <td>16:30</td>
                        <td>暗黑32服</td>
                    </tr>
                    <tr>
                        <td>4月24日</td>
                        <td>10:00</td>
                        <td>暗黑33服</td>
                    </tr>
                    <tr>
                        <td>4月24日</td>
                        <td>16:05</td>
                        <td>暗黑34服</td>
                    </tr>
                    <tr>
                        <td>4月25日</td>
                        <td>10:00</td>
                        <td>暗黑35服</td>
                    </tr>
                    <tr>
                        <td>4月25日</td>
                        <td>16:58</td>
                        <td>暗黑36服</td>
                    </tr>
                    <tr>
                        <td>4月26日</td>
                        <td>10:00</td>
                        <td>暗黑37服</td>
                    </tr>
                    <tr>
                        <td>4月26日</td>
                        <td>17:36</td>
                        <td>暗黑38服</td>
                    </tr>
                    <tr>
                        <td>4月27日</td>
                        <td>10:00</td>
                        <td>暗黑39服</td>
                    </tr>
                    <tr>
                        <td>4月28日</td>
                        <td>10:00</td>
                        <td>暗黑40服</td>
                    </tr>
                    <tr>
                        <td>4月29日</td>
                        <td>10:00</td>
                        <td>暗黑41服</td>
                    </tr>
                    <tr>
                        <td>4月30日</td>
                        <td>10:00</td>
                        <td>暗黑42服</td>
                    </tr>
                    <tr>
                        <td>5月1日</td>
                        <td>10:00</td>
                        <td>暗黑43服</td>
                    </tr>
                    <tr>
                        <td>5月2日</td>
                        <td>10:00</td>
                        <td>暗黑44服</td>
                    </tr>
                    <tr>
                        <td>5月3日</td>
                        <td>10:00</td>
                        <td>暗黑45服</td>
                    </tr> -->
                </table>
            </div>
        </div>
        <Footer-nav></Footer-nav>
    </div>
</template>
<script>
import Header from '../header/header';
import FooterNav from '../footer/footer';
import { open_server_list } from '../../service/ajax.js'
// import func from './vue-temp/vue-editor-bridge';

export default {
    name: 'openService',
    components: {
        Header,
        FooterNav
    },
    data() {
        return {
            selected: 'android',
            androidList: null,
            iosList: null
        };
    },
    methods: {
        // clickSelected(type) {
        //     if(this.selected == type) {
        //         return false;
        //     }
        //     this.selected = type;

        //     if(type == 'android') {//安卓2，ios 1，后台接口设定
        //         this.request_openServer_list(2);
        //     }
        //     if(type == 'ios') {
        //         this.request_openServer_list(1);
        //     }
            
        // },
        request_openServer_list(typeId) {
            let _this = this;
            open_server_list(typeId)
                .then(function(res) {
                    
                    if(typeId == 1) {
                        _this.iosList = res.data.data;
                    }
                    if(typeId == 2) {
                        _this.androidList = res.data.data;
                    }
                });
        },
        dateFormat(time) {
            let dates = time.split(' ')[0].split('-');
            dates.map(function(item, index) {
                return item-0;
            });
            // console.log(dates);
            return (Number(dates[1])+'月'+Number(dates[2])+'日');
        },
        timeFormat(time) {
            let dates = time.split(' ')[1].split(':');
            return dates[0]+':'+dates[1];
        }
    },
    mounted() {
        //初始化默认显示安卓开服列表
        this.request_openServer_list(2);
    }
}
</script>
<style lang="scss" scope>
.openService{
    padding: 100px 0;
    min-height: 100vh;
    .openservice-head{
        width: 750px;
        height: 280px;
        background: url('../../assets/openService-head-bg.png') no-repeat;
        background-size: 100% 100%
    } 
}
.openservice-list{
    width: 640px;
    margin: 0 auto;
}
.list-nav {
    margin: 35px auto 22px;
    .android-btn{
        display: block;
        width: 306px;
        height: 63px;
        background: url('../../assets/openService-btn.png') no-repeat;
        background-size: 643px 155px;
        background-position: -326px -12px;
    }
    .ios-btn{
        width: 306px;
        height: 63px;
        background: url('../../assets/openService-btn.png') no-repeat;
        background-size: 643px 155px;
        background-position: -12px -12px;
    }
    .selected{
        background-position-y: -82px;
    }
}

.android-item, .ios-item{
    color: #ffffff;
    font-size: 30px;
    text-align: center;
    td{
        width: 1/3*640px;
        height: 80px;
        border: 1px solid #4c4356; /*no*/
    }
}
</style>
