<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Features
 *
 */
class IndexController extends Frontend_Controller
{
    /**
     * 默认路由跳转
     *
     * @return void
     */
    public function index()
    {
        redirect('question/list');
    }
}
