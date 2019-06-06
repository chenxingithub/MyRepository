<template>
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="static/image/cmsheadportrait.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{user.nickname}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online（在线）</a>
        </div>
      </div>
      <!-- search form -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> 
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="treeview" v-for="menu in menus">
          <a href="#">
            <i class="fa fa-tv"></i> <span>{{ menu.name }}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li v-for="(item,index) in menu.subs" >

                <template v-if="item.subs" >
                       <a href="#"><i class="fa fa-circle-o text-aqua"></i><span>{{item.name}}</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu">
                            <li v-for="(ritem,index) in item.subs" @click="handleClick(ritem)">
                                 <router-link v-if="item.game_id" :to="ritem.url[0]+item.game_id" tag="a"><i class="fa fa-circle-o text-red"></i><span>{{ritem.name}}</span>
                                 </router-link>

                                 <router-link v-else :to="ritem.url[0]" tag="a"><i class="fa fa-circle-o text-red"></i><span>{{ritem.name}}</span>
                                </router-link>
                            </li>
                      </ul>
                </template>

                <template v-else >
                       <router-link v-if="menu.game_id" :to="item.url[0]+menu.game_id" tag="a"><i class="fa fa-circle-o"></i><span>{{item.name}}</span>
                        </router-link>

                        <router-link v-else :to="item.url[0]" tag="a"><i class="fa fa-circle-o"></i><span>{{item.name}}</span>
                        </router-link>
                </template>
            </li>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
</template>

<script>
import menus from '../../../config/menu.js';

export default {
  data() {
      return {
        menus: window.Menus,
        user: {}
      }
    },
  mounted() {
    this.user = window.User
        // 刷新时以当前路由做为tab加入tabs
    let newTabName = ++this.$store.state.tabIndex + '';  
    if (this.$route.path !== '/home') {
        this.$store.state.options.push({
          title: this.$route.meta.name,
          name: newTabName,
          path: this.$route.path,
          content: ''
        });
        this.$store.state.editableTabsValue2 = newTabName;
    };
  },
    methods: {
          // tab切换时，动态的切换路由
      handleClick (data) {
        let flag = false;
        let tabs = this.$store.state.options;
          /*判断矛标签是否已经存在该路由*/
          tabs.forEach((tab, index) => {
            if (tab.path == this.$route.path) {
                flag = true;
                this.$store.state.editableTabsValue2 = tab.name;
            }
          });
          if (!flag) {
            let newTabName = ++this.$store.state.tabIndex + '';  
                this.$store.state.options.push({
                  title: data.name,
                  name: newTabName,
                  path: this.$route.path,
                  content: ''
                });
            this.$store.state.editableTabsValue2 = newTabName;
          };  
      }
    }
}
</script>
