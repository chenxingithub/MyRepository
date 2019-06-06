$(document).ready(function() {
	 
	var articleId = GetQueryString('id') - 0;//url 取id
	var main = $('.main');
	var container = $('.container');

	//根据url文章ID 获取文章
    var mainNode = $('.main');
    function getText(res) {
		var data = res.data;

		main.find('.bg').attr('src', imageUrl+data.bg_img);
		main.find('.detailText').append(data.content);
		// console.log(data);
	}

    var textParam = {
		url: urlPrefix+'/yh-cms-api/artisan-single/'+ articleId,
		type: 'get',
		data: { id: articleId }
    }
   
    //根据id 发送请求文章
    send(textParam, getText);

	BottomNavChange();//底部导航栏的图标切换
	homePageBtnfunc();//官网按钮点击切换
	downloadBtnfunc();//顶部下载按钮切换

	
	function flexScreen() {
		var sreenHeight = document.body.clientHeight;
		var htmlFontSize = parseInt($('html').css('fontSize'),10);
		var boxNode = container.find('.content');//外部盒子节点
		var detailTextNode = container.find('.detailText');//内容节点
		var navBarHeight = parseInt(container.find('.navBar').css('height'),10);//导航条高度 48px
		var bottomIconHeight = parseInt(container.find('#bottomIcon').css('height'),10);
		var detailTextHeightY = container.find('.detailText')[0].getBoundingClientRect().top;
		var content = container.find('.content');
		var contentHeight = content.css('height');

		detailTextHeight = (sreenHeight - navBarHeight - bottomIconHeight - detailTextHeightY - 8)/htmlFontSize;
		detailTextNode.css('height',detailTextHeight + 'rem');

	}
	flexScreen();

	$(window).resize(function() {	
		flexScreen();
	});

});