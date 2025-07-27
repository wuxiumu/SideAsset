<?php
// 补全代码，添加好注释

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Account 模型
 *
 * 用于管理平台账号（如阿里云、腾讯云、GitHub等）及其关键信息
 *
 * 字段说明：
 * @property int $id 主键
 * @property string $platform 所属平台（必填，如阿里云、GitHub）
 * @property string|null $username 账号名
 * @property string|null $email 绑定邮箱
 * @property string|null $bind_project 绑定的业务或项目
 * @property string|null $login_type 登录方式（如邮箱、手机号、OAuth等）
 * @property string|null $note 备注
 * @property string|null $created_at 创建时间
 * @property string|null $updated_at 更新时间
 */
class Account extends Model
{
    // 指定可批量赋值字段，方便 create/update
    protected $fillable = [
        'platform',       // 所属平台
        'username',       // 账号名
        'email',          // 邮箱
        'bind_project',   // 绑定业务
        'login_type',     // 登录方式
        'note',           // 备注
    ];
}
