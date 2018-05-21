<?php
require_once '../../includes/all_fns.php';

do_html_header("首页-人力资源管理平台");
?>
<div class="container-fluid" style="margin-top: 20px">
<?php
do_sidebar_system();
?>
  <h1>欢迎使用人力资源管理平台</h1>
</div>
<?php
do_html_footer();
?>
<link rel="stylesheet" href="/resource/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/resource/css/bootstrap-datepicker3.standalone.min.css">
<link rel="stylesheet" href="/resource/css/magicsuggest-min.css">
<script src="/resource/js/bootstrap-datepicker.min.js"></script>
<script src="/resource/js/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="/resource/js/magicsuggest.js"></script>
<script>
    $(document).ready(function() {
        hightSidebarItem(0, 0);
    });
</script>
<?php
do_html_end();
?>
