
 <template>
<div class="content-wrapper" style="min-height: 901px;margin-top: 4.5rem;" v-loading="loading"
    element-loading-text="拼命加载中">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        精灵模板列表
        <small>{{GameInfo.game_name}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><router-link to="/admin/user" tag="a"><i class="fa fa-user"></i> {{GameInfo.game_name}}</router-link></li>
        <li class="active">列表</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
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
                                <div class="col-md-2">
                                 <!-- @keyup.enter.native="handleFilter"  监听回车事件执行handleFilter方法 -->
                                    <el-input  @keyup.enter.native="handleFilter" class="filter-item" placeholder="板块名称" v-model="listQuery.keyword">
                                    </el-input>
                                </div>
                                <div class="col-md-3">
                               <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
  <el-button class="filter-item" type="primary" @click="handleCreate" icon="edit">添加</el-button>
<!--<el-button class="filter-item" type="primary" icon="document" @click="handleDownload">导出</el-button> -->
                                <!-- <el-button class="filter-item" type="primary" icon="upload2" @click="handleUpload">导入</el-button> -->
                                </div>
                            </div>
                        </div>

                        <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ myName }}</h3>
                            <div class="box-tools">
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="table-responsive no-padding">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th>板块名称</th>
                                        <th>样式类型</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    <template>
                                    <tr v-for="item in items">
                                        <td>{{ item['name'] }}</td>
                                        <td><el-tag :type="item['type'] | statusFilter">{{ item['type'] | typeShow }}</el-tag></td>
                                        <td>{{ item['created_at'] }}</td>
                                        <td>{{ item['updated_at'] }}</td>
                                        <td>
                                            <el-tag :type="item['status'] | statusFilter">{{ item['status'] | statusShow }}</el-tag>
                                        </td>
                                        <td>
                                            <el-button v-if="parseInt(item['status'])==1" size="small" type="success" @click="handleModifyStatus(item,0)">正常
                                            </el-button>
                                            <el-button v-if="parseInt(item['status'])==0" size="small" @click="handleModifyStatus(item,1)">禁用
                                            </el-button>
                                            <el-button size="small" type="success" icon="el-icon-edit" @click="handleUpdate(item)" v-if="item.type == 1" >修改</el-button>
                                            <el-button size="small" type="success" icon="el-icon-edit" @click="handleRecommendUpdate(item)" v-else-if="item.type == 2" >修改</el-button>
                                            <el-button size="small" type="success" icon="el-icon-edit" @click="handleActivityUpdate(item)" v-else-if="item.type == 3" >修改</el-button>
                                            <el-button size="small" type="success" icon="el-icon-edit" @click="handleFeedbackUpdate(item)" v-else-if="item.type == 4" >修改</el-button>
                                            <el-button size="small" icon="el-icon-delete" type="danger" @click="handleDelete(item)">删除</el-button>
                                        </td>
                                    </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <div class="pull-right">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="listQuery.page" :page-sizes="[10,20,30, 50]"
                                               :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="total">
                                </el-pagination>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible"  size="small">
                        <el-form class="small-space" :model="temp" :rules="rules" ref="temp"  label-position="left" label-width="100px" style='width: 800px; margin-left:100px;'>
                              <el-form-item label="板块名称" prop="name">
                                 <el-input style="width: 50%;" size="small" v-if="dialogStatus=='update'" v-model="temp.name"></el-input>
                                <el-input style="width: 50%;" size="small" v-else v-model="temp.name"></el-input>
                              </el-form-item>

                              <el-form-item label="板块分类" prop="type" v-if="dialogStatus=='create'">
                                <el-select size="small" v-model="temp.type" placeholder="请选择">
                                            <el-option
                                              v-for="item in PlateType"
                                              :key="item.value"
                                              :label="item.label"
                                              :value="item.value">
                                            </el-option>
                                </el-select>
                              </el-form-item>
                              </el-form>

                              <template  v-if="dialogStatus=='update'">
                              <!-- 热点板块 -->
                              <template  v-if="temp.type == 1">
                                <el-row>
                                  <el-col :span="11" v-for="(rulevalue, ruleindex) in temp.rule_assembly" :key="rulevalue" :offset="ruleindex" v-if="ruleindex < 2">
                                    <el-card :body-style="{ padding: '0px' }">
                                      <img :src="ImgPrefix+rulevalue.img" class="image">
                                      <div style="padding: 14px;">
                                        <div class="bottom clearfix">
                                          <el-button slot="append" size="small" icon="el-icon-edit" type="success" @click="UpdateRule(rulevalue,ruleindex,1)" >编辑</el-button>
                                        </div>
                                      </div>
                                    </el-card>
                                  </el-col>
                                  <el-col :span="24" v-else>
                                  <el-input
                                    placeholder="请点击右边按钮进行编辑"
                                    v-model="rulevalue.title"
                                    :disabled="true" style="margin-top: 1rem;">
                                    <el-button   slot="append" size="small" icon="el-icon-edit" type="success" @click="UpdateRule(rulevalue,ruleindex)">编辑</el-button>
                                    <el-button   slot="append" size="small" icon="el-icon-delete" type="danger" @click="DeleteRule(rulevalue,ruleindex)">删除</el-button>
                                  </el-input>
                                  </el-col>
                                </el-row>
                                <el-row>
                                  <el-col :span="24" v-if="temp.rule_assembly.length >= 2"><el-button  @click="rulePrimaryCreate('text')" style="width: 100%;margin-top: 1rem;">添加</el-button></el-col>
                                  <el-col :span="24" v-else ><el-button  @click="rulePrimaryCreate('img')" style="width: 100%;margin-top: 1rem;">添加</el-button></el-col>
                                </el-row>
                                <el-dialog
                                  width="30%"
                                  title="编辑 内容触发规则"
                                  :visible.sync="innerVisible"
                                  append-to-body>
                                  <el-form class="small-space" :model="temp2" :rules="rules2" ref="temp2"  label-position="left" label-width="100px" >
                                      <el-form-item label="标题" v-if="UpdateMode=='text'" prop="title">
                                          <el-input  size="small" v-model="temp2.title"></el-input>
                                      </el-form-item>

                                      <el-form-item label="图片" v-else>
                                          <el-upload 
                                                  class="avatar-uploader"
                                                  :action="avatarUrl"
                                                  :headers="headers"
                                                  :on-success="handleAvatarSuccess"
                                                  :before-upload="beforeAvatarUpload">
                                              <img v-if="temp2.img" :src="ImgPrefix+temp2.img" class="avatar">
                                              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                          </el-upload>
                                      </el-form-item>

                                      <el-form-item label="规则名称" prop="rule">
                                         <el-select v-model="temp2.rule" filterable placeholder="请选择" size="small" >
                                            <el-option
                                              v-for="item in ruleType"
                                              :key="item.rule"
                                              :label="item.rule"
                                              :value="item.rule">
                                            </el-option>
                                          </el-select>
                                      </el-form-item>
                                </el-form>
                                  <span slot="footer" class="dialog-footer">
                                    <el-button size="small" @click="innerVisible = false">取 消</el-button>
                                    <el-button size="small" v-if="ruledialogStatus=='create'" type="primary" @click="ruleAssemblyCreate('temp2')">确 定</el-button>
                                    <el-button size="small" v-else type="primary" @click="ruleAssemblyUpdate('temp2')">确 定</el-button>
                                  </span>
                                </el-dialog>
                                <span slot="footer" class="dialog-footer">
                                  <el-button size="small" @click="dialogFormVisible = false">取 消</el-button>
                                  <el-button size="small" v-if="dialogStatus=='create'" type="primary" @click="create('temp')">确 定</el-button>
                                  <el-button size="small" v-else type="primary" @click="update('temp')">确 定</el-button>
                              </span>  
                              </template>
                            <!-- 推荐板块 -->
                            <template  v-else-if="temp.type == 2">
                            
                            <el-collapse   v-model="QqactiveName" accordion>
                            <template v-for="(ConfigureChannelitem,Qqkey) in tempConfigureChannel">

                             <template v-if="ConfigureChannelitem.type == 1">
                             
                              <el-collapse-item  :name="Qqkey">
                              <template slot="title">
                                  <div class="row">
                                      <div class="col-md-2">
                                          <span>设置QQ号</span>
                                      </div>
                                      <div class="col-md-2">
                                          <span>点击展开</span>
                                      </div>
                                  </div>
                              </template>
                                 <el-form class="small-space" :model="tempConfigureChannel"  label-position="top" label-width="80px">
                                        <el-form-item label="设置QQ号" prop="qq_group_introduce">
                                        <el-row>
                                          <el-col :span="12">
                                            <el-input
                                              type="textarea"
                                              :rows="3"
                                              placeholder="输入风骚文案，分为两行，每行6—8个字"
                                              v-model="ConfigureChannelitem.qq_group_introduce">
                                            </el-input>
                                          </el-col>
                                          <el-col :span="3">
                                            <el-upload 
                                                          class="avatar-uploader"
                                                          style="margin-left: 1.5rem;"
                                                          :action="avatarUrl"
                                                          :headers="headers"
                                                          :on-success="handleQqGroupSuccess"
                                                          :before-upload="beforeAvatarUpload">
                                                      <img v-if="ConfigureChannelitem.qq_group_img" :src="ImgPrefix+ConfigureChannelitem.qq_group_img" class="avatar2">
                                                      <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                                  </el-upload>
                                          </el-col>
                                          <el-col :span="8">
                                               <el-input  size="small" placeholder="输入Q群号" v-model="ConfigureChannelitem.qq_group_num" prop="qq_group_num"></el-input>
                                              <el-button type="success" @click="QqConfigureUpdate(ConfigureChannelitem)" style="width: 100%;">确认修改</el-button>
                                          </el-col>
                                          </el-row>

                                          </el-form-item>

                                          <el-form-item label="设置渠道ID">
                                          <el-row>
                                            <el-col :span="20">
                                              <el-input
                                                :rows="3"
                                                placeholder="请输入需要禁止的渠道ID"
                                                v-model="ConfigureChannelitem.channel">
                                              </el-input>
                                            </el-col>
                                          </el-row>
                                          </el-form-item>

                                    </el-form>
                                  <el-button size="small" icon="el-icon-delete" type="danger" @click="QqConfigureDelete(tempConfigureChannel,Qqkey)">删除</el-button>
                              </el-collapse-item>
                            </template>

                            <template v-else>
                              <el-collapse-item :name="Qqkey">
                              <template slot="title">
                                  <div class="row">
                                      <div class="col-md-2">
                                          <span>设置微信号</span>
                                      </div>
                                      <div class="col-md-2">
                                          <span>点击展开</span>
                                      </div>
                                  </div>
                              </template>
                                    <el-row>
                                    <el-form class="small-space" :model="ConfigureChannelitem"  label-position="top" label-width="80px">
                                        <el-form-item label="设置微信号" prop="wechat_introduce">
                                          <el-col :span="12">
                                            <el-input
                                              type="textarea"
                                              :rows="3"
                                              placeholder="输入风骚文案，分为两行，每行6—8个字"
                                              v-model="ConfigureChannelitem.wechat_introduce">
                                            </el-input>
                                          </el-col>
                                          <el-col :span="3">
                                            <el-upload 
                                                          class="avatar-uploader"
                                                          style="margin-left: 1.5rem;he"
                                                          :action="avatarUrl"
                                                          :headers="headers"
                                                          :on-success="handleWechatGroupSuccess"
                                                          :before-upload="beforeAvatarUpload">
                                                      <img v-if="ConfigureChannelitem.wechat_img" :src="ImgPrefix+ConfigureChannelitem.wechat_img" class="avatar2">
                                                      <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                                  </el-upload>
                                          </el-col>
                                          <el-col :span="8">
                                               <el-input  size="small" placeholder="输入微信号" v-model="ConfigureChannelitem.wechat_num" prop="wechat_num"></el-input>
                                              <el-button type="success" @click="WechatConfigureUpdate(ConfigureChannelitem)" style="width: 100%;">确认修改</el-button>
                                          </el-col>
                                          </el-form-item>
                                          <el-form-item label="设置渠道ID">
                                          <el-row>
                                            <el-col :span="20">
                                              <el-input
                                                :rows="3"
                                                placeholder="请输入需要禁止的渠道ID"
                                                v-model="ConfigureChannelitem.channel">
                                              </el-input>
                                            </el-col>
                                          </el-row>
                                          </el-form-item>
                                    </el-form>
                                    </el-row>
                                  <el-button size="small" icon="el-icon-delete" type="danger" @click="QqConfigureDelete(tempConfigureChannel,Qqkey)">删除</el-button>
                              </el-collapse-item>
                            </template>

                            </template>
                            </el-collapse>
<br/>
                            <el-button size="small" type="success" icon="el-icon-edit" @click="AddQqConfigure()">添加QQ号</el-button>
                            <el-button size="small" type="success" icon="el-icon-edit" @click="AddWechatConfigure()">添加微信号</el-button>
    
      

                                <el-row>
                                  <el-col :span="8" v-for="(rulevalue, ruleindex) in temp.rule_assembly" :key="rulevalue" :offset="ruleindex">
                                    <el-card :body-style="{ padding: '0px' }">
                                      <img :src="ImgPrefix+rulevalue.img" class="image" style="height: 28rem;">
                                      <div style="padding: 14px;">
                                        <div class="bottom clearfix">

  <!--                                       <el-select v-model="rulevalue.label" placeholder="请选择" size="mini" style="width: 30%;">
      <el-option label="请选择" :value="0"></el-option>
      <el-option label="热门" :value="1"></el-option>
      <el-option label="最新" :value="2"></el-option>
  </el-select> -->
                                        <el-tag :type="rulevalue['label'] | statusFilter">{{ rulevalue['label'] | labelShow }}</el-tag>
                                        <el-button slot="append" size="small" icon="el-icon-edit" type="success" @click="UpdateRule(rulevalue,ruleindex,1)" style="margin-left: 1rem" class="button">编辑</el-button>
                                        <el-button slot="append" size="small" icon="el-icon-delete" type="danger" @click="DeleteRule(rulevalue,ruleindex)"  class="button">删除</el-button>
                                        </div>
                                      </div>
                                    </el-card>
                                  </el-col>
                                </el-row>
                                <el-row>
                                  <el-col :span="24"><el-button  @click="RecommendrulePrimaryCreate()" style="width: 100%;margin-top: 1rem;">添加</el-button></el-col>
                                </el-row>
                                <el-dialog
                                  width="30%"
                                  title="编辑 内容触发规则"
                                  :visible.sync="innerVisible"
                                  append-to-body>
                                  <el-form class="small-space" :model="temp2" :rules="rules2" ref="temp2"  label-position="left" label-width="100px" >
                                      <el-form-item label="标题" prop="title">
                                          <el-input  size="small" v-model="temp2.title"></el-input>
                                      </el-form-item>

                                      <el-form-item label="图片" v-if="UpdateMode=='img'">
                                          <el-upload 
                                                  class="avatar-uploader"
                                                  :action="avatarUrl"
                                                  :headers="headers"
                                                  :on-success="handleAvatarSuccess"
                                                  :before-upload="beforeAvatarUpload">
                                              <img v-if="temp2.img" :src="ImgPrefix+temp2.img" class="avatar">
                                              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                          </el-upload>
                                      </el-form-item>

                                    <el-form-item label="标记"  v-if="UpdateMode=='img'">
                                         <el-select v-model="temp2.label" placeholder="请选择" size="mini" style="width: 30%;">
                                            <el-option label="请选择" :value="0"></el-option>
                                            <el-option label="热门" :value="1"></el-option>
                                            <el-option label="最新" :value="2"></el-option>
                                        </el-select>
                                      </el-form-item>

                                      <el-form-item label="规则名称" prop="rule">
                                         <el-select v-model="temp2.rule" filterable placeholder="请选择" size="small" >
                                            <el-option
                                              v-for="item in ruleType"
                                              :key="item.rule"
                                              :label="item.rule"
                                              :value="item.rule">
                                            </el-option>
                                          </el-select>
                                      </el-form-item>
                                </el-form>
                                  <span slot="footer" class="dialog-footer">
                                    <el-button size="small" @click="innerVisible = false">取 消</el-button>
                                    <el-button size="small" v-if="ruledialogStatus=='create'" type="primary" @click="ruleAssemblyCreate('temp2')">确 定</el-button>
                                    <el-button size="small" v-else type="primary" @click="ruleAssemblyUpdate('temp2')">确 定</el-button>
                                  </span>
                                </el-dialog>
                                <span slot="footer" class="dialog-footer">
                                  <el-button size="small" @click="dialogFormVisible = false">取 消</el-button>
                                  <el-button size="small" v-if="dialogStatus=='create'" type="primary" @click="create('temp')">确 定</el-button>
                                  <el-button size="small" v-else type="primary" @click="update('temp')">确 定</el-button>
                              </span>  
                            </template>

                            <!-- 活动板块 -->
                            <template  v-else-if="temp.type == 3">
                            <el-row v-for="(rulevalue, ruleindex) in temp.rule_assembly" :key="ActivityKey" style="margin-top: 1rem;">
                               <el-col :span="4">
                                <el-card :body-style="{ padding: '0px' }">
                                  <img :src="ImgPrefix+rulevalue.img" class="image">
                                </el-card>
                              </el-col>
                              <el-col :span="16">
                                <el-input placeholder="20字符以内" v-model="rulevalue.title" :disabled="true">
                                  <template slot="prepend">标题</template>
                                </el-input>
                                  <div class="el-textarea">
                                  <textarea placeholder="请输入内容,，注：不得超过600字符" disabled="disabled" type="textarea" v-bind:style="{ color: rulevalue.content_color}" v-model="rulevalue.content" rows="3" autocomplete="off" validateevent="true" class="el-textarea__inner" style="min-height: 33px;"></textarea>
                                  </div>
                              </el-col>
                              <el-col :span="4">
                                   <el-input placeholder="请输入0-100" v-model="rulevalue.sort" v-on:blur="handleRuleSort(rulevalue)">
                                   <template slot="prepend">排序</template>
                                </el-input>
                                  <el-tooltip content="请选择状态" placement="top">
                                    <el-switch
                                      v-model="rulevalue.state"
                                      active-color="#13ce66"
                                      inactive-color="#ff4949"
                                      :active-value="1"
                                      :inactive-value="0"
                                      active-text="使用"
                                      inactive-text="禁用"
                                      @change="ActivityStateUpdate(rulevalue)"
                                      style="margin-left: 1rem;margin-top: 1rem; margin-bottom: 0.5rem;"
                                      >
                                    </el-switch>
                                  </el-tooltip>
                                  <el-button size="small" icon="el-icon-edit" type="primary" @click="UpdateRule(rulevalue,ruleindex)" style="margin-left:0px;margin-top: 0.5rem;">编辑</el-button>
                                  <el-button size="small" icon="el-icon-delete" type="danger" @click="DeleteRule(rulevalue,ruleindex)" style="margin-left:0px;margin-top: 0.5rem;">删除</el-button>
                              </el-col>
                            </el-row>
                                <el-row>
                                  <el-col :span="24"><el-button  @click="RecommendrulePrimaryCreate()" style="width: 100%;margin-top: 1rem;">添加</el-button></el-col>
                                </el-row>
                                <el-dialog
                                  width="30%"
                                  title="编辑 内容触发规则"
                                  :visible.sync="innerVisible"
                                  append-to-body>
                                  <el-form class="small-space" :model="temp2" :rules="rules2" ref="temp2"  label-position="left" label-width="100px" >
                                      <el-form-item label="标题" prop="title">
                                          <el-input  size="small" v-model="temp2.title" placeholder="20字符以内"></el-input>
                                      </el-form-item>

                                      <el-form-item label="内容" prop="content">
                                          <el-input
                                            type="textarea"
                                            :rows="2"
                                            placeholder="请输入内容,，注：不得超过60字符"
                                            v-model="temp2.content">
                                          </el-input>
                                      </el-form-item>

                                      <el-form-item label="内容-颜色">
                                          <el-color-picker v-model="temp2.content_color"></el-color-picker>
                                      </el-form-item>
                                      <el-form-item label="图片" >
                                          <el-upload 
                                                  class="avatar-uploader"
                                                  :action="avatarUrl"
                                                  :headers="headers"
                                                  :on-success="handleAvatarSuccess"
                                                  :before-upload="beforeAvatarUpload">
                                              <img v-if="temp2.img" :src="ImgPrefix+temp2.img" class="avatar">
                                              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                          </el-upload>
                                      </el-form-item>

                                      <el-form-item label="规则名称" prop="rule">
                                         <el-select v-model="temp2.rule" filterable placeholder="请选择" size="small" >
                                            <el-option
                                              v-for="item in ruleType"
                                              :key="item.rule"
                                              :label="item.rule"
                                              :value="item.rule">
                                            </el-option>
                                          </el-select>
                                      </el-form-item>
                                </el-form>
                                  <span slot="footer" class="dialog-footer">
                                    <el-button size="small" @click="innerVisible = false">取 消</el-button>
                                    <el-button size="small" v-if="ruledialogStatus=='create'" type="primary" @click="ruleAssemblyCreate('temp2')">确 定</el-button>
                                    <el-button size="small" v-else type="primary" @click="ruleAssemblyUpdate('temp2')">确 定</el-button>
                                  </span>
                                </el-dialog>
                
                              <span slot="footer" class="dialog-footer">
                                  <el-button size="small" @click="dialogFormVisible = false">取 消</el-button>
                                  <el-button size="small" v-if="dialogStatus=='create'" type="primary" @click="create('temp')">确 定</el-button>
                                  <el-button size="small" v-else type="primary" @click="update('temp')">确 定</el-button>
                              </span>                              

                            </template>

                            <!-- 反馈板块 -->
                            <template  v-else-if="temp.type == 4">
                              <!-- 图片上传组件辅助-->
                              <el-upload
                                      class="avatar-uploader vue-quill-editor-img"
                                      :action="imageUrl"
                                      :headers="headers"
                                      :show-file-list="false"
                                      :on-success="uploadSuccess"
                                      :on-error="uploadError"
                                      :before-upload="beforeUpload">
                              </el-upload>
                              <!--富文本编辑器组件-->
                             <el-row v-loading="quillUpdateImg">
                              <quill-editor
                                      v-model="tempConfigure.feedback_text"
                                      ref="myQuillEditor"
                                      :options="editorOption"
                              >
                              </quill-editor>
                              </el-row>

                              <span slot="footer" class="dialog-footer">
                                  <el-button size="small" @click="dialogFormVisible = false">取 消</el-button>
                                  <el-button size="small" v-if="dialogStatus=='create'" type="primary" @click="create('temp')">确 定</el-button>
                                  <el-button size="small" v-else type="primary" @click="updateFeedback('temp')">确 定</el-button>
                              </span>
                            </template>
                              </template>
                              <span slot="footer" class="dialog-footer" v-if="dialogStatus=='create'">
                                  <el-button size="small" @click="dialogFormVisible = false">取 消</el-button>
                                  <el-button size="small" type="primary" @click="create('temp')">确 定</el-button>
                              </span>
                    </el-dialog >
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
  </div>
</template>
<style>
  .el-upload__input {
        display: none !important;;
  }
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #20a0ff;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 80px;
    height: 80px;
    line-height: 80px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
  .avatar2 {
    width: 80px;
    height: 80px;
    display: block;
  }
  .input-with-select .el-input-group__prepend {
    background-color: #fff;
  }
  .el-col{
    margin-left: 0;
  }
  .time {
    font-size: 13px;
    color: #999;
  }
  
  .bottom {
    margin-top: 13px;
    line-height: 12px;
  }

  .button {
    float: right;
  }

  .image {
    width: 100%;
    display: block;
  }
  .clearfix:before,
  .clearfix:after {
      display: table;
      content: "";
  }
  
  .clearfix:after {
      clear: both
  }
</style>
<script>
import { mapState, mapActions } from 'vuex';
/*import Tinymce from '../Tinymce/index.vue';*/
// 工具栏配置
const toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  ['blockquote', 'code-block'],

  [{'header': 1}, {'header': 2}],               // custom button values
  [{'list': 'ordered'}, {'list': 'bullet'}],
  [{'script': 'sub'}, {'script': 'super'}],      // superscript/subscript
  [{'indent': '-1'}, {'indent': '+1'}],          // outdent/indent
  [{'direction': 'rtl'}],                         // text direction

  [{'size': ['small', false, 'large', 'huge']}],  // custom dropdown
  [{'header': [1, 2, 3, 4, 5, 6, false]}],

  [{'color': []}, {'background': []}],          // dropdown with defaults from theme
  [{'font': []}],
  [{'align': []}],
  ['link', 'image', 'video'],
  ['clean']                                         // remove formatting button
]
export default {
  data() {
    return {
            imageUrl: '/upload/image',
            quillUpdateImg: false, // 根据图片上传状态来确定是否显示loading动画，刚开始是false,不显示
            headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
            editorOption: {
                      placeholder: '',
                      theme: 'snow',  // or 'bubble'
                      modules: {
                        toolbar: {
                          container: toolbarOptions,  // 工具栏
                          handlers: {
                            'image': function (value) {
                              if (value) {
                                document.querySelector('.vue-quill-editor-img input').click();
                              } else {
                                this.quill.format('image', false);
                              }
                            }
                          }
                        }
                      }
            },
            ImgPrefix:process.env.IMAGE_PREFIX,
            QqactiveName:'1',
            GameInfo: '',
            loading:false,
            config: {
              initialFrameWidth: null,
              initialFrameHeight: 350
            },
            myName: '精灵板块管理列表',
            loading: true,
            items: [],
            total:null,
            listQuery: {
                page: 1,
                limit: 10,
                keyword: '',
                game_id:this.$route.params.id,
                terminal:'0'
            },
            textMap: {
                update: '编辑',
                create: '创建'
            },
            aa:"100",
            ruleType: [],
            PlateType: [{
                          value: '1',
                          label: '热点板块'
                        }, {
                          value: '2',
                          label: '推荐板块'
                        }, {
                          value: '3',
                          label: '活动板块'
                        }, {
                          value: '4',
                          label: '反馈板块'
                        }],
            dialogFormVisible:false,
            innerVisible:false,
            dialogStatus: '',
            ruledialogStatus: '',
            headers: {'X-CSRF-TOKEN':Laravel.csrfToken},
            temp: {
                id: '',
                rule_assembly: '',
                type:'',
                name:''
            },
            temp2: {
              img: '',
              rule: '',
              rule_id: '',
              rule_name: '',
              title: '',
              sort: '',
              state: '',
              content_color: '',
              content: '',
              label: ''
            },
            tempConfigureChannel:[],
            tempConfigure: {
                id: '',
                game_id: '',
                wechat_num:'',
                qq_group_num:'',
                wechat_img:'',
                qq_group_img:'',
                wechat_introduce:'',
                qq_group_introduce:'',
                prohibit_channel:'',
                feedback_text:''
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isGIF = file.type === 'image/gif';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG && !isPNG && !isGIF) {
                    this.$message.error('上传头像图片只能是 JPG|PNG|GIF 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return (isJPG || isPNG || isGIF) && isLt2M;
            },
            rules: {
                name: [
                    { required: true, message: '请输入板块名称', trigger: 'blur' },
                    { min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur' }
                ],
                type: [
                    {required: true, message: '请选择是回复类型', trigger: 'change' }
                ],
            },
            rules2: {
                title: [
                    { required: true, message: '请输入标题', trigger: 'blur' }
                ],
                content: [
                    { required: true, message: '请输入内容', trigger: 'blur' }
                ],
                rule: [
                    {required: true, message: '请选择规则名称', trigger: 'change' }
                ],
            },
            rulesConfigure: {
                qq_group_introduce: [
                    { required: true, message: '请输入QQ群介绍', trigger: 'blur' }
                ],
                qq_group_num: [
                    { required: true, message: '请输入QQ群号', trigger: 'blur' }
                ]
            },
            avatarUrl:'/upload/image',
    }
  },
  /*components: {UE},*/
  methods: {
            loadData() {
                this.loading = true
                let url = '/spirit/plate';
                this.$axios.get(url, {
                    headers: this.$store.state.headers,
                    params: this.listQuery
                }).then(response => {
                    this.items = response.data.data
                    this.total = response.data.total
                    this.loading = false
                })
            },
            AddQqConfigure(){
                this.tempConfigureChannel.push({
                              channel: '',
                              game_id: this.$route.params.id,
                              qq_group_img: '',
                              qq_group_introduce: '',
                              qq_group_num: '',
                              wechat_img: '',
                              wechat_introduce: '',
                              wechat_num: '',
                              type: 1,
                              id: 0,
                        });
            },
            AddWechatConfigure(){
                this.tempConfigureChannel.push({
                              channel: '',
                              game_id: this.$route.params.id,
                              qq_group_img: '',
                              qq_group_introduce: '',
                              qq_group_num: '',
                              wechat_img: '',
                              wechat_introduce: '',
                              wechat_num: '',
                              type: 2,
                              id: 0,
                        });
            },
            QqConfigureDelete(data,key){
                this.$confirm('此操作将永久删除该设置, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    if (!data[key].id) {
                      data.splice(key,1);
                      return ;
                    }
                    this.$axios.delete('/spirit/channel-configure/'+data[key].id)
                        .then((response) => {
                            if(response.status === 200){
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'删除成功':response.data.message),
                                    type: 'success'
                                });
                                data.splice(key,1);
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'删除失败':response.data.message),
                                    type: 'error'
                                });
                            }
                        }, (response) => {
                            this.$message({
                                showClose: true,
                                message: '删除失败',
                                type: 'error'
                            });
                        })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
          getUEContent() {
                let content = this.$refs.ue.getUEContent();
/*                this.$notify({
                  title: '获取成功，可在控制台查看！',
                  message: content,
                  type: 'success'
                });*/
                alert(content);
                console.log(content)
              },
            handleSizeChange(val) {
                this.listQuery.limit = val;
                this.loadData();
            },
            handleCurrentChange(val) {
                this.listQuery.page = val;
                this.loadData();
            },
            handleFilter() {
                this.loadData();
            },
            handleAvatarSuccess(res, file) {
                if(res.url){
                    this.temp2.img = res.url;
                }
            },
            handleQqGroupSuccess(res, file) {
              console.log(this.QqactiveName);
              console.log(this.tempConfigureChannel);
                if(res.url){
                    this.tempConfigureChannel[this.QqactiveName].qq_group_img = res.url;
                }
            },
            handleWechatGroupSuccess(res, file) {
              console.log(this.QqactiveName);
              console.log(this.tempConfigureChannel);
                if(res.url){
                    this.tempConfigureChannel[this.QqactiveName].wechat_img = res.url;
                }
            },
            handleCreate(){
                this.dialogStatus = 'create';
                this.temp.name = '';
                this.temp.id = '';
                this.temp.type = '1';
                this.dialogFormVisible = true
            },
            handleDownload(){
                window.open(window.location.protocol+'//'+window.location.host+'/admin/user/excel?q='+this.listQuery.q)
            },
            handleUpdate(data) {
                this.dialogStatus = 'update';
                this.$axios.get('/plate/keyword-assembly', {
                    headers: this.$store.state.headers,
                    params: {
                                spirit_plate_id: data.id,
                            }
                  })
                    .then((response) => {

                        if(response.status === 200){
                            this.temp.id = data.id;
                            this.temp.type = data.type.toString();
                            this.temp.name = data.name;
                            this.temp.rule_assembly = response.data;
                            console.log(this.temp.rule_assembly);
                            this.dialogFormVisible = true
                        }
                    })
            },
            handleRecommendUpdate(data) {
                this.loading = true;
                this.dialogStatus = 'update';
                this.$axios.get('/plate/keyword-assembly', {
                    headers: this.$store.state.headers,
                    params: {
                                spirit_plate_id: data.id,
                            }
                  })
                    .then((response) => {
                        if(response.status === 200){
                            this.temp.id = data.id;
                            this.temp.type = data.type.toString();
                            this.temp.name = data.name;
                            this.temp.rule_assembly = response.data;
                            this.$axios.get('/spirit/configure/single', {
                              headers: this.$store.state.headers,
                              params: {
                                          game_id: this.$route.params.id,
                                      }
                            }).then((response) => {
                               this.tempConfigure.id = response.data.id;
                               this.tempConfigure.game_id = response.data.game_id;
                               this.tempConfigure.wechat_num = response.data.wechat_num;
                               this.tempConfigure.qq_group_num = response.data.qq_group_num;
                               this.tempConfigure.wechat_img = response.data.wechat_img;
                               this.tempConfigure.qq_group_img = response.data.qq_group_img;
                               this.tempConfigure.feedback_text = response.data.feedback_text;
                               this.tempConfigure.wechat_introduce = response.data.wechat_introduce;
                               this.tempConfigure.qq_group_introduce = response.data.qq_group_introduce;
                               this.tempConfigure.prohibit_channel = response.data.prohibit_channel;
                               this.tempConfigureChannel = response.data.channel_configure;
                               this.dialogFormVisible = true
                            })
                        }
                    })
                  this.loading = false;
            },
            handleActivityUpdate(data) {
                this.loading = true;
                this.dialogStatus = 'update';
                this.$axios.get('/plate/keyword-assembly?t=' + Date.now(), {
                    headers: this.$store.state.headers,
                    params: {
                                spirit_plate_id: data.id,
                            }
                  })
                    .then((response) => {
                      this.loading = false;
                        if(response.status === 200){
                            this.temp.id = data.id;
                            this.temp.type = data.type.toString();
                            this.temp.name = data.name;
                            this.temp.rule_assembly = response.data;
                            this.dialogFormVisible = true;
                            this.loading = false;
                        }
                    })
            },
            handleFeedbackUpdate(data) {
                  this.loading = true;
                  this.dialogStatus = 'update';
                  this.$axios.get('/spirit/configure/single', {
                    headers: this.$store.state.headers,
                    params: {
                                game_id: this.$route.params.id,
                            }
                  }).then((response) => {
                     this.tempConfigure.id = response.data.id;
                     this.tempConfigure.game_id = response.data.game_id;
                     this.tempConfigure.wechat_num = response.data.wechat_num;
                     this.tempConfigure.qq_group_num = response.data.qq_group_num;
                     this.tempConfigure.wechat_img = response.data.wechat_img;
                     this.tempConfigure.qq_group_img = response.data.qq_group_img;
                     this.tempConfigure.feedback_text = response.data.feedback_text;
                     this.tempConfigure.wechat_introduce = response.data.wechat_introduce;
                     this.tempConfigure.qq_group_introduce = response.data.qq_group_introduce;
                     this.temp.type = data.type.toString();
                     this.temp.name = data.name;
                     this.temp.id = data.id;
                     this.dialogFormVisible = true;
                     this.loading = false;
                  })
            },
            handleModifyStatus(data, status) {
                this.$axios.put('/spirit/plate/' + data.id, {
                    status: status,
                    action: 'changeStatus'
                })
                    .then((response) => {
                        console.log(response);
                            if(response.status === 200){
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'锁定/解锁，成功':response.data.message),
                                    type: 'success'
                                });
                                this.loadData();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'锁定失败':response.data.message),
                                    type: 'error'
                                });
                            }
                    })
            },
            handleDelete(data) {
                this.$confirm('此操作将永久删除改规则, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/spirit/plate/'+data.id)
                        .then((response) => {
                            if(response.status === 200){
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'删除成功':response.data.message),
                                    type: 'success'
                                });
                                this.loadData();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'删除失败':response.data.message),
                                    type: 'error'
                                });
                            }
                        }, (response) => {
                            this.$message({
                                showClose: true,
                                message: '删除失败',
                                type: 'error'
                            });
                        })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            handleUpload(){
                this.dialogUserVisible = true
            },

 // 富文本图片上传前
            beforeUpload() {
                // 显示loading动画
                this.quillUpdateImg = true
            },
            
            uploadSuccess(res, file) {
                // res为图片服务器返回的数据
                // 获取富文本组件实例
                let quill = this.$refs.myQuillEditor.quill
                // 如果上传成功
                if (res.message === 'OK' && res.url !== null) {
                    // 获取光标所在位置
                    let length = quill.getSelection().index;
                    // 插入图片  res.info为服务器返回的图片地址
                    quill.insertEmbed(length, 'image', '/'+res.url)
                    // 调整光标到最后
                    quill.setSelection(length + 1)
                } else {
                    this.$message.error('图片插入失败')
                }
                // loading动画消失
                this.quillUpdateImg = false
            },
       
            // 富文本图片上传失败
            uploadError() {
                // loading动画消失
                this.quillUpdateImg = false
                this.$message.error('图片插入失败')
            },
            update(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.put('/spirit/plate/' + this.temp.id, {
                            name: this.temp.name,
                            action: 'changeContent'
                        })
                            .then((response) => {
                                if(response.status === 200){
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0 ? '修改成功！' : response.data.message),
                                        type: 'success'
                                    });
                                    this.dialogFormVisible = false
                                    this.loadData();
                                }else{
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0?'修改失败！':response.data.message),
                                        type: 'error'
                                    });
                                }
                            }, response => {
                                if (response.status === 422) {
                                    let msg = response.data.password.size === 0?'':response.data.password
                                    this.$message({
                                        showClose: true,
                                        message: msg,
                                        type: 'error'
                                    });
                                }
                            })
                    } else {
                        return false;
                    }
                });
            },
            updateFeedback(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.put('/spirit/configure/' + this.tempConfigure.id, {
                            plate_id: this.temp.id,
                            name: this.temp.name,
                            feedback_text:this.tempConfigure.feedback_text,
                            action: 'changeFeedback'
                        })
                            .then((response) => {
                                if(response.status === 200){
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0 ? '修改成功！' : response.data.message),
                                        type: 'success'
                                    });
                                    this.dialogFormVisible = false
                                    this.loadData();
                                }else{
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0?'修改失败！':response.data.message),
                                        type: 'error'
                                    });
                                }
                            }, response => {
                                if (response.status === 422) {
                                    let msg = response.data.password.size === 0?'':response.data.password
                                    this.$message({
                                        showClose: true,
                                        message: msg,
                                        type: 'error'
                                    });
                                }
                            })
                    } else {
                        return false;
                    }
                });
            },
            ActivityStateUpdate(data){
                this.loading = true
                this.$axios.put('/plate/keyword-assembly/' + data.id, {
                    state: data.state,
                    action: 'changeStatus'
                })
                  .then((response) => {
                      if(response.status === 200){
                          this.$message({
                              showClose: true,
                              message: (response.data.message.length === 0 ? '修改成功！' : response.data.message),
                              type: 'success'
                          });
                          this.loadData();
                      }else{
                          this.$message({
                              showClose: true,
                              message: (response.data.message.length === 0?'修改失败！':response.data.message),
                              type: 'error'
                          });
                      }
                  }, response => {
                      if (response.status === 422) {
                          let msg = response.data.password.size === 0?'':response.data.password
                          this.$message({
                              showClose: true,
                              message: msg,
                              type: 'error'
                          });
                      }
                  })
            },
            QqConfigureUpdate(data){
                this.$axios.put('/spirit/configure/' + data.id, {
                    qq_group_img: data.qq_group_img,
                    qq_group_introduce: data.qq_group_introduce,
                    qq_group_num: data.qq_group_num,
                    wechat_img: data.wechat_img,
                    wechat_introduce: data.wechat_introduce,
                    wechat_num: data.wechat_num,
                    wechat_num: data.wechat_num,
                    game_id: this.$route.params.id,
                    channel: data.channel,
                    type: 1,
                    action: 'changeChannelConfigure'
                })
                    .then((response) => {
                        if(response.status === 200){
                            this.$message({
                                showClose: true,
                                message: (response.data.message.length === 0 ? '修改成功！' : response.data.message),
                                type: 'success'
                            });
                            this.loadData();
                        }else{
                            this.$message({
                                showClose: true,
                                message: (response.data.message.length === 0?'修改失败！':response.data.message),
                                type: 'error'
                            });
                        }
                    }, response => {
                        if (response.status === 422) {
                            let msg = response.data.password.size === 0?'':response.data.password
                            this.$message({
                                showClose: true,
                                message: msg,
                                type: 'error'
                            });
                        }
                    })
            },
            WechatConfigureUpdate(data){
                this.$axios.put('/spirit/configure/' + data.id, {
                    qq_group_img: data.qq_group_img,
                    qq_group_introduce: data.qq_group_introduce,
                    qq_group_num: data.qq_group_num,
                    wechat_img: data.wechat_img,
                    wechat_introduce: data.wechat_introduce,
                    wechat_num: data.wechat_num,
                    wechat_num: data.wechat_num,
                    game_id: this.$route.params.id,
                    channel: data.channel,
                    type: 2,
                    action: 'changeChannelConfigure'
                })
                    .then((response) => {
                        if(response.status === 200){
                            this.$message({
                                showClose: true,
                                message: (response.data.message.length === 0 ? '修改成功！' : response.data.message),
                                type: 'success'
                            });
                            this.loadData();
                        }else{
                            this.$message({
                                showClose: true,
                                message: (response.data.message.length === 0?'修改失败！':response.data.message),
                                type: 'error'
                            });
                        }
                    }, response => {
                        if (response.status === 422) {
                            let msg = response.data.password.size === 0?'':response.data.password
                            this.$message({
                                showClose: true,
                                message: msg,
                                type: 'error'
                            });
                        }
                    })
            },
            ProhibitChannelUpdate(formName){
                this.$axios.put('/spirit/configure/' + this.tempConfigure.id, {
                    prohibit_channel: this.tempConfigure.prohibit_channel,
                    action: 'changeProhibitChannel'
                })
                    .then((response) => {
                        if(response.status === 200){
                            this.$message({
                                showClose: true,
                                message: (response.data.message.length === 0 ? '修改成功！' : response.data.message),
                                type: 'success'
                            });
                            this.loadData();
                        }else{
                            this.$message({
                                showClose: true,
                                message: (response.data.message.length === 0?'修改失败！':response.data.message),
                                type: 'error'
                            });
                        }
                    }, response => {
                        if (response.status === 422) {
                            let msg = response.data.password.size === 0?'':response.data.password
                            this.$message({
                                showClose: true,
                                message: msg,
                                type: 'error'
                            });
                        }
                    })
            },
            ruleAssemblyUpdate(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.put('/plate/keyword-assembly/' + this.temp2.id, {
                            rule: this.temp2.rule,
                            title: this.temp2.title,
                            content: this.temp2.content,
                            label: this.temp2.label,
                            content_color: this.temp2.content_color,
                            img: this.temp2.img,
                            action: 'changeContent'
                        })
                            .then((response) => {
                                if(response.status === 200){
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0 ? '修改成功！' : response.data.message),
                                        type: 'success'
                                    });
                                    this.temp.rule_assembly[this.activeName].rule = this.temp2.rule;
                                    this.temp.rule_assembly[this.activeName].title = this.temp2.title;
                                    this.temp.rule_assembly[this.activeName].label = this.temp2.label;
                                    this.temp.rule_assembly[this.activeName].content = this.temp2.content;
                                    this.temp.rule_assembly[this.activeName].content_color = this.temp2.content_color;
                                    this.temp.rule_assembly[this.activeName].img = this.temp2.img;
                                    this.innerVisible = false
                                    this.loadData();
                                }else{
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0?'修改失败！':response.data.message),
                                        type: 'error'
                                    });
                                }
                            }, response => {
                                if (response.status === 422) {
                                    let msg = response.data.password.size === 0?'':response.data.password
                                    this.$message({
                                        showClose: true,
                                        message: msg,
                                        type: 'error'
                                    });
                                }
                            })
                    } else {
                        return false;
                    }
                });
            },
            DeleteRule(data,key){
                this.$confirm('此操作将永久删除该信息, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/plate/keyword-assembly/' + data.id)
                        .then((response) => {
                            if(response.status === 200){
                                this.temp.rule_assembly.splice(key,1);
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'删除成功':response.data.message),
                                    type: 'success'
                                });
                                this.loadData();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: (response.data.message.length === 0?'删除失败':response.data.message),
                                    type: 'error'
                                });
                            }
                        }, (response) => {
                            this.$message({
                                showClose: true,
                                message: '删除失败',
                                type: 'error'
                            });
                        })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            UpdateRule(data,activeName,tyep = 0){
                if (!this.temp.name) {
                    this.$message({
                          type: 'info',
                          message: '请先输入板块名称！'
                      });
                    return false;
                }
                this.ruledialogStatus =  'update';
                if (tyep) {
                  this.UpdateMode =  'img';
                }else{
                  this.UpdateMode =  'text';
                }
                this.temp2.img =  data.img;
                this.temp2.id =  data.id;
                this.temp2.rule_id =  data.rule_id;
                this.temp2.rule =  data.rule;
                this.temp2.rule_name =  data.rule_name;
                this.temp2.title =  data.title;
                this.temp2.label =  data.label;
                this.temp2.content =  data.content;
                this.temp2.content_color =  data.content_color;
                this.activeName =  activeName;
                this.innerVisible = true;
            },
            rulePrimaryCreate(UpdateMode){
                if (!this.temp.name) {
                    this.$message({
                          type: 'info',
                          message: '请先输入板块名称！'
                      });
                    return false;
                }
                this.ruledialogStatus =  'create';
                this.UpdateMode =  UpdateMode;
                this.temp2.img =  '';
                this.temp2.rule =  '';
                this.temp2.rule_id =  '';
                this.temp2.rule_name =  '';
                this.temp2.title =  '';
                this.activeName =  null;
                this.innerVisible = true;
            },
            handleRuleSort(data) {
                this.$axios.put('/plate/keyword-assembly/' + data.id, {
                    sort: data['sort'],
                    action: 'changeSort'
                })
                    .then((response) => {
                this.handleActivityUpdate(this.temp);
                      this.$message({
                          showClose: true,
                          message: '操作成功',
                          type: 'success'
                      });
                    })
            },
            RecommendrulePrimaryCreate(){
                if (!this.temp.name) {
                    this.$message({
                          type: 'info',
                          message: '请先输入板块名称！'
                      });
                    return false;
                }
                this.ruledialogStatus =  'create';
                this.UpdateMode =  'img';
                this.temp2.img =  '';
                this.temp2.rule =  '';
                this.temp2.rule_id =  '';
                this.temp2.rule_name =  '';
                this.temp2.title =  '';
                this.temp2.sort = '';
                this.temp2.state = '';
                this.temp2.label = 0;
                this.temp2.content_color = '';
                this.temp2.content = '';
                this.activeName =  null;
                this.innerVisible = true;
            },
            create(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$axios.post('/spirit/plate', {
                            name: this.temp.name,
                            type: this.temp.type,
                            game_id: this.$route.params.id
                        })
                            .then((response) => {
                                if(response.status === 200){
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0 ? '操作成功' : response.data.message),
                                        type: 'success'
                                    });
                                    this.dialogFormVisible = false
                                    this.loadData();
                                }else{
                                    this.$message({
                                        showClose: true,
                                        message: (response.data.message.length === 0?'操作失败':response.data.message),
                                        type: 'error'
                                    });
                                }
                            }, response => {
                                if (response.status === 422) {
                                    let msg = ''
                                    $.each(response.data, function(key, val) {
                                        msg += val
                                    });
                                    this.$message({
                                        showClose: true,
                                        message: msg,
                                        type: 'error'
                                    });
                                }
                            })
                    } else {
                        return false;
                    }
                });
            },
            ruleAssemblyCreate(formName) {
                    this.$refs[formName].validate((valid) => {
                        if (valid) {
                            this.loading = true;
                            this.$axios.post('/plate/keyword-assembly', {
                                rule: this.temp2.rule,
                                title: this.temp2.title,
                                sort: this.temp2.sort,
                                state: this.temp2.state,
                                label: this.temp2.label,
                                content_color: this.temp2.content_color,
                                content: this.temp2.content,
                                img: this.temp2.img,
                                spirit_plate_id: this.temp.id
                            })
                                .then((response) => {
                                    if(response.status === 200){
                                        this.temp.rule_assembly.push({
                                              id: response.data.id,
                                              img: this.temp2.img,
                                              title: this.temp2.title,
                                              rule: this.temp2.rule,
                                              sort: this.temp2.sort,
                                              state: this.temp2.state,
                                              label: this.temp2.label,
                                              content_color: this.temp2.content_color,
                                              content: this.temp2.content,
                                              spirit_plate_id: this.temp.id
                                        });
                                        this.$message({
                                            showClose: true,
                                            message: (response.data.message.length === 0 ? '操作成功' : response.data.message),
                                            type: 'success'
                                        });
                                        this.innerVisible = false
                                        this.loadData();
                                    }else{
                                        this.$message({
                                            showClose: true,
                                            message: (response.data.message.length === 0?'操作失败':response.data.message),
                                            type: 'error'
                                        });
                                    }
                                }, response => {
                                    if (response.status === 422) {
                                        let msg = ''
                                        $.each(response.data, function(key, val) {
                                            msg += val
                                        });
                                        this.$message({
                                            showClose: true,
                                            message: msg,
                                            type: 'error'
                                        });
                                    }
                                })
                        } else {
                            return false;
                        }
                    });
            },
        },
        created() {
            this.loading = true;
            //获取游戏信息
            let GamesInfoUrl = '/games/get/'+this.$route.params.id;
            this.$axios.get(GamesInfoUrl, {
            }).then(response => {
                this.GameInfo = response.data;
            })
            //获取该游戏的规则列表
            let ruleTypeUrl = '/spirit/keyword';
            this.$axios.get(ruleTypeUrl, {
                headers: this.$store.state.headers,
                params: {
                    query_type: 1,
                    game_id:this.$route.params.id,
                },
            }).then(response => {
                var data = this.CdkType;
                this.ruleType = response.data;
            })
            this.loadData()
        },
        filters: {
            statusFilter(status) {
                const statusMap = {
                    0: 'success',
                    1: 'gray',
                };
                return statusMap[parseInt(status)]
            },
            statusShow(status){
                const statusMap = {
                    0:'使用',
                    1:'禁用'
                };
                return statusMap[parseInt(status)]
            },
            typeShow(type){
                const typeMap = {
                    1:'热点板块',
                    2:'推荐板块',
                    3:'活动板块',
                    4:'反馈板块'
                };
                return typeMap[parseInt(type)]
            },
            labelShow(label){
                const labelMap = {
                    0:'无',
                    1:'热点',
                    2:'最新'
                };
                return labelMap[parseInt(label)]
            },
            is_vagueShow(is_vague){
                const is_vagueMap = {
                    0:'否',
                    1:'是'
                };
                return is_vagueMap[parseInt(is_vague)]
            },
            
        },
}
</script>
