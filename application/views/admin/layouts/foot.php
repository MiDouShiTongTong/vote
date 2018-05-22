<section class="common-section">

    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo base_url('public/common/libraries/bootstrap_v3/js/bootstrap.min.js'); ?>"></script>
    <!-- pjax -->
    <script src="<?php echo base_url('public/common/plugins/jQuery-pjax/jquery.pjax.js'); ?>"></script>
    <!-- nprogress -->
    <script src="<?php echo base_url('public/common/plugins/nprogress/js/nprogress.js'); ?>"></script>
    <!-- jquery.slimscroll -->
    <script src="<?php echo base_url('public/common/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
    <!-- storage -->
    <script src="<?php echo base_url('public/common/plugins/localStorage/store.js'); ?>"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url('public/common/plugins/iCheck/icheck.js'); ?>"></script>
    <!-- DataTables -->
    <script src="<?php echo base_url('public/common/libraries/DataTables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('public/common/libraries/DataTables/js/dataTables.bootstrap.min.js'); ?>"></script>
    <!-- layer -->
    <script src="<?php echo base_url('public/common/plugins/layer/layer.js'); ?>"></script>
    <!-- Flatpickr -->
    <script src="<?php echo base_url('public/common/plugins/flatpickr/flatpickr.min.js'); ?>"></script>
    <!-- jQuery-validate -->
    <script src="<?php echo base_url('public/common/plugins/jQuery-validate/js/jquery.validate.min.js'); ?>"></script>

    <!-- AdminLTE App -->
    <script src="<?php echo base_url('public/admin/js/app.js'); ?>"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url('public/admin/js/app_menu.js'); ?>"></script>
    <!-- custom js -->
    <script src="<?php echo base_url('public/admin/js/common.js'); ?>"></script>
    <!-- custom tool -->
    <script src="<?php echo base_url('public/admin/js/tool.js'); ?>"></script>


    <script>

        var event = {
            pageInit: function () {
                // init menu style
                var menuSrc = $('.breadcrumb').data('page-src');
                $.each($('.sidebar-menu .treeview-menu li a'), function () {
                    var _this = $(this);
                    if (_this.data('page-src') == menuSrc) {
                        _this.parent().addClass('active');
                    }
                });

                // pjax setting
                $.pjax.defaults.maxCacheLength = 0; // 全局禁止缓存
                $.pjax.defaults.timeout = 10000;    // 请求超时 时间 毫秒
                $.pjax.defaults.replate = true; // 全局禁止缓存

                // pjax event
                $(document).pjax('.pjax', '#pjax-container').on('pjax:click', function () {
                    // do something...
                }).on('pjax:send', function () {
                    NProgress.start();
                }).on('pjax:complete', function () {
                    NProgress.done();
                    // 改变title
                    document.title = $('#page-title').text();
                    // 选中当前菜单样式
                    var menuSrc = $('.breadcrumb').data('page-src');
                    // 清除菜单的激活样式
                    $.each($('.sidebar-menu .treeview-menu li'), function () {
                        var _this = $(this);
                        if (!_this.hasClass('treeview-child')) {
                            _this.removeClass('active')
                        }
                        if (_this.find('a').data('page-src') == menuSrc) {
                            _this.addClass('active');
                        }
                    });
                });

                // dataTables language
                var dataTableLanguageFileUrl = '';
                switch ('1') {
                    case '1':
                        dataTableLanguageFileUrl = '//cdn.datatables.net/plug-ins/1.10.13/i18n/Chinese.json';
                        break;
                    case '2':
                        dataTableLanguageFileUrl = '//cdn.datatables.net/plug-ins/1.10.13/i18n/English.json';
                        break;
                    default:
                        dataTableLanguageFileUrl = '';
                }

                // dataTables setting
                $.extend($.fn.dataTable.defaults, {
                    ordering: true,
                    searching: false,
                    paging: true,
//                     scrollY: $('.content-wrapper').height() - 321,
                    serverSide: true,
                    processing: true,
                    pageLength: 10,
                    lengthMenu: [
                        [
                            10, 30, 45, 100
                        ],
                        [
                            10, 30, 45, 100
                        ]
                    ],
                    ajax: {
                        data: function (data) {
                            // 条件
                            $.each($('.condition-field'), function () {
                                var _this = $(this);
                                data[_this.data('field')] = $.trim(_this.val());
                            });
                        },
                        error: function (xhr, status, error) {
                            tool.showError(error);
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    },
                    oLanguage: {
                        "sProcessing": "处理中...",
                        "sLengthMenu": "显示 _MENU_ 项结果",
                        "sZeroRecords": "没有匹配结果",
                        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                        "sInfoPostFix": "",
                        "sSearch": "搜索:",
                        "sUrl": "",
                        "sEmptyTable": "表中数据为空",
                        "sLoadingRecords": "载入中...",
                        "sInfoThousands": ",",
                        "oPaginate": {
                            "sFirst": "首页",
                            "sPrevious": "上页",
                            "sNext": "下页",
                            "sLast": "末页"
                        },
                        "oAria": {
                            "sSortAscending": ": 以升序排列此列",
                            "sSortDescending": ": 以降序排列此列"
                        }
                    }
                });

                // 初始化验证表单
                $.validator.setDefaults({
                    errorPlacement: function (error, element) {
                        // radio 类型表单 错误的标签 定位
                        if (element.attr('type') == 'radio' || element.attr('type') == 'checkbox') {
                            element.parents('.form-checked-container').append(error);
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });
            }
        };

        event.pageInit();
    </script>
</section>