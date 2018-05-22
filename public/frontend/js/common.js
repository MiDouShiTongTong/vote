"use strict";
var site_url = $.trim($('input[name="site_url"]').val());
var base_url = $.trim($('input[name="base_url"]').val());

// elem
var cElem = {

};

// validation
var cEvent = {

};

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
                input.attr('checked', 'checked');
                break;
            case 'textarea':
                input.val(value);
                break;
        }
        return input;
    },
    setUnCheck: function (name) {
        $('input[name="' + name + '"').attr('checked', false);
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
