<template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        仪表板
        <small>控制 面板</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> 主页</a></li>
        <li class="active">仪表板</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
<!--       Small boxes (Stat box)
Small boxes (Stat box)
      <div class="row">
          <div class="col-md-12">
              <div class="box box-info">
                  <div class="box-header with-border">
                      <h3 class="box-title">搜索选择</h3>
                      <div class="box-tools pull-right">
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                  </div>
                  <div class="box-body">
                      <div class="row">
                          <div class="col-md-3">
                              游戏列表：
                              <el-select v-model="GameId" placeholder="请选择">
                              <el-option
                                v-for="item in GameList"
                                :key="item.id"
                                :label="item.game_name"
                                :value="item.id">
                              </el-option>
                            </el-select>
                          </div>
                          <div class="col-md-3">
                              月份：
                            <el-select v-model="Month" placeholder="请选择">
                                <el-option label="一月份" value="1"></el-option>
                                <el-option label="二月份" value="2"></el-option>
                                <el-option label="三月份" value="3"></el-option>
                                <el-option label="四月份" value="4"></el-option>
                                <el-option label="五月份" value="5"></el-option>
                                <el-option label="六月份" value="6"></el-option>
                                <el-option label="七月份" value="7"></el-option>
                                <el-option label="八月份" value="8"></el-option>
                                <el-option label="九月份" value="9"></el-option>
                                <el-option label="十月份" value="10"></el-option>
                                <el-option label="十一月份" value="11"></el-option>
                                <el-option label="十二月份" value="12"></el-option>
                            </el-select>
                          </div>   
                          <div class="col-md-3">
                         <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
                          </div>
                      </div>
                      <br/>
                      <div class="row">
                      <div class="col-md-2">
                         </div>
                         <div class="col-md-8">
                            <div id="myChart" :style="{width: '100%', height: '400px'}"></div>
                         </div>
                      </div>
                  </div>
                  </div>
                  </div>
                  </div>
/.row (main row) -->
    欢迎使用！
    </section>
    <!-- /.content -->
  </div>
</template>

<script>
// 引入基本模板
let echarts = require('echarts/lib/echarts')
// 引入柱状图组件
require('echarts/lib/chart/line')
// 引入提示框和title组件
require('echarts/lib/component/tooltip')
require('echarts/lib/component/title')
export default {
  name: 'hello',
  data () {
    return {
      GameList: "",
      Series: [],
      Month:this.CurrentMonth(),
      GameId:1,
    }
  },
  mounted(){
    this.drawLine();
  },
  created() {
/*            this.loading = true
            let url = '/games/get';
            this.$axios.get(url, {
                headers: this.$store.state.headers,
                params: {
                    query_type: 1,
                },
            }).then(response => {
                this.GameList = response.data;
            })
            this.loadData()*/
  },
  methods: {
    handleFilter() {
        this.loadData();
    },
    CurrentMonth() {
      var date = new Date;
      var month = date.getMonth()+1;
      console.log(month);
      return month.toString();
    },
    loadData() {
      this.loading = true
        let url = '/statistics';
        this.$axios.get(url, {
            headers: this.$store.state.headers,
            params: {
                    GameId: this.GameId,
                    Month:this.Month,
                },
        }).then(response => {
            this.items = response.data.data
            this.total = response.data.total
            this.loading = false
        })
    },
    drawLine(){
        // 基于准备好的dom，初始化echarts实例
        let myChart = echarts.init(document.getElementById('myChart'))
        // 绘制图表
        myChart.setOption({
            title: { text: '游戏礼包领取状况图' },
            tooltip: {
              trigger: 'axis',
              axisPointer: {
                  type: 'cross',
                  label: {
                      backgroundColor: '#6a7985'
                  }
              }
            },
            legend: {
                    data:['九品小县令','精灵总动员','青云劫','热血烧猪','斗战狂魔']
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            xAxis: [
                {
                    type : 'category',
                    boundaryGap : false,
                    data : ['周一','周二','周三','周四','周五','周六','周日']
                }
            ],
            yAxis: [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'九品小县令',
                    type:'line',
                    stack: '总量',
                    areaStyle: {normal: {}},
                    data:[120, 132, 101, 134, 90, 230, 210]
                },
                {
                    name:'精灵总动员',
                    type:'line',
                    stack: '总量',
                    areaStyle: {normal: {}},
                    data:[220, 182, 191, 234, 290, 330, 310]
                },
                {
                    name:'青云劫',
                    type:'line',
                    stack: '总量',
                    areaStyle: {normal: {}},
                    data:[150, 232, 201, 154, 190, 330, 410]
                },
                {
                    name:'热血烧猪',
                    type:'line',
                    stack: '总量',
                    areaStyle: {normal: {}},
                    data:[320, 332, 301, 334, 390, 330, 320]
                },
                {
                    name:'斗战狂魔',
                    type:'line',
                    stack: '总量',
                    label: {
                        normal: {
                            show: true,
                            position: 'top'
                        }
                    },
                    areaStyle: {normal: {}},
                    data:[820, 932, 901, 934, 1290, 1330, 1320]
                }
            ]
        });
    }
  }
}
</script>

