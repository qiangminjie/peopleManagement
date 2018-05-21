/***************************************************************************************************
 * File Description:
 *  通用工具类
 * Created by   Michael Lee            lipeng@microwu.com           11/21/16 19:03
 * CopyRight    北京小悟科技有限公司      http://www.microwu.com
 * Methods:
 *  isMobilePhone                   返回输入的是否和合法的字符串
 *  isValidAccount                  输入账号是否合法
 *  isValidDecimalArray             输入数组中的小数是否为合法小数，小数位数最长3位
 *  isValidDecimal                  是否是小于3.0的合法小数
 *  isValidNumber                   是否是整数或小数
 *  showSingleButtonNoty            弹出只有1个确认按钮的对话框
 *  hightSidebarItem                高亮显示菜单栏中的特定项目
 *  numberWithCommas                格式化数字，千分位加上,分开
 *  numberRemoveCommas              将格式化的数字删除逗号，并返还数字
 *  randomStringWithLenght          从指定的数组中随机生成指定长度的字符串
 *  ip2long                         ip地址转换成long型
 *  ip_range                        两个ip地址之间个数
 *  pressEnterKeyToSearch           搜索页面输入框回车后自动搜索
 * Updated History:
 * Author       Date            Content
 * Regardo      17/12/8         增加方法numRemoveCommas
/**************************************************************************************************/

/**
 * Method Description:
 *  返回匹配结果是否是合法手机号码
 *  手机号码:
 *  13[0-9], 14[5,7], 15[0, 1, 2, 3, 5, 6, 7, 8, 9], 17[0, 1, 6, 7, 8], 18[0-9]
 *  移动号段: 134,135,136,137,138,139,147,150,151,152,157,158,159,170,178,182,183,184,187,188
 *  联通号段: 130,131,132,145,155,156,170,171,175,176,185,186
 *  电信号段: 133,149,153,170,173,177,180,181,189
 *
 * Created by   Michael Lee            lipeng@microwu.com           11/21/16 19:04
 * @param       $mobile     String      手机号码
 * @param       $type       int         类型 0 = 全网; 1 = 移动, 2 = 联通, 3 = 电信
 */
function isMobilePhone($mobile, $type) {
    if ($mobile.length != 11) return false;
    switch ($type) {
        case 0:
            // 全网号段
            return /^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\d{8}$/.test($mobile);
        case 1:
            // 移动号段
            return /^1(3[4-9]|4[7]|5[0-27-9]|7[08]|8[2-478])\d{8}$/.test($mobile);
        case 2:
            // 联通号段
            return /^1(3[0-2]|4[5]|5[56]|7[0156]|8[56])\d{8}$/.test($mobile);
        case 3:
            // 电信号段
            return /^1(3[3]|4[9]|53|7[037]|8[019])\d{8}$/.test($mobile);
        default:
            return false;
    }
}

/**
 * Method Description:
 *  判断账号输入是否合法，合法账号长度5-16位，且仅能包含 数字、字母及下划线
 * Created by   Michael Lee            lipeng@microwu.com           2/10/2017, 10:54:02 AM
 * @param       $account        String      输入的账号
 * @return      boolean         true = 合法； false = 非法
 */
function isValidAccount(account) {
    return /^[a-zA-Z0-9_]{5,16}$/.test(account);
}

/**
 * Method Description:
 *  判断输入是否合法的ip地址
 * Created by   Michael Lee            lipeng@microwu.com           6/9/2017, 2:01:10 PM
 * @param       ipaddress       String      输入的ip地址
 * @return      boolean         true = 合法； false = 非法
 */
function isValidIpAddress(ipaddress) {
    if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress)) {
        return true;
    }
    return false;
}

/**
 * 校验密码格式
 * Created By Yanghao    yanghao@microwu.com          10/24/2017  15:32
* @param password    需要判断的字符串
 * @param start      内容的最低位数
 * @param end        内容的最高位数
 * @param type       内容所必需的数据类型
 *                   1：大写字母+小写字母+数字+特殊字符
 *                   2. 大写字母+小写字母+数字
 *                   3.字母+数字
 *                   4.纯字母
 *                   5.纯数字
 *                   6.无限制
 * return            true  符合规则
 *                   false 不符合规则
 */

function isValidPassword(password,start,end,type){
    var str = '';
    switch(type){
        case 1:
            str = "^(?![a-zA-Z0-9]+$)(?![^a-zA-Z/D]+$)(?![^0-9/D]+$).{" +start+","+end+"}$";
            break;
        case 2:
            str="^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z]).{"+start+","+end+"}$";
            break;
        case 3:
            str="^(?=.*\\d)(?=.*[a-zA-Z]).{"+start+","+end+"}$";
            break;
        case 4:
            str="^[a-zA-Z]{"+start+","+end+"}$";
            break;
        case 5:
            str="^\\d{" +start+","+end+"}$";
            break;
        case 6:
            str="/^\\S{"+start+","+end+"}$/";
            break;
        default:
            return false;
    }
    var patt = new RegExp(str);
    return patt.test(password);
}

/**
 * Method Description:
 *  判断输入的计价数组中内容是否都是合法的小数，合法的固定值小于3；合法的比率为小于100的数字
 * Created by   Michael Lee            lipeng@microwu.com           2/10/2017, 10:54:02 AM
 * @param       $account        array        包含费率的数组
 * @return      boolean         true = 合法； false = 非法
 */
function isValidPricelArray(array) {
    for (var i = 0; i < array.length; i++) {
        if (!isValidPriceNum(array[i].bill_rate, 0) || !isValidPriceNum(array[i].bill_num, 1) ||
            !isValidPriceNum(array[i].comm_rate,0) || !isValidPriceNum(array[i].comm_num,1)) {
            return false;
        }
    }
    return true;
}

/**
 * Method Description:
 *  判断输入的是否为合法的价格，-1也认定为合法的，其中可以指定数字的最大值
 * Created by   Michael Lee            lipeng@microwu.com           2/10/2017, 10:54:02 AM
 * @param       value           要判断的数字
 * @param       type            0 = 折扣和佣金比率值，0-100,可以为-1; 1 = 固定值，0-3
 * @param       max_num         数字的最大值
 * @return      boolean         true = 合法； false = 非法
 */
function isValidPriceNum(value, type) {
    switch (type) {
        case 0:
            if (value == -1 || value == -2) return true;
            if (value > 10000) return false;
            return isValidNumber(value);
        case 1:
            if (value > 3000) return false;
            return isValidNumber(value);
        default:
            break;
    }
}
// 是否是合法的数字，可以是整数或小数
function isValidNumber(value) {
    return /^\d*\.?\d{1,3}$/.test(value);
}

/**
 * Method Description:
 *  弹出只有确认按钮的noty对话框
 * Created by   Michael Lee            lipeng@microwu.com           2/10/2017, 10:54:02 AM
 * @param       message         String        提示信息
 * @param       type            String        类型
 * @param       url             String        点击后要跳转的页面； 0 = 不跳转； -1 = 刷新当前页面
 */
function showSingleButtonNoty(message, type, url) {
    noty({
        text        : message,
        type        : type,
        dismissQueue: true,
        layout      : "center",
        modal       : true,
        theme       : "relax",
        animation   : {
              open  : 'animated flipInX',
              close : 'animated flipOutX',
              easing: 'swing',
              speed : 500
        },
        buttons     : [
          {addClass: "btn btn-primary btn-sm", text: "确定", onClick: function ($noty) {
                $noty.close();
                if (url !== 0 && url !== -1) {
                    window.top.location.assign(url);
                } else {
                    if (url === -1) {
                        window.top.location.reload();
                    }
                }
              }
          }
        ]
    });
}

/**
 * 页面加载时,设置侧边菜单栏的打开及高亮的条目
 * Created by   Michael Lee            lipeng@microwu.com           2/9/2017, 8:36:54 AM
 * @param out_index     外部大框架的index号
 * @param in_index      每个大框架中条目的不同item index号
 */
function hightSidebarItem(out_index, in_index) {
    if (out_index < 0) return -1;

    var $el = $("#accordion").find(".submenu").eq(out_index);
    $el.slideDown(10).parent().addClass('open');

    var $el_inner = $el.find('li a').eq(in_index);
    if (in_index >= 0) {
        $el_inner.addClass('hightlight');
        $el_inner.attr('href', '#');
    }
}

/**
 * 格式化数字，千分位加上逗号
 * Created by   Michael Lee            lipeng@microwu.com           4/17/2017, 3:20:52 PM
 * @param x             待处理数字
 * @param length        小数点后保留几位  0 = 不保留，
 */
function numberWithCommas(x, length) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    if (length === 0) {
        return parts[0];
    }
    if (parts.length == 1) {
        parts.push('0000000000');
    } else {
        parts[1] = parts[1] + "00000";
    }
    parts[1] = parts[1].substring(0,length);
    return parts.join(".");
}

/**
 * 将格式化的数字删除逗号，并返还数字
 * Created by   Regardo            wwsalt@microwu.com           12/8/2017, 16:22:52 PM
 * @param numStr 格式化后的字符串
 * @param isFloat 是浮点类型还是整数类型 true=浮点型 false=非浮点型
 * @param length 小数点后保留几位
 */
function numberRemoveCommas(numStr,isFloat,length){
    var fix_length = arguments[2] ? arguments[2] : 3;
    var num = numStr.replace(/,/g,"");
    if (isFloat) {
        return parseFloat(num).toFixed(fix_length);
    } else {
        return parseInt(num);
    }
}


/**
 * 从指定的字符床中，随机生成指定长度的字符串
 * Created by   Michael Lee            lipeng@microwu.com           6/3/2017, 7:21:36 PM
 * @param type               类型，0 = default，字符串组为字母和数字，1 = 指定字符串，已array作为输入字符串
 * @param array              指定的字符串
 * @param length             生成字符串的长度
 */
function randomStringWithLenght(type, array, length) {
    var text = "";
    var default_str = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz123456789123456789123456789";
    if (type) {
        default_str = array;
    }

    for( var i=0; i < length; i++ )
        text += default_str.charAt(Math.floor(Math.random() * default_str.length));
    return text;
}

/**
 * ip地址转换成long型
 * @param   String      ip_str      ip地址
 * @return  long
 * Auther       Time
 */
function ip2long(ip_str) {
    var parts = ip_str.split( "." );
    return parts.reduce(function( x, y ) {
        return (+y) + x * 256; //Note: the unary '+' prefix operator casts the variable to an int without the need for parseInt()
    });
}

/**
 * 返回两个ip地址之间的数量
 * @param   String      ip_1    起始ip地址
 * @param   String      ip_2    终止ip地址
 * @return  两个ip地址之间的个数
 * Auther       Time
 */
function ip_range( ip_1, ip_2 ){
    var ip1 = ip2long( ip_1 );
    var ip2 = ip2long( ip_2 );
    return 1 + ip2 - ip1;
}

/**
 * 搜索页面的input敲回车等于点击搜索按钮
 * Created by   Luo Shengjie           shengjie.luo@microwu.com          8/21/2017, 2:25:32 PM
 */
function pressEnterKeyToSearch() {
    $('input.search').keyup(function (e) {
        if (e.keyCode == 13) {
            $('#search-btn').click();
        }
    });

    $('input#page_input').keyup(function (e) {
        if (e.keyCode == 13) {
            $('#jump_btn').click();
        }
    });
}
