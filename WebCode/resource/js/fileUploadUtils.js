

/**
 * 支持文件上传的ajax请求
 * @param btn           提交ajax请求的jquery对象
 * @param url           提交请求路径
 * @param args          需要提交的参数
 *          key formObj       需要提交数据的form的jquery对象
 *          key inputObj      需要提交数据的input的jquery对象 以{key1:obj1,key2:obj2}形式保存
 *          key otherArgs     其他需要提交的数据，以{key:value,key:value}形式保存
 * @param successFunc   请求成功执行函数
 * @param errorFunc     请求失败执行函数
 * @param dataType      返回数据类型 如："json" "text" "xml"等
 */
function fastAjax(btn,url,args,successFunc,errorFunc,dataType){
    btn.on('click',function () {
        var formData = new FormData(args['formObj']==null?null:args['formObj'][0]);
        for (var key in args['inputObj']){
            if (args['inputObj'][key].attr('type')==='file'){
                formData.append(key, args['inputObj'][key][0].files[0]);
            }
            else {
                formData.append(key, args['inputObj'][key].val());
            }
        }
        for (var key in args['otherArgs']){
            formData.append(key,args['otherArgs'][key]);
        }
        $.ajax(
            {
                type: 'post',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (data) {
                    successFunc(data);
                },
                error: function (data) {
                    errorFunc(data);
                },
                dataType:dataType
            }
        );
    })
}

/***************************************************************************************************
 * File Description:
 *
 *      fileUploadValidate.js
 *
 *      文件上传校验的通用js，使用本js请加载jquery
 *
 *      核心方法为validate.js，参数传递及返回类型见方法说明
 *
 *      基于showInfo.js 提供两个便捷方法 fastValidateFileInput以及showFileTip
 *
 * Main Methods:
 *  validateFile            文件校验的核心方法
 *  fastValidateFileInput   绑定上传文件校验事件，可同时管理提示消息、联动提交按钮、返回结果信息
 *  validateFileExtension   判断文件类型的上传文件类型是否合法
 *  validateFileSize        判断文件是否超过了限制
 *  isFileNotNull           判断文件是否非空
 *  initialFileResult       初始化文件上传结果对象
 *  getFileSize             获得上传文件的大小
 *  getCalFileSize          获得上传文件的近似大小（带单位）
 *  getFileExtension        获得上传文件的扩展名
 *  tipMaker                根据限制参数生成上传文件的提示
 *  showTip                 根据限制参数在某个Div内展示提示信息
 *  calFileSize             获得近似文件大小
 *
 /**************************************************************************************************/

/**
 * 文件校验的核心方法
 * validateFile(target,extensions,maxSize)
 * @param           target                              校验标签的jquery对象
 * @param           restrictions {{}}          限制条件，key：value的形式传递，key列表如下
 *                                                      key列表：
 *                              *extensions  {string[]}   合法的文件类型的扩展名数组，如不限制请传null  例：限制上传类型为jpg、gif、png 则传入['jpg','gif','png']
 *                              *maxSize     {int}        上传文件的最大大小，单位是Byte，如不限制请传null 例：如限制大小为2MB 则传入 2*1024*1024
 *
 * @returns         {{status: number, msg: string}}     返回对象result有两个属性：status、msg
 *                                                         * status是识别结果数：当
 *                                                              status = -1 表示当前上传文件为空
 *                                                              status = 1 表示文件通过校验
 *                                                              status > 1 表示文件校验失败
 *                                                              - 失败代码采用素数相乘法
 *                                                                  2 表示文件扩展名验证错误
 *                                                                  3 表示文件超过最大限制
 *                                                         * 用户可自行根据返回值对数据进行处理
 *
 *                                                         * msg是参考提示信息，方便直接使用
 *                                                             status = -1  提示信息         eg：'请上传小于maxSize的xxx、xxx...xxx格式的文件';
 *                                                             status = 1   上传文件大小  eg：'1.84MB'
 *                                                             status > 1   分行显示错误信息  eg：'文件类型错误，请上传xxx、xxx..xxx格式的文件，
 *                                                                                               文件过大(>maxSize)，请重新选择文件'
 *
 */
function validateFile(target,restrictions){
    var result = {status:1,msg:''};
    var errors = [];
    if (!isFileNotNull(target)){
        result['status'] =- 1;
        result['msg'] = tipMaker(restrictions);
        return result;
    }else {
        if (restrictions['extensions'] != null && restrictions['extensions'].length > 0) {
            var extensionFlag = validateFileExtension(target,restrictions['extensions']);
            if (!extensionFlag) {
                result['status']=result['status']*2;
                errors.push(errorMaker(2,restrictions['extensions']));
            }
        }
        if (restrictions['maxSize'] != null && restrictions['maxSize'] > 0) {
            var sizeFlag = validateFileSize(target,restrictions['maxSize']);
            if (!sizeFlag) {
                result['status']=result['status']*3;
                errors.push(errorMaker(3,restrictions['maxSize']));
            }
        }

        if (result['status']==1) {
            result['msg']=getCalFileSize(target);
        }else {
            result['msg']=errorInfoMaker(errors);
        }

        return result;
    }

}


/**
 * 推荐直接使用此方法
 * 绑定上传文件校验事件，可同时管理提示消息、联动提交按钮、返回结果信息
 * (依赖showInfo.js)
 * fastValidateFileInput(target,restrictions,msgDiv,button,resultObj)
 * @param       target              上传文件标签的jquery对象
 * @param       restrictions        文件限制对象 限制参数参见validateFile方法
 * @param       msgDiv              当需要回显消息时在此处输入回显消息的div的jquery对象，否则为null
 * @param       button              当需要在文件上传对表单提交按钮加以限制时输入提交按钮的jquery对象，否则为null
 * @param       resultObj           用来接收当前结果对象的全局参数，不需要可不传入，使用时请声明一个变量 var fileResult = initialFileResult(extensions) 传入即可
 */
function fastValidateFileInput(target,restrictions,msgDiv,button,resultObj) {
    //为上传标签绑定校验事件
    target.on('change', function () {
        //返回结果
        var result = validateFile($(this), restrictions);
        //处理结果
        var status = result['status'];
        var msg = result['msg'];
        //空
        if (status == -1) {
            if (msgDiv != null){
                showTipInfo(msgDiv, msg);
            }
            if (button!=null) {
                button.attr('disabled', true);
            }
        }
        //失败
        if (status > 1) {
            if (msgDiv !=null) {
                showErrorInfo(msgDiv, msg);
            }
            if (button!=null) {
                button.attr('disabled', true);
            }
        }
        //成功
        if (status == 1) {
            if (msgDiv!=null) {
                showSuccessInfo(msgDiv, msg);
            }
            if (button!=null) {
                button.attr('disabled', false);
            }
        }
        //处理结果
        if (resultObj!=null){
            resultObj['msg']=result['msg'];
            resultObj['status']=result['status'];
        }
    });
}




/**
 * validateFileExtension(target, extensions)    通用方法，判断文件类型的上传文件类型是否合法
 * @param       target                          file标签的jQuery对象
 * @param       extensions                      一个由合法扩展名组成的字符串数组,如['jpg','png','gif'];
 * @returns     boolean                         扩展名合法返回true,否则返回false
 */
function validateFileExtension(target, extensions) {

    var extensionFlag = false;
    var extension = getFileExtension(target);

    for (var i = 0; i < extensions.length; i++) {
        if (extension == extensions[i].toLowerCase()) {
            extensionFlag = true;
            break;
        }
    }

    return extensionFlag;
}


/**
 * validateFileSize(target,maxSize)     通用方法，判断文件是否超过了限制
 * @param       target                  file标签的jQuery对象
 * @param       maxSize                 文件大小的最大值，单位为byte
 * @returns     {boolean}               小于最大文件大小返回true，否则返回false
 */
function validateFileSize(target, maxSize) {

    var fileSize = getFileSize(target);

    var sizeFlag = fileSize < maxSize;

    return sizeFlag;
}


/**
 * initialFileResult(restrictions)              通用方法，初始化文件上传结果对象
 * @param       restrictions                    传入限制对象，详情见validateFile方法
 * @return      {{status: number, msg: string}}
 */
function  initialFileResult(restrictions){
    var fileTip = tipMaker(restrictions);
    return {status:-1,msg:fileTip};
}


/**
 * tipMaker(restrictions)           通用方法，根据条件智能生成提示信息
 * @param restrictions              例如{maxSize:2*1024*1024,extensions:}
 * @returns {string}
 */
function tipMaker(restrictions) {
    var tip = '请上传';

    if (restrictions['maxSize'] != null && restrictions['maxSize'] > 0){
        tip = tip + '小于'+calFileSize(restrictions['maxSize'])+'的';
    }

    if (restrictions['extensions'] != null && restrictions['extensions'].length>0) {
        var str = '';
        for (var i = 0; i < restrictions['extensions'].length; i++) {
            if (i != restrictions['extensions'].length - 1) {
                str = str + restrictions['extensions'][i] + "、";
            }
            else {
                str = str + restrictions['extensions'][i];
            }
        }

        tip = tip + str + '类型的';
    }

    tip = tip + '文件'

    return tip;
}


/**
 * 根据定义的限制类型智能生成提示并在目标div中进行显示
 * (依赖showInfo.js)
 * showFileTip(msgDiv,restrictions)
 * @param       msgDiv            提示信息的div jquery对象
 * @param       restrictions      文件限制对象 限制参数参见validateFile方法
 */
function showFileTip(msgDiv,restrictions) {
    showTipInfo(msgDiv,tipMaker(restrictions));
}


/**
 * errorMaker(errorType, args)      校验错误信息生成器
 * @param       errorType           错误类型，在本页面中 -1=文件为空 2=文件类型错误 3=大小超出限制
 * @param       args                不同错误类型对应的不同参数 errorType=2，传入参数为(arr)extensions
 *                                                          errorType=3，传入参数为(int)maxSize
 * return       {string}            返回string类型的错误消息用于回显
 */
function errorMaker(errorType, args) {
    switch (errorType) {
        case 2:
            var str='';
            for (var i  = 0 ; i < args.length ; i++){
                if (i != args.length-1){
                    str=str+args[i]+"、";
                }
                else {
                    str=str+args[i];
                }
            }
            var errorInfo = '文件类型错误，请上传'+str+'格式的文件';
            return errorInfo;
        case 3:
            str=calFileSize(args);
            var errorInfo = '文件过大(>'+str+')，请重新选择文件';
            return errorInfo;
    }
}


/**
 * errorInfoMaker(errors)               把错误数组转换成错误信息
 * @param       errors(arr)             含有错误信息的数组
 * @returns     {string}                返回拼接好的错误信息
 */
function errorInfoMaker(errors){
    var errorInfo = '';

    for (var i = 0; i < errors.length; i++) {
        if (i != errors.length-1){
            errorInfo = errorInfo + errors[i] + '<br/>'
        }else {
            errorInfo += errors[i];
        }
    }
    return errorInfo;
}


/**
 * isFileNotNull(target)            通用方法，判断上传文件是否非空
 * @param       target              file标签的jQuery对象
 * @returns     {boolean}           如果非空返回true，否则返回false
 */
function isFileNotNull(target) {
    var flag = target.val() != '';
    return flag;
}


/**
 * getFileExtension(target)         通用方法，获得上传文件的扩展名
 * @param       target              file标签的jQuery对象
 * @returns     {string}            如果文件的扩展名存在则返回扩展名的小写形式，不存在则返回''
 */
function getFileExtension(target) {
    var path = target.val();
    var index = path.lastIndexOf(".");
    var extension;
    if (index != -1) {
        extension = path.substr(index + 1).toLowerCase();
        return extension;
    } else {
        return '';
    }
}


/**
 * getCalFileSize(target)       通用方法，获得上传文件的近似大小
 * @param target                file标签的jQuery对象
 * @param count {int}           精确到小数点后几位，不填默认为2
 * @returns {string}            返回近似大小的字符串 eg：'44.52KB'
 */
function getCalFileSize(target,count) {
    return calFileSize(getFileSize(target),count);
}


/**
 * getFileSize(target)              通用方法，获得上传文件的大小
 * @param       target              file标签的jQuery对象
 * @returns     {int}               返回大小值，单位byte
 */
function getFileSize(target) {
    //判断是否是IE或者OPERA
    var isIE = /msie/i.test(navigator.userAgent) && !window.opera;
    var size;
    if (isIE && !target[0].files) {
        var filePath = target[0].value;
        var fileSystem = new ActiveXObject("Scripting.FileSystemObject");
        var file = fileSystem.GetFile (filePath);
        size = file.Size;
    }else {
        size = target[0].files[0].size;
    }
    return size;
}


/**
 * calFileSize(size)            通用函数，计算以Byte为单位的int值的近似的文件大小
 * @param       size            原始大小，单位是byte
 * @param       count           近似位数，默认是2
 * return       {string}        返回精确到小数点后两位的文件大小
 */
function calFileSize(size,count) {
    if (count == null || count < 0){
        count = 2;
    }
    var sizeLabel = ["B", "KB", "MB", "GB"];
    for (var index = 0; index < sizeLabel.length-1; index++) {
        if (size < 1024) {
            return round(size, count) + sizeLabel[index];
        }
        size = size / 1024;
    }
    return round(size, count) + sizeLabel[index];
}


/**
 * round(number, count)         通用函数，取小数点后几位近似数
 * @param       number          被近似数
 * @param       count           近似位数
 * @returns     {number}        返回近似后的结果
 */
function round(number, count) {
    return Math.round(number * Math.pow(10, count)) / Math.pow(10, count);
}


/***************************************************************************************************
 * File Description:
 *
 *      showInfo.js
 *
 *      展示提示信息的通用js，必须加载jquery
 *
 *      使用了bootstrap的css样式
 *
 * Created by   Victor Zhou            victorzhou6@microwu.com           19/9/2017, 4:37:00 PM
 /**************************************************************************************************/


/**
 * showTipInfo(target,tipInfo)      通用方法，用于显示提示信息
 * @param       target              回显div的jquery对象
 * @param       tipInfo             提示信息
 */
function showTipInfo(target, tipInfo) {
    target.html("<span class='text-info'>" + tipInfo + " </span>");
}


/**
 * showSuccessInfo(target)          通用方法，用于显示成功信息,如无信息只显示图标
 * @param       target              回显div的jquery对象
 * @param       successInfo         成功信息
 */
function showSuccessInfo(target, successInfo) {
    if (successInfo != null) {
        target.html("<span class='text-success' style='color: green'>" + successInfo + " </span>");
    } else {
        target.html("<span class='glyphicon glyphicon-ok' style='color: green'></span>");
    }
}


/**
 * showErrorInfos(target, errorInfos)   通用方法，用于显示错误信息，如无信息只显示图标
 * @param       target                  回显div的jquery对象
 * @param       errorInfo               错误信息
 */
function showErrorInfo(target, errorInfo) {
    if (errorInfo != null) {
        target.html("<span class='text-danger' style='color: red'>" + errorInfo + " </span>")
    }else {
        target.html("<span class='glyphicon glyphicon-remove' style='color: red'></span>");
    }
}
