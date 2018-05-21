<?php
/***************************************************************************************************
 * File Description:
 *  本文件是用于做各种通用代码输出, 包括header, footer等公用的信息
 * Methods:
 *  do_html_header(string)          设置html页面的头部
 *  do_html_navbar                  设置title navbar的右半部分内容
 *  do_sidebar_system               设置系统管理员菜单项
 *  do_html_pagination              设置底部页面指示器
 *  do_html_footer                  设置html的footer部分
 *  do_html_end                     设置html结束部分
 *
 * Updated History:
 * Author       Date            Content
 * Regardo      11/6/2017       为业务管理员和系统管理员侧边栏，调账功能增加出现条件
/**************************************************************************************************/
require_once 'global_variables.php';
/**
 * Method Description:
 *   Print an html header
 * Created by   Michael Lee            lipeng@microwu.com           6/1/2017, 10:28:09 AM
 * @param   $title   string     标题
 */
function do_html_header($title) {
    // 检查session
    if (!isset($_SESSION['user_id'])) {
        redirect("/login.php");
    }
?>
<!DOCTYPE html>

  <head>
    <title><?php echo $title?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Management System for MicroWu Order System" />
    <meta name="keywords" content="mobile internet financial" />
    <meta name="author" content="Michael Lee" />
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/resource/css/bootstrap.min.css">
    <link rel="stylesheet" href="/resource/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/resource/css/animate.css">
    <link rel="stylesheet" href="/resource/css/font-awesome.min.css">
    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="/resource/css/<?php echo $GLOBALS['main_css']?>">
  </head>

  <div class="se-pre-con"></div>
  <body style="background-color: #f5f5f5">
    <div id="wrap">
      <nav id="top_navbar" class="navbar navbar-fixed-top" role="navigation">
        <div class="container-fluid nav-orange">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <?php
            if ($title == "登录") {
                echo "<a class=\"navbar-brand\" href=\"#\">登录</a>";
            } else {
                if (isset($_SESSION["permission"]) && $_SESSION["permission"] != 2) {
                    echo "<a class=\"text-white navbar-brand\" href=\"/index.php\">{$GLOBALS['system_name']}</a>";
                } else {
                    echo "<a class=\"text-white navbar-brand\" href=\"#\">{$GLOBALS['system_name']}</a>";
                }
            }
            ?>
        </div>
  <?php
  if ($title != "登录") {
      do_html_navbar();
  } ?>
      </div><!-- /.container-fluid -->
    </nav>
    <div id="top-placeholder" style="height: 50px"></div>
  <?php

}

/**
 * Method Description:
 *  显示顶部的Navigation右半部分内容
 * Created by   Michael Lee            lipeng@microwu.com           11/10/16 16:51
 */
function do_html_navbar() {
    ?>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
          <?php
          if (isset($_SESSION['pending_num']) && $_SESSION['pending_num'] > 0) {
              echo "<li><a style='padding-right: 3px; padding-left: 3px' href='/web/finance/subsystem_1/adjust_list.php'>
                  <span class=\"badge\">{$_SESSION['pending_num']}</span></a></li>";
          }
          ?>
          <li class="dropdown">
              <a class="dropdown-toggle text-white" data-toggle="dropdown" href="#">
                <i class="icon-user"></i>
                <?php echo $_SESSION['username']?>
                <i class="icon-caret-down"></i>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="/web/system/my_account.php"><i class="icon-cog"></i>个人中心</a></li>
                <li class="divider"></li>
                <li><a href="/logout.php"><i class="icon-signout"></i>退出登录</a></li>
              </ul>
          </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  <?php
}

/**
 * Method Description:
 *  系统管理员菜单栏
 * Created by   Michael Lee            lipeng@microwu.com           6/1/2017, 10:38:38 AM
 */
function do_sidebar_system() {
    ?>
    <div class="nav col-md-2">
      <!-- Contenedor -->
      <ul id="accordion" class="accordion">
        <!-- 系统页面 -->
        <li>
          <div class="link"><i class="icon-home"></i>系统管理<i class="icon-chevron-down"></i></div>
          <ul class="submenu">
            <li><a href="/web/system/index.php">系统首页</a></li>
            <li><a href="/web/system/my_account.php">个人中心</a></li>
          </ul>
        </li>

        <li>
          <div class="link"><i class="icon-file"></i>人才招聘<i class="icon-chevron-down"></i></div>
          <ul class="submenu">
            <li><a href="/web/employ/employ_list.php">招聘列表</a></li>
            <li><a href="/web/employ/employ_add.php">添加招聘</a></li>
          </ul>
        </li>
        <!-- 员工信息 -->
        <li>
          <div class="link"><i class="icon-cogs"></i>员工信息<i class="icon-chevron-down"></i></div>
          <ul class="submenu">
            <li><a href="/web/staff/staff_list.php">员工列表</a></li>
            <li><a href="/web/staff/staff_import.php">信息录入</a></li>
          </ul>
        </li>
        <!-- 面试安排 -->
        <li>
          <div class="link"><i class="icon-user"></i>面试安排<i class="icon-chevron-down"></i></div>
          <ul class="submenu">
            <li><a href="/web/interview/interview_info.php">面试人员资料</a></li>
            <li><a href="/web/interview/offer_review.php">近日Offer审批</a></li>
          </ul>
        </li>
        <!-- 统计查询 -->
        <li>
          <div class="link"><i class="icon-bar-chart"></i>统计查询<i class="icon-chevron-down"></i></div>
          <ul class="submenu">
            <li><a href="/web/statistics/salary_stat.php">薪酬统计</a></li>
<!--            <li><a href="/web/statistics/salary_level.php">等级管理</a></li>-->
          </ul>
        </li>
        <!-- 人才库 -->
        <li>
          <div class="link"><i class="icon-file"></i>人才库<i class="icon-chevron-down"></i></div>
          <ul class="submenu">
            <li><a href="/web/employee_db/employee_sea.php">人才公海</a></li>
              <li><a href="/web/employee_db/employee.php">人才捞</a></li>
          </ul>
        </li>
      </ul>
    </div>
  <?php
}

/**
 * Method Description:
 *  打印分页指示器，样式参见byr。
 *  当总页数小于10时，全部输出；
 *  当总页数大于10， 当前页数小于8时，输出前8个和最后一页，中间... 最后可以跳转；
 *  当总页数大于10， 当前页数处于中间时，输出当前页数前后3页(共7页)，前后都...，加上首页和尾页 最后可以跳转；
 *  当总页数大于10， 当前页数小于total-8时，输出最后8个和第一页，中间... 最后可以跳转；
 * @param   $total              总页数
 * @param   $current            当前页数
 * @param   $url                跳转的主体url， 最后必须带上?
 * @param   $sub_url            部分页面可能会有附带的get参数，附在url后面，允许为NULL, 最后要带上&
 */
function do_html_pagination($total, $current, $url, $sub_url) {
    $prev_page = $current - 1;
    $next_page = $current + 1;
    // 组合url
    if ($sub_url != null) {
        $url = $url . $sub_url;
    }
    // 输出前一页按钮
    if ($prev_page > 0) {
        echo "<li><a href=\"{$url}page={$prev_page}\">&laquo;</a></li>";
    } else {
        echo "<li class='disabled'><a href=\"javascript:void(0)\">&laquo;</a></li>";
    }
    if ($total <= 10) {
        // 小于等于10时，全部输出每个单独的页面地址
        for ($i = 1; $i <= $total; $i++) {
            if ($i == $current) {
                echo "<li class='active'><a href=\"#\">{$i}</a></li>";
            } else {
                echo "<li><a href=\"{$url}page={$i}\">{$i}</a></li>";
            }
        }
    } else {
        // 大于10个时，判断当前页面的位置
        if ($current <=8 ) {
            for ($i = 1; $i <= 8; $i++) {
                if ($i == $current) {
                    echo "<li class='active'><a href=\"#\">{$i}</a></li>";
                } else {
                    echo "<li><a href=\"{$url}page={$i}\">{$i}</a></li>";
                }
            }
            echo "<li><a>...</a></li>";
            echo "<li><a href=\"{$url}page={$total}\">{$total}</a></li>";
        } else if ($current > $total - 8) {
            echo "<li><a href=\"{$url}page=1\">1</a></li>";
            echo "<li><a>...</a></li>";
            for ($i = $total - 7; $i <= $total; $i++) {
                if ($i == $current) {
                    echo "<li class='active'><a href=\"#\">{$i}</a></li>";
                } else {
                    echo "<li><a href=\"{$url}page={$i}\">{$i}</a></li>";
                }
            }
        } else {
            echo "<li><a href=\"{$url}page=1\">1</a></li>";
            echo "<li><a>...</a></li>";
            for ($i = $current - 3; $i <= $current + 3; $i++) {
                if ($i == $current) {
                    echo "<li class='active'><a href=\"#\">{$i}</a></li>";
                } else {
                    echo "<li><a href=\"{$url}page={$i}\">{$i}</a></li>";
                }
            }
            echo "<li><a>...</a></li>";
            echo "<li><a href=\"{$url}page={$total}\">{$total}</a></li>";
        }
    }
    // 后一页按钮
    if ($next_page <= $total) {
        echo "<li><a href=\"{$url}page={$next_page}\">&raquo;</a></li>";
    } else {
        echo "<li class='disabled'><a href=\"javascript:void(0)\">&raquo;</a></li>";
    }
    // 如果页数大于10， 则可以填写跳转
    if ($total > 10) {
        echo "<li>
                <div class=\"col-sm-2\">
                  <div class=\"input-group\">
                    <input type=\"text\" class=\"form-control right-align\" id=\"page_input\">
                    <span class=\"input-group-btn\">
                      <button class=\"btn btn-default\" type=\"button\" id='jump_btn'>Go</button>
                    </span>
                  </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
              </li>";
    }
    ?>
    <!-- jQuery -->
    <script type="text/javascript" src="/resource/js/jquery-3.1.1.min.js"></script>
    <script>
        // 绑定按钮动作
        $(document).ready(function () {
            $('#jump_btn').on('click', function(){
                var page_index = parseInt($('#page_input').val()) < 0 ? 0 : $('#page_input').val();
                var url = "<?php echo $url . 'page=';?>" + page_index;
                window.top.location.assign(url);
            });
        });
    </script>
    <?php
}

/**
 * Method Description:
 */
function do_html_footer() {
    ?>
      <div id="footer-placeholder" style="height: 80px"></div>
    </div><!-- /#wrap -->
    <footer id="footer" class="row-fluid">
      <div class="navbar-inner nav-orange">
        <div class="container">
          <p class="text-white text-center center-block"><?php echo $GLOBALS['footer_name']?></p>
        </div>
      </div>
    </footer>
    <!-- jQuery -->
    <script type="text/javascript" src="/resource/js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap -->
    <script type="text/javascript" src="/resource/js/bootstrap.min.js"></script>
    <!-- noty   -->
    <script type="text/javascript" src="/resource/js/noty/packaged/jquery.noty.packaged.js"></script>
    <!-- Custom JS -->
    <script type="text/javascript" src="/resource/js/commutils.js"></script>
    <!-- Modernizr JS -->
    <script type="text/javascript" src="/resource/js/modernizr-2.6.2.min.js"></script>
    <!-- Sidebar JS-->
    <script type="text/javascript" src="/resource/js/sidebar.js"></script>
    <!-- BlueBird for promise -->
    <script type="text/javascript" src="/resource/js/bluebird.min.js"></script>
    <script>
      $(document).ready(function () {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");
      });
    </script>
<?php
}

/**
 * Method Description:
 *  打印html结尾部分，这样可以把所有的js放在最后面
 * Created by   Michael Lee            lipeng@microwu.com           10/24/16 16:31
 */
function do_html_end() {
  ?>
    <!-- FOR IE9 below -->
    <!--[if lt IE 9]>
    <script src="js/respond.min.js"></script>
    <![endif]-->
  </body>
</html>

<?php
}
