$(document).ready(function() {

    var articleId = GetQueryString('id') - 0;//url 取id
    var main = $('.main');

	//根据url文章ID 获取文章
    var mainNode = $('.main');
    function getText(res) {
		var data = res.data;

        main.find('.bg').attr('src', imageUrl+data.bg_img);
        main.find('.describe p').text(data.sketch);
		main.find('.txt').append(data.content);
		// console.log(data.content);
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
    
});