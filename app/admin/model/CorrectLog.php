<?php
declare (strict_types = 1);

namespace app\admin\model;

use app\common\model\BaseModel;
use think\Model;

/**
 * @mixin \think\Model
 */
class CorrectLog extends BaseModel
{
    // 表名
    protected $name = 'treatise_correct_log';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = '';

    public function treatise(){
        return $this->belongsTo(Treatise::class, 'treatise_id', 'id');
    }
    public function teacher(){
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }
}
