<?php

require_once '../../includes/all_fns.php';
require_once '../../includes/dbconn.php';

// 生成form token
$token = setFormToken();

// add head
do_html_header("账户设置-人力资源管理平台");

echo "<div class=\"container-fluid\" style=\"margin-top: 20px\">";
do_sidebar_system();
?>
    <div class="row-fluid">
      <div class="page-header div-no-margin-top">
      </div>
      <form class="col-md-offset-1 col-md-8 form-horizontal">
        <div class="form-group" id="account-account">
          <label  class="col-md-3 control-label">账号:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="account" value="<?php echo $_SESSION['account']?>">
          </div>
        </div>
        <div class="form-group" id="account-username">
          <label  class="col-md-3 control-label">姓名:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="name" value="<?php echo $_SESSION['username']?>">
          </div>
        </div>
        <div class="form-group" id="account-mobile">
          <label  class="col-md-3 control-label">手机号码:</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="mobile" value="<?php echo $_SESSION['mobile']?>">
          </div>
        </div>

        <div class="form-group hide" id="account-originpass">
          <label  class="col-md-3 control-label">原始密码:</label>
          <div class="col-sm-8">
            <input type="password" class="form-control" id="origin-pass" placeholder="输入原始密码">
          </div>
        </div>
        <div class="form-group hide" id="account-newpass">
          <label  class="col-md-3 control-label">新密码:</label>
          <div class="col-sm-8">
            <input type="password" class="form-control" id="new-pass" placeholder="输入新密码,至少6位">
          </div>
        </div>
        <div class="form-group hide" id="account-confirmpass">
          <label  class="col-md-3 control-label">确认密码:</label>
          <div class="col-sm-8">
            <input type="password" class="form-control" id="confirm-pass" placeholder="再次输入确认新密码">
          </div>
        </div>

        <div class="form-group text-center">
          <button type="button" class="btn btn-success" id="trigger-modify-pass">修改密码</button>
          <button type="button" class="btn btn-primary" id="account-submit">保存修改</button>
        </div>
          <?php
          if ($GLOBALS['system_id'] == 1) {
              if ($_SESSION['permission'] < 2 ){
                  echo "<div class=\"form-group text-center\">
                  <h1><a href=\"./res/user-document/compnymanage_readme.zip\">下载管理员用户手册</a></h1>
              </div>";
              } else {
                  echo "<div class=\"form-group text-center\">
                  <h1><a href=\"./res/user-document/manage-readme.zip\">下载公司管理员用户手册</a></h1>
              </div>";
              }
          }
          ?>

        <br/>
        <?php
        //   if (isset($_SESSION["permission"]) && $_SESSION["permission"] == 2) {
        //     echo "<h2 class=\"text-center\"><a href=\"res/client.docx.zip\">点我下载系统使用手册</a></h2>";
        //   }
        //   if (isset($_SESSION["permission"]) && $_SESSION["permission"] > 0 && $_SESSION["permission"] < 3) {
        //     echo "<h2 class=\"text-center\"><a id=\"download-link\" href=\"res/sms.rar\" data-toggle=\"tooltip\">点我下载客户端</a></h2>";
        //   }
        ?>
        <?php
        //   if (isset($_SESSION["permission"]) && $_SESSION["permission"] == 0) {
        //     echo "<h2 class=\"text-center\"><a href=\"res/admin.docx.zip\">点我下载系统管理员操作指南</a></h2>";
        //   }
        //   if (isset($_SESSION["permission"]) && $_SESSION["permission"] == 1) {
        //     echo "<h2 class=\"text-center\"><a href=\"res/company.docx.zip\">点我下载公司管理员操作指南</a></h2>";
        //     echo "<h2 class=\"text-center\"><a href=\"res/http_document.zip\">点我下载HTTP接口协议文档</a></h2>";
        //   }
        ?>
      </form>
    </div>
  </div>
<?php
do_html_footer();
?>
  <script src="/resource/js/md5.min.js"></script>
  <script>
      $(document).ready(function() {
          hightSidebarItem(0, 1);
      });
   /**
    * Method Description:
    *  修改账号信息
    *  判断trigger-modify-pass按钮的文字,
    *    如果是"取消修改密码"则说明本次修改包含密码,要检测3个密码框;
    *    如果是"修改密码"则说明本次修改仅修改名称;
    * Created by   Michael Lee            lipeng@microwu.com           11/22/16 11:43
    */
    function modifyAccount() {
      $("#account-account").removeClass("has-error");
      $("#account-username").removeClass("has-error");
      $("#account-account").removeClass("has-error");

      var account = $("#account").val().trim();
      var name = $("#name").val().trim();
      var mobile = $("#mobile").val().trim();
      // token
      var token = "<?php echo $token?>";
      if (account === "") {
          $("#account-account").addClass("has-error");
          $("#account").popover({
              content: "姓名不能为空",
              placement: "top"
          });
          $("#account").popover("show");
          return ;
      }
      if (name === "") {
          $("#account-username").addClass("has-error");
          $("#name").popover({
              content: "姓名不能为空",
              placement: "top"
          });
          $("#name").popover("show");
          return ;
      }
      <?php
        if ($_SESSION['permission'] != 0) {
      ?>
          if ( mobile != "" && !isMobilePhone(mobile, 0)) {
              $("#mobile").addClass("has-error");
              $("#mobile").popover({
                  content: "请输入有效的11位手机号码",
                  placement: "top"
              });
              $("#mobile").popover("show");
              return;
          }
      <?php
        }
      ?>


      // type = 0 : 仅修改普通信息;  1 : 修改密码
      var modify_type = ($("#trigger-modify-pass").text() == "修改密码" ? 0 : 1);
      if (modify_type == 1) {
          var origin_pass = $("#origin-pass").val().trim();
          var new_pass = $("#new-pass").val().trim();
          var confirm_pass = $("#confirm-pass").val().trim();

          if (origin_pass == "" || new_pass == "" || confirm_pass == "") {
              showTopMessage("danger", "请完整填写输入内容", 3000);
              if (origin_pass == "") {
                  $("#account-originpass").addClass("has-error");
              }
              if (new_pass == "") {
                  $("#account-newpass").addClass("has-error");
              }
              if (confirm_pass == "") {
                  $("#account-confirmpass").addClass("has-error");
              }
              return ;
          }

          if (new_pass.length < 6) {
              $("#account-newpass").addClass("has-error");
              $("#new-pass").popover({
                  content: "密码长度至少6位",
                  placement: "top"
              });
              $("#new-pass").popover("show");
              setTimeout(function () {
                  $("#new-pass").popover("destroy");
              }, 3000);
              return ;
          }

          if (new_pass != confirm_pass) {
              $("#account-newpass").addClass("has-error");
              $("#account-confirmpass").addClass("has-error");
              showSingleButtonNoty("两次密码输入不一致,请重新输入", "error", 0);
              return;
          }

          // 新密码不能和原始密码一样
          if (new_pass === origin_pass) {
              $("#account-newpass").addClass("has-error");
              $("#account-confirmpass").addClass("has-error");
              showSingleButtonNoty("新密码和原始密码不能一样，请更改后重试", "error", 0);
              return;
          }
      }

      $.ajax({
          url : '/includes/jscallphp.php',
          type : 'POST',
          data : {type : 1, token: token, modify_type : modify_type, account : account, mobile : mobile,  name : name,
              origin_pass: md5(origin_pass), new_pass : md5(new_pass)},
          dataType: 'JSON',
          success: function (json) {
              // Animate loader off screen
              $(".se-pre-con").fadeOut("slow");
              // 登录成功
              if (json.response == 0) {
                  showSingleButtonNoty("修改个人信息成功", "success", -1);
                  $("#account-submit").text("保存修改");
                  $("#account-submit").removeClass("btn-info");
              } else if (json.response == 2) {
                  $("#account-originpass").addClass("has-error");
                  showSingleButtonNoty("密码输入错误,请重新输入", "error", 0);
                  $("#account-submit").text("保存修改");
                  $("#account-submit").removeClass("btn-info");
              } else {
                  showSingleButtonNoty(json.info, "error", 0);
                  $("#account-submit").text("保存修改");
                  $("#account-submit").removeClass("btn-info");
              }
          },
          error: function (textStatus, errorThrown) {
              console.error(textStatus);
              console.error(errorThrown);
              // Animate loader off screen
              $(".se-pre-con").fadeOut("slow");
              showSingleButtonNoty("服务器内部错误,请稍后重试. 如果此问题重复出现,请联系网站管理人员", "error", 0);
              $("#account-submit").text("保存修改");
              $("#account-submit").removeClass("btn-info");
          },
          beforeSend: function () {
              // Animate loader on screen
              $(".se-pre-con").fadeIn("slow");
              $("#account-submit").text("保存中...");
              $("#account-submit").addClass("btn-info");
          }
      });
      return false;
    }

    $(document).ready(function () {
      // document加载完之后 打开侧边菜单栏的特定选项
        hightSidebarItem(0, 2);

      // 下载提示信息
      $('#download-link').tooltip({
          placement: "top",
          title: "部分杀毒软件会误认为客户端程序为木马，请将本客户端添加至杀毒软件信任白名单列表。"
      });

      // 显示或隐藏密码按钮动作
      $("#trigger-modify-pass").click(function () {
        if ($(this).text() == "修改密码") {
          $("#account-originpass").addClass("show");
          $("#account-newpass").addClass("show");
          $("#account-confirmpass").addClass("show");
          $(this).text("取消修改密码");
          $(this).removeClass("btn-success");
        } else {
          $("#account-originpass").removeClass("show");
          $("#orgin-pass").val("");
          $("#account-newpass").removeClass("show");
          $("#new-pass").val("");
          $("#account-confirmpass").removeClass("show");
          $("#confirm-pass").val("");
          $(this).text("修改密码");
          $(this).addClass("btn-success");
        }
      });

      // 保存修改按钮动作
      $("#account-submit").click(modifyAccount);
    });
  </script>
<?php
// add footer
do_html_end();
