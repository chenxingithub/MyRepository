import Admin from './components/Dashboard.vue';
import Parent from './components/Parent.vue';
export default[
    { 
      path: '/admin',
   	  component: Admin,
      children: [
		      {
		        path: '/',
		        redirect: '/admin/home'
		      },
		      {
		        path: '/admin/home',
		        component: Parent,
		        component: require('./components/admin/Home.vue')
		        //component: require('./views/App.vue')
		      },
		      {
		        path: 'user',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: require('./components/admin/user/Index.vue')
		            }
		        ]
		      },
		      {
		        path: 'goods',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: require('./components/admin/goods/Index.vue')
		            }
		        ]
		      },
		      {
		        path: 'wzdqqhd',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: require('./components/admin/wzdqqhd/Index.vue')
		            }
		        ]
		      },
		      {
		        path: 'administrators',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: require('./components/admin/Administrators/Index.vue')
		            },
		            {
		                path: 'roles',
		                component: require('./components/admin/Administrators/Roles.vue')
		            },
                    {
                        path: 'permissions',
                        component: require('./components/admin/Administrators/Permissions.vue')
                    }
		        ]
		      },
		  ]
    },
    ]

