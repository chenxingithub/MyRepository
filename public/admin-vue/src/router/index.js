import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Admin from '@/components/Dashboard.vue';
import Parent from '@/components/Parent.vue';
import Home from '@/components/admin/Home.vue';
import UserIndex from '@/components/admin/user/Index.vue';
import AdministratorsIndex from '@/components/admin/Administrators/Index.vue';
import AdministratorsRoles from '@/components/admin/Administrators/Roles.vue';
import AdministratorsPermissions from '@/components/admin/Administrators/Permissions.vue';
import BaseIndex from '@/components/admin/base/Index.vue';
import BaseFocus from '@/components/admin/base/Focus.vue';
import BaseSvip from '@/components/admin/base/Svip.vue';
import articleType from '@/components/admin/base/ArticleType.vue';
import GamesIndex from '@/components/admin/games/Index.vue';
import Article from '@/components/admin/base/Article.vue';
import ArticleAdd from '@/components/admin/base/ArticleAdd.vue';
import ArticleEdit from '@/components/admin/base/ArticleEdit.vue';
import CdkType from '@/components/admin/base/CdkType.vue';
import CdkList from '@/components/admin/base/CdkList.vue';
import DaySignGift from '@/components/admin/base/DaySignGift.vue';
import SignInCdk from '@/components/admin/base/SignInCdk.vue';
import Configure from '@/components/admin/wechat/Configure.vue';
import Menu from '@/components/admin/wechat/Menu.vue';
import Keyword from '@/components/admin/wechat/Keyword.vue';
import JurisdictionIndex from '@/components/admin/jurisdiction/Index.vue';
// import TinymceIndex from '@/components/admin/Tinymce/Index.vue';
import OfficialRecruit from '@/components/admin/official/Recruit.vue';
import gameSpiritType from '@/components/admin/gameSpirit/Type.vue';
import gameSpiritKeyword from '@/components/admin/gameSpirit/Keyword.vue';
import gameSpiritPlate from '@/components/admin/gameSpirit/Plate.vue';
import gameSpiritFeedback from '@/components/admin/gameSpirit/Feedback.vue';
import gameSpiritRetrieval from '@/components/admin/gameSpirit/Retrieval.vue';
import examineSensitiveWord from '@/components/admin/examine/SensitiveWord.vue';
import TurntableCommodity from '@/components/admin/base/TurntableCommodity.vue';
import TurntableRecord from '@/components/admin/base/TurntableRecord.vue';
import TurntableUser from '@/components/admin/base/TurntableUser.vue';
import WeekSignGift from '@/components/admin/base/WeekSignGift.vue';
import JpTurntableCommodity from '@/components/admin/base/JpTurntableCommodity.vue';
import JpTurntableRecord from '@/components/admin/base/JpTurntableRecord.vue';
import OpenService from '@/components/admin/base/OpenService.vue';
import WechatAppFocus from '@/components/admin/wechatApp/Focus.vue';
import WechatAppGames from '@/components/admin/wechatApp/Games.vue';
import OfficialGift from '@/components/admin/config/OfficialGift.vue';




Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: Admin,
      children: [
		      {
		        path: '/',
		        redirect: '/home'
		      },
		      {
		        path: '/home',
		        component: Home,
	            meta: {
			      keepAlive: true,// 需要被缓存
			      name:'主页'
			    }

		        //component: require('./views/App.vue')
		      },
			  {
		        path: 'user',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: UserIndex
		            }
		        ]
		      },
		      {
		        path: 'games',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: GamesIndex,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'游戏列表'
					    }
		            }
		        ]
		      },
/*		      {
		        path: 'Tinymce',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: TinymceIndex,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'主页'
					    }
		            }
		        ]
		      },*/
		      {
		        path: 'jurisdiction',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: JurisdictionIndex,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'权限控制'
					    }
		            }
		        ]
		      },
		      {
		        path: 'JpTurntableCommodity',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: JpTurntableCommodity,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'九品-转盘奖品'
					    }
		            }
		        ]
		      },
			  {
		        path: 'JpTurntableRecord',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: JpTurntableRecord,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'九品-转盘抽奖记录'
					    }
		            }
		        ]
		      },
		      {
		        path: 'turntableCommodity',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: TurntableCommodity,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'大转盘奖品'
					    }
		            }
		        ]
		      },
			  {
		        path: 'TurntableRecord',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: TurntableRecord,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'大转盘抽奖记录'
					    }
		            }
		        ]
		      },
		      {
		        path: 'TurntableUser',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: TurntableUser,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'大转盘用户列表'
					    }
		            }
		        ]
		      },
		      {
		        path: 'official',
		        component: Parent,
		        children: [
		            {
		                path: 'recruit',
		                component: OfficialRecruit,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'简历库'
					    }
		            }
		        ]
		      },
		      {
		        path: 'gameSpirit',
		        component: Parent,
		        children: [
		            {
		                path: 'Type/:id',
		                component: gameSpiritType,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'精灵规则分类'
					    }
		            },
		            {
		                path: 'Keyword/:id',
		                component: gameSpiritKeyword,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'精灵规则列表'
					    }
		            },
		            {
		                path: 'Plate/:id',
		                component: gameSpiritPlate,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'精灵模板'
					    }
		            },
		            {
		                path: 'Feedback/:id',
		                component: gameSpiritFeedback,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'精灵用户反馈'
					    }
		            },
		            {
		                path: 'Retrieval/:id',
		                component: gameSpiritRetrieval,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'精灵用户检索'
					    }
		            }
		        ]
		      },
		      {
		        path: 'examine',
		        component: Parent,
		        children: [
		            {
		                path: 'SensitiveWord',
		                component: examineSensitiveWord,
			            meta: {
					      keepAlive: true, // 需要被缓存
					      name:'敏感词过滤'
					    }
		            },
		        ]
		      },
		      {
		        path: 'base',
		        component: Parent,
		        children: [
/*		            {
		                path: '/',
		                component: BaseIndex
		            },*/
		            
		            {
		                path: 'focus/:id',
		                component: BaseFocus,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'焦点图'
					    }
								},
								
								{
									path: 'svip/:id',
									component: BaseSvip,
										meta: {
							keepAlive: true, // 需要被缓存
							name:'Svip配置'
						}
							},
		            {
		                path: 'articleType/:id',
		                component: articleType,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'文章分类'
					    }
		            },
		            {
		                path: 'article/:id',
		                component: Article,
	                    meta: {
					      name:'文章列表'
					    }
		            },
		            {
		                path: 'article_add/:id',
		                component: ArticleAdd,
	                    meta: {
					      name:'文章-添加'
					    }
		            },
		            {
		            	name: "ArticleEdit",
		                path: 'article_edit',
		                component: ArticleEdit,
	                    meta: {
					      name:'文章-修改'
					    }
		            },
		            {
		                path: 'cdkType/:id',
		                component: CdkType,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'礼包分类'
					    }
		            },
		            {
		                path: 'CdkList/:id',
		                component: CdkList,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'礼包码列表'
					    }
		            },
		            {
		                path: 'DaySignGift/:id',
		                component: DaySignGift,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'日签到礼包'
					    }
		            },
		            {
		                path: 'WeekSignGift/:id',
		                component: WeekSignGift,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'日签到礼包'
					    }
		            },
		            {
		                path: 'SignInCdk/:id',
		                component: SignInCdk,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'签到礼包码'
					    }
		            },
		            {
		                path: 'OpenService/:id',
		                component: OpenService,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'开服列表'
					    }
		            }
		        ]
		      },
		      {
		        path: 'wechat',
		        component: Parent,
		        children: [
		            {
		                path: 'Configure/:id',
		                component: Configure,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'公众号配置'
					    }
		            },
		            {
		                path: 'Menu/:id',
		                component: Menu,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'微信菜单'
					    }
		            },
		            {
		                path: 'Keyword/:id',
		                component: Keyword,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'微信关键字回复'
					    }
		            },
		        ]
		      },
		      {
		        path: 'WechatApp',
		        component: Parent,
		        children: [
		            {
		                path: 'Focus',
		                component: WechatAppFocus,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'游戏盒子焦点图'
					    }
		            },
		            {
		                path: 'Games',
		                component: WechatAppGames,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'游戏盒子游戏列表'
					    }
		            },
		        ]
		      },
			  {
		        path: 'config',
		        component: Parent,
		        children: [
		            {
		                path: 'official-gift/:id',
		                component: OfficialGift,
	                    meta: {
					      keepAlive: true, // 需要被缓存
					      name:'官网礼包配置'
					    }
		            },
		        ]
		      },
		      {
		        path: 'administrators',
		        component: Parent,
		        children: [
		            {
		                path: '/',
		                component: AdministratorsIndex
		            },
		            {
		                path: 'roles',
		                component: AdministratorsRoles
		            },
                    {
                        path: 'permissions',
                        component: AdministratorsPermissions
                    }
		        ]
		      },
		  ]
    }
  ]
})
