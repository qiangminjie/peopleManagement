<?php
/***************************************************************************************************
 * File Description:
 *  新建公司页面，系统管理员和业务管理员均可以访问。业务管理员在功能资费列表，仅能查看自己所管理的功能资费
 * Created by   Michael Lee            lipeng@microwu.com           6/3/2017, 12:05:55 PM
 * CopyRight    北京小悟科技有限公司      http://www.microwu.com
 *
 * Update History:
 * Author       Time            Contennt
 * Michael Lee  11/24/2017      页面加载时生成token， 发送ajax请求时带上token值，使用后即清除
 ***************************************************************************************************/
require_once '../../includes/all_fns.php';
require_once '../../includes/dbconn.php';
do_html_header("新增面试-人力资源管理平台");
?>

<div class="container-fluid" style="margin-top: 20px">
    <?php
    do_sidebar_system();
    ?>
  <div class="container col-md-10">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="panel-title">
          新增面试
        </div>
      </div>
      <div class="panel-body">
        <div class="container col-md-12">
          <form class="form-horizontal" id="create-form">
            <div class="form-group" id="create-form-type">
              <label class="col-md-2 control-label">岗位</label>
              <div class="col-md-3">
                <select class="form-control" name="type" id="select-create-type"></select>
              </div>
              <div class="col-md-3">
                <select class="form-control" name="subtype" id="select-create-subtype"></select>
              </div>
              <div class="col-md-3">
                <select class="form-control" name="level" id="select-create-level"></select>
              </div>
            </div>

            <div class="form-group" id="create-form-name">
              <label  class="col-md-2 control-label">姓名</label>
              <div class="col-md-9">
                <input type="text" name="name" class="form-control" id="text-create-name">
              </div>
            </div>

            <div class="form-group" id="create-form-sex">
              <label  class="col-md-2 control-label">性别</label>
              <div class="col-md-9">
                <label class="radio-inline">
                  <input type="radio" name="sex" id="radio-create-sex" value="0" checked> 男
                </label>
                <label class="radio-inline">
                  <input type="radio" name="sex" id="radio-create-sex" value="1"> 女
                </label>
              </div>
            </div>

            <div class="form-group" id="create-form-education">
              <label  class="col-md-2 control-label">学历</label>
              <div class="col-md-9">
                <select name="education" class="form-control" id="select-create-education">
                  <option value="0">博士</option>
                  <option value="1">硕士</option>
                  <option value="2" selected>本科</option>
                  <option value="3">其它</option>
                </select>
              </div>
            </div>

            <div class="form-group" id="create-form-school">
              <label  class="col-md-2 control-label">毕业院校</label>
              <div class="col-md-9">
                <input type="text" name="school" class="form-control" id="text-create-school">
              </div>
            </div>

            <div class="form-group" id="create-form-major">
              <label  class="col-md-2 control-label">专业</label>
              <div class="col-md-9">
                <input type="text" name="school" class="form-control" id="text-create-major">
              </div>
            </div>

            <div class="form-group" id="create-form-birthday">
              <label  class="col-md-2 control-label">出生日期</label>
              <div class='col-md-9'>
                <div class="input-group date" data-provide="datepicker">
                  <input type="text" name="birthday" class="form-control" id="date-create-birthday" readonly>
                  <div class="input-group-addon">
                    <span class="icon-calendar"></span>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group" id="create-form-status">
              <label  class="col-md-2 control-label">职工状态</label>
              <div class="col-md-9">
                <select name="status" id="select-create-status" class="form-control">
                  <option value="0">在职</option>
                  <option value="1" >离职</option>
                </select>
              </div>
            </div>

            <div class="form-group" id="create-form-in_time">
              <label  class="col-md-2 control-label">入职日期</label>
              <div class='col-md-9'>
                <div class="input-group date" data-provide="datepicker">
                  <input type="text" name="in_time" class="form-control" id="date-create-in_time" readonly>
                  <div class="input-group-addon">
                    <span class="icon-calendar"></span>
                  </div>
                </div>
              </div>
            </div>
            <hr/>
            <div class="col-md-offset-9 col-md-3">
              <input type="button" id="create-btn" class="btn btn-primary" value="提交"/>
              <button class="btn btn-default">返回</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
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
<script src="/resource/js/utils.js"></script>
<script>
    $(document).ready(function () {
        hightSidebarItem(3, 0);

        // load datepicker
        $('.input-group.date').datepicker({
            language: "zh-CN",
            autoclose: true,
            todayHighlight: true,
        }).datepicker("setDate", "0");

        //load type subtype and level
        $.ajax({
            url:"/includes/interview/ajax_interview_list.php",
            type:"post",
            data:{code:1},
            dataType:"json",
            success:function (json) {
                if (json.resp_code === 0) {
                    types = json.resp_info.types;
                    subtypes = json.resp_info.subtypes;
                    levels = json.resp_info.levels;
                    //trippleLinkage(types, subtypes, levels, $("#type"), $("#subtype"), $("#level"), $("#label-type"), $("#label-subtype"), $("#label-level"), true);

                    trippleLinkage(types, subtypes, levels, $("#select-create-type"), $("#select-create-subtype"), $("#select-create-level"));

                } else {
                    showSingleButtonNoty(json.resp_info, "error", 0);
                }
            },
            error:function (textStatus, errorThrown) {
                console.error(textStatus);
                console.error(errorThrown);
                showSingleButtonNoty("服务器内部错误,请稍后重试. 如果此问题重复出现,请联系网站管理人员", "error", 0);
            }
        });

        $("#select-create-education").change(function () {
            if ($(this).val() === '3') {
                $("#text-create-school").attr("disabled", true);
            } else {
                $("#text-create-school").attr("disabled", false);
            }
        });

        // 1 教育经历为其它则不需要填写school
        $("#select-create-education").change(function () {
            if ($(this).val() === '3') {
                $("#text-create-school").attr("disabled", true);
            } else {
                $("#text-create-school").attr("disabled", false);
            }
        });

        // 2 确认后发送ajax请求
        $("#create-btn").click(function () {
            var type = $("#select-create-type").val() === '-1' ? null : $("#select-create-type").val();
            var subtype = $("#select-create-subtype").val() === '-1' ? null : $("#select-create-subtype").val();
            var level = $("#select-create-level").val() === '-1' ? null : $("#select-create-level").val();
            var name = $("#text-create-name").val();
            var sex = $("#create-form input[name='sex']").val();
            var education = $("#select-create-education").val();
            var school = $("#text-create-school").val();
            var major = $("#text-create-major").val();
            var birthday = getDatePickerValue($("#date-create-birthday").val());
            var status = $("#select-create-status").val();
            var in_time = getDatePickerValue($("#date-create-in_time").val());
            if (!checkCreateForm()) {
                alert("ithti")
            } else {

                // 发送请求
                $.post({
                    url: "/includes/interview/ajax_interview_list.php",
                    dataType: "json",
                    data: {
                        code: 2,
                        type: type,
                        subtype: subtype,
                        level: level,
                        name: name,
                        sex: sex,
                        education: education,
                        school: school,
                        major: major,
                        birthday: birthday,
                        status: status,
                        in_time: in_time
                    },
                    success: function (json) {
                        showSingleButtonNoty("操作成功", "success","操作成功");

                        window.location.href = "/web/interview/interview_info.php";
                    },
                    error: function (textStatus, errorThrown) {
                        console.error(textStatus);
                        console.error(errorThrown);
                        showSingleButtonNoty("服务器内部错误,请稍后重试. 如果此问题重复出现,请联系网站管理人员", "error", 0);
                    }
                });
            }
        });
    });

    var types, subtypes, levels;

    // TODO 表单校验
    function checkCreateForm() {
        return true;
    }

</script>
<?php
do_html_end();


?>
