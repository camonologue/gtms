<?php

namespace app\admin\controller\treatise;

use app\common\controller\Backend;
use \app\admin\model\Treatise;
use think\Request;

class CorrectLog extends Backend
{
    protected $relationSearch = true;

    protected $searchFields = 'id,username,nickname';

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();

        $this->model = new \app\admin\model\CorrectLog();

        $re = Treatise::all();
        foreach ($re as $k => $v) {
            $treatisedata[$v['id']] = $v;
        }
        $this->view->assign('treatisedata', $treatisedata);

        $teacherdata = [];
        $this->view->assign('teacherdata', $teacherdata);

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
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list  = $this->model
                ->limit($offset, $limit)
                ->where($where)
                ->order($sort, $order)
                ->select();

            // 数据处理
            foreach ($list as $v) {
                if($v->treatise){
                    $v->treatise->hidden(['', '']);
                    if (($v->treatise)->user){
                        ($v->treatise)->user->hidden(['password', 'salt']);
                    }
                }
                if($v->teacher){
                    $v->teacher->hidden(['', '']);
                }

            }

            $result = ['total' => $total, 'rows' => $list];
            return json($result);
        }
        return $this->view->fetch();
    }

}
