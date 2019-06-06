$(document).ready(function() {

    var articleId = GetQueryString('id') - 0;//url 取id
    var showChild = GetQueryString('child') - 0;//url 取id
    var resId,page=1,limit=10;
    var container = $('.container');

    //根据获取id显示标题 导航栏
    function showTitle(id) {
        var title = '',nav='';
        if(id == 4) { 
            title = '新闻资讯';
            nav = '<li class="listNavClick" data-id=4>最新</li>'+
            '<li data-id=11>新闻</li><li data-id=12>公告</li><li data-id=13>活动</li>';
            resId = 4;
        }
        if(id == 15) {
            title = '游戏资料';
            resId = 15;
            if(showChild == 17) {
                nav = '<li class="listNavClick" data-id=17>新手引导</li><li data-id=18>玩法活动</li>';
                resId = 17;
            }
            if(showChild == 18) {
                nav = '<li data-id=17>新手引导</li><li class="listNavClick" data-id=18>玩法活动</li>';
                resId = 18;
            }

        }
        container.find('.news-head').text(title);
        container.find('.news-nav ul').append(nav);
    }
    showTitle(articleId);

    //将请求数据添加到如ul中
    function totalList(res) {

        var totalData = res[resId].data[0];
        var data = res[resId].data[0].data;

        var text = '',updataTime = '';
        
        for(var i = 0; i < data.length; i++) {
            updataTime = data[i].updated_at.split(' ')[0].split('-');

            text += '<li><a href="./listDetail.html?id='+ data[i].id +'"><em>'+ data[i].title +
            '</em><span>'+ updataTime[1] +'-'+ updataTime[2] +'</span></a></li>';
        }
        container.find('.news-list ul').append(text);
        
        if((totalData.last_page - totalData.current_page) > 0) {
            container.find('.load .loadMore').show();
            container.find('.load .moreOver').hide();
        } else {
            container.find('.load .loadMore').hide();
            container.find('.load .moreOver').show();
        }
    }

    function param(id,page,limit) {
        var listParam = {
            url: urlPrefix+'/yh-cms-api/artisan-info',
            type: 'get',
            data: {
                artisan_type_id: id,
                terminal: 2,
                page: page,
                limit: limit
            }
        }
        return listParam;
    }
    //进入页面首次加载
    var listParam = param(resId,1,10);
	send(listParam, totalList);

   //页面内点击导航切换 
   function changeNav() {

   }

    //class[listNavClick] 点击后添加class改变样式
    var listNav = $('.news-nav');
    var listNavs = $('.news-nav ul li');
    listNav.on('click',function(event) {
        listNavs.forEach(function(item){
            $(item).removeClass('listNavClick');
        });
        var target = event.target;
        $(target).addClass('listNavClick');
        
        if($(target).data('id') != resId) {
            resId = $(target).data('id');
            page = 1;
            limit = 10;
            var listParam = param(resId,page,limit);
            send(listParam, totalList);

            container.find('.news-list ul').empty();
            // send(listParam, totalList);
        }
        // console.log($(target).data('id'));
    });

    //列表点击后效果
    var newsLisUl = $('.news-list ul');
    var newsLiss = $('.news-list ul');
    newsLisUl.on('click',function(event) {
        var target = event.target;
        $(target).parents('li').addClass('listClick');
    });

    var listLoad = $('.load');
    //根据请求状态显示
    var loadMore = listLoad.find('.loadMore');//点击加载显示更多
    var loading = listLoad.find('.loadMore');//正在加载更多
    var moreOver = listLoad.find('.moreOver');//已全部加载
    
    //点击加载更多
    function loadMoreFn() {
        page += 1;
        var listParam = param(resId,page,limit);
        send(listParam, totalList);
    }
    loadMore.on('click',loadMoreFn);


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
    }//首页基本信息请求发送
	send(homeParam, homeBase);

    scanModal();//扫一扫 Modal
    shareModal();//分享 Modal
    pcNoneModal();//电脑版未开放提示 modal
    homePageBtnfunc();//官网按钮点击切换
    downloadBtnfunc();//顶部下载按钮切换
    BottomNavChange();//底部导航栏的图标切换
});