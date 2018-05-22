"use strict";
// elem
var elem = {
    load: $('#load')
};

// flag
var flag = {};

// tool
var tool = {
    showLoad: function () {
        elem.load.show();
    },
    closeLoad: function () {
        elem.load.hide();
    },
    showToast: function (text) {
        M.toast({
            html: text
        })
    },
    showError: function (errMsg) {
        M.toast({
            html: errMsg
        })
    }
};