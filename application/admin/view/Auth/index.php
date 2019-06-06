<!-- <!DOCTYPE html>

[if IE 8]> <html lang="en" class="ie8"> <![endif]

[if IE 9]> <html lang="en" class="ie9"> <![endif]

[if !IE]>> <html lang="en"> <![endif]

BEGIN HEAD

<head>

    <meta charset="utf-8" />

    <title>Metronic | Layouts - Blank Page</title>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <meta content="" name="description" />

    <meta content="" name="author" />

    BEGIN GLOBAL MANDATORY STYLES

    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('/css/bootstrap-responsive.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('/css/style-metro.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('/css/style.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('/css/style-responsive.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('/css/default.css') }}" rel="stylesheet" type="text/css" id="style_color"/>

    <link href="{{ asset('/css/uniform.default.css') }}" rel="stylesheet" type="text/css"/>

    END GLOBAL MANDATORY STYLES

    <link rel="shortcut icon" href="{{ asset('/image/favicon.ico') }}" />

</head>

END HEAD

BEGIN BODY

<body class="page-header-fixed">
        <div id="app">
         <router-view class="view"></router-view>
</div></body>
    END FOOTER

    BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time)

    BEGIN CORE PLUGINS
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('/js/model/jquery-1.10.1.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('/js/model/jquery-migrate-1.2.1.min.js') }}" type="text/javascript"></script>

    IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip

    <script src="{{ asset('/js/model/jquery-ui-1.10.1.custom.min.js') }}" type="text/javascript"></script>      

    <script src="{{ asset('/js/model/bootstrap.min.js') }}" type="text/javascript"></script>

    [if lt IE 9]>

    <script src="media/js/excanvas.min.js"></script>

    <script src="media/js/respond.min.js"></script>  

    <![endif]   

    <script src="{{ asset('/js/model/jquery.slimscroll.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('/js/model/jquery.blockui.min.js') }}" type="text/javascript"></script>  

    <script src="{{ asset('/js/model/jquery.cookie.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('/js/model/jquery.uniform.min.js') }}" type="text/javascript" ></script>

    END CORE PLUGINS

    <script src="{{ asset('/js/model/app.js') }}"></script>      

    <script>

        jQuery(document).ready(function() {    

           App.init();

        });

    </script>

    END JAVASCRIPTS

<script type="text/javascript">  var _gaq = _gaq || [];  _gaq.push(['_setAccount', 'UA-37564768-1']);  _gaq.push(['_setDomainName', 'keenthemes.com']);  _gaq.push(['_setAllowLinker', true]);  _gaq.push(['_trackPageview']);  (function() {    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);  })();</script>
<script type="text/javascript">
    var Laravel = {
        // 设置 csrfToken
       csrfToken: '{{ csrf_token() }}' 
    };
</script>
END BODY

</html>
 -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="__CSS__/bootstrap.min.css">
  <!-- Font Awesome -->
<!--   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
Ionicons
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"> -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="__CSS__/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="__CSS__/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="__CSS__/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="__CSS__/skins/_all-skins.min.css">

  <link href="__VUE__/css/app.css" rel="stylesheet">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script>
        window.Laravel = {"__token__":"{{$Request.token}}"};
        window.User = {{$user}};
        window.Menus = {{$menus}};
  </script>
<!-- 富文本 -->
<!-- <script src="__JS__/tinymce4.7.5/tinymce.min.js"></script> -->
</head>
<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
<!-- the fixed layout is not compatible with sidebar-mini -->
<body class="hold-transition skin-blue fixed sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper" id='app'>
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
</body>
<script src="__JS__/jquery-2.2.3.min.js"></script>
<script src="__JS__/bootstrap.min.js"></script>
<!-- <script src="__JS__/app.js"></script> -->
<script type="text/javascript" src="__VUE__/js/manifest.js"></script>
<script type="text/javascript" src="__VUE__/js/vendor.js"></script>
<script type="text/javascript" src="__VUE__/js/app.js"></script>
<!-- Bootstrap 3.3.6 -->

<!-- SlimScroll -->
<script src="__JS__/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="__JS__/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="__JS__/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="__JS__/demo.js"></script>
<!-- 比心心 -->
<!-- <script src="__JS__/anime.js"></script> -->
</html>
