<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('platform', 64)->nullable();
            $table->string('api_key', 256)->nullable();
            $table->string('bind_project', 128)->nullable();
            $table->string('quota', 64)->nullable();
            $table->string('fee', 64)->nullable();
            $table->string('doc_url', 255)->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('api_services');
    }
}
