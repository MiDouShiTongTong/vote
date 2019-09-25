<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Features
 *
 */
class Features extends Admin_Controller
{
    /**
     * Features constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
        // load model
        $this->load->model('question_model');
    }

    /**
     * 问卷视图
     *
     * @return void
     */
    public function question()
    {
        $status_bases = $this->common_model->get($this->status_table, '', ['status_code' => 'status_base']);

        $data = [
            'page' => [
                'title' => 'Question',
                'view' => 'admin/features/question'
            ],
            'breadcrumb' => [
                'breadcrumb' => [
                    [
                        'class' => '',
                        'icon' => 'fa-dashboard',
                        'name' => 'Features',
                        'href' => current_url()
                    ],
                    [
                        'class' => 'active',
                        'icon' => '',
                        'name' => 'Question',
                        'href' => 'javascript:void(0)'
                    ]
                ]
            ],
            'status_bases' => $status_bases
        ];
        $this->load_page($data);
    }

    /**
     * 获取所有问卷
     *
     * @return string
     */
    public function get_question()
    {
        $status_bases = $this->common_model->get('status', 'status_value as id, status_name as value', ['status_code' => 'status_base']);
        $sys_users = $this->common_model->get('sys_user', 'sys_user_id as id, user_name as value');

        $columns = [
            [
                'db' => 'question_id',
                'dt' => 'question_id',
                'formatter_data' => [],
                'formatter' => function ($value, $row, $data) {
                    return $value;
                }
            ],
            [
                'db' => 'question_title',
                'dt' => 'question_title',
                'formatter_data' => [],
                'formatter' => function ($value, $row, $data) {
                    return $value;
                }
            ],
            [
                'db' => 'start_time',
                'dt' => 'start_time',
                'formatter_data' => [],
                'formatter' => 'date_formatter'
            ],
            [
                'db' => 'end_time',
                'dt' => 'end_time',
                'formatter_data' => [],
                'formatter' => 'date_formatter'
            ],
            [
                'db' => 'status',
                'dt' => 'status',
                'formatter_data' => $status_bases,
                'formatter' => 'get_value_by_id'
            ],
            [
                'db' => 'updated_by',
                'dt' => 'updated_by',
                'formatter_data' => $sys_users,
                'formatter' => 'get_value_by_id'
            ],
            [
                'db' => 'updated_at',
                'dt' => 'updated_at',
                'formatter_data' => [],
                'formatter' => 'date_formatter'
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
                'DomName' => 'question_title',
                'FieldName' => 'question_title',
                'FilterType' => 'blk'
            ]
        ];
        $cus_filter = $this->datatables->get_cus_filter_data($default_filter, $cus_filter_arr);

        // 返回JSON
        $datatable_data = $this->datatables->get_datatables_data($this->question_table, $columns, $cus_filter, $default_filter, $this->question_table_primaryKey);
        echo json_encode($datatable_data);
    }

    /**
     * 保存问卷
     *
     * @return string
     */
    public function save_question()
    {
        $post_data = $this->input->post();
        $question = $post_data['question'];

        switch ($question['dataActionType']) {
            case 'add':
            case 'edit':
                $data = [
                    'question_title' => $question['questionTitle'],
                    'start_time' => $question['startTime'],
                    'end_time' => $question['endTime'],
                    'status' => $question['status']
                ];
                break;
        }

        switch ($question['dataActionType']) {
            case 'add':
                $data['created_at'] = time();
                $data['created_by'] = $this->sign_in_sys_user->sys_user_id;
                $data['updated_at'] = time();
                $data['updated_by'] = $this->sign_in_sys_user->sys_user_id;
                // 新增主表
                if (!$this->common_model->insert($this->question_table, $data)) {
                    $this->result->toJson([
                        'errCode' => '400401',
                        'errMsg' => $this->lang->line('add_fail')
                    ]);
                }
                $question_id = $this->common_model->get_insert_id();
                break;
            case 'edit':
                $data['updated_at'] = time();
                $data['updated_by'] = $this->sign_in_sys_user->sys_user_id;
                $question_id = $question['questionId'];
                $condition = [
                    'question_id' => $question_id
                ];
                // 修改主表
                if (!$this->common_model->update($this->question_table, $data, $condition)) {
                    $this->result->toJson([
                        'errCode' => '400402',
                        'errMsg' => $this->lang->line('edit_fail')
                    ]);
                }
                break;
            case 'del':
                $condition = [
                    'question_id' => $question['questionId']
                ];
                // 删除主表
                if ($this->common_model->delete($this->question_table, $condition)) {
                    // 删除子表
                    $this->common_model->delete($this->question_item_table, $condition);
                    $this->common_model->delete($this->question_item_option_table, $condition);
                    $this->result->toJson([
                        'errCode' => '0',
                        'errMsg' => $this->lang->line('del_success')
                    ]);
                }
        }

        switch ($question['dataActionType']) {
            case 'add':
            case 'edit':
                // 问题以及选项
                foreach ($question['questionItems'] as $_key_question_item => $question_item) {
                    $data = [
                        'question_id' => $question_id,
                        'question_item_title' => $question_item['questionItemTitle'],
                        'is_multiple' => $question_item['isMultiple']
                    ];
                    // 新增还是修改
                    if (isset($question_item['editQuestionItemId']) && !empty($question_item['editQuestionItemId'])) {
                        $condition = [
                            'question_id' => $question_id,
                            'question_item_id' => $question_item['editQuestionItemId']
                        ];
                        $this->common_model->update($this->question_item_table, $data, $condition);
                        $question_item_id = $question_item['editQuestionItemId'];
                    } else {
                        $this->common_model->insert($this->question_item_table, $data);
                        $question_item_id = $this->common_model->get_insert_id();
                    }

                    // 修改问卷选项
                    foreach ($question_item['questionItemOptions'] as $_key_question_item_option => $question_item_option) {
                        $data = [
                            'question_id' => $question_id,
                            'question_item_id' => $question_item_id,
                            'value' => $question_item_option['value']
                        ];
                        if (isset($question_item_option['editQuestionItemOptionId']) && !empty($question_item_option['editQuestionItemOptionId'])) {
                            $condition = [
                                'question_id' => $question_id,
                                'question_item_id' => $question_item['editQuestionItemId'],
                                'question_item_option_id' => $question_item_option['editQuestionItemOptionId']
                            ];
                            $this->common_model->update($this->question_item_option_table, $data, $condition);
                        } else {
                            $this->common_model->insert($this->question_item_option_table, $data);
                        }
                    }
                }

                // 删除问题以及选项
                if (isset($question['delQuestionItemIds']) && !empty($question['delQuestionItemIds'])) {
                    $question_item_id_arr = explode(',', $question['delQuestionItemIds']);
                    // 问题表
                    $this->db->where_in($this->question_item_table_primaryKey, $question_item_id_arr)->delete($this->question_item_table);
                    // 选项表
                    $this->db->where_in($this->question_item_table_primaryKey, $question_item_id_arr)->delete($this->question_item_option_table);
                }

                if (isset($question['delQuestionItemOptionIds']) && !empty($question['delQuestionItemOptionIds'])) {
                    $question_item_option_id_arr = explode(',', $question['delQuestionItemOptionIds']);
                    // 选项表
                    $this->db->where_in($this->question_item_option_table_primaryKey, $question_item_option_id_arr)->delete($this->question_item_option_table);
                }
                break;
        }

        $this->result->toJson([
            'errCode' => '0',
            'errMsg' => $this->lang->line('edit_success')
        ]);
    }

    /**
     * 获取问卷详情 [修改]
     *
     * @return string
     */
    public function get_question_detail()
    {
        $post_data = $this->input->post();
        $question_id = $post_data['questionId'];

        $question = $this->question_model->get_question_detail($question_id);

        $this->result->toJson([
            'errCode' => '0',
            'errMsg' => $this->lang->line('get_success'),
            'question' => $question
        ]);
    }

    /**
     * 获取提交列表
     *
     */
    public function question_collect()
    {
        $data = [
            'page' => [
                'title' => 'Question Collect',
                'view' => 'admin/features/question_collect'
            ],
            'breadcrumb' => [
                'breadcrumb' => [
                    [
                        'class' => '',
                        'icon' => 'fa-dashboard',
                        'name' => 'Features',
                        'href' => current_url()
                    ],
                    [
                        'class' => 'active',
                        'icon' => '',
                        'name' => 'Question Collect',
                        'href' => 'javascript:void(0)'
                    ]
                ]
            ]
        ];
        $this->load_page($data);
    }

    /**
     * 获取提交列表
     *
     */
    public function get_question_collect()
    {
        $question_title_by_match = $this->common_model->get($this->question_table, 'question_id as id, question_title as value');

        $columns = [
            [
                'db' => 'question_collect_id',
                'dt' => 'question_collect_id',
                'formatter_data' => [],
                'formatter' => function ($value, $row, $data) {
                    return $value;
                }
            ],
            [
                'db' => 'ip_address',
                'dt' => 'ip_address',
                'formatter_data' => [],
                'formatter' => function ($value, $row, $data) {
                    return $value;
                }
            ],
            [
                'db' => 'question_id',
                'dt' => 'question_id',
                'formatter_data' => [],
                'formatter' => function ($value, $row, $data) {
                    return $value;
                }
            ],
            [
                'db' => 'question_id',
                'dt' => 'question_title',
                'formatter_data' => $question_title_by_match,
                'formatter' => 'get_value_by_id'
            ],
            [
                'db' => 'created_at',
                'dt' => 'created_at',
                'formatter_data' => [],
                'formatter' => 'date_formatter'
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
                'DomName' => 'ip_address',
                'FieldName' => 'ip_address',
                'FilterType' => 'blk'
            ]
        ];
            $cus_filter = $this->datatables->get_cus_filter_data($default_filter, $cus_filter_arr);

        // 返回JSON
        $datatable_data = $this->datatables->get_datatables_data($this->question_collect_table, $columns, $cus_filter, $default_filter, $this->question_collect_table_primaryKey);
        echo json_encode($datatable_data);
    }

    /**
     * 保存提交列表
     *
     * @return string
     */
    public function save_question_collect()
    {
        $post_data = $this->input->post();
        $question_collect = $post_data['questionCollect'];

        switch ($question_collect['dataActionType']) {
            case 'del':
                $condition = [
                    'question_collect_id' => $question_collect['questionCollectId']
                ];
                // 删除主表
                if ($this->common_model->delete($this->question_collect_table, $condition)) {
                    // 删除子表
                    $this->common_model->delete($this->question_collect_detail_table, $condition);
                    $this->result->toJson([
                        'errCode' => '0',
                        'errMsg' => $this->lang->line('delete_success')
                    ]);
                }
        }
    }

    /**
     * 获取问卷提交
     *
     */
    public function get_question_detail_and_question_collect_detail()
    {
        $post_data = $this->input->post();
        $question_collect_id = $post_data['questionCollectId'];
        $question_id = $post_data['questionId'];

        $question_detail = $this->question_model->get_question_detail($question_id);
        $question_collect_detail = $this->common_model->get($this->question_collect_detail_table, '', ['question_collect_id' => $question_collect_id]);

        $this->result->toJson([
            'errCode' => '0',
            'errMsg' => $this->lang->line('get_success'),
            'questionDetail' => $question_detail,
            'questionCollectDetail' => $question_collect_detail
        ]);
    }
}
