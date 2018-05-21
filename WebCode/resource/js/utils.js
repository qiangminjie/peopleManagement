// 三级联动
function trippleLinkage(arr1, arr2, arr3, dom1, dom2, dom3, label1, label2, label3, all_flag) {
    // type subtype level 三级联动
    resetType(arr1, dom1, label1, all_flag);
    resetSubtype(arr2, dom1, dom2, label2, all_flag);
    resetLevel(arr3, dom1, dom2, dom3, label3, all_flag);

    dom1.change(function () {
        resetSubtype(arr2, dom1, dom2, label2, all_flag);
        resetLevel(arr3, dom1, dom2, dom3, label3, all_flag);
    });

    dom2.change(function () {
        resetLevel(arr3, dom1, dom2, dom3, label3, all_flag);
    });
}

// 重置type select框
function resetType(types, typeJqueryDom, labelJqueryDom, all_flag) {
    var type_html;
    if (all_flag) {
        type_html = "<option value='-1'>全部</option>";
    } else {
        type_html = "<option value='-1'>请选择岗位</option>";
    }

    for (var i = 0; i < types.length; i++) {
        type_html += "<option value='" + types[i].type_id + "'>" + types[i].type_name + "</option>"
    }
    typeJqueryDom.html(type_html);
    $("#select-create-type").html(type_html);

    if (types.length > 0) {
        typeJqueryDom.removeClass("hidden");
        if (labelJqueryDom !== undefined) {
            labelJqueryDom.removeClass("hidden");
        }
    } else {
        typeJqueryDom.addClass("hidden");
        if (labelJqueryDom !== undefined) {
            labelJqueryDom.addClass("hidden");
        }
    }
}

// 重置subtype select框
function resetSubtype(subtypes, typeJqueryDom, subtypeJqueryDom, labelJqueryDom, all_flag) {
    var subtype_html;
    if (all_flag) {
        subtype_html = "<option value='-1'>全部</option>";
    } else {
        subtype_html = "<option value='-1'>请选择类型</option>";
    }
    var type_id = typeJqueryDom.val();
    var thisSubtypes = [];
    for (var i = 0; i < subtypes.length; i++) {
        if (subtypes[i].type_id === type_id) {
            thisSubtypes.push({subtype_id: subtypes[i].subtype_id, subtype_name: subtypes[i].subtype_name});
        }
    }

    for (var i = 0; i < thisSubtypes.length; i++) {
        subtype_html += "<option value='" + thisSubtypes[i].subtype_id + "'>" + thisSubtypes[i].subtype_name + "</option>"
    }
    subtypeJqueryDom.html(subtype_html);

    if (thisSubtypes.length > 0) {
        subtypeJqueryDom.removeClass("hidden");
        if (labelJqueryDom !== undefined) {
            labelJqueryDom.removeClass("hidden");
        }
    } else {
        subtypeJqueryDom.addClass("hidden");
        if (labelJqueryDom !== undefined) {
            labelJqueryDom.addClass("hidden");
        }
    }

}

// 重置level select框
function resetLevel(levels, typeJqueryDom, subtypeJqueryDom, levelJqueryDom, labelJqueryDom, all_flag) {
    var level_html;
    if (all_flag) {
        level_html = "<option value='-1'>全部</option>";
    } else {
        level_html = "<option value='-1'>请选择级别</option>";
    }
    var type_id = typeJqueryDom.val();
    var subtype_id = subtypeJqueryDom.val();
    var thisLevels = [];

    for (var i = 0; i < levels.length; i++) {
        if (levels[i].type_id === type_id && levels[i].subtype_id === subtype_id) {
            thisLevels.push({level_id: levels[i].level_id, level_name: levels[i].level_name});
        }
    }

    for (var i = 0; i < thisLevels.length; i++) {
        level_html += "<option value='" + thisLevels[i].level_id + "'>" + thisLevels[i].level_name + "</option>"
    }
    levelJqueryDom.html(level_html);

    if (thisLevels.length > 0) {
        levelJqueryDom.removeClass("hidden");
        if (labelJqueryDom !== undefined) {
            labelJqueryDom.removeClass("hidden");
        }
    } else {
        levelJqueryDom.addClass("hidden");
        if (labelJqueryDom !== undefined) {
            labelJqueryDom.addClass("hidden");
        }
    }
}

// 根据type_id获得type_name
function getTypeName(type_id) {
    var type_name = "-";
    for (var i = 0; i < types.length; i++) {
        var type = types[i];
        if (type_id === type.type_id) {
            type_name = type.type_name;
            break;
        }
    }
    return type_name;
}

// 根据type_id, subtype_id获得subtype_name
function getSubtypeName(type_id, subtype_id) {
    var subtype_name = "-";
    for (var i = 0; i < subtypes.length; i++) {
        var subtype = subtypes[i];
        if (type_id === subtype.type_id && subtype_id === subtype.subtype_id) {
            subtype_name = subtype.subtype_name;
            break;
        }
    }
    return subtype_name;
}

// 根据type_id, subtype_id, level获得level_name
function getLevelName(type_id, subtype_id, level_id) {
    var level_name = "-";
    for (var i = 0; i < levels.length; i++) {
        var level = levels[i];
        if (type_id === level.type_id && subtype_id === level.subtype_id && level_id === level.level_id) {
            level_name = level.level_name;
            break;
        }
    }
    return level_name;
}

// 根据status显示label是在职还是离职
function getStatus(status) {
    var status_label = "";
    switch (status) {
        case '0':
            status_label = "<span class='label label-success'>在职</span>";
            break;
        case '1':
            status_label = "<span class='label label-danger'>离职</span>";
    }
    return status_label;
}


function getofferStatus(isoffer) {
    var status_label = "";
    switch (isoffer) {
        case '1':
            status_label = "<span class='label label-success'>初试</span>";
            break;
        case '2':
            status_label = "<span class='label label-success'>复试</span>";
            break;
        case '3':
            status_label = "<span class='label label-success'>通过</span>";
            break;
        case '4':
            status_label = "<span class='label label-danger'>淘汰</span>";

            break;
        case '5':
            status_label = "<span class='label label-success'>接受offer</span>";
    }
    return status_label;
}

//获得datepicker的值 yyyyMMdd
function getDatePickerValue(str) {
    return str.substr(0, 4) + str.substr(5, 2) + str.substr(8, 2);
}

// 根据status显示label是在职还是离职
function getEmployStatus(status) {
    var status_label = "";
    switch (status) {
        case '0':
            status_label = "<span class='label label-default'>审核中</span>";
            break;
        case '1':
            status_label = "<span class='label label-success'>招聘中</span>";
            break;
        case '2':
            status_label = "<span class='label label-primary'>已结束</span>";
            break;
        case '3':
            status_label = "<span class='label label-danger'>已驳回</span>";
            break;
    }
    return status_label;
}