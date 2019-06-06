$(document).ready(function() {
    var articleId = GetQueryString('id') - 0;//url 取id
    var resId=articleId,page=1,limit=12;
    var container = $('.container');
     

    function totalList(res) {
        var totalData = res[resId].data[0];
        var data = res[resId].data[0].data;
        var text = '';
        if(resId == 20) {
            for(var i = 0; i < data.length; i++) {
                text += '<li><a href="./girlDetail.html?id='+ data[i].id +'"><img src="'+ iconUrl + data[i].icon +'" '+
                'alt="character"><span>'+ data[i].subtitled +'</span></a></li>';
            }
        } else {
            for(var i = 0; i < data.length; i++) {
                text += '<li><a href="./boyDetail.html?id='+ data[i].id +'"><img src="'+ iconUrl + data[i].icon +'" '+
                'alt="character"><span>'+ data[i].subtitled +'</span></a></li>';
            }
        }
        // console.log(res[resId].data[0].data);
      
        container.find('.personImg ul').append(text);

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
    var listParam = param(resId,1,12);
	send(listParam, totalList);   


	//首页基本信息请求
	function homeBase(res) {
		var data = res.data[0];
		// console.log(data);
		if(judgeClient() == 'iOS'){//下载链接
			container.find('.download').attr('href', data.ios_download_url);
            $('#erweima img').attr('src',imageUrl + data.ios_dow_code_img);
		} else {
			container.find('.download').attr('href', data.android_download_url);
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
   
    //点击nav 
    var nav = $('.content .nav');
    var girlNav = nav.find('.girlNav');
    var boyNav = nav.find('.boyNav');
    var girlNavi = nav.find('.girlNav i');
    var boyNavi = nav.find('.boyNav i');
    if(resId == 20) {  girlNavi.show();boyNavi.hide(); }
    if(resId == 21) {  boyNavi.show();girlNavi.hide(); }
    function clickGirl(event) {
        var event = $(event.target);
        var id = event.parents('li').data('id');
        if(id) {
            if(id != resId) {
                resId = id;//切换时初始化数据，请求列表数据
                page = 1,limit = 12;
                var listParam = param(resId,page,limit);
                send(listParam, totalList);
                container.find('.personImg ul').empty();
            }
            girlNavi.show();
            boyNavi.hide();
        }

    }
    function clickBoy(event) {
        var event = $(event.target);
        var id = event.parents('li').data('id');
        if(id) {
            if(id != resId) {
                resId = id;//切换时初始化数据，请求列表数据
                page = 1,limit = 12;
                var listParam = param(resId,page,limit);
                send(listParam, totalList);
                container.find('.personImg ul').empty();
            }
            boyNavi.show();
            girlNavi.hide();
        }
    }

    girlNav.on('click',clickGirl);//点击时请求红颜数据
    boyNav.on('click',clickBoy);//点击时请求门客数据

    var listLoad = $('.load');
    //根据请求状态显示
    var loadMore = listLoad.find('.loadMore');//点击加载显示更多
    var loading = listLoad.find('.loadMore');//正在加载更多
    var moreOver = listLoad.find('.moreOver');//已全部加载
    
    function loadMoreFn() {
        page += 1;
        var listParam = param(resId,page,limit);
        send(listParam, totalList);
    }
    loadMore.on('click',loadMoreFn);

    scanModal();//扫一扫 Modal
    shareModal();//分享 Modal
    pcNoneModal();//电脑版未开放提示 modal
    homePageBtnfunc();//官网按钮点击切换
    downloadBtnfunc();//顶部下载按钮切换
    BottomNavChange();//底部导航栏的图标切换
});