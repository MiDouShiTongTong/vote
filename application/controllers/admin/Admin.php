<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Admin
 *
 */
class Admin extends Admin_Controller
{

    /**
     * Admin constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 后台首页视图
     *
     * @return void
     */
    public function index()
    {
        $data = [
            'page' => [
                'title' => 'System',
                'view'  => 'admin/system/system_info'
            ],
            'breadcrumb' => [
                'breadcrumb' => [
                    [
                        'class' => '',
                        'icon'  => 'fa-dashboard',
                        'name'  => 'System',
                        'href'  => site_url('admin')
                    ],
                    [
                        'class' => 'active',
                        'icon'  => '',
                        'name'  => 'system-info',
                        'href'  => 'javascript:void(0)'
                    ]
                ]
            ]
        ];
        $this->load_page($data);
    }
}