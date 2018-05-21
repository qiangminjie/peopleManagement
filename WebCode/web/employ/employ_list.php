<?php

require_once '../../includes/all_fns.php';

do_html_header("招聘列表-人力资源管理平台");
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
                    招聘需求列表
                </div>
            </div>
            <!-- panel body -->
            <div class="panel-body">
                <!-- search area -->
                <div class="row div-search-area">
                    <!-- title row -->
                    <div class="row row-with-margin-right">
                        <div class="col-md-2">
                            <label>ID</label>
                        </div>
                        <div class="col-md-2">
                            <label>岗位</label>
                        </div>
                        <div class="col-md-2">
                            <label id="label-type">状态</label>
                        </div>
                    </div>
                    <!-- input row -->
                    <div class="row row-with-margin-right">
                        <div class="col-md-2">
                            <input id="input-employ_list-id" class="form-control" placeholder="输入需求id"/>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="select-employ_list-type">
                                <option value="-1">全部</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="select-employ_list-status">
                                <option value="-1">全部</option>
                                <option value="0">审核中</option>
                                <option value="1">招聘中</option>
                                <option value="2">已结束</option>
                                <option value="3">已驳回</option>
                            </select>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-success" id="create-btn" href="employ_add.php">新增</a>
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
                            <th class="text-center-align">ID</th>
                            <th class="text-center-align">需求名称</th>
                            <th class="text-center-align">岗位</th>
                            <th class="text-center-align">需求描述</th>
                            <th class="text-center-align">人数</th>
                            <th class="text-center-align">状态</th>
                            <th class="text-center-align">操作</th>
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
        hightSidebarItem(1, 0);

        // load datepicker
        $('.input-group.date').datepicker({
            language: "zh-CN",
            autoclose: true,
            todayHighlight: true,
        }).datepicker("setDate", "0");

        //load type
        $.ajax({
            url:"/includes/staff/ajax_staff_list.php",
            type:"post",
            data:{code:1},
            dataType:"json",
            success:function (json) {
                if (json.resp_code === 0) {
                    types = json.resp_info.types;
                    resetType(types, $("#select-employ_list-type"));
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
            var id = $("#input-employ_list-id").val();
            var type = $("#select-employ_list-type").val() === '-1' ? null: $("#select-employ_list-type").val();
            var status = $("#select-employ_list-status").val() === '-1' ? null: $("#select-employ_list-status").val();

            // 发送查询请求
            $.ajax({
                url: '/includes/employ/ajax_employ_list.php',
                type: 'POST',
                data: {
                    code : 1,
                    id : id,
                    type : type,
                    status : status
                },
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
                            //console.log(json);
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

        // 点击查询按钮后动作
        $("#create-btn").on("click", function () {
            window.location.href = './import_staff.php';
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
                "<td nowrap class='text-center'>" + row.id + "</td>" +
                "<td nowrap class='text-center'>" + row.title + "</td>" +
                "<td nowrap class='text-center'>" + getTypeName(row.type) + "</td>" +
                "<td nowrap class='text-center'>" + row.desc + "</td>" +
                "<td nowrap class='text-center'>" + row.count + "</td>" +
                "<td nowrap class='text-center'>" + getEmployStatus(row.status) + "</td>" ;

            if (row.status === '0') {
                html += "<td nowrap class='text-center'>" +
                    "<div class='btn-group'>" +
                    "<button class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>操作<span class='caret'></span></button>" +
                    "<ul class='dropdown-menu'>" +
                    "<li class='employ_add-status-to-1'  employ_id='"+ row.id +"'><a>通过</a></li>" +
                    "<li class='employ_add-status-to-3'  employ_id='"+ row.id +"'><a>驳回</a></li>" +
                    "</ul>" +
                    "</div>" +
                    "</td>" +
                    "</tr>";
            } else if (row.status == '1') {
                html += "<td nowrap class='text-center'>" +
                    "<div class='btn-group'>" +
                    "<button class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>操作<span class='caret'></span></button>" +
                    "<ul class='dropdown-menu'>" +
                    "<li class='employ_add-status-to-2'  employ_id='"+ row.id +"'><a>结束</a></li>" +
                    "</ul>" +
                    "</div>" +
                    "</td>" +
                    "</tr>";
            } else {
                html += "<td></td></tr>";
            }
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

        
        $(".employ_add-status-to-1").click(function () {
            var id = $(this).attr("employ_id");
            changeStatusToN(id, 1)
        });
        $(".employ_add-status-to-2").click(function () {
            var id = $(this).attr("employ_id");
            changeStatusToN(id, 2)
        });
        $(".employ_add-status-to-3").click(function () {
            var id = $(this).attr("employ_id");
            changeStatusToN(id, 3)
        });

        // 移除table隐藏
        $("#result-table").removeClass('hidden');
    }

    function changeStatusToN(id,n) {
        $.ajax({
            url:"/includes/employ/ajax_employ_list.php",
            type:"post",
            data:{code: 2, id: id, status: n},
            dataType:"json",
            success:function (json) {
                $(".se-pre-con").fadeOut();
                //console.log(json);
                showSingleButtonNoty(json.resp_info, "success", -1);
            },
            error:function (textStatus, errorThrown) {
                $(".se-pre-con").fadeOut();
                showSingleButtonNoty("服务器内部错误,请稍后重试. 如果此问题重复出现,请联系网站管理人员", "error", 0);
                console.error(textStatus);
                console.error(errorThrown);
            },
            beforeSend:function () {
                $(".se-pre-con").fadeIn();
            }
        });
    }
</script>
<?php
do_html_end();
?>
