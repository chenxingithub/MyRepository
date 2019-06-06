$(function() {
    var txtId = GetQueryString('id');
    var jobInfo = null; // 简历信息
    var formData = null;// form 信息
    var uploading = false;//上传flag

    function setTxt(data) {
        
        var data = data.data;
        var content = data.content;
        $('.detial-c').html(content);

        jobInfo = data;

    }
    function getTxtParam() { //ajax 参数
        var textId = txtId;
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/artisan-single/'+textId,
            data: {
                artisan_type_id: '33,34,35,36,37,38',
                terminal: 1,
                page: 1,
                limit: 20
            }
        }
        return param;
    }
    send(getTxtParam(),setTxt);//请求职位信息



    $('.sec-btn').on('click',function() {//显示简历信息框
        $('#sub-info').slideDown(1200);
    });


    function clearModal() {//初始化简历信息框数据

        $('.sub-form input').val('');
        $("#input-file")[0].files[0] = null;
        $('.file-name').text('选择文件');
        formData = '';
    } 

    $('.sub-close').on('click',function() {

        $('#sub-info').fadeOut(function() {
            clearModal();
        });

        
    });


    $('#input-file').on('change', function() {//上传简历文件
        formData = new FormData();
        formData.append("resume_file",$("#input-file")[0].files[0]);

        $('.file-name').text($("#input-file")[0].files[0].name);

    });


    $('.sub-btn').on('click',function() {//提交简历及信息
        var sub_form = $('.sub-form');
        var uploadForm = $('#uploadForm');

        inputs = sub_form.find('input');
     
        if(!inputs[0].value.trim()) {
            util.tipModal('fail', '姓名不能为空哟！');
            return false;
        }
        if(!inputs[1].value.trim()) {
            util.tipModal('fail', '专业不能为空哟！');
            return false;
        }
        if(!inputs[2].value.trim()) {
            util.tipModal('fail', '学校不能为空哟！');
            return false;
        }
        if(inputs[3].value.trim().length < 11) {
            util.tipModal('fail', '请输入正确的手机号码！');
            return false;
        }
        if(!inputs[4].value.trim()) {
            util.tipModal('fail', '信息来源不能为空哟！');
            return false;
        }
        if(!formData) {
            util.tipModal('fail', '您忘记上传简历了呢！');
            return false;
        }
        formData.append("position_name",jobInfo.title);
        formData.append("position_type_id",jobInfo.id);
        formData.append("name",inputs[0].value);
        formData.append("major",inputs[1].value);
        formData.append("college",inputs[2].value);
        formData.append("phone",inputs[3].value);
        formData.append("msg_source",inputs[4].value);

        $.ajax({
            url: urlPrefix + "yh-cms-api/resume",
            type: 'POST',
            cache: false,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function(){
                uploading = true;
            },
            success : function(data) {

                util.loadingEnd();
                $('#sub-info').fadeOut(function() {
                    clearModal();
                });
                util.tipModal('success',data.message);
                uploading = false;
            },
            error: function(data) {

                util.loadingEnd();

                if(data.status == 205) {
                    util.tipModal('fail','此岗位您已提交过了，请勿重复提交！');
                    return false;
                }
                
                util.tipModal('fail',data.message);
            }
        });

        util.loadingStart();

    });


    function coverAdapt() {//简历信息框遮罩层适配
        var height = $('body').height();
        var width = $('body').width();
        $('#sub-info').height(height);
        $('#sub-info').width(width);
    }

    coverAdapt();
    $(window).on('resize',function() {
        coverAdapt();
    });

    //导航栏
    $('.nav').mousedown(function(e) {
        var target = $(e.target);
        target.addClass('nav_active');
    }).mouseup(function (e) {
        var target = $(e.target);
        target.removeClass('nav_active');
    })
    
});