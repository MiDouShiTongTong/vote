<section class="content">
    <!-- search bar container -->
    <?php
    $data = [
        'conditions_info' => [
            [
                'field' => 'user_name',
                'type' => 'text',
                'field_placeholder' => $this->lang->line('sys_user_name')
            ]
        ],
    ];
    $this->load->view('admin/components/search_bar', $data);
    ?>

    <!-- to data action container -->
    <div class="to-data-action-container text-right mb-3">
        <div class="dropdown">
            <button class="btn btn-primary to-add-sys-user"><?php echo $this->lang->line('add'); ?></button>
        </div>
    </div>

    <!-- data list -->
    <div class="data-container">
        <table class="table table-hover table-striped table-sort dataTable dtb sys-user-dtb">
            <thead>
            <tr>
                <th><?php echo $this->lang->line('seq'); ?></th>
                <th><?php echo $this->lang->line('sys_user_name'); ?></th>
                <th><?php echo $this->lang->line('role_name'); ?></th>
                <th><?php echo $this->lang->line('status'); ?></th>
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
                        <h3 class="box-title data-action-title"></h3>
                        <button type="button" class="close" data-target="close-data-action-container">&times;</button>
                    </div>
                    <div class="bg-white">
                        <div class="box-body">
                            <form id="sys-user-form" class="form-horizontal">
                                <div class="form-group">
                                    <label for="user-name" class="col-sm-2 col-form-label"><?php echo $this->lang->line('sys_user_name'); ?>
                                        <span class="input-require">*</span></label>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="user-name" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-2 col-form-label"><?php echo $this->lang->line('password'); ?></label>
                                    <div class="col-sm-10">
                                        <input type="password"  name="password" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="sys-role-id" class="col-sm-2 col-form-label"><?php echo $this->lang->line('role'); ?></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="sys-role-id">
                                            <?php
                                            foreach ($sys_roles as $sys_role) {
                                                echo '<option value="'. $sys_role->sys_role_id . '">' . $sys_role->role_name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="status" class="col-sm-2 col-form-label"><?php echo $this->lang->line('status'); ?></label>
                                    <div class="col-sm-10 form-checked-container">
                                        <?php
                                        if (!empty($status_bases)) {
                                            foreach ($status_bases as $_key => $status_base) {
                                        ?>
                                        <div class="form-checked form-checked-inline">
                                            <label class="form-checked-label">
                                                <input type="radio" name="status" class="minimal" value="<?php echo $status_base->status_value ?>">
                                                <span class="form-checked-label-desc"><?php echo $status_base->status_name; ?></span>
                                            </label>
                                        </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-info pull-right save-sys-user"><?php echo $this->lang->line('save'); ?></button>
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
                'use strict';
                // elem
                var elem = {
                    dataContainer: $('.data-container'),
                    dataActionContainer: $('.data-action-container'),
                    dataActionTitle: $('.data-action-title'),
                    closeDataActionContainer: $('[data-target="close-data-action-container"]'),

                    sysUserDtb: $('.sys-user-dtb'),
                    sysUserForm: $('#sys-user-form'),

                    toAddSysUser: $('.to-add-sys-user'),
                    saveSysUser: $('.save-sys-user')
                };

                // request
                var request = {
                    saveSysUser: siteUrl + 'admin/system/save_sys_user',
                    getSysUser: siteUrl + 'admin/system/get_sys_user'
                };

                // curr add type
                var flag = {
                    dataActionType: undefined,
                    sysUserFormCheck: undefined
                };

                // buf
                var buf = {
                    sysUserId: undefined
                };

                // check
                var check = {
                    sysUserForm: function () {
                        flag.sysUserFormCheck = undefined;

                        elem.sysUserForm.validate({
                            rules: {
                                'user-name': {
                                    required: true,
                                    maxlength: 20
                                }
                            },
                            messages: {
                                'user-name': {
                                    required: '<?php echo $this->lang->line('sys_user_name') . $this->lang->line('form_validate_required'); ?>',
                                    maxlength: '<?php echo $this->lang->line('sys_user_name') . $this->lang->line('form_validate_max_length'); ?>{0}'
                                }
                            }
                        });

                        if (!elem.sysUserForm.valid()) {
                            flag.sysUserFormCheck = 1;
                            return false;
                        } else {
                            return true;
                        }
                    }
                };

                // event
                var event = {
                    initPage: function () {
                        elem.sysUserDtb = elem.sysUserDtb.DataTable({
                            ajax: {
                                url: request.getSysUser,
                                type: 'POST'
                            },
                            columns: [
                                {
                                    data: 'sys_user_id'
                                },
                                {
                                    data: 'user_name'
                                },
                                {
                                    data: 'role_name'
                                },
                                {
                                    data: 'status'
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
                                        return '<button class="btn btn-primary btn-sm to-edit-sys-user">\
                                                <i class="fa fa-pencil-alt"></i>\
                                            </button>\
                                            <button class="btn btn-primary btn-sm to-del-sys-user">\
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
                        elem.toAddSysUser.click(event.toAddSysUser);
                        // 操作修改
                        elem.sysUserDtb.on('click', '.to-edit-sys-user', event.toEditSysUser);
                        // 操作保存
                        elem.saveSysUser.click(event.saveSysUser);
                        // 操作删除
                        elem.sysUserDtb.on('click', '.to-del-sys-user', event.toDelSysUser);

                        // 关闭操作层层
                        elem.closeDataActionContainer.click(cEvent.closeDataActionContainer);
                    },
                    initDataActionContainer: function (actionType) {
                        var password = cForm.getForm('text', 'password');
                        switch (actionType) {
                            case 'add':
                                elem.dataActionTitle.html(lang.add);
                                password.next().remove();
                                break;
                            case 'edit':
                                elem.dataActionTitle.html(lang.edit);
                                if (password.next().length == 0) {
                                    password.after($('<span class="edit-tooltip"><?php echo $this->lang->line('null_note_edit') ?></span>'));
                                }
                                break;
                        }
                        // 取消 ICheck 插件样式 和 取消选中
                        cVal.setUnCheck('status');
                        // 清空表单
                        elem.sysUserForm.get(0).reset();
                        // 影藏列表 显示操作
                        cEvent.showDataActionContainer();
                    },
                    toAddSysUser: function () {
                        flag.dataActionType = 'add';

                        // 初始化设置 清空表单值
                        event.initDataActionContainer('add');

                        // 设置状态字段 为1
                        cVal.setVal('radio', 'status', 1);
                    },
                    toEditSysUser: function () {
                        var _this = $(this);
                        flag.dataActionType = 'edit';

                        // 初始化设置 清空表单值
                        event.initDataActionContainer('edit');

                        // 当前行数据
                        var sysUser = elem.sysUserDtb.row(_this.parents('tr')).data().DT_RowData;
                        buf.sysUserId = sysUser.sys_user_id;
                        cVal.setVal('text', 'user-name', sysUser.user_name);
                        cVal.setVal('select', 'sys-role-id', sysUser.sys_role_id);
                        cVal.setVal('radio', 'status', sysUser.status);
                    },
                    saveSysUser: function () {
                        // 表单验证
                        if (!check.sysUserForm()) return false;

                        var sysUser = {};

                        switch (flag.dataActionType) {
                            case 'add':
                                break;
                            case 'edit':
                                sysUser.sysUserId = buf.sysUserId;
                                break;
                        }

                        sysUser.dataActionType = flag.dataActionType;
                        sysUser.userName = cVal.getVal('text', 'user-name');
                        sysUser.password = cVal.getVal('text', 'password');
                        sysUser.sysRoleId = cVal.getVal('select', 'sys-role-id');
                        sysUser.status = cVal.getVal('radio', 'status');

                        $.ajax({
                            url: request.saveSysUser,
                            data: {
                                sysUser: sysUser
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
                    toDelSysUser: function () {
                        var _this = $(this);
                        var sysUser = elem.sysUserDtb.row(_this.parents('tr')).data().DT_RowData;
                        layer.confirm('<?php echo $this->lang->line('action_delete_tooltip'); ?>', {
                            btn: [
                                '<?php echo $this->lang->line('confirm'); ?>',
                                '<?php echo $this->lang->line('cancel'); ?>'
                            ] //按钮
                        }, function (index) {
                            layer.close(index);
                            flag.dataActionType = 'del';
                            var sysUserData = {
                                dataActionType: flag.dataActionType,
                                sysUserId: sysUser.sys_user_id
                            };
                            $.ajax({
                                url: request.saveSysUser,
                                type: 'post',
                                data: {
                                    sysUser: sysUserData
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
                        elem.sysUserDtb.draw(false);
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
