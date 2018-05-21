<?php
/***************************************************************************************************
 * File Description:
 *  员工修改界面
 * Update History:
 * Author       Time            Contennt
 ***************************************************************************************************/
require_once '../../includes/all_fns.php';
require_once '../../includes/dbconn.php';
require_once '../../includes/staff/sql_staff_list.php';
do_html_header("信息修改-人力资源管理平台");
if ($_GET['staff_id'] === null || $_GET['staff_id'] === "") {
  redirect('/index.php');
}

// 获得员工信息
$staff = getStaffById($_GET['staff_id']);
if ($staff === 1 && $staff === -1) {
  redirect('/index.php');
}
?>

<div class="container-fluid" style="margin-top: 20px">
    <?php
    do_sidebar_system();
    ?>
  <div class="container col-md-10">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="panel-title">
          员工信息修改 > <?php echo $staff['name']; ?>
        </div>
      </div>
      <div class="panel-body">
        <div class="container col-md-12">
          <form class="form-horizontal" id="update-form">
            <div class="form-group" id="update-form-type">
              <!-- 根据type subtype level获取岗位-->
              <label class="col-md-2 control-label">岗位</label>
              <div class="col-md-3">
                <select class="form-control" name="type" id="select-update-type"></select>
              </div>
              <div class="col-md-3">
                <select class="form-control" name="subtype" id="select-update-subtype"></select>
              </div>
              <div class="col-md-3">
                <select class="form-control" name="level" id="select-update-level"></select>
              </div>
            </div>

            <div class="form-group" id="update-form-name">
              <label  class="col-md-2 control-label">姓名</label>
              <div class="col-md-9">
                <input type="text" name="name" class="form-control" id="text-update-name" value="<?php echo $staff['name']; ?>">
              </div>
            </div>

            <div class="form-group" id="update-form-sex">
              <label  class="col-md-2 control-label">性别</label>
              <div class="col-md-9">
                <label class="radio-inline">
                  <input type="radio" name="sex" id="radio-update-sex" value="0" <?php if ($staff['sex'] === "0") { ?>checked<?php } ?>> 男
                </label>
                <label class="radio-inline">
                  <input type="radio" name="sex" id="radio-update-sex" value="1" <?php if ($staff['sex'] === "1") { ?>checked<?php } ?>> 女
                </label>
              </div>
            </div>

            <div class="form-group" id="update-form-education">
              <label  class="col-md-2 control-label">学历</label>
              <div class="col-md-9">
                <select name="education" class="form-control" id="select-update-education">
                  <option value="0" <?php if ($staff['education'] === "0") { ?>selected <?php } ?>>博士</option>
                  <option value="1" <?php if ($staff['education'] === "1") { ?>selected <?php } ?>>硕士</option>
                  <option value="2" <?php if ($staff['education'] === "2") { ?>selected <?php } ?>>本科</option>
                  <option value="3" <?php if ($staff['education'] === "3") { ?>selected <?php } ?>>其它</option>
                </select>
              </div>
            </div>

            <div class="form-group" id="update-form-school">
              <label  class="col-md-2 control-label">毕业院校</label>
              <div class="col-md-9">
                <input type="text" name="school" class="form-control" id="text-update-school" value="<?php echo $staff['school'];?>">
              </div>
            </div>

            <div class="form-group" id="update-form-major">
              <label  class="col-md-2 control-label">专业</label>
              <div class="col-md-9">
                <input type="text" name="school" class="form-control" id="text-update-major" value="<?php echo $staff['major'];?>">
              </div>
            </div>

            <!-- todo 根据date更新datepicker-->
            <div class="form-group" id="update-form-birthday">
              <label  class="col-md-2 control-label">出生日期</label>
              <div class='col-md-9'>
                <div class="input-group date" data-provide="datepicker">
                  <input type="text" name="birthday" class="form-control" id="date-update-birthday" readonly>
                  <div class="input-group-addon">
                    <span class="icon-calendar"></span>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group" id="update-form-status">
              <label  class="col-md-2 control-label">职工状态</label>
              <div class="col-md-9">
                <select name="status" id="select-update-status" class="form-control">
                  <option value="0" <?php if ($staff['status'] === '0') { ?>selected<?php }?>>在职</option>
                  <option value="1" <?php if ($staff['status'] === '1') { ?>selected<?php }?>>离职</option>
                </select>
              </div>
            </div>
            <!-- todo date to datepicker -->
            <div class="form-group" id="update-form-in_time">
              <label  class="col-md-2 control-label">入职日期</label>
              <div class='col-md-9'>
                <div class="input-group date" data-provide="datepicker">
                  <input type="text" name="in_time" class="form-control" id="date-update-in_time" readonly>
                  <div class="input-group-addon">
                    <span class="icon-calendar"></span>
                  </div>
                </div>
              </div>
            </div>
            <!-- todo date to datepicker -->
            <div class="form-group <?php if ($staff['status'] === '0') { ?>hidden<?php }?>" id="update-form-out_time" >
              <label  class="col-md-2 control-label" >离职日期</label>
              <div class='col-md-9'>
                <div class="input-group date" data-provide="datepicker">
                  <input type="text" name="out_time" class="form-control" id="date-update-out_time" readonly>
                  <div class="input-group-addon">
                    <span class="icon-calendar"></span>
                  </div>
                </div>
              </div>
            </div>
            <hr/>
            <div class="col-md-offset-9 col-md-3">
              <input type="button" id="update-btn" class="btn btn-primary" value="提交"/>
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
        hightSidebarItem(2, 0);

        // load datepicker
        $('.input-group.date').datepicker({
            language: "zh-CN",
            autoclose: true,
            todayHighlight: true,
        }).datepicker("setDate", "0");

        //load type subtype and level
        $.ajax({
            url:"/includes/staff/ajax_staff_list.php",
            type:"post",
            data:{code:1},
            dataType:"json",
            success:function (json) {
                if (json.resp_code === 0) {
                    types = json.resp_info.types;
                    subtypes = json.resp_info.subtypes;
                    levels = json.resp_info.levels;
                    //trippleLinkage(types, subtypes, levels, $("#type"), $("#subtype"), $("#level"), $("#label-type"), $("#label-subtype"), $("#label-level"), true);

                    trippleLinkage(types, subtypes, levels, $("#select-update-type"), $("#select-update-subtype"), $("#select-update-level"));

                    $('#select-update-type').val(<?php echo $staff['type'];?>);
                    <?php if ($staff['subtype'] !== "" && $staff['subtype'] !== null) {?>
                    resetSubtype(subtypes, $("#select-update-type"), $("#select-update-subtype"));

                    $('#select-update-subtype').val(<?php echo $staff['subtype'];?>);
                    <?php }
                    if ($staff['level'] !== "" && $staff['level'] !== null) {?>
                    resetLevel(levels, $("#select-update-type"), $("#select-update-subtype"), $("#select-update-level"));

                    $('#select-update-level').val(<?php echo $staff['level'];?>);
                    <?php } ?>

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

        // 1 教育经历为其它则不需要填写school
        $("#select-update-education").change(function () {
            if ($(this).val() === '3') {
                $("#text-update-school").attr("disabled", true);
            } else {
                $("#text-update-school").attr("disabled", false);
            }
        });

        // 当修改状态为离职时 显示离职时间窗口
        $("#select-update-status").change(function () {
            if ($(this).val() === '1') {
                $("#update-form-out_time").removeClass("hidden");
            } else {
                $("#update-form-out_time").addClass("hidden");
            }
        });

        // 2 确认后发送ajax请求
        $("#update-btn").click(function () {
            var id = <?php echo $_GET['staff_id'];?>;
            var type = $("#select-update-type").val() === '-1' ? null : $("#select-update-type").val();
            var subtype = $("#select-update-subtype").val() === '-1' ? null : $("#select-update-subtype").val();
            var level = $("#select-update-level").val() === '-1' ? null : $("#select-update-level").val();
            var name = $("#text-update-name").val();
            var sex = $("#update-form input[name='sex']").val();
            var education = $("#select-update-education").val();
            var school = $("#text-update-school").val();
            var major = $("#text-update-major").val();
            var birthday = getDatePickerValue($("#date-update-birthday").val());
            var status = $("#select-update-status").val();
            var in_time = getDatePickerValue($("#date-update-in_time").val());
            var out_time = status === "0" ? null : getDatePickerValue($("#date-update-out_time").val());

            // 发送请求
            $.post({
                url: "/includes/staff/ajax_staff_list.php",
                dataType: "json",
                data: {
                    code: 3,
                    id: id,
                    type: type,
                    subtype: subtype,
                    level: level,
                    name: name,
                    sex: sex,
                    education: education,
                    school: school,
                    major:major,
                    birthday: birthday,
                    status: status,
                    in_time: in_time,
                    out_time: out_time
                },
                success: function (json) {
                    switch (json.resp_code) {
                        case 0:
                            showSingleButtonNoty(json.resp_info, "success", "staff_list.php");
                            break;
                        default:
                            showSingleButtonNoty(json.resp_info, "error", 0);
                            break;
                    }
                },
                error: function (textStatus, errorThrown) {
                    console.error(textStatus);
                    console.error(errorThrown);
                    showSingleButtonNoty("服务器内部错误,请稍后重试. 如果此问题重复出现,请联系网站管理人员", "error", 0);
                }
            });

        });
    });

    var types, subtypes, levels;

</script>
<?php
do_html_end();
if ($_GET['info'] == -1){
    echo "<script>
    showSingleButtonNoty(\"信息不全\",\"error\", 0);
</script>";
}

?>
