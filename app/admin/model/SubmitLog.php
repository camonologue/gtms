<?php
declare (strict_types = 1);

namespace app\admin\model;

use app\common\model\Attachment;
use app\common\model\BaseModel;
use think\Model;

/**
 * @mixin \think\Model
 */
class SubmitLog extends BaseModel
{
    // 表名
    protected $name = 'treatise_submit_log';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = '';

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id')->joinType('LEFT');
    }
    public function attachment(){
        return $this->belongsTo(Attachment::class, 'download', 'url')->joinType('LEFT');
    }
}
