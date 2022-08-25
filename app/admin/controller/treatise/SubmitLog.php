<?php

namespace app\admin\controller\treatise;

use app\common\controller\Backend;
use think\Request;

class SubmitLog extends Backend
{
    public function _initialize()
    {
        parent::_initialize();

        $this->model = new \app\admin\model\SubmitLog();

    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            [$where, $sort, $order, $offset, $limit] = $this->buildparams();
            $total = $this->model
                ->withJoin('user')
                ->where($where)
                ->count();
            $list  = $this->model
                ->limit($offset, $limit)
                ->withJoin('user')
                ->where($where)
                ->select();

            // 数据处理
//            foreach ($list as $v) {
//                if($v->treatise){
//                    $v->treatise->hidden(['', '']);
//                    if (($v->treatise)->user){
//                        ($v->treatise)->user->hidden(['password', 'salt']);
//                    }
//                }
//            }

            $result = ['total' => $total, 'rows' => $list];
            return json($result);
        }
        return $this->view->fetch();
    }

//    /**
//     * 显示编辑资源表单页.
//     *
//     * @param  int  $id
//     * @return \think\Response
//     */
//    public function edit($ids = NULL)
//    {
//        //
//    }
}
