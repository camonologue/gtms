<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use app\common\model\BaseModel;

/**
 * @mixin \think\Model
 */
class TopicSelected extends BaseModel
{
    // 表名
    protected $name = 'topic_selected';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id')->joinType('LEFT');
    }
    public function topic(){
        return $this->belongsTo(Topic::class, 'topic_id', 'id')->joinType('LEFT');
    }
}
