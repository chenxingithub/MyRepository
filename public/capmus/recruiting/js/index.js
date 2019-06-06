$(function() {
    var typeIds = [33,34,35,36,37,38];

    $('.tab-con').on('mouseover','li',function() {
        $(this).find('span').addClass('bre-light');
        $(this).addClass('cur-tab');
    });

    $('.tab-con').on('mouseout','li',function() {
        $(this).find('span').removeClass('bre-light');
        $(this).removeClass('cur-tab');
    });

    $('.section').on('mouseenter','span',function(e) {
        $('.section span').removeClass('bre-light');
        $(this).addClass('bre-light');
        
        $('.t-c').fadeOut();
        var id = $(this).parent('li').data('id');
        $('.t-'+ id).slideDown();

    });

     //设置招聘类别
    function setListType(data) {
        var data = data.data;

        var html = '';
        for(var i = 0; i < data.length; i++) {
            html += '<li data-id="'+ (i+1) +'" class="tab-'+ (i+1) +'">'
                    +'<span></span>'
                    +'<em>'+ data[i].name +'</em>'
                +'</li>';
        }
        $('.w-tab .section').append(html);

    }

    function getParam() { //ajax 参数
        var classId = 32;
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/artisan-type-children/32',
            data: ''
        }
        return param;
    }

    send(getParam(),setListType);//ajax
    
    


    function setList(data) { //设置基本配置
        // var data = data.data[0];
        var html = '';
        for(var i = 0; i < typeIds.length; i++) {

            var lists = data[typeIds[i]].data[0].data;
            html += '<div><div class="t-'+ (i+1) +' t-c" '+  (i == 0 ? 'style="display:block;"' : '') +'>'
                    +'<ul class="t-con">';
            var text = '';
            for(var j = 0; j < lists.length; j++) {
                text += '<li><a target="_blank" href="./detail.html?id='+ lists[j].id  +'">'+ lists[j].title +'</a></li>';
            }

            html += text + '</ul></div></div>';
        }

        $('.tab-con').append(html);
        
    }
    function getListParam() { //ajax 参数
        var classId = 32;
        var typeIdLiset = typeIds.join(',');
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/artisan-info',
            data: {
                artisan_type_id: typeIdLiset,
                terminal: 1,
                page: 1,
                limit: 20
            }
        }
        return param;
    }
    send(getListParam(),setList);//ajax


    $('.sign-up-btn').on('click',function() {//显示简历信息框
        // $('#sub-info').fadeIn(1100);
        console.log('111');
        $('html,body').animate({scrollTop: 1100}, 800);
    });
    //
    // var jobInfo = null; // 简历信息
    // var formData = null;// form 信息
    // var uploading = false;//上传flag

    // function clearModal() {//初始化简历信息框数据

    //     $('.sub-form input').val('');
    //     $("#input-file")[0].files[0] = null;
    //     $('.file-name').text('选择文件');
    //     formData = '';
    // }

    // $('.sub-close').on('click',function() {

    //     $('#sub-info').fadeOut(function() {
    //         clearModal();
    //     });
    // });

    // $('#input-file').on('change', function() {//上传简历文件
    //     formData = new FormData();
    //     formData.append("resume_file",$("#input-file")[0].files[0]);

    //     $('.file-name').text($("#input-file")[0].files[0].name);

    // });


});
