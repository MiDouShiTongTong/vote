<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class System
 *
 */
class System extends Admin_Controller
{
    /**
     * constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 系统用户界面
     *
     * @return void
     */
    public function sys_user()
    {
        $sys_roles = $this->common_model->get('sys_role');

        // 所有基本状态
        $status_bases = $this->common_model->get('status', '', ['status_code' => 'status_base']);

        $data = [
            'page' => [
                'title' => 'System User',
                'view' => 'admin/system/sys_user'
            ],
            'breadcrumb' => [
                'breadcrumb' => [
                    [
                        'class' => '',
                        'icon' => 'fa-dashboard',
                        'name' => 'System',
                        'href' => current_url()
                    ],
                    [
                        'class' => 'active',
                        'icon' => '',
                        'name' => 'System User',
                        'href' => 'javascript:void(0)'
                    ]
                ]
            ],
            'sys_roles' => $sys_roles,
            'status_bases' => $status_bases
        ];
        $this->load_page($data);
    }

    /**
     * 获取系统用户数据
     *
     * @return void
     */
    public function get_sys_user()
    {
        $status_bases_by_match = $this->common_model->get($this->status_table, 'status_value as id, status_name as value');
        $sys_user_by_match = $this->common_model->get($this->sys_user_table, 'sys_user_id as id, user_name as value');
        $sys_role_by_match = $this->common_model->get($this->sys_role_table, 'sys_role_id as id, role_name as value');

        $columns = [
            [
                'db' => 'sys_user_id',
                'dt' => 'sys_user_id',
                'formatter_data' => [],
                'formatter' => function ($value, $row, $data) {
                    return $value;
                }
            ],
            [
                'db' => 'user_name',
                'dt' => 'user_name',
                'formatter_data' => [],
                'formatter' => function ($value, $row, $data) {
                    return $value;
                }
            ],
            [
                'db' => 'sys_role_id',
                'dt' => 'role_name',
                'formatter_data' => $sys_role_by_match,
                'formatter' => 'get_value_by_id'
            ],
            [
                'db' => 'sys_role_id',
                'dt' => 'sys_role_id',
                'formatter_data' => [],
                'formatter' => function ($value, $row, $data) {
                    return $value;
                }
            ],
            [
                'db' => 'status',
                'dt' => 'status',
                'formatter_data' => $status_bases_by_match,
                'formatter' => 'get_value_by_id',
            ],
            [
                'db' => 'updated_at',
                'dt' => 'updated_at',
                'formatter_data' => [],
                'formatter' => 'date_formatter'
            ],
            [
                'db' => 'updated_by',
                'dt' => 'updated_by',
                'formatter_data' => $sys_user_by_match,
                'formatter' => 'get_value_by_id'
            ]
        ];

        // 数据默认的条件
        // FieldName -数据库中的字段, FilterType -条件符号, FilterValue -值
        $perm_arr = [];
        // 返回查询条件
        $default_filter = $this->datatables->parse_filter_array($perm_arr);

        // 查询时用的
        $cus_filter_arr = [
            [
                'DomName' => 'user_name',
                'FieldName' => 'user_name',
                'FilterType' => 'blk'
            ]
        ];
        $cus_filter = $this->datatables->get_cus_filter_data($default_filter, $cus_filter_arr);

        // 返回JSON
        $datatable_data = $this->datatables->get_datatables_data($this->sys_user_table, $columns, $cus_filter, $default_filter, $this->sys_user_table_primaryKey);
        echo json_encode($datatable_data);
    }

    /**
     * 保存用户信息
     *
     * @return string
     */
    public function save_sys_user()
    {
        $sys_user = $this->input->post('sysUser');

        switch ($sys_user['dataActionType']) {
            case 'add':
            case 'edit':
                $data = [
                    'user_name' => $sys_user['userName'],
                    'sys_role_id' => $sys_user['sysRoleId'],
                    'status' => $sys_user['status'],
                    'updated_by' => $this->session->userdata('sys_user')->sys_user_id,
                    'updated_at' => time()
                ];
                break;
        }

        switch ($sys_user['dataActionType']) {
            case 'add':
                // 用户是否存在
                if (!empty($this->common_model->get($this->sys_user_table,'', ['user_name' => $sys_user['userName']]))) {
                    $this->result->toJson([
                        'errCode' => '400405',
                        'errMsg' => $this->lang->line('sys_user_name') . $this->lang->line('is_repeat')
                    ]);
                }
                $data['password'] = sha1('sysUser' . $sys_user['password']);
                $data['created_at'] = time();
                $data['created_by'] = $this->sign_in_sys_user->sys_user_id;
                if ($this->common_model->insert($this->sys_user_table, $data)) {
                    $this->result->toJson([
                        'errCode' => '0',
                        'errMsg' => $this->lang->line('add_success')
                    ]);
                } else {
                    $this->result->toJson([
                        'errCode' => '400401',
                        'errMsg' => $this->lang->line('add_fail')
                    ]);
                }
                break;
            case 'edit':
                if (!empty($sys_user['password'])) {
                    $data['password'] = sha1('sysUser' . $sys_user['password']);
                }
                $data['updated_at'] = time();
                $data['updated_by'] = $this->sign_in_sys_user->sys_user_id;
                $condition = [
                    'sys_user_id' => $sys_user['sysUserId']
                ];
                if ($this->common_model->update($this->sys_user_table, $data, $condition)) {
                    $this->result->toJson([
                        'errCode' => '0',
                        'errMsg' => $this->lang->line("edit_success")
                    ]);
                } else {
                    $this->result->toJson([
                        'errCode' => '400402',
                        'errMsg' => $this->lang->line("edit_fail")
                    ]);
                }
                break;
            case 'del':
                $condition = [
                    'sys_user_id' => $sys_user['sysUserId']
                ];
                if ($this->common_model->delete($this->sys_user_table, $condition)) {
                    $this->result->toJson([
                        'errCode' => '0',
                        'errMsg' => $this->lang->line("delete_success")
                    ]);
                } else {
                    $this->result->toJson([
                        'errCode' => '400403',
                        'errMsg' => $this->lang->line("delete_fail")
                    ]);
                }
                break;
        }
    }

    /**
     * 系统角色界面
     *
     */
    public function sys_role()
    {

        $sys_menus = $this->sys_menu_model->get_all_sys_menu();

        $data = [
            'page' => [
                'title' => 'System Role',
                'view' => 'admin/system/role'
            ],
            'breadcrumb' => [
                'breadcrumb' => [
                    [
                        'class' => '',
                        'icon' => 'fa-dashboard',
                        'name' => 'System',
                        'href' => current_url()
                    ],
                    [
                        'class' => 'active',
                        'icon' => '',
                        'name' => 'System Role',
                        'href' => 'javascript:void(0)'
                    ]
                ]
            ],
            'sys_menus' => $sys_menus,
        ];
        $this->load_page($data);

    }

    /**
     * 获取角色信息
     *
     */
    public function get_sys_role()
    {
        // 所有基本状态
        $sys_user_by_match = $this->common_model->get($this->sys_user_table, 'sys_user_id as id, user_name as value');

        $columns = [
            [
                'db' => 'sys_role_id',
                'dt' => 'sys_role_id',
                'formatter_data' => [],
                'formatter' => function ($value, $row, $data) {
                    return $value;
                }
            ],
            [
                'db' => 'role_name',
                'dt' => 'role_name',
                'formatter_data' => [],
                'formatter' => function ($value, $row, $data) {
                    return $value;
                }
            ],
            [
                'db' => 'role_desc',
                'dt' => 'role_desc',
                'formatter_data' => [],
                'formatter' => function ($value, $row, $data) {
                    return $value;
                }
            ],
            [
                'db' => 'updated_by',
                'dt' => 'updated_by',
                'formatter_data' => $sys_user_by_match,
                'formatter' => 'get_value_by_id'
            ],
            [
                'db' => 'updated_at',
                'dt' => 'updated_at',
                'formatter_data' => [],
                'formatter' => 'date_formatter'
            ],
            [
                'db' => 'sys_menu_ids',
                'dt' => 'sys_menu_ids',
                'isRowData' => true
            ],
            [
                'db' => 'sys_permission_ids',
                'dt' => 'sys_permission_ids',
                'isRowData' => true
            ],
        ];

        // 数据默认的条件
        // FieldName -数据库中的字段, FilterType -条件符号, FilterValue -值
        $perm_arr = [];
        // 返回查询条件
        $default_filter = $this->datatables->parse_filter_array($perm_arr);

        // 查询时用的
        $cus_filter_arr = [
            [
                'DomName' => 'role_name',
                'FieldName' => 'role_name',
                'FilterType' => 'blk'
            ]
        ];
        $cus_filter = $this->datatables->get_cus_filter_data($default_filter, $cus_filter_arr);

        // 返回JSON
        $datatable_data = $this->datatables->get_datatables_data($this->sys_role_table, $columns, $cus_filter, $default_filter, $this->sys_role_table_primaryKey);
        echo json_encode($datatable_data);
    }

    /**
     * 保存角色信息
     *
     * @return void
     */
    public function save_sys_role()
    {
        $role = $this->input->post('role');

        switch ($role['dataActionType']) {
            case 'add':
            case 'edit':
                $data = [
                    'role_name' => $role['roleName'],
                    'role_desc' => $role['roleDesc'],
                    'sys_menu_ids' => $role['sysMenuIds'],
                    'sys_permission_ids' => $role['sysPermissionIds']
                ];
                break;
        }

        switch ($role['dataActionType']) {
            case 'add':
                $data['created_at'] = time();
                $data['created_by'] = $this->sign_in_sys_user->sys_user_id;
                $data['updated_at'] = time();
                $data['updated_by'] = $this->sign_in_sys_user->sys_user_id;
                if ($this->common_model->insert($this->sys_role_table, $data)) {
                    $this->result->toJson([
                        'errCode' => '0',
                        'errMsg' => $this->lang->line('add_success')
                    ]);
                } else {
                    $this->result->toJson([
                        'errCode' => '400401',
                        'errMsg' => $this->lang->line('add_fail')
                    ]);
                }
                break;
            case 'edit':
                $data['updated_at'] = time();
                $data['updated_by'] = $this->sign_in_sys_user->sys_user_id;
                $condition = [
                    'sys_role_id' => $role['sysRoleId']
                ];
                if ($this->common_model->update($this->sys_role_table, $data, $condition)) {
                    $this->result->toJson([
                        'errCode' => '0',
                        'errMsg' => $this->lang->line('edit_success')
                    ]);
                } else {
                    $this->result->toJson([
                        'errCode' => '400402',
                        'errMsg' => $this->lang->line('edit_fail')
                    ]);
                }
                break;
            case 'del':
                $condition = [
                    'sys_role_id' => $role['sysRoleId']
                ];
                if ($this->common_model->delete($this->sys_role_table, $condition)) {
                    $this->result->toJson([
                        'errCode' => '0',
                        'errMsg' => $this->lang->line('delete_success')
                    ]);
                } else {
                    $this->result->toJson([
                        'errCode' => '400103',
                        'errMsg' => $this->lang->line('delete_fail')
                    ]);
                }
                break;
        }
    }

    /**
     * 返回menu数据
     */
    public function get_all_sys_menu()
    {
        $this->result->toJson([
            'errCode' => '0',
            'errMsg' => $this->lang->line('get_success'),
            'data' => $this->sys_menu_model->get_all_sys_menu('get_all')
        ]);
    }

    /**
     * 返回permission数据
     *
     */
    public function get_all_sys_permission()
    {
        $this->result->toJson([
            'errCode' => '0',
            'errMsg' => $this->lang->line('get_success'),
            'data' => $this->sys_permission_model->get_all_sys_permission()
        ]);
    }

    /**
     * 没有权限网页
     *
     * @return void
     */
    public function no_permission()
    {
        echo '没有权限';
    }
}