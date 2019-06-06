$(document).ready(function() {

    homePageBtnfunc();//官网按钮点击切换
    downloadBtnfunc();//顶部下载按钮切换
    BottomNavChange();//底部导航栏的图标切换
    $($('.navBar li a')[4]).css('backgroundPositionY','-1.2795rem');//默认选中礼包领取
    

    //首页基本信息
	function homeBase(res) {

        var data = res.data[0];
        
        var text = '',service_qq='',service_qq_group='',service_phone='';

        if(data.service_qq) {
            service_qq = data.service_qq.split(',');
            $.each(service_qq, function(index, item) {
                text += '<div class="qq"><i></i><span>'+ item +'</span></div>';
            })
        }
        // if(data.service_qq_group) {
        //     service_qq_group = data.service_qq_group.split(',');
        //     $.each(service_qq_group, function(index, item) {
        //         text += '<div class="qq_qun"><i></i><span>'+ item +'</span></div>';
        //     })
        // }
        if(data.service_phone) {
            service_phone = data.service_phone.split(',');
            $.each(service_phone, function(index, item) {
                text += '<div class="tel"><i></i><span>'+ item +'</span></div>';
            })
        }
        // 客服
        $('.main .content').append(text);

        if(judgeClient() == 'iOS'){//下载链接
			$('.download').attr('href', data.ios_download_url);
		} else {
			$('.download').attr('href', data.android_download_url);
		}

    }
    
	var homeParam = {
		url: urlPrefix+'/yh-cms-api/game-info',
		type: 'get',
		data: {
			idfa: 'jpxxl'
		}
	}
	send(homeParam, homeBase);

});