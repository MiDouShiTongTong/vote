<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Mresult
 *
 */
class Result
{
    /**
     * 返回对象json
     *
     * @return string
     */
    public function toJson($data)
    {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}