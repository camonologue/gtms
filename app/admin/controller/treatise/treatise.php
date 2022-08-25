<?php
declare (strict_types = 1);

namespace app\admin\controller\treatise;

use app\admin\model\User as UserModel;
use app\common\controller\Backend;

class treatise extends Backend
{
    protected $relationSearch = true;

    protected $searchFields = 'id,username,nickname';

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();

        $this->model = new \app\admin\model\Treatise();

        $re = UserModel::all();
        foreach ($re as $k => $v) {
            $userdata[$v['id']] = $v;
        }
        $this->view->assign('userdata', $userdata);
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
                ->order($sort, $order)
                ->count();
            $list  = $this->model
                ->limit($offset, $limit)
                ->withJoin('user')
                ->where($where)
                ->order($sort, $order)
                ->select();

            $result = ['total' => $total, 'rows' => $list];
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id
     * @return \think\Response
     */
//    public function edit($ids = NULL)
//    {
//        //
//
//        return parent::edit();
//    }
}
