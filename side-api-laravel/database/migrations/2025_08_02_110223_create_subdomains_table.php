<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubdomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subdomains', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('主键');
            $table->unsignedBigInteger('domain_id')->comment('主域名ID，关联domains表');
            $table->string('subdomain', 128)->comment('子域名');
            $table->unsignedBigInteger('bind_server_id')->nullable()->comment('绑定服务器ID，可为空');
            $table->string('project', 128)->nullable()->comment('关联业务');
            $table->string('note', 255)->nullable()->comment('备注');
            $table->timestamp('published_at')->nullable()->comment('发布时间');
            $table->tinyInteger('status')->default(1)->comment('状态(1正常/0禁用)');
            $table->timestamps();

            // 如需外键，去掉注释
            // $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
            // $table->foreign('bind_server_id')->references('id')->on('servers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subdomains');
    }
}
