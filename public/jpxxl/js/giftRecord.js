$(document).ready(function() {
    var dataName = 'records';//存储名
    var main = $('.main');
    var recordList = main.find('.recordList');

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



    //判断时间是领取记录时间是否过期
    function isOverTime(overTime, nowTime) {
        
        overTime = overTime.split(' ')[0].split('-');
        nowTime = [nowTime.getFullYear(),nowTime.getMonth()+1,nowTime.getDate()];
       
        if(overTime[0] - nowTime[0] < 0) {
            return false;
        } else if(overTime[0] - nowTime[0] > 0) {
            return true;
        } else {

            if(overTime[1] - nowTime[1] < 0) {
                return false;
            } else if (overTime[1] - nowTime[1] > 0) {
                return true;
            } else {

                if(overTime[2] - nowTime[2] >= 1) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
    
    //取cookie “领取记录”遍历展示
    function recordDis(DomNode) {
        var data = getCookie(dataName);
        var time = new Date();
        var text = '';
        if(data) {
            var limitTime = [];
            for(var i = 0; i < data.length; i++) {
                var overTime = data[i].overTime;
                if(data[i].code) {
                    if(isOverTime(overTime, time)) {
                        limitTime = overTime.split(' ')[0].split('-');

                        text += '<li><p class="recordTitle"><i></i><em> '+ data[i].title +'</em></p>'+
                        '<p class="recordTxt">'+ data[i].describe +' </p>'+
                        '<p class="recordTime">礼包有期限：<a href="#">'+ limitTime[0] + '-' + limitTime[1] + '-' + limitTime[2] +'</a></p>'+
                        '<div class="giftCode"><span>请长按右边礼包码复制 >></span>'+
                        '<div class="code"><span>'+ data[i].code +'</span></div></di</li>';
                    } else {
                        limitTime = overTime.split(' ')[0].split('-');

                        text += '<li><p class="recordTitle"><i></i><em> '+ data[i].title +'</em></p>'+
                        '<p class="recordTxt">'+ data[i].describe +' </p>'+
                        '<p class="recordTime">礼包有期限：<a href="#" class="overTimeClo">'+ limitTime[0] + '-' + limitTime[1] + '-' + limitTime[2] +'</a></p>'+
                        '<div class="giftCode"><span>请长按右边礼包码复制 >></span>'+
                        '<div class="code"><span>'+ data[i].code +'</span></div></di</li>';
                        // year = new Date(overTime).getFullYear();
                    }
                }
            }
            if(!text) {
                text='<li style="text-align:center;color:#AD7D11;font-size: .4rem;">暂无领取记录</li>';
            }
        } else {
            text='<li style="text-align:center;color:#AD7D11;font-size: .4rem;">暂无领取记录</li>';
        }
       
        DomNode.append(text);

    }
    recordDis(recordList);

    scanModal();//扫一扫 Modal
    shareModal();//分享 Modal
    pcNoneModal();//电脑版未开放提示 modal
    homePageBtnfunc();//官网按钮点击切换
    downloadBtnfunc();//顶部下载按钮切换
    BottomNavChange();//底部导航栏的图标切换
    $($('.navBar li a')[3]).css('backgroundPositionY','-1.2795rem');//默认选中礼包领取

});