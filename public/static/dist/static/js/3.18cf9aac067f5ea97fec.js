webpackJsonp([3],{GLt2:function(t,e,a){e=t.exports=a("FZ+f")(!1),e.push([t.i,"\n.el-upload__input {\n\t\tdisplay: none !important;\n}\n.avatar-uploader .el-upload {\n  border: 1px dashed #d9d9d9;\n  border-radius: 6px;\n  cursor: pointer;\n  position: relative;\n  overflow: hidden;\n}\n.avatar-uploader .el-upload:hover {\n  border-color: #20a0ff;\n}\n.avatar-uploader-icon {\n  font-size: 28px;\n  color: #8c939d;\n  width: 178px;\n  height: 178px;\n  line-height: 178px;\n  text-align: center;\n}\n.avatar {\n  width: 178px;\n  height: 178px;\n  display: block;\n}\n\n",""])},VZIj:function(t,e,a){"use strict";function s(t){o||a("vHfu")}Object.defineProperty(e,"__esModule",{value:!0});var i=a("dFe0"),n=a("qOtt"),o=!1,l=a("VU/8"),r=s,u=l(i.a,n.a,!1,r,null,null);u.options.__file="src\\components\\admin\\base\\ExchangeRecord.vue",u.esModule&&Object.keys(u.esModule).some(function(t){return"default"!==t&&"__"!==t.substr(0,2)})&&console.error("named exports are not supported in *.vue files."),e.default=u.exports},dFe0:function(t,e,a){"use strict";var s=a("bOdI"),i=a.n(s);a("NYxO");e.a={data:function(){var t;return t={ImgPrefix:"",GameInfo:"",content:"2312312312",loading:!1,config:{initialFrameWidth:null,initialFrameHeight:350},myName:"焦点图列表"},i()(t,"loading",!0),i()(t,"items",[]),i()(t,"total",null),i()(t,"listQuery",{page:1,limit:10,keyword:"",position:"",game_id:this.$route.params.id,terminal:"0"}),i()(t,"PositionType",[{value:null,label:"请选择"},{value:1,label:"顶部"},{value:2,label:"游戏特色"},{value:3,label:"实景截图"}]),i()(t,"PositionTypeX",[{value:1,label:"顶部"},{value:2,label:"游戏特色"},{value:3,label:"实景截图"}]),i()(t,"textMap",{update:"编辑",create:"创建"}),i()(t,"dialogFormVisible",!1),i()(t,"dialogStatus",""),i()(t,"headers",{"X-CSRF-TOKEN":Laravel.csrfToken}),i()(t,"temp",i()({id:"",img:"",name:"",probability:"",total_number:"",surplus_number:""},"surplus_number","")),i()(t,"beforeAvatarUpload",function(t){var e="image/jpeg"===t.type,a="image/png"===t.type,s="image/gif"===t.type,i=t.size/1024/1024<2;return e||a||s||this.$message.error("上传头像图片只能是 JPG|PNG|GIF 格式!"),i||this.$message.error("上传头像图片大小不能超过 2MB!"),(e||a||s)&&i}),i()(t,"rules",{title:[{required:!0,message:"请输入轮播图标题",trigger:"blur"},{min:1,max:50,message:"长度在 1 到 50 个字符",trigger:"blur"}],position:[{type:"number",required:!0,message:"请选择位置",trigger:"change"}],terminal:[{required:!0,message:"请选择终端",trigger:"change"}]}),i()(t,"avatarUrl","/upload/image"),t},methods:{loadData:function(){var t=this;this.loading=!0;this.$axios.get("/jp-exchange-record",{headers:this.$store.state.headers,params:this.listQuery}).then(function(e){t.items=e.data.data,t.total=e.data.total,t.loading=!1})},getUEContent:function(){var t=this.$refs.ue.getUEContent();alert(t),console.log(t)},handleSizeChange:function(t){this.listQuery.limit=t,this.loadData()},handleCurrentChange:function(t){this.listQuery.page=t,this.loadData()},handleFilter:function(){this.loadData()},handleAvatarSuccess:function(t,e){t.url&&(this.temp.img=t.url)},handleCreate:function(){this.dialogStatus="create",this.temp.id="",this.temp.name="",this.temp.img="",this.temp.probability="",this.temp.total_number="",this.temp.surplus_number="",this.dialogFormVisible=!0},handleDownload:function(){window.open(window.location.protocol+"//"+window.location.host+"/admin/user/excel?q="+this.listQuery.q)},handleUpdate:function(t){this.dialogStatus="update",this.temp.id=t.id,this.temp.name=t.name,this.temp.img=t.img,this.temp.probability=t.probability,this.temp.total_number=t.total_number,this.temp.surplus_number=t.surplus_number,this.dialogFormVisible=!0},handleDelete:function(t){var e=this;this.$confirm("此操作将永久删除该抽奖记录, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){e.$axios.delete("/jp-exchange-record/"+t.id).then(function(t){200===t.status?(e.$message({showClose:!0,message:0===t.data.message.length?"删除成功":t.data.message,type:"success"}),e.loadData()):e.$message({showClose:!0,message:0===t.data.message.length?"删除失败":t.data.message,type:"error"})},function(t){e.$message({showClose:!0,message:"删除失败",type:"error"})})}).catch(function(){e.$message({type:"info",message:"已取消删除"})})},handleUpload:function(){this.dialogUserVisible=!0},update:function(t){var e=this;this.$refs[t].validate(function(t){if(!t)return!1;e.$axios.put("/turntable-commodity/"+e.temp.id,{name:e.temp.name,img:e.temp.img,probability:e.temp.probability,total_number:e.temp.total_number,surplus_number:e.temp.surplus_number,action:"changeContent"}).then(function(t){200===t.status?(e.$message({showClose:!0,message:0===t.data.message.length?"修改成功！":t.data.message,type:"success"}),e.dialogFormVisible=!1,e.loadData()):e.$message({showClose:!0,message:0===t.data.message.length?"修改失败！":t.data.message,type:"error"})},function(t){if(422===t.status){var a=0===t.data.password.size?"":t.data.password;e.$message({showClose:!0,message:a,type:"error"})}})})},create:function(t){var e=this;this.$refs[t].validate(function(t){if(!t)return!1;e.$axios.post("/turntable-commodity",{img:e.temp.img,name:e.temp.name,probability:e.temp.probability,total_number:e.temp.total_number,surplus_number:e.temp.surplus_number}).then(function(t){200===t.status?(e.$message({showClose:!0,message:0===t.data.message.length?"操作成功":t.data.message,type:"success"}),e.dialogFormVisible=!1,e.loadData()):e.$message({showClose:!0,message:0===t.data.message.length?"操作失败":t.data.message,type:"error"})},function(t){if(422===t.status){var a="";$.each(t.data,function(t,e){a+=e}),e.$message({showClose:!0,message:a,type:"error"})}})})},handleModifyStatus:function(t,e){var a=this;this.$axios.put("/jp-exchange-record/"+t.id,{is_grant:e,action:"changeGrant"}).then(function(t){console.log(t),200===t.status?(a.$message({message:0===t.data.message.length?"发货状态切换成功..":t.data.message,type:"success"}),a.loadData()):a.$message({message:0===t.data.message.length?"操作失败":t.data.message,type:"error"})})},handleModifySort:function(t){var e=this;this.$axios.put("/focus/put/"+t.id,{sort:t.sort,action:"changeSort"}).then(function(t){console.log(t),200===t.status?(e.$message({showClose:!0,message:0===t.data.message.length?"修改排序成功！":t.data.message,type:"success"}),e.loadData()):e.$message({showClose:!0,message:0===t.data.message.length?"修改排序失败！":t.data.message,type:"error"})})}},created:function(){this.loadData()},filters:{statusFilter:function(t){return{0:"success",1:"gray"}[parseInt(t)]},statusShow:function(t){return{0:"正常",1:"禁用"}[parseInt(t)]},terminalShow:function(t){return{0:"全部",1:"PC端",2:"移动端"}[parseInt(t)]},typeShow:function(t){return{1:"谢谢参与",2:"虚拟货币",3:"实物"}[parseInt(t)]}}}},qOtt:function(t,e,a){"use strict";var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}],staticClass:"content-wrapper",staticStyle:{"min-height":"901px","margin-top":"4.5rem"},attrs:{"element-loading-text":"拼命加载中"}},[a("section",{staticClass:"content-header"},[a("h1",[t._v("\r\n        转盘-用户奖励记录\r\n        "),a("small",[t._v(t._s(t.GameInfo.game_name))])]),t._v(" "),a("ol",{staticClass:"breadcrumb"},[a("li",[a("router-link",{attrs:{to:"/admin/user",tag:"a"}},[a("i",{staticClass:"fa fa-user"}),t._v(" "+t._s(t.GameInfo.game_name))])],1),t._v(" "),a("li",{staticClass:"active"},[t._v("列表")])])]),t._v(" "),a("section",{staticClass:"content"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"box box-info"},[t._m(0),t._v(" "),a("div",{staticClass:"box-body"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-3"},[a("el-input",{staticClass:"filter-item",staticStyle:{width:"50%"},attrs:{placeholder:"奖品名称/抽奖人"},nativeOn:{keyup:function(e){if(!("button"in e)&&t._k(e.keyCode,"enter",13,e.key))return null;t.handleFilter(e)}},model:{value:t.listQuery.keyword,callback:function(e){t.$set(t.listQuery,"keyword",e)},expression:"listQuery.keyword"}})],1),t._v(" "),a("div",{staticClass:"col-md-3"},[a("el-button",{staticClass:"filter-item",attrs:{type:"primary",icon:"search"},on:{click:t.handleFilter}},[t._v("搜索")])],1)])]),t._v(" "),a("div",{staticClass:"box"},[a("div",{staticClass:"box-header with-border"},[a("h3",{staticClass:"box-title"},[t._v(t._s(t.myName))]),t._v(" "),a("div",{staticClass:"box-tools"})]),t._v(" "),a("div",{staticClass:"table-responsive no-padding"},[a("table",{staticClass:"table table-hover"},[a("tbody",[t._m(1),t._v(" "),t._l(t.items,function(e){return a("tr",[a("td",[t._v(t._s(e.id))]),t._v(" "),a("td",[t._v(t._s(e.bn_uid))]),t._v(" "),a("td",[t._v(t._s(e.exchange_name))]),t._v(" "),a("td",[t._v(t._s(e.receiving_address))]),t._v(" "),a("td",[t._v(t._s(e.phone))]),t._v(" "),a("td",[t._v(t._s(e.cdk_code))]),t._v(" "),a("td",[t._v(t._s(t._f("typeShow")(e.prize_type)))]),t._v(" "),a("td",[t._v(t._s(e.integral))]),t._v(" "),a("td",[t._v(t._s(e.created_at))]),t._v(" "),a("td",[t._v(t._s(e.updated_at))]),t._v(" "),a("td",[1==parseInt(e.is_grant)?a("el-button",{attrs:{size:"small",type:"success"},on:{click:function(a){t.handleModifyStatus(e,0)}}},[t._v("已发放\r\n                                            ")]):t._e(),t._v(" "),0==parseInt(e.is_grant)?a("el-button",{attrs:{size:"small"},on:{click:function(a){t.handleModifyStatus(e,1)}}},[t._v("未发放\r\n                                            ")]):t._e(),t._v(" "),a("el-button",{attrs:{size:"small",type:"danger",icon:"el-icon-delete"},on:{click:function(a){t.handleDelete(e)}}},[t._v("删除")])],1)])})],2)])]),t._v(" "),a("div",{staticClass:"box-footer clearfix"},[a("div",{staticClass:"pull-right"},[a("el-pagination",{attrs:{"current-page":t.listQuery.page,"page-sizes":[10,20,30,50],"page-size":t.listQuery.limit,layout:"total, sizes, prev, pager, next, jumper",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)])]),t._v(" "),a("el-dialog",{attrs:{title:t.textMap[t.dialogStatus],visible:t.dialogFormVisible,size:"small"},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[a("el-form",{ref:"temp",staticClass:"small-space",staticStyle:{width:"800px","margin-left":"100px"},attrs:{model:t.temp,rules:t.rules,"label-position":"left","label-width":"100px"}},[a("el-form-item",{attrs:{label:"奖品图片"}},[a("el-upload",{staticClass:"avatar-uploader",attrs:{action:t.avatarUrl,"show-file-list":!1,headers:t.headers,"on-success":t.handleAvatarSuccess,"before-upload":t.beforeAvatarUpload}},[t.temp.img?a("img",{staticClass:"avatar",attrs:{src:t.ImgPrefix+t.temp.img}}):a("i",{staticClass:"el-icon-plus avatar-uploader-icon"})])],1),t._v(" "),a("el-form-item",{attrs:{label:"奖品名称",prop:"name"}},[(t.dialogStatus,a("el-input",{staticStyle:{width:"50%"},model:{value:t.temp.name,callback:function(e){t.$set(t.temp,"name",e)},expression:"temp.name"}}))],1),t._v(" "),a("el-form-item",{attrs:{label:"中奖概率",prop:"probability"}},[(t.dialogStatus,a("el-input",{staticStyle:{width:"50%"},model:{value:t.temp.probability,callback:function(e){t.$set(t.temp,"probability",e)},expression:"temp.probability"}}))],1),t._v(" "),a("el-form-item",{attrs:{label:"总数量",prop:"total_number"}},[(t.dialogStatus,a("el-input",{staticStyle:{width:"50%"},model:{value:t.temp.total_number,callback:function(e){t.$set(t.temp,"total_number",e)},expression:"temp.total_number"}}))],1),t._v(" "),a("el-form-item",{attrs:{label:"剩余数量",prop:"surplus_number"}},[(t.dialogStatus,a("el-input",{staticStyle:{width:"50%"},model:{value:t.temp.surplus_number,callback:function(e){t.$set(t.temp,"surplus_number",e)},expression:"temp.surplus_number"}}))],1)],1),t._v(" "),a("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{on:{click:function(e){t.dialogFormVisible=!1}}},[t._v("取 消")]),t._v(" "),"create"==t.dialogStatus?a("el-button",{attrs:{type:"primary"},on:{click:function(e){t.create("temp")}}},[t._v("确 定")]):a("el-button",{attrs:{type:"primary"},on:{click:function(e){t.update("temp")}}},[t._v("确 定")])],1)],1)],1)])])])])},i=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"box-header with-border"},[a("h3",{staticClass:"box-title"},[t._v("搜索选择")]),t._v(" "),a("div",{staticClass:"box-tools pull-right"},[a("button",{staticClass:"btn btn-box-tool",attrs:{type:"button","data-widget":"collapse"}},[a("i",{staticClass:"fa fa-minus"})]),t._v(" "),a("button",{staticClass:"btn btn-box-tool",attrs:{type:"button","data-widget":"remove"}},[a("i",{staticClass:"fa fa-times"})])])])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("tr",[a("th",[t._v("ID")]),t._v(" "),a("th",[t._v("冰鸟UID")]),t._v(" "),a("th",{staticStyle:{"word-break":"break-word"}},[t._v("奖品名称")]),t._v(" "),a("th",[t._v("收货地址")]),t._v(" "),a("th",[t._v("手机号码")]),t._v(" "),a("th",[t._v("ckd码")]),t._v(" "),a("th",[t._v("类型")]),t._v(" "),a("th",[t._v("消费积分")]),t._v(" "),a("th",[t._v("抽奖时间")]),t._v(" "),a("th",[t._v("更新时间")]),t._v(" "),a("th",[t._v("操作")])])}];s._withStripped=!0;var n={render:s,staticRenderFns:i};e.a=n},vHfu:function(t,e,a){var s=a("GLt2");"string"==typeof s&&(s=[[t.i,s,""]]),s.locals&&(t.exports=s.locals);a("rjj0")("a55090f4",s,!1)}});