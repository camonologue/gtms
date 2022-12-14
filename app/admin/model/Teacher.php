<?php
declare (strict_types = 1);

namespace app\admin\model;

use app\common\model\BaseModel;

/**
 * @mixin \think\Model
 */
class Teacher extends BaseModel
{
    // 表名
    protected $name = 'teacher';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = '';
}
