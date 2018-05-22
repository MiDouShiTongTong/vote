<section class="content">
    <!-- search bar container -->
    <?php
    $data = [
        'conditions_info' => [
            [
                'field' => 'role_name',
                'type' => 'text',
                'field_placeholder' => $this->lang->line('role_name')
            ]
        ],
    ];
    $this->load->view('admin/components/search_bar', $data);
    ?>

    <!-- to data action container -->
    <div class="to-data-action-container text-right mb-3">
        <div class="dropdown">
            <button class="btn btn-primary to-add-role"><?php echo $this->lang->line('add'); ?></button>
        </div>
    </div>

    <!-- data list -->
    <div class="data-container">
        <table class="table table-hover table-striped table-sort dataTable dtb role-dtb">
            <thead>
            <tr>
                <th><?php echo $this->lang->line('seq'); ?></th>
                <th><?php echo $this->lang->line('role_name'); ?></th>
                <th><?php echo $this->lang->line('updated_at'); ?></th>
                <th><?php echo $this->lang->line('updated_by'); ?></th>
                <th><?php echo $this->lang->line('operate'); ?></th>
            </tr>
            </thead>
            <tbody class="data">

            </tbody>
        </table>
    </div>

    <!-- data action container -->
    <div class="container-fluid data-action-container">
        <div class="row">
            <div class="col-xs-12 p-0">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('merchant') . ' ' . $this->lang->line('curd_action'); ?></h3>
                        <button type="button" class="close" data-target="close-data-action-container">&times;</button>
                    </div>
                    <div class="bg-white">
                        <div class="box-body">
                            <form id="role-form" class="form-horizontal">
                                <div class="form-group">
                                    <label for="role-name" class="col-sm-2 col-form-label"><?php echo $this->lang->line('role_name'); ?>
                                        <span class="input-require">*</span></label>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="role-name" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="role-desc" class="col-sm-2 col-form-label"><?php echo $this->lang->line('role_desc'); ?></label>
                                    <div class="col-sm-10">
                                        <input type="text"  name="role-desc" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="status" class="col-sm-2 col-form-label"><?php echo $this->lang->line('role_permission'); ?></label>
                                    <div class="col-sm-10">
                                        <div class="col-sm-5">
                                            <label class="col-form-label"><?php echo $this->lang->line('sys_menu'); ?></label>
                                            <div class="sys-menu-container" style="height: 600px; overflow-y:scroll;">

                                            </div>
                                        </div>
                                        <div class="col-sm-5 col-xs-offset-1">
                                            <label class="col-form-label"><?php echo $this->lang->line('sys_permission'); ?></label>
                                            <div class="sys-permission-container" style="height: 600px; overflow-y:scroll;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-info pull-right save-sys-role"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- my js -->
    <section class="my-js">
        <script type="text/javascript">

            $(function () {
                var html = {
                    treeContainer: function () {
                        return '<div class="tree-container form-checked-container"></div>';
                    },
                    formChecked: function () {
                        return '<div class="form-checked">\
                            <label class="form-checked-label">\
                                <input type="checkbox" name="tree-node" class="minimal" value="">\
                                <span class="form-checked-label-desc"></span>\
                            </label>\
                        </div>';
                    }
                };

                'use strict';
                // elem
                var elem = {
                    dataContainer: $('.data-container'),
                    dataActionContainer: $('.data-action-container'),
                    dataActionTitle: $('.data-action-title'),
                    closeDataActionContainer: $('[data-target="close-data-action-container"]'),

                    roleDtb: $('.role-dtb'),
                    roleForm: $('#role-form'),

                    toRoleAdd: $('.to-add-role'),
                    saveSysRole: $('.save-sys-role'),

                    sysMenuContainer: $('.sys-menu-container'),
                    sysPermissionContainer: $('.sys-permission-container')
                };

                // request
                var request = {
                    saveSysRole: siteUrl + 'admin/system/save_sys_role',
                    getSysRole: siteUrl + 'admin/system/get_sys_role',
                    getAllSysMenu: siteUrl + 'admin/system/get_all_sys_menu',
                    getAllSysPermission: siteUrl + 'admin/system/get_all_sys_permission'
                };

                // curr add type
                var flag = {
                    dataActionType: undefined,
                    roleFormCheck: undefined
                };

                // buf
                var buf = {
                    sysRoleId: undefined
                };

                // check
                var check = {
                    roleForm: function () {
                        flag.roleFormCheck = undefined;

                        elem.roleForm.validate({
                            rules: {
                                'role-name': {
                                    required: true,
                                    maxlength: 20
                                }
                            },
                            messages: {
                                'role-name': {
                                    required: '<?php echo $this->lang->line('role_name') . $this->lang->line('form_validate_required'); ?>',
                                    maxlength: '<?php echo $this->lang->line('role_name') . $this->lang->line('form_validate_max_length'); ?>{0}'
                                }
                            }
                        });

                        if (!elem.roleForm.valid()) {
                            flag.roleFormCheck = 1;
                            return false;
                        } else {
                            return true;
                        }
                    }
                };

                // event
                var event = {
                    initPage: function () {
                        elem.roleDtb = elem.roleDtb.DataTable({
                            ajax: {
                                url: request.getSysRole,
                                type: 'POST'
                            },
                            columns: [
                                {
                                    data: 'sys_role_id'
                                },
                                {
                                    data: 'role_name'
                                },
                                {
                                    data: 'updated_at'
                                },
                                {
                                    data: 'updated_by'
                                },
                                {
                                    data: 'DT_RowData',
                                    orderable: false,
                                    render: function (data, type, row, meta) {
                                        return '<button class="btn btn-primary btn-sm to-edit-role">\
                                                <i class="fa fa-pencil-alt"></i>\
                                            </button>\
                                            <button class="btn btn-primary btn-sm to-del-role">\
                                                <i class="fa fa-trash"></i>\
                                            </button>';
                                    }
                                }
                            ]
                        }).on('preXhr.dt', function () {

                        }).on('xhr.dt', function () {

                        });
                    },
                    initEvent: function () {
                        // iCheck 样式初始化
                        cEvent.initICheckStyle();

                        // 操作新增
                        elem.toRoleAdd.click(event.toRoleAdd);
                        // 操作修改
                        elem.roleDtb.on('click', '.to-edit-role', event.toRoleEdit);
                        // 操作保存
                        elem.saveSysRole.click(event.saveSysRole);
                        // 操作删除
                        elem.roleDtb.on('click', '.to-del-role', event.toDelRole);

                        // 关闭操作层层
                        elem.closeDataActionContainer.click(cEvent.closeDataActionContainer);
                    },
                    initDataActionContainer: function (actionType) {
                        switch (actionType) {
                            case 'add':
                                elem.dataActionTitle.html(lang.add);
                                break;
                            case 'edit':
                                elem.dataActionTitle.html(lang.edit);
                                break;
                        }
                        // 清空表单
                        elem.roleForm.get(0).reset();
                        // 影藏列表 显示操作
                        cEvent.showDataActionContainer();
                    },
                    toRoleAdd: function () {
                        flag.dataActionType = 'add';

                        // 初始化设置 清空表单值
                        event.initDataActionContainer('add');

                        // 设置状态字段 为1
                        cVal.setVal('radio', 'status', 1);

                        event.addTree(request.getAllSysMenu, elem.sysMenuContainer, null);
                        event.addTree(request.getAllSysPermission, elem.sysPermissionContainer, null);
                    },
                    toRoleEdit: function () {
                        var _this = $(this);
                        flag.dataActionType = 'edit';

                        // 初始化设置 清空表单值
                        event.initDataActionContainer('edit');

                        // 当前行数据
                        var role = elem.roleDtb.row(_this.parents('tr')).data().DT_RowData;
                        buf.sysRoleId = role.sys_role_id;
                        cVal.setVal('text', 'role-name', role.role_name);
                        cVal.setVal('text', 'role-desc', role.role_desc);
                        event.addTree(request.getAllSysMenu, elem.sysMenuContainer, role);
                        event.addTree(request.getAllSysPermission, elem.sysPermissionContainer, role);
                    },
                    saveSysRole: function () {
                        // 表单验证
                        if (!check.roleForm()) return false;

                        var role = {};

                        switch (flag.dataActionType) {
                            case 'add':
                                break;
                            case 'edit':
                                role.sysRoleId = buf.sysRoleId;
                                break;
                        }

                        role.dataActionType = flag.dataActionType;
                        role.roleName = cVal.getVal('text', 'role-name');
                        role.roleDesc = cVal.getVal('text', 'role-desc');
                        role.sysMenuIds = event.getSelectTreeNode(elem.sysMenuContainer);
                        role.sysPermissionIds = event.getSelectTreeNode(elem.sysPermissionContainer);

                        $.ajax({
                            url: request.saveSysRole,
                            data: {
                                role: role
                            },
                            dataType: 'json',
                            type: 'POST',
                            beforeSend: function () {
                                tool.showLoad();
                            },
                            success: function (data) {
                                // 关闭加载
                                tool.closeLoad();
                                if (data.errCode == 0) {
                                    event.dataActionSuccess();
                                } else {
                                    tool.showError(data.errMsg);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log(xhr);
                                console.log(status);
                                console.log(error);
                            }
                        });
                    },
                    toDelRole: function () {
                        var _this = $(this);
                        var role = elem.roleDtb.row(_this.parents('tr')).data().DT_RowData;
                        layer.confirm('<?php echo $this->lang->line('action_delete_tooltip'); ?>', {
                            btn: [
                                '<?php echo $this->lang->line('confirm'); ?>',
                                '<?php echo $this->lang->line('cancel'); ?>'
                            ] //按钮
                        }, function (index) {
                            layer.close(index);
                            flag.dataActionType = 'del';
                            var roleData = {
                                dataActionType: flag.dataActionType,
                                sysRoleId: role.sys_role_id
                            };
                            $.ajax({
                                url: request.saveSysRole,
                                type: 'post',
                                data: {
                                    role: roleData
                                },
                                dataType: 'JSON',
                                beforeSend: function () {
                                    tool.showLoad();
                                },
                                success: function (data) {
                                    // 关闭加载
                                    tool.closeLoad();
                                    if (data.errCode == 0) {
                                        event.dataActionSuccess();
                                    } else {
                                        tool.showError(data.errMsg);
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.log(xhr);
                                    console.log(status);
                                    console.log(error);
                                }
                            });
                        }, function () {

                        });
                    },
                    dataActionSuccess: function () {
                        // 关闭添加的容器
                        cEvent.closeDataActionContainer();
                        // 重新加载数据
                        elem.roleDtb.draw(false);
                    },
                    treeRender: function(data) {
                        var treeContainer = $(html.treeContainer());
                        $.each(data, function (index, value) {
                            var formChecked = $(html.formChecked());
                            formChecked.find('.form-checked-label-desc').html(value.value);
                            formChecked.find('input').val(value.id).attr('parent-id', value.parent_id);
                            // 是否有子菜单
                            if (value.child != undefined) {
                                var treeContainerChild = event.treeRender(value.child);
                                formChecked.append(treeContainerChild);
                            }
                            treeContainer.append(formChecked);
                        });
                        return treeContainer;
                    },
                    addTree: function(url, elem, rowData) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: 'json',
                            beforeSend: function () {
                                tool.showLoad();
                            },
                            success: function (data) {
                                if (data.errCode == 0) {
                                    // traverse build
                                    var treeContainer = event.treeRender(data.data);
                                    treeContainer.find('.tree-container').css('margin-left', '2.5rem');
                                    elem.empty().append(treeContainer);
                                } else {
                                    tool.showError(data.errMsg);
                                }

                                cEvent.initICheckStyle();

                                // 事件触发条件
                                var iCheckCheckedFlag = undefined;

                                var parentTreeNode = null;
                                $('input[name="tree-node"]').on('ifClicked', function () {
                                    var _this = $(this);
                                    _this.parents('.form-checked:first').find('input[name="tree-node"]').iCheck('check');
                                }).on('ifChecked', function () {
                                    if (iCheckCheckedFlag != undefined) return;
                                    // 选中父id
                                    var _this = $(this);
                                    parentTreeNode = $('input[name="tree-node"][value="' + _this.attr('parent-id') + '"]');
                                    if (parentTreeNode.length >= 1) {
                                        parentTreeNode.iCheck('check');
                                    }
                                }).on('ifUnchecked', function () {
                                    var _this = $(this);
                                    _this.parentsUntil('.form-checked').parent().find('input[name="tree-node"]').iCheck('uncheck');
                                    // 取消父id
                                    var parentTreeNode =  $('input[name="tree-node"][value="' + _this.attr('parent-id') + '"]');
                                    var parentTreeFormCheckedTreeNode = parentTreeNode.parents('.form-checked:first').find('.tree-container').children().find('input[name="tree-node"]:checked');
                                    if (parentTreeFormCheckedTreeNode.length <= 0) {
                                        parentTreeNode.iCheck('uncheck');
                                    }
                                });

                                if (flag.dataActionType == 'edit') {
                                    iCheckCheckedFlag = 1;
                                    $.each(rowData.sys_menu_ids.split(','), function (index, value) {
                                        $.each(elem.find('input[name="tree-node"]'), function (index, value2) {
                                            var _this = $(this);
                                            if (_this.val() == value) {
                                                _this.iCheck('check');
                                            }
                                        });
                                    });

                                    $.each(rowData.sys_permission_ids.split(','), function (index, value) {
                                        $.each(elem.find('input[name="tree-node"]'), function (index, value2) {
                                            var _this = $(this);
                                            if (_this.val() == value) {
                                                _this.iCheck('check');
                                            }
                                        });
                                    });
                                    iCheckCheckedFlag = undefined;
                                }

                                tool.closeLoad();
                            }
                        });
                    },
                    getSelectTreeNode: function (elem) {
                        console.log(elem);
                        var nodes = elem.find('input[name="tree-node"]:checked');
                        var valueArr = [];
                        $.each(nodes, function (index, node) {
                            var _this = $(this);
                            valueArr.push(_this.val());
                        });
                        return valueArr.join(',');
                    }
                };

                // 页面初始化
                event.initPage();

                // 刷新事件
                event.initEvent();

            });
        </script>
    </section>
</section>