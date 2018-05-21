<?php

require_once '../../includes/all_fns.php';

do_html_header("人才公海");
?>
<div class="container-fluid" style="margin-top: 20px">
    <!-- left area -->
    <?php
    do_sidebar_system();
    ?>
    <!-- right area -->
  <div class="container col-md-10">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="panel-title">
          人才公海
        </div>
      </div>

      <div class="panel-body">
        <!-- search area -->
        <div class="row div-search-area">

          <!-- input栏 -->
          <div class="row row-with-margin-right">
            <div class="col-md-4">
              <input type="text" class="form-control" id="input-search-doc" placeholder="输入简历名称，支持模糊查询"/>
            </div>
            <button class="btn btn-primary" id="search-btn"><span class="glyphicon glyphicon-search"></span>搜索</button>
            <button class="btn btn-success" id="upload-btn"><span class="glyphicon glyphicon-upload"></span>上传简历</button>
          </div>
        </div>

        <div class="row div-with-margin div-margin-top-10">
          <!-- 结果显示表格 -->
          <table class="table table-bordered table-striped table-hover table-condensed" id="result-table">
            <thead>
            <tr>
              <th nowrap class="text-center-align">简历名称</th>
              <th nowrap class="text-center-align">上传时间</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
      </div>
    </div>
  </div>
</div>
</div>

<!-- upload modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="ipUploadModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">简历上传</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-ipUpload">
          <div class="form-group">
            <label  class="col-md-2 control-label" for="file-ipUpload">上传文件</label>
            <div class="col-sm-8 fileInput">
              <div>
                <input type="file" class="fileModule" id="file-ipUpload">
              </div>
              <div id="msg-file-ipUpload" class="fileInput">
                <!-- 显示提示消息的div -->
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" id="file-ipUpload-btn">提交</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
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
<script src="/resource/js/fileUploadUtils.js"></script>
<script>
    $(document).ready(function () {
        hightSidebarItem(5, 0);

        // 设置文件上传规则
        var restrictions = {maxSize: 2 * 1024 * 1024};

        // 初始化文件上传结果
        var result = initialFileResult(restrictions);

        // 绑定上传文件校验
        fastValidateFileInput($("#file-ipUpload"), restrictions, $("#msg-file-ipUpload"), $("#file-ipUpload-btn"), result);

        // 绑定导入按钮 显示modal
        $("#upload-btn").on("click", function () {
            $("#ipUploadModal").modal("show");
        });

        //绑定上传按钮
        $("#file-ipUpload-btn").on("click", function () {
            var file = $("#file-ipUpload")[0].files[0];
            var formData = new FormData();
            formData.append("file",file);
            formData.append("code", "2")
            $.ajax({
                type:'POST',
                url:"/includes/employee_db/ajax_employee_sea.php",
                data:formData,
                contentType: false,
                processData: false,
                cache: false,
                success:function (data) {
                    //show noty
                    var resp_code = data.resp_code;
                    var resp_info = data.resp_info;
                    if (resp_code === 0) {
                        showSingleButtonNoty(resp_info, "success", -1);
                    } else {
                        showSingleButtonNoty(resp_info, "error", -1);
                    }
                },
                error: function (textStatus, errorThrown) {
                    console.error(textStatus);
                    console.error(errorThrown);
                    // Animate loader off screen
                    $(".se-pre-con").fadeOut("fast");
                    showSingleButtonNoty("服务器内部错误,请稍后重试. 如果此问题重复出现,请联系网站管理人员","error", 0);
                },
                beforeSend: function () {
                    // Animate loader on screen
                    $(".se-pre-con").fadeIn("fast");
                },
                dataType:'JSON'
            });
        });

        //绑定查询按钮
        $("#search-btn").on("click", function () {
            var fileName = $("#input-search-doc").val();
            $.ajax({
                url:"/includes/employee_db/ajax_employee_sea.php",
                type:"post",
                dataType:"json",
                data:{
                    code:1,
                    fileName:fileName
                },
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
            })
        })

        //模拟点击事件
        $("#search-btn").trigger("click");

    });

    // 处理查询结果
    function showResult(list) {
        console.log(list);
        var html = "";
        for (var i = 0; i < list.length; i++) {
            var row = list[i];
            html += "<tr>" +
                "<td nowrap class='text-center'>" + row.fileName + "</td>" +
                "<td nowrap class='text-center'>" + row.date + "</td>" +
                "</tr>";
        }

        $("#result-table").children("tbody").html(html);

        // 移除table隐藏
        $("#result-table").removeClass('hidden');
    }
</script>
<?php
do_html_end();
?>
