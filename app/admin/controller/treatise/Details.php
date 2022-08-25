<?php

namespace app\admin\controller\treatise;

use app\common\controller\Backend;
use think\Request;

class Details extends Backend
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        return $this->view->fetch();
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($ids = NULL)
    {
        //
    }
}
