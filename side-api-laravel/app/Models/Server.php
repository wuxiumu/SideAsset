<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Server 模型
 *
 * 用于服务器管理（如主机、ECS、虚拟主机等）信息的存储。
 * 字段说明：
 * @property int $id 主键
 * @property string $name 服务器名称
 * @property string|null $type 主机类型（ECS、虚拟主机等）
 * @property string|null $ip IP 地址
 * @property string|null $location 地区/机房
 * @property string|null $os 操作系统
 * @property string|null $purpose 用途
 * @property string|null $project 运行项目
 * @property string|null $register_date 注册时间
 * @property string|null $expire_date 到期时间
 * @property float|null $price 购买价格
 * @property array|null $extra 扩展数据（如账号、密钥、授权等）
 * @property string|null $created_at 创建时间
 * @property string|null $updated_at 更新时间
 */

class Server extends Model
{
    protected $fillable = [
        'name',         // 服务器名称
        'type',         // 主机类型
        'ip',           // IP 地址
        'location',     // 地区
        'os',           // 操作系统
        'purpose',      // 用途
        'project',      // 运行项目
        'register_date',// 注册时间
        'expire_date',  // 到期时间
        'price',        // 购买价格
        'extra',        // 扩展数据
    ];

    // 可选：如果需要自动将 extra 转数组
    protected $casts = [
        'extra' => 'array',
        'price' => 'float',
    ];
}

