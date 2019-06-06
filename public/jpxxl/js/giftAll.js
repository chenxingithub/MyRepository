$(document).ready(function() {

    var dataName = 'records';//存cookie名
    var page = 1,limit = 4;
    var container = $('.container');
    var codeList = getCookie(dataName);

    function totalList(res) {
        
        var data = res.data;
        var text = '',count,code,getStatus = '',existId;

        if(data) {
            for(var i = 0; i < data.length; i++) {
                code = judgeCode(data[i].id);//判断礼包码是否存在
                count = parseInt((data[i].surplus / (data[i].cdk_total_num || 1))*100, 10);//比例
                getStatus = data[i].surplus == 0 ? '已领完' : (code ? '查看礼包码' : '领取礼包码');
                existId = judgeId(data[i].id);
                
                if(!existId) {//根据id判断礼包是否存在，不存在则保存cookie
                    saveCookie(data[i].cdk_type_title, data[i].cdk_type_content, data[i].id, null, data[i].expired_at);
                }
    
                text += '<li><div class="focusGift"><em>'+ data[i].cdk_type_title +'</em><p>'+ data[i].cdk_type_content +
                '</p><div class="bili_bar"><div class="fill_bar" style="width: '+ count +'%;"></div></div>'+
                '<span>剩余 ：'+ data[i].surplus +'</span></div><div class="checkGift"><span data-id='+
                data[i].id +'>'+ getStatus +'</span></div></li>';
                
            }
            container.find('.giftList').append(text);
        } else {
            text = '<p style="text-align:center;color:#AD7D11;font-size: .4rem;margin-top:.4rem;">暂无可领取礼包</p>';
            container.find('.giftAll').append(text);
        }
        

        if((data.last_page - data.current_page) > 0) {
            container.find('.load .loadMore').show();
            container.find('.load .moreOver').hide();
        } else {
            container.find('.load .loadMore').hide();
            container.find('.load .moreOver').show();
        }
    }
    function getListParam(page, limit) {
        var listParam = {
            url: urlPrefix+'/yh-cms-api/cdk-type-list',
            type: 'get',
            data: {
                page: page,
                limit: limit,
                game_id: 1
            }
        }
        return listParam;
    }
    //礼包列表首次请求发送
    var listParam = getListParam(page, limit);
	send(listParam, totalList);

    //首页基本信息请求
	function homeBase(res) {
		var data = res.data[0];

		if(judgeClient() == 'iOS'){//下载链接
			container.find('.download').attr('href', data.ios_download_url);
			container.find('#erweima img').attr('src',imageUrl + data.ios_dow_code_img);
		} else {
			container.find('.download').attr('href', data.android_download_url);
			container.find('#erweima img').attr('src',imageUrl + data.android_dow_code_img);
		}
	}
	var homeParam = {
		url: urlPrefix+'/yh-cms-api/game-info',
		type: 'get',
		data: {
			idfa: 'jpxxl'
		}
	}//首页基本信息请求发送
	send(homeParam, homeBase);


    //领取 或 查看礼包码
    var giftList = container.find('.giftList');
    var giftCodeModal = container.find('#giftCodeModal');
    
    function reqCode(id,domNode) {//请求礼包码
        function appendCode(res) {
            var data = res.data;
            if(!data) {
                alert('该礼包已领完');
                return false;
            }
            saveCode(data.cdk_type_id, data.code);
            if(judgeCode(data.cdk_type_id)) {
                domNode.text('查看礼包码');
                giftCodeModal.show();
                giftCodeModal.find('.code span').text(data.code);
            }   
        }

        var codeParam = {
            url: urlPrefix+'/yh-cms-api/cdk-list-receive/'+ id,
            type: 'get',
            data: {
                idfa: 'jpxxl'
            }
        }//请求发送
        send(codeParam, appendCode);
    }
    function getCode(event) {
        var target = $(event.target);
        var codeId = target.data('id');
        var giftCode = judgeCode(codeId);//cookie取code
        if(codeId) {
            if(giftCode) {//判断是否存在cookie中
                giftCodeModal.show();
                giftCodeModal.find('.code span').text(giftCode);
            } else {
                reqCode(codeId,target);//根据id请求code
            }
        }
    }
    giftList.on('click',$('.checkGift'),getCode);//点击领取或查看


    /**
     * 存cookie
     * title: 标题
     * describe：描述
     * id：ID
     * code： 礼包码
     * overTime： 过期时间
     * */   
    function saveCookie(title, describe, id, code, overTime) {
        
        var record = {
            title: title,
            describe: describe,
            id: id,
            code: code,
            overTime: overTime
        };
        var records = getCookie(dataName);
        if (records) {
            records.push(record);
        } else {
            var records = [];
            records.push(record);
        }
        records = escape(JSON.stringify(records));
        // var d = new Date();
        // d.setTime(d.getTime() + (24*60*60*1000));
        // var expires = "expires=" + d.toGMTString();
        document.cookie = dataName + "="+ records +";path=/";

    }    


    /**
     * 传一个礼包 ID 返回 是否已领取 ( Cookie里面 )
     * 传一个礼包 ID 返回 对应的礼包码 ( Cookie 里面 )
     * 
     * return code
     *判断礼包码是否存在
     */
    function judgeCode(id) {
        var data = getCookie(dataName);
        var code = false;

        if(data) {
            for(var i=0; i < data.length; i++ ) {
                
                if(data[i].id == id) {
                    code = data[i].code;
                }
            }
        }
        return code;
    }
    //判断礼包是否存在
    function judgeId(id) {
        var data = getCookie(dataName);
        var existId = false;
        if(data) {
            for(var i=0; i < data.length; i++ ) {
                
                if(data[i].id == id) {
                    existId = true;
                }
            }
        }
        return existId;
    }
    //根据id存 code
    function saveCode(id, code) {
        var data = getCookie(dataName);
        if(data) {
            for(var i=0; i < data.length; i++ ) {
                
                if(data[i].id == id) {
                    data[i].code = code;
                }
            }
            data = JSON.stringify(data);
            document.cookie = dataName + "="+ escape(data) + ";path=/";
        }
    }

    scanModal();//扫一扫 Modal
    shareModal();//分享 Modal
    pcNoneModal();//电脑版未开放提示 modal
    homePageBtnfunc();//官网按钮点击切换
    downloadBtnfunc();//顶部下载按钮切换
    BottomNavChange();//底部导航栏的图标切换
    $($('.navBar li a')[3]).css('backgroundPositionY','-1.2795rem');//默认选中礼包领取

    //所有礼包-》 签到、礼包点击切换改变样式
    var giftAll = $('.giftAll');
    var giftLink1 = giftAll.find('.giftLink1');
    var giftLink2 = giftAll.find('.giftLink2');
    var pcTipModal = $('#pcTipModal');
    giftLink1.on('click',function() { 
        // giftLink1.css({'backgroundPositionY':'-2.53rem','color':'#8f0000'});
        pcTipModal.show();
    });
    giftLink2.on('click',function() {
        // giftLink2.css({'backgroundPositionY':'-3.8rem','color':'#8f0000'});
        pcTipModal.show();
    });
    //礼包码 Modal
    var giftCodeModal = $('#giftCodeModal');
    var codeClose = giftCodeModal.find('.codeClose');
    codeClose.on('click',function() {
        giftCodeModal.hide();
    });


    var listLoad = $('.load');
    //根据请求状态显示
    var loadMore = listLoad.find('.loadMore');//点击加载显示更多
    var loading = listLoad.find('.loadMore');//正在加载更多
    var moreOver = listLoad.find('.moreOver');//已全部加载
     //点击加载更多 发送ajax请求

    function loadMoreList() {
        page += 1;
        limit = 4;
        var listParam = getListParam(page, limit);
        send(listParam, totalList);
    }
    loadMore.on('click',loadMoreList);
    
});