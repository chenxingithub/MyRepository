$(function() {

    var navInfo = {
        id: 25,
        page: 1,
        limit: 15,
        last_page: 2,
        per_page: 15,
        total: 7,
        showMaxPageBtn: 4,
        idMapToNav: {
            '25': '最新',
            '26': '新闻',
            '27': '公告',
            '28': '活动',
            '29': '攻略',
            '30': '新闻',
            '31': '攻略',
        },
        getNav: function() {
            return this.idMapToNav[this.id];
        },
    };
    
    function getUrlId() {
        if(GetQueryString('id')) {
            navInfo.id = GetQueryString('id');
        } 
    }
    function navShowText() {
        $('#navShowText').text(navInfo.getNav());
    }
    function initNav() {
        var type1 = $('#type1');
        var navs = type1.find('a');
        type1.find('.curr').removeClass('curr');
        for(var i = 0; i < navs.length; i++) {
            if($(navs[i]).data('id') === navInfo.id) {
                $(navs[i]).addClass('curr');
                return false;
            }
        }
    }
    getUrlId();
    navShowText();
    initNav();

    //基本配置
    function setBaseInfo(data) { //设置基本配置
        var data = data.data[0];
        var downloadbox_m = $('.downloadbox_m');

        var iosUrl = data.ios_download_url ? data.ios_download_url : "JavaScript:alert('暂无ios版');";
        var androidUrl = data.android_download_url ? data.android_download_url : "JavaScript:alert('暂无android版');";

        downloadbox_m.find('img').attr('src', imgDomain + data.android_dow_code_img);
        downloadbox_m.find('.ios_btn').attr('href', iosUrl);
        downloadbox_m.find('.android_btn').attr('href', androidUrl);

        $('.logo').attr('src', imgDomain + data.logo_url);
    }
    function getParam() { //ajax 参数
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/game-info',
            data: {
                idfa: 'dzkm',
            }
        }
        return param;
    }
    send(getParam(),setBaseInfo);//ajax


    //导航栏切换
    function getList(id,page,limit) {//ajax 参数
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/artisan-info',
            data: {
                artisan_type_id: id,
                terminal: 1,
                page: page,
                limit: limit,
            },
        }
        return param;
    }
    function initPagination() {//首次加载时初始化分页器
        var pagination = $('#pagination');
        
        if(navInfo.total < navInfo.limit) {//总条数小于一页总条数
            pagination.hide();
            return false;
        }
        if(navInfo.page < navInfo.last_page) {
            var pageBtn = '';
            var page = (navInfo.last_page < navInfo.showMaxPageBtn ? navInfo.last_page : navInfo.showMaxPageBtn);
            pagination.show();
            if(!pagination.find('.pageBtn').length) {
                for(var i = 1; i <= page; i++) {
                    pageBtn += '<a href="javascript:;"class="pageBtn '+ (i == 1 ? 'current' : '') +'" data-page="'+ i +'">'+ i +'</a>'
                }
                pagination.find('.previous').after(pageBtn);
            }
        }
        if(navInfo.page == 1) {
            pagination.find('.toFirst').addClass('disable');
            pagination.find('.previous').addClass('disable');
            return false;
        }
        if(navInfo.page == navInfo.last_page) {
            pagination.find('.toLast').addClass('disable');
            pagination.find('.next').addClass('disable');
        }
    }
    function setList(data) {//生成列表
        var listInfo = data[navInfo.id].data[0];
        var data = listInfo.data;
        var text = '';

        navInfo.last_page =  listInfo.last_page;
        navInfo.per_page = listInfo.per_page - 0;
        navInfo.total =  listInfo.total;
        initPagination();//分页初始化
 
        for(var i = 0; i < data.length; i++) {
            text += '<li><span>'+ formatTime(data[i].updated_at) +'</span><em>['+ navInfo.idMapToNav[data[i].artisan_type_id] +']</em><a href="./article.html?id='+
            data[i].id +'" target="_blank">'+ data[i].title +'</a></li>';
        }
        // log(text);
        $('.newslist ul').children().remove();
        $('.newslist ul').append(text);
    }
    function selectedNav() {
        var param = getList(navInfo.id, navInfo.page, navInfo.limit);
        send(param,setList);//ajax
    }
    selectedNav();// 初始加载

    $('#type1').on('click',function(e) {
        var targetNode = $(e.target);

        if(targetNode.hasClass('curr')) { return false; }//点击的是当前选中元素
        navInfo.page = 1;
        initPagination();
        $(this).find('.curr').removeClass('curr');
        targetNode.addClass('curr');
        $('.newslist ul').children().remove();
        navInfo.id = targetNode.data('id');
        navShowText();
        selectedNav();

    });

    //点击分页器触发事件 
        //默认显示四页，不足时按实际显示
    $('#pagination').on('click',function(e) {
        var showMaxPage = 4;
        //navInfo.page  当前页码
        // navInfo.last_page 最后一页页码
        var pagination = $('#pagination');
        var targetNode = $(e.target);

        if(targetNode.hasClass('current')) {//判断点击的是否为当前页数
            return false;
        } else if (targetNode.hasClass('pageBtn')) {
            
            pagination.find('.current').removeClass('current');
            targetNode.addClass('current');
            navInfo.page =  targetNode.data('page');

        }

        if(targetNode.hasClass('toFirst')) {//回到首页
            // log('page='+navInfo.page);
            if(navInfo.page == 1) {
                return false;
            } else {
                pagination.find('.current').removeClass('current');
                $(pagination.find('.pageBtn')[0]).addClass('current');
                // if(pagination.find('.pageBtn:first').data()) {}
                $.each(pagination.find('.pageBtn'),function (index, item) {
                    var item = $(item);
                    var page = index + 1;
                    item.data('page', page);
                    item.text(page);
                });
                navInfo.page = 1;
            }
        }
        if(targetNode.hasClass('previous')) {//上一页
            // log('page='+navInfo.page);
            if(navInfo.page == 1) {
                return false;
            } else {
                var current = pagination.find('.current');
                var prevNode = current.prev('.pageBtn');
                if(prevNode.length) {//不为最后一个按钮
                    current.removeClass('current');
                    prevNode.addClass('current');
                    navInfo.page -= 1;

                } else {
                    //当前选中的为最后一个按钮 更新每个按钮页码
                    $.each(pagination.find('.pageBtn'),function (index, item) {
                        var item = $(item);
                        var page = item.data('page') - 1;
                        item.data('page', page);
                        item.text(page);
                    });
                    navInfo.page -= 1;
                    // log(navInfo.page);
                }
            }
        }
        if(targetNode.hasClass('next')) {//下一页
            // log('page='+navInfo.page);
            if(navInfo.page >= navInfo.last_page) {
                return false;
            } else {
                var current = pagination.find('.current');
                var nextNode = current.next('.pageBtn');
                if(nextNode.length) {
                    current.removeClass('current');
                    nextNode.addClass('current');
                    navInfo.page += 1;
                    //ajax 
                } else {
                    $.each(pagination.find('.pageBtn'),function (index, item) {
                        var item = $(item);
                        var page = item.data('page') + 1;
                        item.data('page', page);
                        item.text(page);
                    });
                    navInfo.page += 1;
                }
            }
        }
        if(targetNode.hasClass('toLast')) {//到尾页
            // log('page='+navInfo.page);
            if(navInfo.page == navInfo.last_page) {
                return false;
            } else {
                var current = pagination.find('.current');
                var nextNode = current.next('.pageBtn');
            
                // navInfo.page == navInfo.last_page;
                // pagination.find('.toFirst').addClass('disable');
                // pagination.find('.previous').addClass('disable');
                //ajax 请求 ==========================
                pagination.find('.current').removeClass('current');
                $(pagination.find('.pageBtn:last')).addClass('current');
                $.each(pagination.find('.pageBtn'),function (index, item) {
                    var item = $(item);
                    var btnMount = pagination.find('.pageBtn').length;
                    var page = navInfo.last_page - btnMount + index + 1;
                    item.data('page', page);
                    item.text(page);
                });
                navInfo.page = navInfo.last_page;
            }
        }

        // log(navInfo.page);

        selectedNav();//分页器改变page结束后根据navInfo.page发送ajax

        //根据 navInfo.page 判断分页器按钮可点击的状态
        if(navInfo.page == 1) {
            pagination.find('.toFirst').addClass('disable');
            pagination.find('.previous').addClass('disable');

            pagination.find('.toLast').removeClass('disable');
            pagination.find('.next').removeClass('disable');
        } else if(navInfo.page == navInfo.last_page) {
            pagination.find('.toFirst').removeClass('disable');
            pagination.find('.previous').removeClass('disable');

            pagination.find('.toLast').addClass('disable');
            pagination.find('.next').addClass('disable');
        } else {
            pagination.find('.toFirst').removeClass('disable');
            pagination.find('.previous').removeClass('disable');

            pagination.find('.toLast').removeClass('disable');
            pagination.find('.next').removeClass('disable');
        }


    });

});