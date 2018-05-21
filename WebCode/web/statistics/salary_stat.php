<?php
require_once '../../includes/all_fns.php';

do_html_header("薪酬统计-人力资源管理平台");
?>
<div class="container-fluid" style="margin-top: 20px">
    <?php
    do_sidebar_system();
    ?>
  <div class="container col-md-10">

    <div class="col-md-6">
      <div id="chart1" class="chartdiv"></div>
    </div>

    <div class="col-md-6">
      <div id="chart2" class="chartdiv"></div>
    </div>

  </div>

</div>
<style>
  .chartdiv {
    margin-left: 5px;
    margin-right: 5px;
    height: 400px;
    background-color: white;
    border: solid;
    border-color: #CCCCCC;
    border-width: 1px;
  }
</style>
<?php
do_html_footer();
?>
<link rel="stylesheet" href="/resource/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/resource/css/bootstrap-datepicker3.standalone.min.css">
<link rel="stylesheet" href="/resource/css/magicsuggest-min.css">
<script src="/resource/js/bootstrap-datepicker.min.js"></script>
<script src="/resource/js/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="/resource/js/magicsuggest.js"></script>
<script src="/resource/js/echarts.js"></script>
<script>
    $(document).ready(function () {
        hightSidebarItem(4, 0);
        $.ajax({
            url:"/includes/statistic/ajax_salary_stat.php",
            data:{code:1},
            dataType:'json',
            type:"post",
            success:function (json) {
                var myChart1 = echarts.init(document.getElementById('chart1'));

                console.log(json);
                var option1 = {
                    title : {
                        text: '公司工资构成',
                        subtext : '单位：元/月',
                        x:'center',
                        top:20
                    },
                    tooltip : {
                        trigger: 'item',
                        formatter: "{a} ：{b} 元/月 <br/> 数量： {c}<br/> 占比：({d}%)"
                    },
                    legend: {
                        orient: 'vertical',
                        left: 'right'
                    },
                    series : [
                        {
                            name: '收入区间',
                            type: 'pie',
                            radius : '55%',
                            center: ['50%', '60%'],
                            data:json.resp_desc,
                            itemStyle: {
                                emphasis: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                };

                // 使用刚指定的配置项和数据显示图表。
                myChart1.setOption(option1);


            }
        });

        $.ajax({
            url:"/includes/statistic/ajax_salary_stat.php",
            data:{code:2},
            dataType:'json',
            type:"post",
            success:function (json) {
                var myChart2 = echarts.init(document.getElementById('chart2'));
                var option2 = {
                    title : {
                        text: '各部门员工人数',
                        subtext : '单位：人',
                        x:'center',
                        top:20
                    },
                    color: ['#6edaee'],
                    tooltip : {
                        trigger: 'item',
                        formatter: "{b}:<br/>{c}人"

                    },
                    grid : {
                        top:100
                    },
                    xAxis: {
                        type: 'category',
                        data: json.resp_desc.types
                    },
                    yAxis: {
                        type: 'value',
                        minInterval:1
                    },
                    series: [{
                        name: '人数',
                        data: json.resp_desc.numbers,
                        type: 'bar'
                    }]
                };
                myChart2.setOption(option2);
            }
        });
    });
</script>
<?php
do_html_end();
?>
