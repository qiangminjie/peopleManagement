

/**
 * Method Description:
 *  使得页面中的某个控件抖动
 * @param   id          控件id
 */
function shake() {
    var style = document.getElementById("form").style,
        p = [4, 8, 4, 0, -4, -8, -4, 0],
        fx = function() {
            style.marginLeft = p.shift() + 'px';
            if (p.length <= 0) {
                style.marginLeft = 0;
                clearInterval(timerId);
            }
        };
    p = p.concat(p.concat(p));
    timerId = setInterval(fx, 13);
}

/**
 * Method Description:
 *  登录页面(login_frame.html)中点击登录按钮后的动作
 *  1. 检测用户名和密码输入框是否齐全,否则震动登录框并提示用户
 *  2. ajax异步请求验证用户名和密码是否正确
 */
function loginAction() {
    var el_login_user = $(document).find("#login-username");
    var el_login_pass = $(document).find("#login-password");
    var el_login_message = $(document).find("#login-message");
    var el_div_user = $(document).find("#div-login-username");
    var el_div_pass = $(document).find("#div-login-password");
    var el_group_button = $(document).find("#login-button-group");

    var user_name = (el_login_user.val() || "").trim(),
        pass_word = (el_login_pass.val() || "").trim();
    if (user_name === "" && pass_word === "") {
        el_login_message.html("<p align='center' style='color: red'>请输入用户名和密码</p>");
        el_div_user.addClass("has-error");
        el_div_pass.addClass("has-error");
    } else {
        if (user_name === "") {
            el_login_message.html("<p align='center' style='color: red'>用户名不能为空</p>");
            el_div_user.addClass("has-error");
            el_div_pass.removeClass("has-error");
        } else if (pass_word === "") {
            el_login_message.html("<p align='center' style='color: red'>密码输入为空</p>");
            el_div_user.removeClass("has-error");
            el_div_pass.addClass("has-error");
        } else {
            var encoded_pass = md5(md5(pass_word) + getCurrentDate());
            var auth_code = $("#validate-code-input").val() || "";
            //console.log(auth_code + ", " + user_name + ", " + encoded_pass + ", " + getCurrentDate());
            // 验证用户名和密码
            $.ajax({
                url: '/includes/jscallphp.php',
                type: 'POST',
                data: {
                    type: 0,
                    username: user_name,
                    password: encoded_pass,
                    time: getCurrentDate(),
                    code: auth_code
                },
                dataType: 'JSON',
                success: function(json) {
                    // 登录成功
                    if (json.response === 0) {
                        console.log(getReturnUrl());
                        window.top.location.assign(getReturnUrl());
                    } else {
                        shake();
                        el_login_message.html("<p align='center' style='color: red'>" + json.info + "</p>");
                        el_group_button.show();
                        if (json.response == 4) {
                            $("#div-login-validate-code").addClass("has-error");
                            showAuthCode();
                        } else {
                            el_div_user.addClass("has-error");
                            el_div_pass.addClass("has-error");

                            if (json.resp_valid === true) {
                                showAuthCode();
                                $("#verification-img").click();
                            }
                        }
                    }
                },
                error: function(textStatus, errorThrown) {
                    console.error(textStatus);
                    console.error(errorThrown);
                    el_login_message.html("<p align='center' style='color: red'>内部错误,请联系网站管理员</p>");
                    el_group_button.show();
                },
                beforeSend: function() {
                    el_login_message.html("<p class='text-center'><img width='34' height='34' src='/resource/res/ellipsis.gif'></p>");
                    el_group_button.hide();
                }
            });
            return false;
        }
    }
    shake();
}

/**
 * Method Description:
 *  显示验证码
 */
function showAuthCode() {
    if ($("#div-login-validate-code").css("display") != "none") {
        return;
    }
    $("#div-login-validate-code").find("label").html("<img id=\"verification-img\" src=\"/validate_code.php\" style=\"padding: 0px 5px 1px 5px\" " +
        "onclick=\"javascript:this.src='/validate_code.php?tm='+Math.random();\" width=\"100\" height=\"35\" />");
    $("#div-login-validate-code").removeClass("hide");
}

/**
 * Method Description:
 *  获取当前的时间为字符串格式,格式为 yyyyMMddHHmmss.
 *  例如当前是2016-10-31 15:30:44        则返回20161031153044
 * @return  时间格式
 */
function getCurrentDate() {
    var current_date = new Date();
    return current_date.getFullYear() +
        (((current_date.getMonth() + 1) < 10) ? "0" : "") + (current_date.getMonth() + 1) +
        ((current_date.getDate() < 10) ? "0" : "") + current_date.getDate() +
        ((current_date.getHours() < 10) ? "0" : "") + current_date.getHours() +
        ((current_date.getMinutes() < 10) ? "0" : "") + current_date.getMinutes() +
        ((current_date.getSeconds() < 10) ? "0" : "") + current_date.getSeconds();
}

/**
 * Method Description:
 *  获取父窗口中的url地址,并解析出returnUrl 并返回
 * @return  String      父窗口的url中的ReturnURL,如果没有则,返回""
 */
function getReturnUrl() {
    // 获取父窗口的url
    var url = window.top.location.href;
    if (url === "") {
        try {
            url = window.top.document.referrer;
        } catch (M) {
            if (window.parent) {
                try {
                    url = window.parent.document.referrer;
                } catch (L) {
                    url = "";
                }
            }
        }
    }

    // 解析出return_url
    var index = url.indexOf('?return_url=');
    if (index != -1) {
        return url.substring(index + 12);
    } else {
        return "/index.php";
    }
}
