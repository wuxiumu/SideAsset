<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User 模型
 *
 * 用于系统用户（后台账号、注册用户等）的基础信息管理。
 *
 * 字段说明：
 * @property int $id 主键
 * @property string $name 用户名
 * @property string $email 邮箱地址
 * @property string $password 密码（加密存储）
 * @property string|null $remember_token 自动登录token
 * @property string|null $email_verified_at 邮箱验证时间
 * @property string|null $created_at 创建时间
 * @property string|null $updated_at 更新时间
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * 隐藏字段（如序列化为数组/JSON时不会输出）
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 字段类型自动转换
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
