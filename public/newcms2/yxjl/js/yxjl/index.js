$(function() {
    
    var content = $('.bn-content');
    var nav_bar = content.find('.bn-nav-bar .bn-nav-content');
    var switch_panel = $('#bn-switch-panel');
    var question_panel = $('.bn-question');
    var quesBtn = question_panel.find('.bn-question-btn');
    var dialog_panel = $('#bn-dialog-panel');
    var freeback_panel = $('#bn-freeback-panel');
    var freeback_history = '';
    var isWebview = '';

    var gameParams = {
        game_id: bn_utils.getUrlParam('game_id') || 2,
        role_id: bn_utils.getUrlParam('role_id') || 11,
        user_id: bn_utils.getUrlParam('user_id') || 22,
        service_id: bn_utils.getUrlParam('service_id') || '',
        role_name: bn_utils.getUrlParam('role_name') || '',
        vip: bn_utils.getUrlParam('vip') || '',
        role_rank: bn_utils.getUrlParam('role_rank') || ''
    };

    nav_bar.on('touchstart','.bn-nav', function(e) {
        var target = $(e.target);
        var dis_panel_id = target.data('chag-id');
        var quesBtn_data = quesBtn.data('bn-type');

        bottomInput_chag(dis_panel_id,quesBtn_data);//底部输入框的切换
        if(target.hasClass('bn-nav-active')) {
            var panel = switch_panel.find('.bn-sw-panel-'+ dis_panel_id);
            var panelShow = panel.css('display');
       
            if(panelShow == 'none') {
                $('#bn-dialog-panel').hide();
                panel.show();
            }
            return false;
        }
        if(target.hasClass('bn-nav')) {
            nav_bar.find('.bn-nav-active').removeClass('bn-nav-active');
            target.addClass('bn-nav-active');
        }

        $('.bn-sw-chld').hide();
        switch_panel.find('.bn-sw-panel-'+ dis_panel_id).show();

           
        if($('.bn-nav-active').data('chag-id') == 2){//推荐初始化
            if(!target.data('load')) {
                target.data('load', true);
                ruleComponentReq({
                        spirit_plate_id: target.data('nav-id')
                    }, function(res) {
                        var data = res.data;
                        
                        var _html = '',flag='';

                        for(var i = 0; i < data.length; i++) {
                            if(data[i].label == 1) {
                                flag = '<img src="./images/yxjl/HOT-flg.png" alt="image">';
                            } else if(data[i].label == 2) {
                                flag = '<img src="./images/yxjl/NEW-flg.png" alt="image">';
                            } else {
                                flag='';
                            }
                            _html += '<div><div class="bn-2-cont-txt">'
                                    +'<div class="bn-2-txt-flg">'+ flag +'</div>'
                                    +'<span class="bn-rule" data-id="'+ data[i].id +'" data-rule="'+ data[i].rule +'">'+ data[i].title +'</span>'
                                +'</div>'
                            +'</div>';
                        }
                        $('.bn-sw-panel-2 .bn-pan-2-cont').append(_html);
                        setTimeout(function () {

                            var recom = new BScroll('.bn-sw-panel-2',{click: true});
                        }, 0);
                    });
            }
            
        }
        if($('.bn-nav-active').data('chag-id') == 3){//活动初始化
            if(!target.data('load')) {//初次点击加载数据
                target.data('load', true);
                ruleComponentReq({
                        spirit_plate_id: target.data('nav-id')
                    }, function(res) {
                        var data = res.data;
                        var _html = '';

                        for(var i = 0; i < data.length; i++) {
                            _html += '<li class="bn-panel-3-chld-list bn-f list-item bn-rule" data-rule="'+ data[i].rule +'" data-id="'+ data[i].id +'">'
                                    +'<div class="bn-3-list-icon">'
                                        +'<img src="'+ imageUrl + data[i].img +'" alt="images">'
                                    +'</div>'
                                    +'<div class="bn-3-list-text" >'
                                    +'<p style="font-weight: 600; font-size: .42rem;">'+ data[i].title +'</p>'
                                    +'<p style="color: '+ data[i].content_color +'; line-height: 1.3em; display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 3;overflow: hidden;">'+ data[i].content +'</p>'
                                    +'</div>'
                                +'</li>';
                        }
                        $('.bn-sw-panel-3 ul').append(_html);
                        var act = new BScroll('.bn-sw-panel-3', {click: true});
                    });
            }
        }
        if($('.bn-nav-active').data('chag-id') == 4) {
        
            var _html = '<li class="bn-server list-item">'
                    +'<div class="bn-ser-content bn-dialog-cont" style="padding-bottom: .2rem;">'
                        +'<span>亲爱的训练师：</span>'
                        +'<p style="text-indent: 2em">如果您对游戏什么建议，可以及时反馈给金皮卡，金皮卡会合同工作人员好好交流反馈的~</p>'
                        +'<p style="text-indent: 2em">如果您的建议被采纳了，会有神秘大奖的哦，么么哒~</p>'
                        +'<br/>'
                        +'<br/>'
                        +'<span style="color: #FF9900;">建议反馈格式：</span>'
                        +'<p style="text-indent: 2em">在下方输入框内直接输入您的建议即可，内容越详尽，被采纳的几率越大哦~</p>'
                    +'</div>'
                    +'<div class="bn-ser-icon bn-dialog-icon"></div>'
                +'</li>';
            $('#bn-freeback-panel ul').append(_html);
            setTimeout(function() {
                freebackScro.scrollTo(0,freebackScro.maxScrollY,700);
            }, 20);
        }
    });
   //基本信息
    var baseInfoParams = {
        game_id: gameParams.game_id
    };
    function baseInfoFn(res) {
        var data = res.data;
        var swPanel2 = $('.bn-sw-panel-2');
        var clientOs = bn_utils.judgeClient();

        var panel_hed_html = '<div class="bn-2-h bn-2-h-1 bn-f-j">'
                +'<div class="bn-2-h-cont bn-f-item">'+ data.qq_group_introduce +'</div>'
                +'<a id="bn-cp-QQ" href="javascript:;" class="bn-2-cp-btn bn-f-item" data-clipboard-text="'+ data.qq_group_num +'">'
                    +'复制Q群号'
                +'</a>'
            +'</div>'
            +'<div class="bn-2-h bn-2-h-2 bn-f-j" >'
                +'<div class="bn-2-h-cont bn-f-item">'+ data.wechat_introduce +'</div>'
                +'<a id="bn-cp-wx" href="javascript:;" class="bn-2-cp-btn bn-f-item" data-clipboard-text="'+ data.wechat_num +'">'
                    +'复制微信号'
                +'</a>'
            +'</div>';
        swPanel2.find('.bn-pan-2-header').append(panel_hed_html);
        
        $('.bn-2-h-1').on('click',function (e) {// 复制 qq
            if(clientOs == 'iOS') {
                window.webkit.messageHandlers.iOS.postMessage({functionName:'copy', parameter:data.qq_group_num});
            }
            if(clientOs == 'Android') {
                android.copyText(data.qq_group_num);
            }
            var contact_modal = $('#bn-contact-modal');
            contact_modal.find('.bn-cp-qq').removeClass('bn-cp-wx');
            contact_modal.show();
        });
    

        $('.bn-2-h-2').on('click',function (e) {// 复制微信
            if(clientOs == 'iOS') {
                window.webkit.messageHandlers.iOS.postMessage({functionName:'copy', parameter:data.wechat_num});
            }
            if(clientOs == 'Android') {
                android.copyText(data.wechat_num);
            }
            var contact_modal = $('#bn-contact-modal');
            contact_modal.find('.bn-cp-qq').addClass('bn-cp-wx');            
            contact_modal.show();
            e.clearSelection();
        });
        
        cpCloseBindEvent();
    }
    function cpCloseBindEvent() {//关闭复制提示弹窗事件
        $('#bn-contact-modal .bn-cp-close').on('click', function (e) {
            $(this).parents('#bn-contact-modal').hide();
        });
        $('#bn-contact-modal').on('click', function(e) {
            var tag = $(e.target);
            if(tag.hasClass('bn-box-com')) {
                $(this).hide();
            } else {
                return false;
            }
        });
    }
    baseInfoReq(baseInfoParams, baseInfoFn);


    // id: 10, "热点板块"
    var ruleComponentParams = {
        spirit_plate_id: 10
    };
    function ruleComponentFn(res) {//初始热点板块

        var data = res.data,listHtml = '';
        var container = $('#bn-switch-panel');

        var headHtml = '<div class="bn-header-1"><img class="bn-rule"  data-rule="'+ data[0].rule +'" data-id="'+ data[0].id +'" src="'+ imageUrl + data[0].img +'" alt="image"></div>'
                    +'<div class="bn-header-2"><img class="bn-rule" data-rule="'+ data[1].rule +'" data-id="'+ data[1].id +'" src="'+ imageUrl + data[1].img +'" alt="image"></div>';
        
        $('.bn-nav-btn1').data('load', true);
        for(var i = 2; i < data.length; i++) {
            listHtml += '<li><div class="bn-1-list">'
                            +'<div class="bn-rule" data-id="'+ data[i].id +'" data-rule="'+ data[i].rule +'">'+ data[i].title +'</div>'
                        +'</div>'
                    +'</li>';
        }
        container.find('.bn-pan-1-header').append(headHtml);
        container.find('.bn-panel-1-cont ul').append(listHtml);
    
    }
    ruleComponentReq(ruleComponentParams, ruleComponentFn);

    function bottomInput_chag(panelId,btnData) {//不同的板块切换不同的底部输入面板

        if(panelId == 4) {
            quesBtn.data('bn-type', 'freeback');
            quesBtn.text('提建议');
        } else {
            if(btnData != 'question') {
                quesBtn.data('bn-type', 'question');
                quesBtn.text('提问');
            }
        }

    }
    
    //热点板块（滚动事件）
    var hotScro = new BScroll('.bn-sw-panel-1', {click: true});
    
    //反馈板块（滚动事件）
    var freebackScro = new BScroll('#bn-freeback-panel',{click: true});

    //对话板块滚动（滚动事件）
    var dialogScro = '';
    setTimeout(function () {
        dialogScro = new BScroll('#bn-dialog-panel', {click: true});
    },20);

    
    /**
     * 提问内容显示对话框并滚动到底部
     * @param {String} msg 
     * @param {String} msgType server/client
     * @param {String} panelType freeback/question
     */
    function msgDisplay(msg,msgType,panelType) {
        var _html = '';
        msg = matchKeyWord(msg);
        if(msgType == 'server') {
           if(panelType == 'question') {
                _html = '<li class="bn-server list-item">'
                    +'<div class="bn-ser-content bn-dialog-cont">'
                            + msg
                            +'<div class="bn-freeback bn-f">'
                                +'<div class="bn-f-item bn-f-question">'
                                    +'<span >此回答是否解决了您的问题？</span>'
                                +'</div>'
                                +'<div class="bn-f-item bn-f">'
                                    +'<div data-solve="false" class="bn-fb-btn bn-freeback-fal">未解决</div>'
                                    +'<div data-solve="true" class="bn-fb-btn bn-freeback-tru">解决</div>'
                                +'</div>'
                            +'</div>'
                        +'</div>'
                    +'<div class="bn-ser-icon bn-dialog-icon"></div>'
                +'</li>';
           } else {
                _html = '<li class="bn-server list-item">'
                    +'<div class="bn-ser-content bn-dialog-cont" style="padding-bottom: .3rem;">'
                            + msg
                        +'</div>'
                    +'<div class="bn-ser-icon bn-dialog-icon"></div>'
                +'</li>';
           }
        } else{
            _html = ' <li class="bn-client list-item">'
                +'<div class="bn-client-icon bn-dialog-icon"></div>'
                +'<div class="bn-client-content bn-dialog-cont">'
                    + msg +'</div>'
            +'</li>';
        }
        
        if(panelType == 'freeback') {
            freeback_panel.find('ul').append(_html);
            setTimeout(function() {
                freebackScro.scrollTo(0,freebackScro.maxScrollY,700);
            }, 20);
        } else {
            
           
            dialog_panel.find('ul').append(_html);
           
            setTimeout(function() {//dom 渲染完成后滚动到底部
                
                dialogScro.scrollTo(0,dialogScro.maxScrollY,700);
            }, 20);
        }
         
    }

    //输入框提问-请求后台回复
    function bottomInputSubFn(res) {

        var data = res.data;
        // log(data);
        var _data = quesBtn.data('bn-type');
        if(_data == 'question'){
            setTimeout(function() {
                msgDisplay(data.content,'server','question');
            },1000);
        }
        if(_data == 'freeback'){
            msgDisplay('反馈成功','server','freeback');
        }
    }
    question_panel.on('touchstart', '.bn-question-btn', function(e) {

        var quesNode = question_panel.find('.bn-ques-cont'),
            ques = quesNode.val().trim();

        if(!ques) { return false; }

        var _data = quesBtn.data('bn-type');
        if(_data == 'question') {
            switch_panel.find('.bn-sw-chld').hide();
            dialog_panel.show();
    
            keywordRequest(2, ques, bottomInputSubFn);
            quesNode.val('');
            msgDisplay(ques,'client','question');

        }
        if(_data == 'freeback') {
            switch_panel.find('.bn-sw-chld').hide();
            freeback_panel.show();
    
            var params = {
                game_id: gameParams.game_id,
                user_id: gameParams.user_id,
                from: gameParams.role_id,
                role_name: gameParams.role_name,
                vip: gameParams.vip,
                role_rank: gameParams.role_rank,
                feedback_message: ques,
                service_id: gameParams.service_id
            };
            feedbackSubReq(params, bottomInputSubFn);
            quesNode.val('');
            msgDisplay(ques,'client','freeback');
        }
       
    });

    //问题是否解决反馈
    dialog_panel.on('touchstart','.bn-fb-btn', function(e) {
        var tag = $(e.target);
        var parent = tag.parents('.bn-freeback');
        var resolve = tag.data('solve');

        parent.children().remove();
        parent.append('<div style="font-size: .32rem;text-align: center;line-height: 1rem;width: 100%;color: #CCCCCC;">感谢您对金皮卡的支持</div>');
    });

    /**
     * 规则、关键字请求
     * 
     * @param {Number} type 1:规则名/2:关键字（用于提问）
     * @param {String} text 匹配文本
     * @param {Function} callbackFn 回调函数
     */
    function keywordRequest(type, text,callbackFn) {
        var keywordParams = {
            keyword: text,
            type: type,
            game_id: gameParams.game_id,
            from: gameParams.role_id,
            user_id: gameParams.user_id,
            role_name: gameParams.role_name,
            vip: gameParams.vip,
            role_rank: gameParams.role_rank,
            service_id: gameParams.service_id
        };
        keywordReq(keywordParams, callbackFn);
    }
    //匹配规则请求后操作
    function keywordFn(res) {
        var data = res.data;
        var content = matchKeyWord(data.content);

        setTimeout(function () {
            msgDisplay(content,'server','question');
        },10);
    }
    $('#bn-switch-panel').on('click','.bn-rule', function (e) {
        var target = $(e.currentTarget);
        
        var rule = target.data('rule');
        var id = target.data('id');

        if(rule) {
            switch_panel.find('.bn-sw-chld').hide();
            switch_panel.find('.bn-sw-panel-5').show();
            
            if(!$('#bn-dialog-panel ul').children().length) {
                $('#bn-dialog-panel ul').append('<li class="bug">1234</li>');
                setTimeout(function() {
                    $('#bn-dialog-panel .bug').remove();
                },30);
            }
           
            keywordRequest(1,rule,keywordFn);
        }
    });
    //对话面板中关键字请求
    dialog_panel.on('click','.bn-keyword', function (e) {
        var tag = $(e.target);

        keywordRequest(1,tag.text(),keywordFn);
    });

    

    //获取所有板块信息
    var panelListReqParams = {
        game_id: gameParams.game_id
    };
    function panelListFn(res) {

        var data = res.data;
        var navLists = $('.bn-nav-content .bn-nav');
        
        navLists.each(function (index, item) {
            
            $(item).data('nav-id', data[index].id);
        });

    }
    panelListReq(panelListReqParams, panelListFn);
    

    //反馈历史查询
    function freebackHistory(res) { 
        var data = res.data.data;
        var dialog_html = '',dialogs = data;

        if(!data.length) {
            bn_utils.tipModal('fail', '您还没有反馈信息！');
            return false;
        }
        for(var i = dialogs.length - 1; 0 <= i; i--) {
            if(dialogs[i].from == 'admin') {//管理员回复
                
                dialog_html += '<li class="bn-server list-item">'
                            +'<div class="bn-ser-content bn-dialog-cont" style="padding-bottom: .3rem;">'
                                    + dialogs[i].feedback_message
                                +'</div>'
                            +'<div class="bn-ser-icon bn-dialog-icon"></div>'
                        +'</li>';
            } else {
                dialog_html += ' <li class="bn-client list-item">'
                        +'<div class="bn-client-icon bn-dialog-icon"></div>'
                        +'<div class="bn-client-content bn-dialog-cont">'
                            + dialogs[i].feedback_message +'</div>'
                    +'</li>';
            }
        }
        freeback_panel.find('ul li').first().before(dialog_html);

        setTimeout(function() {//等待DOM渲染完成
            freebackScro.scrollTo(0,1,700);
        }, 200);
        freeback_history = true;//标记是否已加载载过反馈信息
    }
    question_panel.find('.bn-question-history').on('click', function(e) {
        var tag = $(this);
        var _data = question_panel.find('.bn-question-btn').data('bn-type');

        if(_data == 'freeback') {
            if(!freeback_history) {
                var params = {
                    game_id: gameParams.game_id,
                    to: gameParams.role_id,
                    limit: 50,
                    page: 1
                };
                feedbackReplyQueryReq(params, freebackHistory); 
            }
            
        }
        if(_data == 'question') {
            $('.bn-sw-chld').hide();
            dialog_panel.show();
            setTimeout(function() {
                dialogScro.scrollTo(0,1,500);
            },20);
        }
    });

    //关闭webview
    $('.bn-header .bn-h-close').on('click', function() {
        var clientOs = bn_utils.judgeClient();
        if(clientOs == 'iOS') {
            window.webkit.messageHandlers.iOS.postMessage({functionName:'dismiss'});
        }
        if(clientOs == 'Android') {
            android.closeWebView();
        }
       
    });
   
});