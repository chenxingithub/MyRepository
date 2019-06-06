$(document).ready(function() {
	var navTitle = ['最新','新闻','公告','活动'];//新闻资讯导航条title
	var videoSrc = '';
	var video_scource = 'https://player.youku.com/embed/XMzQ5NjY4MjAwOA==';

	function newsSlider() {//创建“新闻资讯”swiper对象
		var news_Swiper = new Swiper('.newsSlide-content',{
			// initialSlide: 1,
			pagination: '.news-pagination',
			paginationClickable: true,
			noSwiping : true,
			noSwipingClass : 'stop-swiping',
			pagiantionElement: 'div',
		
			paginationBulletRender: function(swiper, index, className) {
				return '<span class="' + className + '">' + navTitle[index] + '</span>';
			}
		});
	}

	function sliderFirst() { //创建 首图轮播swiper对象
		var mySwiper1 = new Swiper('.gameSlider1-content', {
			// autoplay: 5000,//可选选项，自动滑动
				loop: true,
				initialSlide: 0,
				pagination: '.game-pagination',
			});
	}

	function sliderSecond() {//创建“游戏特色”swiper对象
		var game_swiper = new Swiper('.game-content', {
			initialSlide: 2,
			paginationClickable: true,
			speed: 200,
			// loop: true,
			nextButton: '.button-next',//对应"下一题"按钮的class
			prevButton: '.button-prev',//对应"上一题"按钮的class
			effect : 'coverflow',
			slidesPerView: 2,
			centeredSlides: true,
			coverflow: {
				rotate: 30,
				stretch: 42,
				depth: 40,
				modifier: 1,
				slideShadows : true
			},
		});
	}

	function sliderThird() {//创建“实景截图”swiper对象
		var printScreen_swiper = new Swiper('.printScreen-content',{
			initialSlide: 1,
			pagination: '.print-pagination',
			effect : 'coverflow',
			nextButton: '.button-next',//对应"下一题"按钮的class
			prevButton: '.button-prev',//对应"上一题"按钮的class
			paginationClickable: true,
			slidesPerView: 2,
			centeredSlides: true,
			coverflow: {
				rotate: 0,
				stretch: 10,
				depth: 60,
				modifier: 2,
				slideShadows : false
			},
			pagiantionElement: 'div',
		});
	}
	scanModal();//扫一扫 Modal
    shareModal();//分享 Modal
	pcNoneModal();//电脑版未开放提示 modal
	downloadBtnfunc();//顶部下载按钮切换
    BottomNavChange();//底部导航栏的图标切换

	//video
	var videoPlayBtn = $('.videoPlayBtn');
	var videoModal = $('#videoModal');
	var video_cover = $('#videoModal .modal_cover');
	var notVideoSrc = $('#notVideoSrc');
	var videoClose = $('#videoModal .videoClose');

	videoPlayBtn.on('click',function() {
		videoModal.show();
		var video_html = '<iframe id="videoFrame" src="'+ video_scource +'" frameborder=0 "allowfullscreen"></iframe>';
		videoModal.find('.modal_frame').append(video_html);
	});
	video_cover.on('click',videoCloseFun);
	videoClose.on('click',videoCloseFun);
	function videoCloseFun() {
		
		videoModal.hide();
		$('#videoFrame').remove();
	}

	

	//游戏图标切换
	var infoHeadImgs = $('#gameInfo .infoHeadImg');
	var infoContent = $('#gameInfo .info-content');
	infoContent.on('click',function(event) {
		var target = event.target;
		if(infoHeadImgs[0] == target) {
			$(target).css('backgroundPosition','-1.839rem 0');
		}
		if(infoHeadImgs[1] == target) {
			$(target).css('backgroundPosition','-1.839rem -2.2rem');
		}
		if(infoHeadImgs[2] == target) {
			$(target).css('backgroundPosition','-1.839rem -4.3rem');
		}
	});
	
	//下载图标切换  新闻资讯更多图片
	var downloadBtn1 = $('#downloadBtn1');
	var downloadBtn2 = $('#downloadBtn2');
	var newsMoreBtn = $('#newsMoreBtn');
	downloadBtn1.on('click',function() {
		$(this).css('backgroundPosition','-1.72rem 0');
	});
	downloadBtn2.on('click',function() {
		$(this).css('backgroundPosition','-4.73rem 0');
	});
	newsMoreBtn.on('click',function() {
		$(this).css('backgroundPosition','0 -1.23rem');
	});

	 //列表点击后效果
	 var newsLisUl = $('.newsInformation .txt-list');
	 newsLisUl.on('click',function(event) {
		 var target = event.target;
		 $(target).parents('a').addClass('newsaVisited');
		// console.log(target);
	 });
	
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
		container.find('#videoModal #media source').attr('src',data.video_url);;
	}
	var homeParam = {
		url: urlPrefix+'/yh-cms-api/game-info',
		type: 'get',
		data: {
			idfa: 'jpxxl'
		}
	}//首页基本信息请求发送
	send(homeParam, homeBase);

	
	//首页轮播图片请求加载
	/**
	 * 
	 * @param {Object} imageData 返回的数据
	 * @param {Node} domNode 要插入的轮播图容器节点
	 */
	function sliderImg(imageData,domNode) {
		var text = '';
		if(imageData) {
			if(imageData[0].jump_url) {
				$.each(imageData, function(index, item) {
					text += '<div class="swiper-slide"><a href=" '+ item.jump_url +
					'"><img src="'+ imageUrl + item.picture +'" alt="gameDetial"></a></div>';
				});
			} else {
				$.each(imageData, function(index, item) {
					text += '<div class="swiper-slide"><img src="'+ imageUrl + item.picture +'" alt="gameDetial"></div>';
				});
			}
		}
		domNode.append(text);//插入到轮播图容器中
	}
	function reqImage(res) {
		var data = res.data;
		var content = $('.content');
		//slider1
		var slider1 = content.find('#slider1 .swiper-wrapper');
		// slider1.children('.swiper-slide').remove();
		sliderImg(data[1],slider1);
		sliderFirst();//获取添加轮播图后 创建swiper对象
		//游戏特色
		var slider2 = content.find('#gameFeature .swiper-wrapper');
		sliderImg(data[2],slider2);
		sliderSecond();//获取添加轮播图后 创建swiper对象
		//实景截图
		var slider3 = content.find('#printScreen .swiper-wrapper');
		sliderImg(data[3],slider3);
		sliderThird();//获取添加轮播图后 创建swiper对象
	}
	var imageParam = {
		url: urlPrefix+'/yh-cms-api/focus',
		type: 'get',
		data: {
			game_id: 1,
			position: '1,2,3',
			terminal: 2,
		}
	}
	//请求轮播图图片
	send(imageParam, reqImage);


	//新闻资讯
	function newsFn(res) {
		var dataArr = [];
		$.each(res,function(index, item) {//将得到的数据排序
			if(item.name[0] == '新闻资讯') {
				dataArr[0] = item.data[0].data;
			}
			if(item.name[0] == '新闻') {
				dataArr[1] = item.data[0].data;
			}
			if(item.name[0] == '公告') {
				dataArr[2] = item.data[0].data;
			}
			if(item.name[0] == '活动') {
				dataArr[3] = item.data[0].data;
			}	
		});
		var text='',textChild='',flag=0,updataTime;
		var textId = 123;//特殊文章id
		$.each(dataArr, function(index, item) {
			textChild = '';
			flag = index;
			$.each(item, function(index, item) {
				updataTime = item.updated_at.split(' ')[0].split('-');
				if(item.id === textId) {
					textChild += '<li><a href="./listDetail.html?id='+ item.id +'"><em>【'+ navTitle[flag] + '】' + item.title +
					'</em><span>'+ '01' + '-' + '07' +'</span></a></li>';
				} else {
					textChild += '<li><a href="./listDetail.html?id='+ item.id +'"><em>【'+ navTitle[flag] + '】' + item.title +
					'</em><span>'+ updataTime[1] + '-' + updataTime[2] +'</span></a></li>';
				}
			});
			text += '<div class="swiper-slide stop-swiping"><ul class="txt-list">'+ textChild +'</ul></div>'
		});
		container.find('#newsContentSlide .swiper-wrapper').append(text);
		newsSlider();//获取添加新闻资讯列表后 创建swiper对象
	}
	var newsParam = {
		url: urlPrefix+'/yh-cms-api/artisan-info',
		type: 'get',
		data: {
			artisan_type_id: '4,11,12,13',
			terminal: 2,
			page: 1,
			limit: 4
		}
	}
	send(newsParam, newsFn);
	
	//游戏资料
	function tableSpell(row,line, tbData) {//游戏资料table拼接
		var newsTable='',newsTd='',flag=0;
		for(var i=0; i < row; i++) {
			newsTd = '';
			for(var j=0; j < line; j++) {
				newsTd += '<td><a href="./listDetail.html?id='+ tbData[flag].id +
				'"><span>'+ tbData[flag].subtitled +'</span></a></td>';
				flag++;
			}
			newsTable += '<tr>'+ newsTd +'</tr>'
		}
		return newsTable;
	}
	function gemeInfo(res) {//游戏资料插入table数据
		var newsInstruction = res['22'].data[0].data;
		var aplayActive = res['23'].data[0].data;

		var newsRow = 2,newsLine = 3;
		var aplayRow = 3, aplayLine = 3;
		var gameInfo = $('#gameInfo');
		
		var InstructionTxt = tableSpell(newsRow,newsLine,newsInstruction);
		gameInfo.find('.infoLine2 table').append(InstructionTxt);
		var activeTxt = tableSpell(aplayRow,aplayLine,aplayActive);
		gameInfo.find('.infoLine3 table').append(activeTxt);
	}
	var gameParam = {
		url: urlPrefix+'/yh-cms-api/artisan-info',
		type: 'get',
		data: {
			artisan_type_id: '22,23',
			terminal: 2,
			page: 1,
			limit: 9
		}
	}
	send(gameParam, gemeInfo);

});