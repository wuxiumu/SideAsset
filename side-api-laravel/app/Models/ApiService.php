<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ApiService 模型
 *
 * 管理第三方 API 接口服务，例如 OpenAI、百度智能云等的配置与授权信息
 *
 * 字段：
 * @property int $id 主键
 * @property string $name 接口服务名称
 * @property string|null $platform 平台（如 OpenAI、百度智能云等）
 * @property string|null $api_key API Key/密钥
 * @property string|null $bind_project 绑定业务/项目
 * @property string|null $quota 额度/套餐描述
 * @property string|null $fee 费用（如每月/每次计费说明）
 * @property string|null $doc_url 官方文档链接
 * @property string|null $note 备注说明
 * @property string|null $created_at 创建时间
 * @property string|null $updated_at 更新时间
 */
class ApiService extends Model
{
    // 可批量赋值的字段
    protected $fillable = [
        'name',           // 接口服务名称
        'platform',       // 平台
        'api_key',        // API 密钥
        'bind_project',   // 绑定业务
        'quota',          // 额度
        'fee',            // 费用
        'doc_url',        // 文档链接
        'note',           // 备注
    ];
}
