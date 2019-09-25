<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Features
 *
 */
class Features extends Frontend_Controller
{

    /**
     * Question constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
        // load model
        $this->load->model('question_model');
    }

    /**
     * 问卷列表
     *
     * @return void
     */
    public function question_list()
    {
        $current_time = time();
        $questions = $this->db->query("SELECT * FROM " . $this->question_table . " WHERE start_time <= $current_time AND end_time >= $current_time AND status = 1")->result();
        if (empty($questions)) {
            Tool::show_tooltip([
                'page_title' => '来自友情提示',
                'tooltip_title' => '暂无问卷可填写',
                'tooltip_content' => '当前暂无可填写的问卷，请稍后重试'
            ]);
        }

        $data = [
            'page' => [
                'title' => '问卷列表',
                'view' => 'frontend/features/question_list'
            ],
            'questions' => $questions
        ];
        $this->load_page($data);
    }

    /**
     * 问卷视图
     *
     * @param  string $question_id
     * @return void
     */
    public function question($question_id = '')
    {
        if (empty($question_id)) show_error('参错错误', '0301', 'Error');
        // 获取Ip
        $question_info = [
            'question_id' => $question_id
        ];
        // 是否已经填写过问卷
        $condition = [
            'question_id' => $question_id,
            'ip_address' => Tool::get_ip()
        ];
        if (empty($this->common_model->get($this->question_collect_table, '', $condition))) {
            // 获取问卷详情
            $question_info['question_join'] = 'false';
        } else {
            $question_info['question_join'] = 'true';
        }

        $data = [
            'page' => [
                'title' => '问卷调查',
                'view' => 'frontend/features/question'
            ],
            'question_info' => $question_info
        ];
        $this->load_page($data);
    }

    /**
     * 获取问卷详情
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
     * 保存问卷结果
     *
     * @return string
     */
    public function question_collect()
    {
        $post_data = $this->input->post();
        $question_collect_arr = $post_data['questionCollect'];
        $question_collect_detail_arr = $post_data['questionCollectDetail'];

        $data = [
            'question_id' => $question_collect_arr['questionId'],
            'ip_address' => Tool::get_ip(),
            'created_at' => time()
        ];
        $this->common_model->insert($this->question_collect_table, $data);
        $question_collect_id = $this->common_model->get_insert_id();

        // 添加到数据的 数组
        foreach ($question_collect_detail_arr as $_key => $question_collect_detail) {

            $data = [
                'question_collect_id' => $question_collect_id,
                'question_id' => $question_collect_detail['questionId'],
                'question_item_id' => $question_collect_detail['questionItemId'],
                'question_item_option_id' => $question_collect_detail['questionItemOptionId']
            ];
            $this->common_model->insert($this->question_collect_detail_table, $data);
        }


        $this->result->toJson([
            'errCode' => '0',
            'errMsg' => $this->lang->line('commit_success')
        ]);
    }
}
