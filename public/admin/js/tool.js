"use strict";

// tool
var tool = {

    // toast
    showToast: function(text) {
        layer.msg(text);
    },

    // error
    showError: function (errMsg) {
        layer.msg(errMsg, {
            icon: 5
        });
    },

    // show load
    showLoad: function () {
        layer.load(1);
    },

    // close load
    closeLoad: function () {
        // 关闭加载
        layer.closeAll('loading');
    }
};
