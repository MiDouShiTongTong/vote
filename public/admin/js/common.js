"use strict";
var siteUrl = $.trim($('input[name="site-url"]').val());
var baseUrl = $.trim($('input[name="base-url"]').val());

// val
var cVal = {
    getVal: function (type, name, isRepeat) {
        var input = null;
        if (isRepeat == 'repeat') {
            $.each($('.data-action-field-container').children(), function () {
                var _this = $(this);
                if (_this.is(':visible') == true) {
                    switch (type) {
                        case 'text':
                            input = _this.find($('input[name="' + name + '"]'));
                            break;
                        case 'radio':
                        case 'checkbox':
                            input = _this.find($('input[name="' + name + '"]:checked'));
                            break;
                        case 'select':
                            input = _this.find($('select[name="' + name + '"]'));
                            break;
                        case 'textarea':
                            input = _this.find($('textarea[name="' + name + '"]'));
                            break;
                        default:
                            tool.showToast('form type error');
                    }
                }
            });
        } else {
            switch (type) {
                case 'text':
                    input = $('input[name="' + name + '"]');
                    break;
                case 'radio':
                case 'checkbox':
                    input = $('input[name="' + name + '"]:checked');
                    break;
                case 'select':
                    input = $('select[name="' + name + '"]');
                    break;
                case 'textarea':
                    input = $('textarea[name="' + name + '"]');
                    break;
                default:
                    tool.showToast('form type error');
            }
        }
        // data build
        var value = '';
        switch (type) {
            case 'text':
            case 'select':
            case 'radio':
            case 'textarea':
                value = $.trim(input.val());
                break;
            case 'checkbox':
                break;
            default:
                tool.showToast('form type error');
        }
        return value;
    },
    setVal: function (type, name, value, isRepeat) {
        var input = null;
        if (isRepeat == 'repeat') {
            $.each($('.data-action-field-container').children(), function () {
                var _this = $(this);
                if (_this.is(':visible') == true) {
                    switch (type) {
                        case 'text':
                            input = _this.find($('input[name="' + name + '"]'));
                            break;
                        case 'radio':
                        case 'checkbox':
                            input = _this.find($('input[name="' + name + '"][value="' + value + '"]'));
                            break;
                        case 'select':
                            input = _this.find($('select[name="' + name + '"]'));
                            break;
                        case 'textarea':
                            input = _this.find($('textarea[name="' + name + '"]'));
                            break;
                        default:
                            tool.showToast('form type error');
                    }
                }
            });
        } else {
            switch (type) {
                case 'text':
                    input = $('input[name="' + name + '"]');
                    break;
                case 'select':
                    input = $('select[name="' + name + '"]');
                    break;
                case 'radio':
                case 'checkbox':
                    input = $('input[name="' + name + '"][value="' + value + '"]');
                    break;
                case 'textarea':
                    input = $('textarea[name="' + name + '"]');
                    break;
                default:
                    tool.showToast('form type error');
            }
        }
        switch (type) {
            case 'text':
                input.val(value);
                break;
            case 'select':
                input.val(value);
                break;
            case 'radio':
            case 'checkbox':
                input.iCheck('check');
                break;
            case 'textarea':
                input.val(value);
                break;
        }
        return input;
    },
    setUnCheck: function (name) {
        $('input[name="' + name + '"').iCheck('uncheck');
    }
};

var cForm = {
    getForm: function (type, name) {
        var input;
        switch (type) {
            case 'text':
                input = $('input[name="' + name + '"]');
                break;
            case 'radio':
            case 'checkbox':
                input = $('input[name="' + name + '"]');
                break;
            case 'select':
                input = $('select[name="' + name + '"]');
                break;
            case 'textarea':
                input = $('textarea[name="' + name + '"]');
                break;
            default:
                tool.showToast('form type error');
        }
        return input;
    }
};

// event
var cEvent = {
    initICheckStyle: function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        }).on('ifChecked', function () {
            $(this).parents('.form-checked-container').find('label.error').hide();
        });
    },
    showDataActionContainer: function () {
        // 显示操作层 隐藏数据列表层
        $('.data-action-container').show();
        $('.data-container').hide();
        // 隐藏所有表单验证报错
        $('.form-horizontal label.error').hide();
    },
    closeDataActionContainer: function () {
        // 隐藏操作层 显示数据列表层
        $('.data-action-container').hide();
        $('.data-container').show();
        // 隐藏所有表单验证报错
        $('.form-horizontal label.error').hide();
    }
};

// html
var cHtml = {
    getMaterialLoading: function () {
        return '<div id="material-loading">\
                        <div class="loading-container text-center" aria-live="assertive">\
                            <span>加载中...</span>\
                            </div>\
                            <div class="loading-box loading">\
                                <div class="loading-box-parent">\
                                    <div class="loading-list-item loading-list-item-blue">\
                                        <div class="loading-carousel carousel-group-container">\
                                        <div class="carousel-list-item"></div>\
                                        </div><div class="carousel-group"><div class="carousel-list-item"></div></div><div class="loading-carousel load-carousel-group-container"><div class="carousel-list-item"></div></div>\
                                    </div>\
                                    <div class="loading-list-item loading-list-item-orange">\
                                        <div class="loading-carousel carousel-group-container">\
                                        <div class="carousel-list-item"></div>\
                                        </div><div class="carousel-group"><div class="carousel-list-item"></div></div><div class="loading-carousel load-carousel-group-container"><div class="carousel-list-item"></div></div>\
                                    </div>\
                                    <div class="loading-list-item loading-list-item-yellow">\
                                        <div class="loading-carousel carousel-group-container">\
                                        <div class="carousel-list-item"></div>\
                                    </div><div class="carousel-group"><div class="carousel-list-item"></div></div><div class="loading-carousel load-carousel-group-container"><div class="carousel-list-item"></div></div>\
                                    </div>\
                                    <div class="loading-list-item loading-list-item-green">\
                                        <div class="loading-carousel carousel-group-container">\
                                        <div class="carousel-list-item"></div>\
                                    </div><div class="carousel-group"><div class="carousel-list-item"></div></div><div class="loading-carousel load-carousel-group-container"><div class="carousel-list-item"></div></div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>';
    },
    option: function () {
        return '<option></option>';
    }
};
