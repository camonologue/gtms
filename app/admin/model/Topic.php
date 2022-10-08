<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use app\common\model\BaseModel;

/**
 * @mixin \think\Model
 */
class Topic extends BaseModel
{
    // 表名
    protected $name = 'topic';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function teacher(){
        return $this->belongsTo(Teacher::class, 'create_id', 'id')->joinType('LEFT');
    }
}
