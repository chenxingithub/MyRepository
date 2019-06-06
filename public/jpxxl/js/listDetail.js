$(document).ready(function() {
    var articleId = GetQueryString('id') - 0;//url 取id
    var ImageInitWidth = '70%';

    //根据url文章ID 获取文章
    var mainNode = $('.main');
    function getText(res) {
        var textId = 123;//特殊文章id

        var data = res.data;
        var imgs = '',imgWidth='',imgNode='';
        mainNode.find('.textContent .title span').text(data.title);
        var timeValue = data.updated_at.split(' ');
        var monthValue = timeValue[1].split(':');
        var timeText = timeValue[0]+ ' ' +monthValue[0]+ ':' +monthValue[1]
        if(data.id === textId) {
            mainNode.find('.textContent #time').text('2018-01-07' + ' ' + +monthValue[0]+ ':' +monthValue[1]);
        } else {
            mainNode.find('.textContent #time').text(timeText);
        }
        // console.log(data);
        mainNode.find('.textContent .content').html(data.content);

        $('.textContent img').css('height','auto');//后台未设置图片大小则赋值初始width
        imgs = $('.content p img');
        $.each(imgs,function(index, item) {
            imgNode = $(item);
            imgWidth = imgNode.attr('width');
            if(!imgWidth) {
                $(item).attr('width', ImageInitWidth);
            }
        });
    }


   saveLocalClickStatus(articleId);//保存每篇文章点击状态
    var textParam = {
		url: urlPrefix+'/yh-cms-api/artisan-single/'+ articleId,
		type: 'get',
		data: {
			id: articleId
		}
    }
    // localStorage.setItem('status',JSON.stringify(status));
    // var localStorageValue = localStorage.getItem('status');
    // console.log(JSON.parse(localStorageValue));

    //根据id 发送请求文章
    send(textParam, getText);

    
    //下载链接，二维码图片 请求
    function homeBase(res) {
		var data = res.data[0];

		if(judgeClient() == 'iOS'){//下载链接
			$('.download').attr('href', data.ios_download_url);
			$('#erweima img').attr('src',imageUrl + data.ios_dow_code_img);
		} else {
			$('.download').attr('href', data.android_download_url);
			$('#erweima img').attr('src',imageUrl + data.android_dow_code_img);
		}

	}
	var homeParam = {
		url: urlPrefix+'/yh-cms-api/game-info',
		type: 'get',
		data: {
			idfa: 'jpxxl'
		}
    }
    //发送请求 下载链接，二维码图片
    send(homeParam, homeBase);


    //点赞
    var dianzan = $('.funPart .zan');
    var dianzanIcon = $('.funPart .zan i');
    var dianzanTxt = $('.funPart .zan span');

    function saveLocalClickStatus(id) {
        var isStatus = localStorage.hasOwnProperty('status'); // true
        var  localStorageValue;
        if(isStatus) {//判断文章点击状态是否存在本地，不在则添加
            localStorageValue= JSON.parse(localStorage.getItem('status'));
            
            var isExistId = localStorageValue.some(function(item, index) {
                return (item == id);//遍历是否存在该id
            });

            if(!isExistId) {//不存在该id
                localStorageValue.push(id);
                localStorage.setItem('status',JSON.stringify(localStorageValue));
            } else {

            }
        } else {
            var status = [id];
            localStorage.setItem('status',JSON.stringify(status));
        }
    }
    dianzan.on('click',function() {
        dianzanIcon.css('backgroundPosition', '-.4rem 0');
        var count = dianzanTxt.text() - 0 + 1;
        dianzanTxt.text(count);

    });

    $('.textContent img').css('height','auto');
    scanModal();//扫一扫 Modal
    shareModal();//分享 Modal
    pcNoneModal();//电脑版未开放提示 modal
    homePageBtnfunc();//官网按钮点击切换
    downloadBtnfunc();//顶部下载按钮切换
    BottomNavChange();//底部导航栏的图标切换
    //修改图片 后台设置高度
    
    var erweima = $('#erweima');
    var share = $('.share');
	share.on('click',function() {
        erweima.show();
    });
   

});