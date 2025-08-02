<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Subdomain 模型
 *
 * 用于管理每个域名下的子域名及其业务归属。
 */
class Subdomain extends Model
{
    protected $fillable = [
        'domain_id', 'subdomain', 'bind_server_id', 'project', 'note', 'published_at', 'status'
    ];

    public function domain() {
        return $this->belongsTo(Domain::class);
    }
    public function server() {
        return $this->belongsTo(Server::class, 'bind_server_id');
    }
}
