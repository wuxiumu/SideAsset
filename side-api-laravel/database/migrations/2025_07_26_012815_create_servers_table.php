<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->comment('主机名/标识');
            $table->string('type', 32)->nullable()->comment('主机类型（如 ECS、虚拟主机等）');
            $table->string('ip', 32)->nullable()->comment('IP地址');
            $table->string('location', 64)->nullable()->comment('机房/地区');
            $table->string('os', 64)->nullable()->comment('操作系统');
            $table->string('purpose', 128)->nullable()->comment('用途/备注');
            $table->string('project', 128)->nullable()->comment('关联业务项目');
            $table->date('register_date')->nullable()->comment('注册时间');
            $table->date('expire_date')->nullable()->comment('到期时间');
            $table->decimal('price', 10, 2)->nullable()->comment('购买价格');
            $table->json('extra')->nullable()->comment('扩展数据，预留账号/密钥/授权等');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
