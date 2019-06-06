$(document).ready(function() {
    var isShowGiftAll = false;// 标记《所有礼包》板块是否显示

    //扫一扫
	var attentionWX = $('#attentionWX');
	var erweima = $('#erweima');
	attentionWX.on('click',function() {
		erweima.show();
	});
	erweima.on('click',function() {
		erweima.hide();
	});
	//分享
	var shareWX = $('#shareWX');
	var shareModal = $('#shareModal');
	shareWX.on('click',function() {
		shareModal.show();
	});
	shareModal.on('click',function() {
		shareModal.hide()
    });
    //电脑版未开放提示 modal
	var pcTipModal = $('#pcTipModal');
	var versionPc = $('#versionPc');
	versionPc.on('click',function() {
		pcTipModal.show();
	});
	pcTipModal.on('click',function() {
		pcTipModal.hide();
    });
    
     //底部导航栏的图标切换
	var navs = $('.navBar .navIcon');
    var ulFather = $('.navBar ul');
    var links = ulFather.find('li a');
	ulFather.on('click',function(event) {
		var target = event.target;
		navs.forEach(function(element) {
			var backgroundPositionY = $(element).css('backgroundPositionY');
			backgroundPositionY == '-.1733rem' ? '' : $(element).css('backgroundPositionY','-.1733rem');
		}, this);
		$(target).css('backgroundPositionY','-1.2795rem');
    });
    $(links[3]).css('backgroundPositionY','-1.2795rem');

    //中间导航栏图标切换 内容切换
    var contentNav = $('.main .nav');
    var contentNavs = contentNav.find('div');

    var mainNode = $('.main');
    var giftRecord = mainNode.find('.giftRecord');
    var giftAll = mainNode.find('.giftAll');
    var exchange = mainNode.find('.exchange');
    var sectionArr = [giftRecord,giftAll,exchange];
    contentNav.on('click',function(event) {
        var target = event.target;
        sectionArr.forEach(function(item) {
            item.hide();
        });
        if(target == contentNavs[0]){
            $(this).css("backgroundPosition","0 0");
            sectionArr[0].show();
            isShowGiftAll = false;
        }
        if(target == contentNavs[1]){
            $(this).css("backgroundPositionY","-1.19rem");
            sectionArr[1].show();
            isShowGiftAll = true;
        }
        if(target == contentNavs[2]){
            $(this).css("backgroundPositionY","-2.38rem");
            sectionArr[2].show();
            isShowGiftAll = false;
        }
    });
   

    //所有礼包-》 签到、礼包点击切换
    var giftAll = $('.giftAll');
    var giftLink1 = giftAll.find('.giftLink1');
    var giftLink2 = giftAll.find('.giftLink2');
    giftLink1.on('click',function() { 
        giftLink1.css({'backgroundPositionY':'-2.53rem','color':'#8f0000'});
        pcTipModal.show();
    });
    giftLink2.on('click',function() {
        giftLink2.css({'backgroundPositionY':'-3.8rem','color':'#8f0000'});
        pcTipModal.show();
    });
    //  所有礼包-》 点击查看礼包码
    $('.giftList').on('click','.checkGift',function() {
            contentNav.css("backgroundPosition","0 0");
            sectionArr[0].show();
            sectionArr[1].hide();
            isShowGiftAll = false;
    });
    

    //顶部按钮点击样式变化
    var homePageBtn = $('#topBtn .homePageBtn');
    var downloadBtn1 = $('#topBtn .gemeDownloadBtn');
    homePageBtn.on('click',function() {
		$(this).css('backgroundPosition','-1.72rem 0');
	});
    downloadBtn1.on('click',function() {
		$(this).css('backgroundPosition','-1.72rem 0');
    });

    //获取滚动条当前的位置 
    function getScrollTop() { 
        var scrollTop = 0; 
        if (document.documentElement && document.documentElement.scrollTop) { 
        scrollTop = document.documentElement.scrollTop; 
        } 
        else if (document.body) { 
        scrollTop = document.body.scrollTop; 
        } 
        return scrollTop; 
    } 
    
    //获取当前可视范围的高度 
    function getClientHeight() { 
        var clientHeight = 0; 
        if (document.body.clientHeight && document.documentElement.clientHeight) { 
            clientHeight = Math.min(document.body.clientHeight, document.documentElement.clientHeight); 
        } 
        else { 
            clientHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight); 
        } 
            return clientHeight; 
    } 
    
    //获取文档完整的高度 
    function getScrollHeight() { 
        return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight); 
    }
    var i = 0;//临时变量（可删除）
    var listLoad = $('.load');
    //根据请求状态显示
    var loadMore = listLoad.find('.loadMore');//加载显示更多
    var loading = listLoad.find('.loadMore');//正在加载更多
    var moreOver = listLoad.find('.moreOver');//已全部加载
    
    $(document.body).scroll(function() {//监听页面滚动事件
        if(isShowGiftAll) {//判断是否显示的为《所有礼包》
            if (getScrollTop() + getClientHeight() == getScrollHeight()) { 
                /**
                 * ajax请求
                 * 
                 * */
                i++;
                var nodeLi = '<li><div class="focusGift"><em>公众号关注礼包</em><p>兵符*100 银票*300 论语 春秋 孟子 三字经 孙子兵法</p><div class="bili_bar"><div class="fill_bar"></div></div><span>剩余 ：1600</span></div><div class="checkGift"><span>查看礼包码</span></div></li>';
                var nodeLis = nodeLi + nodeLi + nodeLi + nodeLi + nodeLi;
                $('.giftAll .giftList').append(nodeLis);
                console.log("到底了！！！！");    
            }
        }
    });
});