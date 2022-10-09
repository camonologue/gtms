<?php
declare (strict_types = 1);

namespace app\admin\controller\topic;

use app\common\controller\Backend;
use think\Request;
use app\admin\model\Teacher;

class Topic extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Topic();

        $re = Teacher::all();
        foreach ($re as $k => $v) {
            $teacherdata[$v['id']] = $v;
        }
        $this->view->assign('teacherdata', $teacherdata);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            [$where, $sort, $order, $offset, $limit] = $this->buildparams();
            $total = $this->model
                // ->withJoin('teacher')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list  = $this->model
                ->limit($offset, $limit)
                ->withJoin('teacher')
                ->where($where)
                ->order($sort, $order)
                ->select();

            // if ($list) {
            //     foreach ($list as $k => $v) {
            //         if ($v->teacher) {
            //             $v->teacher->hidden();
            //         }
            //     }
            // }
            $result = ['total' => $total, 'rows' => $list];
            return json($result);
        }
        return $this->view->fetch();
    }

    // /**
    //  * 添加
    //  *
    //  * @return \think\Response
    //  */
    // public function add()
    // {
    //     if ($this->request->isPost()) {
    //         $this->token();
    //         $params = $this->request->post('row/a');
    //         if ($params) {
    //             $params = $this->preExcludeFields($params);
    //             //把数据转换成时间戳
    //             $params['start_time'] = strtotime($params['start_time']);
    //             $params['end_time'] = strtotime($params['end_time']);

    //             if ($params['end_time'] <= $params['start_time']){
    //                 $this->error('结束时间要大于开始时间！');
    //             }
    //         }
    //     }
    //     $row                 = $this->model->get($ids);
    //     $this->modelValidate = true;
    //     if (!$row) {
    //         $this->error(__('No Results were found'));
    //     }
    //     return parent::add();
    // }

}
