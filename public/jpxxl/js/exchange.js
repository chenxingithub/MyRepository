$(document).ready(function() {
    
    var container = $('.container');
    
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

	scanModal();//扫一扫 Modal
    shareModal();//分享 Modal
    pcNoneModal();//电脑版未开放提示 modal
    homePageBtnfunc();//官网按钮点击切换
    downloadBtnfunc();//顶部下载按钮切换
    BottomNavChange();//底部导航栏的图标切换
	$($('.navBar li a')[3]).css('backgroundPositionY','-1.2795rem');//默认选中礼包领取
	
});