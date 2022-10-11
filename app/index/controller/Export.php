<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Excel;

class Export extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        return $this->view->fetch();
    }

    //导出
    public function export(){
        $excel = new Excel();
        // 数据库名
        $database = 'yfcmf_tp6';
        // 表名
        $table='fa_user';
        $type = true;
        // 重命名
        $fileName = '用户表';
        $res = $excel::exportExcel($table,$database,$type,$fileName);
    }



}
