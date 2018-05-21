<?php
/***************************************************************************************************
 * File Description:
 *  登录界面
 *
 * Updated History:
 * Author       Date            Content
/**************************************************************************************************/
require_once 'includes/all_fns.php';
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" xmlns="http://www.w3.org/1999/html"> <!--<![endif]-->
  <head>
    <title>登录</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="/resource/res/favicon.ico" rel="bookmark" type="image/x-icon" />
    <link href="/resource/res/favicon.ico" rel="icon" type="image/x-ico™n" />
    <link href="/resource/res/favicon.ico" rel="shortcut icon" type="image/x-icon" />

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Management System for Unicom SMS Template Manage" />
    <meta name="keywords" content="mobile internet financial" />
    <meta name="author" content="Michael Lee" />
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/resource/css/bootstrap.min.css">
    <link rel="stylesheet" href="/resource/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/resource/css/animate.css">
    <link rel="stylesheet" href="/resource/css/font-awesome.min.css">
    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="/resource/css/<?php echo $GLOBALS['main_css']?>">

    <!-- jQuery -->
    <script type="text/javascript" src="/resource/js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap -->
    <script type="text/javascript" src="/resource/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script type="text/javascript" src="/resource/js/commutils.js"></script>
  </head>
  <body>
    <iframe id="preview" src="/resource/<?php echo $GLOBALS['login_html']?>" frameborder="0" width="100%"></iframe>
    <footer id="footer" class="navbar-fixed-bottom row-fluid login-bottom-nav">
      <div class="navbar-inner login-bottom-nav">
        <div class="container">
          <p class="text-orange text-center center-block"><?php echo $GLOBALS['footer_name']?></p>
        </div>
      </div>
    </footer>
    <script>
      $(document).ready(function () {
          // 重置登录iFrame的位置和大小
          function fix_height(){
            $("#preview").attr("height", (($(window).height())) + "px");
          }
          $(window).resize(function(){ fix_height(); }).resize();
      });
    </script>
    <!-- FOR IE9 below -->
    <!--[if lt IE 9]>
    <script src="js/respond.min.js"></script>
    <![endif]-->
  </body>
</html>
