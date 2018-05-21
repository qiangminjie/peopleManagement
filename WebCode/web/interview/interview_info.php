<?php

require_once '../../includes/all_fns.php';

do_html_header("面试人员资料-人力资源管理平台");
?>
<div class="container-fluid" style="margin-top: 20px">
    <!-- left area -->
    <?php
    do_sidebar_system();
    ?>
    <!-- right area -->
    <div class="container col-md-10">
        <!-- panel -->
        <div class="panel panel-default">
            <!-- penal heading -->
            <div class="panel-heading clearfix">
                <div class="panel-title">
                    面试人员
                </div>
            </div>
            <!-- panel body -->
            <div class="panel-body">
                <!-- search area -->
                <div class="row div-search-area">
                    <!-- title row -->
                    <div class="row row-with-margin-right">
                        <div class="col-md-2">
                            <label>面试人编号</label>
                        </div>
                        <div class="col-md-2">
                            <label>姓名</label>
                        </div>
                        <div class="col-md-2">
                            <label id="label-type">岗位</label>
                        </div>
                        <div class="col-md-2">
                            <label id="label-subtype">类型</label>
                        </div>
                        <div class="col-md-2">
                            <label id="label-level">级别</label>
                        </div>
                    </div>
                    <!-- input row -->
                    <div class="row row-with-margin-right">
                        <div class="col-md-2">
                            <input id="number" class="form-control"/>
                        </div>
                        <div class="col-md-2">
                            <input id="name" class="form-control"/>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="type">
                                <option value="-1">全部</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="subtype">
                                <option value="-1">全部</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="level">
                                <option value="-1">全部</option>
                            </select>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-success" id="create-btn">新增</a>
                            <a class="btn btn-primary" id="search-btn">查询</a>
                        </div>
                    </div>
                </div>
                <!-- result table -->
                <div class="row div-with-margin div-margin-top-10">
                    <table id="result-table" class="table table-bordered table-striped table-hover table-condensed">
                        <!-- thead -->
                        <thead>
                        <tr>
                            <th nowrap class="text-center-align">面试人编号</th>
                            <th nowrap class="text-center-align">姓名</th>
                            <th nowrap class="text-center-align">岗位</th>
                            <th nowrap class="text-center-align">职位</th>
                            <th nowrap class="text-center-align">等级</th>
                            <th nowrap class="text-center-align">状态</th>
                            <th nowrap class="text-center-align">更多</th>
                        </tr>
                        </thead>
                        <!-- tbody -->
                        <tbody></tbody>
                    </table>
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
<style>
    .modal-body-row{
        padding-left: 15px;
        padding-right: 15px;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .modal-subtitle{
        border-bottom: solid;
        border-bottom-width: 1px;
        border-bottom-color: #e5e5e5;
        padding-left: 0px;
    }
</style>

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
                    trippleLinkage(types, subtypes, levels, $("#type"), $("#subtype"), $("#level"), $("#label-type"), $("#label-subtype"), $("#label-level"), true);

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

        // 点击查询按钮后动作
        $("#search-btn").on("click", function () {
            var number = $("#number").val();
            var name = $("#name").val();
            var type = $("#type").val() === '-1' ? null: $("#type").val();
            var subtype = $("#subtype").val() === '-1' ? null: $("#subtype").val();
            var level = $("#level").val() === '-1' ? null: $("#level").val();

            // 发送查询请求
            $.ajax({
                url: '/includes/interview/ajax_interview_list.php',
                type: 'POST',
                data: {code:0, type: 2, number: number, name: name, type: type, subtype: subtype, level: level,isoffer : -1},
                dataType: 'JSON',
                beforeSend: function () {
                    // fade in
                    $(".se-pre-con").fadeIn();
                },
                success: function (json) {
                    // fade out
                    $(".se-pre-con").fadeOut();

                    switch (json.resp_code) {
                        case 0:
                            showResult(json.resp_info);
                            break;
                        case 1:
                            $("#result-table").addClass('hidden');
                            showSingleButtonNoty(json.resp_info, "warning", 0);
                            break;
                        default:
                            $("#result-table").addClass('hidden');
                            showSingleButtonNoty(json.resp_info, "error", 0);
                            break;
                    }
                },
                error: function (textStatus, errorThrown) {
                    // fade out
                    $(".se-pre-con").fadeOut();
                    showSingleButtonNoty("服务器内部错误,请稍后重试. 如果此问题重复出现,请联系网站管理人员", "error", 0);
                    console.error(textStatus);
                    console.error(errorThrown);
                }
            });
        });

        // 点击新增按钮后动作
        $("#create-btn").on("click", function () {
            window.location.href = 'interview_import.php';
        });

        // 模拟点击事件
        $("#search-btn").trigger('click');


    });

    var types, subtypes, levels;

    // 处理查询结果
    function showResult(list) {

        var html = "";
        for (var i = 0; i < list.length; i++) {
            var row = list[i];
            html += "<tr>" +
                "<td nowrap class='text-center'>" + row.number + "</td>" +
                "<td nowrap class='text-center'>" + row.name + "</td>" +
                "<td nowrap class='text-center'>" + getTypeName(row.type) + "</td>" +
                "<td nowrap class='text-center'>" + getSubtypeName(row.type, row.subtype) + "</td>" +
                "<td nowrap class='text-center'>" + getLevelName(row.type, row.subtype, row.level) + "</td>" +
                "<td nowrap class='text-center'>" + getStatus(row.status) + "</td>" +
                "<td nowrap class='text-center'>" +
                "<div class='btn-group'>" +
                "<button class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>操作<span class='caret'></span></button>" +
                "<ul class='dropdown-menu'>" +
                "<li class='staff-update'  staff_id='"+ row.id +"'><a>发送面试邀请</a></li>" +
                "</ul>" +
                "</div>" +
                "</td>" +
                "</tr>";
        }

        $("#result-table").children("tbody").html(html);
        // 添加绑定事件
        // 1 操作按钮的显示
        $("tbody").children("tr").on("mouseover", function () {
            $(this).children("td:eq(6)").children("div:eq(0)").css("visibility","visible");
        });
        $("tbody").children("tr").on("mouseleave", function () {
            $(this).children("td:eq(6)").children("div:eq(0)").css("visibility","hidden");
        });


        // 2 修改按钮
        $(".staff-update").click(function () {
            var id = $(this).attr("staff_id");
            $.ajax({
                url: '/includes/interview/ajax_interview_list.php',
                type: 'POST',
                data: {code:6, isoffer:2 ,id:id },
                dataType: 'JSON',

                success: function (json) {
                    alert("邀约成功");
                    window.location.href = "/web/interview/interview_info.php";
                },
                error: function (textStatus, errorThrown) {
                    showSingleButtonNoty("服务器内部错误,请稍后重试. 如果此问题重复出现,请联系网站管理人员", "error", 0);
                    console.error(textStatus);
                    console.error(errorThrown);
                }
            });
        });


        // 移除table隐藏
        $("#result-table").removeClass('hidden');
    }

    // TODO 表单校验
    function checkCreateForm() {
        return true;
    }
</script>
<?php
do_html_end();
?>
