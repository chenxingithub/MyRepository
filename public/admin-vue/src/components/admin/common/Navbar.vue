<template>
<header class="main-header">
    <!-- Logo -->
    <a href="../../index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="http://img.7160.com/uploads/170807/12-1FPF91603E9.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="static/image/cmsheadportrait.png" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ user.nickname }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img :src="user.picture" class="img-circle" alt="User Image">

                <p>
                   系统管理员
                  <small>邮箱：{{ user.email }}</small>
                  <small>部门：{{ user.group_name }}</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a>粉丝</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a>销售额</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a>好友</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <!-- <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div> -->
                <div class="pull-right">
                  <a href="/logout" class="btn btn-default btn-flat" onclick="event.preventDefault();document.getElementById('logout-form').submit();">登出</a>
                  <form id="logout-form" action="/examineadmin/logout" method="POST" style="display: none;">
                    <input type="hidden" v-model="token" name="_token">
                  </form>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
    <!-- 矛标签 -->
    <div class="navbar" style="background-color: #ffffff;">
        <el-tabs v-model="editableTabsValue2" type="card" closable @tab-remove="removeTab" @tab-click="tabClick">
          <el-tab-pane
            v-for="(item, index) in options"
            :key="item.name"
            :label="item.title"
            :name="item.name"
          >
          </el-tab-pane>
        </el-tabs>
        </div>
  </header>
</template>

<script>
import { mapActions } from 'vuex';

export default {
    data() {
        return {
            token: window.Laravel.csrfToken,
            user: {},
        }
    },
    mounted() {
        this.user = window.User
    },
    methods: {
          // tab切换时，动态的切换路由
      tabClick (tab) {
        this.$router.push({path: this.$store.state.options[tab.index].path});
        this.$store.state.editableTabsValue2 = tab.name;
      },
      removeTab(targetName) {
        let tabs = this.$store.state.options;
        let activeName = this.$store.state.editableTabsValue2;
        if (activeName === targetName) {
          tabs.forEach((tab, index) => {
            if (tab.name === targetName) {
              let nextTab = tabs[index + 1] || tabs[index - 1];
              if (nextTab) {
                activeName = nextTab.name;
                this.$router.push({path: nextTab.path});
              }
            }
          });
        }
        this.$store.state.editableTabsValue2 = activeName;
        this.$store.state.options = tabs.filter(tab => tab.name !== targetName);
      }
    },
  computed: {
    options () {
      return this.$store.state.options;
    },
        editableTabsValue2 () {
      return this.$store.state.editableTabsValue2;
    },
  },
  //监听路由跳转
/*  watch: {
    '$route'(to) {
      console.log(this.$route.path);
      let newTabName = ++this.tabIndex + '';
        this.$store.state.options.push({
          title: 'New Tab',
          name: newTabName,
          path: this.$route.path,
          content: 'New Tab content'
        });
      this.editableTabsValue2 = newTabName;
    }
  }*/
}
</script>