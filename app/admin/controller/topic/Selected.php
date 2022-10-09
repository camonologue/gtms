<?php
declare (strict_types = 1);

namespace app\admin\controller\topic;

use app\common\controller\Backend;
use think\Request;
use app\admin\model\Topic;
use app\admin\model\User;

class Selected extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\TopicSelected();

        
        $re = Topic::all();
        foreach ($re as $k => $v) {
            $topicdata[$v['id']] = $v;
        }
        $this->view->assign('topicdata', $topicdata);

        $rt = User::all();
        foreach ($rt as $k => $v) {
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
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list  = $this->model
                ->limit($offset, $limit)
                // ->withJoin('teacher')
                ->where($where)
                ->order($sort, $order)
                ->select();

            if ($list) {
                foreach ($list as $k => $v) {
                    if ($v->user) {
                        $v->user->hidden();
                    }
                    if ($v->topic) {
                        $v->topic->hidden();
                    }
                }
            }
            $result = ['total' => $total, 'rows' => $list];
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     *
     * @return \think\Response
     */
    public function add()
    {
        if ($this->request->isPost()) {
            // $this->token();
            $params = $this->request->post('row/a');
            if ($params) {
                $params = $this->preExcludeFields($params);
                // 查询id，并把数量-1
                $res = Topic::where('id',$params['topic_id'])->value('leave_quota');
                // $res = Topic::find($params['topic_id']);
                if(!$res){
                    $this->error('暂无该课题，请重试 ！！！');
                }
                if($res <= 0){
                    $this->error('该课题已满，请重试 ！！！');
                }
                $result = Topic::where('id',$params['topic_id'])->save(['leave_quota' => $res-1]);
                if(!result){
                    $this->error('添加失败，请重试 ！！！');
                }
            }
        }
        return parent::add();
    }

}
