<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 2018/5/1
 * Time: 下午6:39
 */

require_once '../../includes/dbconn.php';
require_once '../../includes/all_fns.php';
do_html_header("新增招聘需求");
?>

<div class="container-fluid" style="margin-top: 20px">
    <?php
    do_sidebar_system();
    ?>
    <div class="container col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="panel-title">
                    新增招聘需求
                </div>
            </div>
            <div class="panel-body">
                <div class="container col-md-12">
                    <form class="form-horizontal" id="create-form">
                        <div class="form-group" id="div-employ_add-title">
                            <label  class="col-md-2 control-label">需求标题</label>
                            <div class="col-md-6">
                                <input type="text" name="count" class="form-control" id="input-employ_add-title">
                            </div>
                        </div>
                        <div class="form-group" id="div-employ_add-type">
                            <label class="col-md-2 control-label">需求岗位</label>
                            <div class="col-md-3">
                                <select class="form-control" name="type" id="select-employ_add-type"></select>
                            </div>
                        </div>

                        <div class="form-group" id="div-employ_add-desc">
                            <label  class="col-md-2 control-label">需求描述</label>
                            <div class="col-md-6">
                                <textarea name="desc" class="form-control" id="textarea-employ_add-desc" placeholder="请在此输入需求描述"></textarea>
                            </div>
                        </div>

                        <div class="form-group" id="div-employ_add-count">
                            <label  class="col-md-2 control-label">人数</label>
                            <div class="col-md-1">
                                <input type="text" name="count" class="form-control" id="input-employ_add-count">
                            </div>
                        </div>

                        <div class="form-group" id="div-employ_add-status">
                            <label  class="col-md-2 control-label">状态</label>
                            <div class="col-md-3">
                                <select name="education" class="form-control" id="select-employ_add-status">
                                    <option value="0" selected>审核中</option>
                                    <option value="1">发布中</option>
                                    <option value="2">已完成</option>
                                </select>
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
        hightSidebarItem(1, 1);
        //load type
        $.ajax({
            url: "/includes/staff/ajax_staff_list.php",
            type: "post",
            data: {code: 1},
            dataType: "json",
            success: function (json) {
                if (json.resp_code === 0) {
                    types = json.resp_info.types;
                    resetType(types, $("#select-employ_add-type"));
                } else {
                    showSingleButtonNoty(json.resp_info, "error", 0);
                }
            },
            error: function (textStatus, errorThrown) {
                console.error(textStatus);
                console.error(errorThrown);
                showSingleButtonNoty("服务器内部错误,请稍后重试. 如果此问题重复出现,请联系网站管理人员", "error", 0);
            }
        });

        $("#create-btn").click(function () {
            var title = $("#input-employ_add-title").val();
            var type = $("#select-employ_add-type").val();
            var desc = $("#textarea-employ_add-desc").val();
            var count = $("#input-employ_add-count").val();
            var status = $("#select-employ_add-status").val();

            $.ajax({
                url: "/includes/employ/ajax_employ_add.php",
                type: "post",
                data: {
                    code: 1,
                    title: title,
                    type: type,
                    desc: desc,
                    count: count,
                    status: status
                },
                dataType: "json",
                success: function (json) {
                    if (json.resp_code === 0) {
                        showSingleButtonNoty(json.resp_info, "success", "/web/employ/employ_list.php");
                    } else {
                        showSingleButtonNoty(json.resp_info, "error", 0);
                    }
                },
                error: function (textStatus, errorThrown) {
                    console.error(textStatus);
                    console.error(errorThrown);
                    showSingleButtonNoty("服务器内部错误,请稍后重试. 如果此问题重复出现,请联系网站管理人员", "error", 0);
                }
            });
        })
    });
    var types;
</script>
<?php
do_html_end();
?>
