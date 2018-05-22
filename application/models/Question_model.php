<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Question_model
 *
 */
class Question_model extends CI_Model
{

    protected $question_table = 'q_question';
    protected $question_table_primaryKey = 'question_id';

    protected $question_item_table = 'q_question_item';
    protected $question_item_table_primaryKey = 'question_item_id';

    protected $question_item_option_table = 'q_question_item_option';
    protected $question_item_option_table_primaryKey = 'question_item_option_id';

    protected $question_collect_table = 'q_question_collect';
    protected $question_collect_table_primaryKey = 'question_collect_id';

    protected $question_collect_detail_table = 'q_question_collect_detail';
    protected $question_collect_detail_table_primaryKey = 'question_collect_detail_id';

    /**
     * 获取投票详情
     *
     * @param  string $question_id 投票id
     * @return mixed
     */
    public function get_question_detail($question_id)
    {
        $condition = [
            'question_id' => $question_id
        ];
        // 获取投票
        $question = $this->common_model->get($this->question_table, '', $condition);
        if (!empty($question)) {
            $question = $question[0];
            // 解析时间戳
            $question->start_time = date('Y-m-d H:i:s');
            $question->end_time = date('Y-m-d H:i:s');
            // 获取问题
            $question_items = $this->common_model->get($this->question_item_table, '', $condition);
            if (!empty($question_items)) {
                // 获取投票 选择的总人数
                $question->question_collect_counter = $this->db->query("SELECT COUNT(DISTINCT ip_address) AS questionCollectCounter FROM $this->question_collect_table WHERE question_id = $question_id")->result()[0]->questionCollectCounter;
                foreach ($question_items as $_key_question_item => $question_item) {
                    $condition = [
                        'question_id' => $question->question_id,
                        'question_item_id' => $question_item->question_item_id
                    ];
                    // 获取问题 选择的总数
                    $question_item->question_item_counter = $this->common_model->count_all($this->question_collect_detail_table, $condition, $this->question_collect_table_primaryKey);
                    // 获取问题选项
                    $question_item_options = $this->common_model->get($this->question_item_option_table, '', $condition);
                    if (!empty($question_item_options)) {
                        foreach ($question_item_options as $_key_question_item_option => $question_item_option ) {
                            // 获取问题选项 选择的总数
                            $condition = [
                                'question_id' => $question->question_id,
                                'question_item_id' => $question_item->question_item_id,
                                'question_item_option_id' => $question_item_option->question_item_option_id
                            ];
                            $question_item_option->question_item_option_counter = $this->common_model->count_all($this->question_collect_detail_table, $condition, $this->question_collect_table_primaryKey);
                            // 问题选项 选择的总数 所占 百分比
                            if ($question_item_option->question_item_option_counter != 0) {
                                // 取小数点后两位
                                $question_item_option->question_item_option_percentage = round(round($question_item_option->question_item_option_counter / $question_item->question_item_counter, 2) * 100);
                            } else {
                                $question_item_option->question_item_option_percentage = 0;
                            }
                        }
                        $question_item->question_item_options = $question_item_options;
                    }
                }
                $question->question_items = $question_items;
            }
            return $question;
        } else {
            $this->result->toJson([
                'errCode' => '400404',
                'errMsg' => $this->lang->line('get_fail')
            ]);
        }
        return null;
    }
}