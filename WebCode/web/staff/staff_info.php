<?php

require_once '../../includes/all_fns.php';
require_once '../../includes/dbconn.php';
require_once '../../includes/staff/sql_staff_list.php';

do_html_header("员工详情");

$staff = getStaffById($_GET['staff_id'], 'all');

?>
<div class="container-fluid" style="margin-top: 20px">
<?php do_sidebar_system();?>
  <div class="row col-md-10">
    <!-- info panel -->
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="panel-title">
          员工信息
        </div>
      </div>
      <div class="panel-body">
        <!-- 基本信息 -->
        <div class="row col-md-12">
          <div class="page-header div-no-margin-top">
            <h4>基本信息</h4>
          </div>
          <!-- 第1行 -->
          <div class="row col-md-8">
            <div class="col-md-2">
              <label>工号</label>
            </div>
            <div class="col-md-10">
              <p><?php echo $staff['number'];?></p>
            </div>
          </div>
          <!-- 第2行 -->
          <div class="row col-md-8">
            <div class="col-md-2">
              <label>姓名</label>
            </div>
            <div class="col-md-4">
              <p><?php echo $staff['name'];?></p>
            </div>
            <div class="col-md-2">
              <label>性别</label>
            </div>
            <div class="col-md-4">
              <p><?php echo $staff['sex'] === "0" ? '男':'女';?></p>
            </div>
          </div>
          <!-- 第3行 -->
          <div class="row col-md-8">
            <div class="col-md-2">
              <label>生日</label>
            </div>
            <div class="col-md-4">
              <p><?php echo date('Y-m-d', strtotime($staff['birthday'])); ?></p>
            </div>
            <div class="col-md-2">
              <label>年龄</label>
            </div>
            <div class="col-md-4">
              <p>24</p>
            </div>
          </div>
        </div>

        <!-- 工作情况 -->
        <div class="row col-md-12">
          <div class="page-header">
            <h4>工作情况</h4>
          </div>
          <div class="row col-md-12">
            <!-- 第1行 -->
            <div class="row col-md-8">
              <div class="col-md-2">
                <label>状态</label>
              </div>
              <div class="col-md-10">
                <div class="btn-group btn-group-xs" role="group">
                  <?php if ($staff['status']  === '0') { ?>
                  <button type="button" class="btn btn-success" disabled>在职</button>
                  <button type="button" class="btn btn-default" disabled>离职</button>
                  <?php } else { ?>
                  <button type="button" class="btn btn-default" disabled>在职</button>
                  <button type="button" class="btn btn-danger" disabled>离职</button>
                  <?php } ?>
                </div>
              </div>
            </div>
            <!-- 第7行 -->
            <div class="row col-md-8">
              <div class="col-md-2">
                <label>入职时间</label>
              </div>
              <div class="col-md-4">
                <p><?php echo local_time_format($staff['in_time'], 'Ymd'); ?></p>
              </div>
              <div class="col-md-2">
                <label>离职时间</label>
              </div>
              <div class="col-md-4">
                <?php
                $str = '--';
                if ($staff['status'] === '1') {
                  $str = local_time_format($staff['out_time'], 'Ymd');
                }
                echo $str;
                ?>
              </div>
            </div>
            <!-- 第2行 -->
            <div class="row col-md-12">
              <div class="col-md-2">岗位</div>
              <div class="col-md-2">
                <p>
                    <?php
                    echo $staff['type_name'];
                    ?>
                </p>
              </div>

              <div class="col-md-2">
                <label>职位</label>
              </div>
              <div class="col-md-2">
                <p>
                    <?php
                    echo $staff['subtype_name'];
                    ?>
                </p>
              </div>

              <div class="col-md-2">
                <label>级别</label>
              </div>
              <div class="col-md-2">
                <p>
                    <?php
                    echo $staff['level_name'];
                    ?>
                </p>
              </div>
            </div>
            <!-- 第5行 -->
            <div class="row col-md-8">
              <div class="col-md-2">
                <label>工资</label>
              </div>
              <div class="col-md-10">
                <!-- todo 添加和工资和级别的联动 在完成工资管理页面后回来处理 -->
                <p>15,000 元/月</p>
              </div>
            </div>
            <!-- 第6行 -->
            <div class="row col-md-8">
              <div class="col-md-2">
                <label>奖金</label>
              </div>
              <div class="col-md-10">
                <div class="btn-group btn-group-xs" role="group">
                  <button type="button" class="btn btn-success" disabled>有</button>
                  <button type="button" class="btn btn-default" disabled>无</button>
                </div>
              </div>
            </div>
          </div>


          <!-- 教育经历 -->


      </div>

        <!-- 教育经历 -->
        <div class="row col-md-12">
          <div class="page-header">
            <h4>教育经历</h4>
          </div>
          <div class="row col-md-12">
            <!-- 第1行 -->
            <div class="row col-md-8">
              <div class="col-md-2">
                <label>学历</label>
              </div>
              <div class="col-md-10">
                <p>
                    <?php
                    switch ($staff['education']) {
                        case '0':
                            echo '博士';
                            break;
                        case '1':
                            echo '硕士';
                            break;
                        case '2':
                            echo '本科';
                            break;
                        case '3':
                            echo '其它';
                            break;
                        default:
                            break;
                    };
                    ?>
                </p>
              </div>
            </div>
            <!-- 第2行 -->
            <div class="row col-md-8">
              <div class="col-md-2">
                <label>毕业院校</label>
              </div>
              <div class="col-md-10">
                <p>
                    <?php
                    if ($staff['education'] !== '3') {
                        echo $staff['school'];
                    } else {
                        echo '--';
                    }
                    ?>
                </p>
              </div>
            </div>
            <!-- 第3行 -->
            <div class="row col-md-8">
              <div class="col-md-2">
                <label>专业</label>
              </div>
              <div class="col-md-10">
                <p>
                    <?php
                    if ($staff['education'] !== '3') {
                        echo $staff['major'];
                    } else {
                        echo '--';
                    }
                    ?>
                </p>
              </div>
            </div>
          </div>
        </div>
    </div>

  </div>
</div>
<?php
do_html_footer();
?>
<link rel="stylesheet" href="/resource/css/bootstrap-select.min.css">
<script src="/resource/js/bootstrap-select.min.js"></script>
<script src="/resource/js/i18n/defaults-zh_CN.min.js"></script>
<style>
  td div.visible {
    visibility: visible;
  }
</style>
<script src="/resource/js/md5.min.js"></script>
<?php if ($GLOBALS['system_id'] == 1){ ?>
  <script src="/resource/js/fileUploadUtils.js"></script>
<?php } ?>
<script>
    $(document).ready(function () {

        hightSidebarItem(2, 0);

    });

</script>
<?php
do_html_end();
?>
