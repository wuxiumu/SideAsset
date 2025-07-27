<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Log 模型
 *
 * 用于系统日志和提醒（如到期提醒、操作记录等）的管理。
 *
 * 字段说明：
 * @property int $id 主键
 * @property string $type 日志类型（如接口到期、域名到期、系统操作等）
 * @property string $level 日志等级（如 info、warn、error）
 * @property string $content 日志内容
 * @property string|null $object 关联对象（如域名、服务名等，可为空）
 * @property string $status 状态（默认'未读'，可为'已读'）
 * @property string|null $created_at 创建时间
 * @property string|null $updated_at 更新时间
 */
class Log extends Model
{
    // 可批量赋值的字段，需与表结构一致
    protected $fillable = [
        'type',      // 日志类型
        'level',     // 日志等级
        'content',   // 日志内容
        'object',    // 关联对象
        'status',    // 状态
    ];
}
