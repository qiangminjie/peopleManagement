/**
 * Created by Luo Shengjie on 2017/5/3.
 */
/**
 * 根据传入的数据绘制柱状图
 * @param xArray    x轴(经过反向排序的)时间数组
 * @param yArray    y轴表格数据数组
 */
function drawD3BarChart(xArray, yArray, systemId) {
    d3.select("div#main_area").selectAll("svg").remove();

    var margin = {top: 15, right: 20, bottom: 15, left: 80},
        width = 970 - margin.left - margin.right,
        height = 145 - margin.top - margin.bottom;

    // 在柱状图顶部添加legend
    var legend = d3.select("div#main_area")
        .append("svg")
        .attr("preserveAspectRatio", "xMinYMin meet")
        // .attr("viewBox", "0 0 1260 30")
        .classed("svg-content", true)
        .attr("width", width + 50)
        .attr("height", 30)
        .append("g")
        .attr("font-family", "sans-serif")
        .attr("font-size", 10)
        // .attr("text-anchor", "end")
        .selectAll("g");
    if (systemId == 1) {
        legend = legend.data(["提交总数", "收到状态报告数", "状态报告等待数", "提交失败/状态报告失败"])
            .enter()
            .append("g")
            .attr("transform", function (d, i) {
                return "translate(" + (width - 400 + i * 50) + ",10)";
            });
    }
    else {
        legend = legend.data(["提交总数", "提交失败"]).enter()
            .append("g")
            .attr("transform", function (d, i) {
                return "translate(" + (width - 400 + i * 50) + ",10)";
            });
    }

    legend = legend.append("rect")
        .attr("x", function (d, i) {
            return i * 50;
        })
        .attr("width", 19)
        .attr("height", 19);
    if (systemId == 1) {
        legend = legend.attr("fill", function (d, i) {
            return (function (e) {
                return ["#31bd00", "#00a1e9", "#ff0000", "#c0c0c0"][e];
            })(i);
        });
    } else {
        legend = legend.attr("fill", function (d, i) {
            return (function (e) {
                return ["#31bd00", "#c0c0c0"][e];
            })(i);
        });
    }
    legend.append("text")
        .attr("x", function (d, i) {
            return i * 50 + 25;
        })
        .attr("y", 9.5)
        .attr("dy", "0.32em")
        .text(function (d) {
            return d;
        });

    // 添加3个SVG对象

    if (systemId == 1) {
        var svg0 = d3.select("div#main_area")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet")
            // .attr("viewBox", "0 0 1260 230")
            .classed("svg-content", true)
            .attr("width", width + 90)
            .attr("height", height + 30)
            .attr("display", "block")
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        var svg1 = d3.select("div#main_area")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet")
            // .attr("viewBox", "0 0 1260 230")
            .classed("svg-content", true)
            .attr("width", width + 90)
            .attr("height", height + 30)
            .attr("display", "block")
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
        var svg2 = d3.select("div#main_area")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet")
            // .attr("viewBox", "0 0 1260 230")
            .classed("svg-content", true)
            .attr("width", width + 90)
            .attr("height", height + 86)
            .attr("display", "block")
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    } else {
        var svg0 = d3.select("div#main_area")
            .append("svg")
            .attr("preserveAspectRatio", "xMinYMin meet")
            // .attr("viewBox", "0 0 1260 230")
            .classed("svg-content", true)
            .attr("width", width + 90)
            .attr("height", height + 86)
            .attr("display", "block")
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    }

    // x轴
    var xScale = d3.scaleBand()
        .domain(xArray.map(function (d) {
            return d;
        }))
        .range([0, width])
        .padding(0.07);
    // y轴，分别对应3个svg

    var yScale0 = d3.scaleLinear()
        .domain([0, d3.max(yArray, function (d) {
            return d[1] + d[2];
        })])
        .range([height, 0]);
    if (systemId == 1) {
        var yScale1 = d3.scaleLinear()
            .domain([0, d3.max(yArray, function (d) {
                return d[3] + d[4];
            })])
            .range([height, 0]);
        var yScale2 = d3.scaleLinear()
            .domain([0, d3.max(yArray, function (d) {
                return d[5];
            })])
            .range([height, 0]);
    }

    // 设置柱状图堆叠
    var series0 = d3.stack().keys([1, 2]).order(d3.stackOrderReverse)(yArray);
    var series1 = d3.stack().keys([3, 4]).order(d3.stackOrderReverse)(yArray);

    // 为3个svg分别创建tip
    var tip0 = d3.tip()
        .attr('class', 'd3-tip')
        .offset([-10, 0])
        .html(function (d) {
            return "<span style='color:black'>提交总数: " + (d['data'][2] + d['data'][1]) +
                "<br/>提交失败数: " + d['data'][1] +
                "<br/>提交成功率: " + (d['data'][2] != 0 ?
                    ((d['data'][2] / (d['data'][1] + d['data'][2]) * 100).toLocaleString(undefined, {maximumFractionDigits: 2}) + '%') : '-') + "</span>";
        });
    if (systemId == 1) {
        var tip1 = d3.tip()
            .attr('class', 'd3-tip')
            .offset([-10, 0])
            .html(function (d) {
                return "<span style='color:black'>收到状态报告总数: " + (d['data'][4] + d['data'][3]) +
                    "<br/>状态报告失败数: " + d['data'][3] +
                    "<br/>状态报告成功率: " + (d['data'][4] != 0 ?
                        ((d['data'][4] / (d['data'][3] + d['data'][4]) * 100).toLocaleString(undefined, {maximumFractionDigits: 2}) + '%') : '-') + "</span>";
            });
        var tip2 = d3.tip()
            .attr('class', 'd3-tip')
            .offset([-10, 0])
            .html(function (d) {
                return "<span style='color:black'>状态报告等待数: " + d + "</span>";
            });
    }
    svg0.call(tip0);
    if (systemId == 1) {
        svg1.call(tip1);
        svg2.call(tip2);
    }

    // 绘制3个矩形，分别对应3个SVG
    var rect0 = svg0.append("g")
        .selectAll("g")
        .data(series0)
        .enter()
        .append("g")
        .attr("fill", function (d) {
            return d.key == 1 ? "#c0c0c0" : "#31bd00";
        })
        .selectAll("rect")
        .data(function (d) {
            return d;
        })
        .enter()
        .append("rect")
        .attr("class", "MyRect")
        .attr("x", function (d, i) {
            return i * (width / xArray.length);
        })
        .attr("y", function (d, i) {
            return yScale0(d[1]);
        })
        .attr("width", xScale.bandwidth())
        .attr("height", function (d) {
            return yScale0(d[0]) - yScale0(d[1]);
        })
        .on("mouseover", function (d) {
            tip0.show(d);
        })
        .on("mouseout", function (d) {
            tip0.hide(d);
        });
    if (systemId == 1) {
        var rect1 = svg1.append("g")
            .selectAll("g")
            .data(series1)
            .enter()
            .append("g")
            .attr("fill", function (d) {
                return d.key == 3 ? "#c0c0c0" : "#00a1e9";
            })
            .selectAll("rect")
            .data(function (d) {
                return d;
            })
            .enter()
            .append("rect")
            .attr("class", "MyRect")
            .attr("x", function (d, i) {
                return i * (width / xArray.length);
            })
            .attr("y", function (d, i) {
                return yScale1(d[1]);
            })
            .attr("width", xScale.bandwidth())
            .attr("height", function (d) {
                return yScale1(d[0]) - yScale1(d[1]);
            })
            .on("mouseover", function (d) {
                tip1.show(d);
            })
            .on("mouseout", function (d) {
                tip1.hide(d);
            });
        var rect2 = svg2.selectAll("rect")
            .data(yArray.map(function (d) {
                return d[5];
            }))
            .enter()
            .append("rect")
            .attr("class", "MyRect")
            .attr("x", function (d, i) {
                return i * (width / xArray.length);
            })
            .attr("y", function (d, i) {
                return yScale2(d);
            })
            .attr("width", xScale.bandwidth())
            .attr("height", function (d) {
                return height - yScale2(d);
            })
            .attr("fill", "#ff0000")
            .on("mouseover", function (d) {
                tip2.show(d);
            })
            .on("mouseout", function (d) {
                tip2.hide(d);
            });
    }

    // 添加x轴
    if (systemId == 1) {
        svg2.append("g")
            .attr("class", "axis")
            .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(xScale))
            // 设置坐标数值旋转90度
            .selectAll("text")
            .attr("y", 0)
            .attr("x", 9)
            .attr("dy", ".35em")
            .attr("transform", "rotate(90)")
            .style("text-anchor", "start");
    } else {
        svg0.append("g")
            .attr("class", "axis")
            .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(xScale))
            // 设置坐标数值旋转90度
            .selectAll("text")
            .attr("y", 0)
            .attr("x", 9)
            .attr("dy", ".35em")
            .attr("transform", "rotate(90)")
            .style("text-anchor", "start");
    }


    // 添加y轴
    svg0.append("g")
        .attr("class", "axis")
        .call(d3.axisLeft(yScale0).ticks(6));   // 自定义刻度数量
    if (systemId == 1) {
        svg1.append("g")
            .attr("class", "axis")
            .call(d3.axisLeft(yScale1).ticks(6));
        svg2.append("g")
            .attr("class", "axis")
            .call(d3.axisLeft(yScale2).ticks(6));
    }
    // svg2.append("g")
    //     .attr("class", "axis")
    //     .call(d3.axisLeft(yScale2).ticks(null, "s"));   // 自定义刻度精度

    // y轴旁添加文字说明
    svg0.append("text")
        .attr("transform", "rotate(-90)")
        .attr("x", 0 - (height / 2))
        .attr("y", 0 - margin.left)
        .attr("dy", "1em")
        .style("text-anchor", "middle")
        .text("提交总数");
    if (systemId == 1) {
        svg1.append("text")
            .attr("transform", "rotate(-90)")
            .attr("x", 0 - (height / 2))
            .attr("y", 0 - margin.left)
            .attr("dy", "1em")
            .style("text-anchor", "middle")
            .text("收到状态报告数");
        svg2.append("text")
            .attr("transform", "rotate(-90)")
            .attr("x", 0 - (height / 2))
            .attr("y", 0 - margin.left)
            .attr("dy", "1em")
            .style("text-anchor", "middle")
            .text("状态报告等待数");
    }
}
