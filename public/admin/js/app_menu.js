/**
 * AdminLTE Menu
 * ------------------
 * This file is system option
 */
(function ($, AdminLTE) {

    "use strict";

    // elem
    var elem = {
        body: $('body')
    };

    /**
     * List of all the available skins
     *
     * @type Array
     */
    var my_skins = [
        "skin-blue",
        "skin-black",
        "skin-red",
        "skin-yellow",
        "skin-purple",
        "skin-green",
        "skin-blue-light",
        "skin-black-light",
        "skin-red-light",
        "skin-yellow-light",
        "skin-purple-light",
        "skin-green-light"
    ];

    // event
    var event = {
        systemSettingInit: function () {
            // skin
            $("[data-skin]").on('click', function (e) {
                if ($(this).hasClass('knob')) return;
                e.preventDefault();
                event.change_skin($(this).data('skin'));
            });

            // Reset options
            event.storageInit();

            // iCheck for checkbox and radio inputs
            $('input[type="checkbox"].control-sidebar-option-checkbox, input[type="radio"].control-sidebar-option-checkbox').iCheck({
                checkboxClass: 'icheckbox_minimal-blue pull-right',
                radioClass: 'iradio_minimal-blue'
            }).on('ifClicked', function (e) {
                // layout
                var _this = $(e.target);
                if (_this.data('layout') != undefined) {
                    event.change_layout(_this.data('layout'));
                }
                // sidebarSkin
                if (_this.data('sidebarskin') != undefined) {
                    event.change_sidebarSkin();
                }
            });
        },
        /**
         * storageInit 获取用户操作记录
         *
         * @return void
         */
        storageInit: function() {
            if (elem.body.hasClass('fixed')) {
                $("[data-layout='fixed']").attr('checked', 'checked');
            }

            if (elem.body.hasClass('sidebar-collapse')) {
                $("[data-layout='sidebar-collapse']").attr('checked', 'checked');
            }

            // skin
            var skin = event.get('skin');
            if (skin && $.inArray(skin, my_skins)) {
                event.change_skin(skin);
            }

            // Fixed layout
            var layoutFixed = event.get('layout-fixed');
            if (layoutFixed == null) {
                event.store('layout-fixed', '1');
                layoutFixed = event.get('layout-fixed');
            }

            if (layoutFixed == '1') {
                elem.body.addClass('fixed');
                $('input[data-layout="fixed"]').attr('checked', 'checked');
            } else {
                elem.body.removeClass('fixed');
            }

            // sidebar-collapse
            var sidebarCollapse = event.get('layout-sidebar-collapse');
            if (sidebarCollapse == 1) {
                $('input[data-layout="sidebar-collapse"]').attr('checked', 'checked');
                setTimeout(function () {
                    elem.body.addClass('sidebar-collapse');
                }, 60);
            } else {
                elem.body.removeClass('sidebar-collapse');
            }

            // sidebarSkin
            var sidebarSkin = event.get('sidebarSkin');
            var sidebar = $(".control-sidebar");
            if (sidebarSkin == 'light') {
                sidebar.removeClass("control-sidebar-dark");
                sidebar.addClass("control-sidebar-light");
                $('input[data-sidebarskin="toggle"]').attr('checked', 'checked');
            } else {
                sidebar.removeClass("control-sidebar-light");
                sidebar.addClass("control-sidebar-dark")
            }
        },
        /**
         * Toggles layout classes
         *
         * @param String cls the layout class to toggle
         * @returns void
         */
        change_layout: function (cls) {
            var _body = $("body");
            _body.toggleClass(cls);
            AdminLTE.layout.fixSidebar();
            //Fix the problem with right sidebar and layout boxed
            if (cls == "layout-boxed")
                AdminLTE.controlSidebar._fix($(".control-sidebar-bg"));

            // fixed layout
            if (_body.hasClass('fixed') && cls == 'fixed') {
                event.store('layout-fixed', 1);
                AdminLTE.pushMenu.expandOnHover();
                AdminLTE.layout.activate();
            } else if (cls == 'fixed') {
                event.store('layout-fixed', 0);
            }

            // sidebar-collapse
            if (_body.hasClass('sidebar-collapse') && cls == 'sidebar-collapse') {
                event.store('layout-sidebar-collapse', 1);
            } else if (cls == 'sidebar-collapse') {
                event.store('layout-sidebar-collapse', 0);
            }

            AdminLTE.controlSidebar._fix($(".control-sidebar-bg"));
            AdminLTE.controlSidebar._fix($(".control-sidebar"));
        },
        /**
         * Replaces the old skin with the new skin
         *
         * @param String cls the new skin class
         * @returns Boolean false to prevent link's default action
         */
        change_skin: function(cls) {
            $.each(my_skins, function (i) {
                $("body").removeClass(my_skins[i]);
            });
            $("body").addClass(cls);
            event.store('skin', cls);
            return false;
        },
        /**
         * 改变setting背景色
         *
         * @return void
         */
        change_sidebarSkin: function () {
            var sidebar = $(".control-sidebar");
            if (sidebar.hasClass("control-sidebar-dark")) {
                sidebar.removeClass("control-sidebar-dark");
                sidebar.addClass("control-sidebar-light");
                event.store('sidebarSkin', 'light');
            } else {
                sidebar.removeClass("control-sidebar-light");
                sidebar.addClass("control-sidebar-dark");
                event.store('sidebarSkin', 'dark');
            }
        },
        /**
         * Store a new settings in the browser
         *
         * @param String name Name of the setting
         * @param String val Value of the setting
         * @returns void
         */
        store: function(name, val) {
            if (typeof (Storage) !== "undefined") {
                localStorage.setItem(name, val);
            } else {
                window.alert('Please use a modern browser to properly view this template!');
            }
        },
        /**
         * Get a preevent.stored setting
         *
         * @param String name Name of of the setting
         * @returns String The value of the setting | null
         */
        get: function (name) {
            if (typeof (Storage) !== "undefined") {
                return localStorage.getItem(name);
            } else {
                window.alert('Please use a modern browser to properly view this template!');
            }
        }
    };

    event.systemSettingInit();
})(jQuery, $.AdminLTE);