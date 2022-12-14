<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use app\common\model\BaseModel;

/**
 * @mixin \think\Model
 */
class Major extends BaseModel
{
    // 表名
    protected $name = 'major';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    public function faculty(){
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id')->joinType('LEFT');
    }
}
