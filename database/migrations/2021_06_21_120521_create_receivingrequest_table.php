<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateReceivingRequestTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('receivingrequest',function(Blueprint $table){
            $table->increments("id");
            $table->string("project_name")->nullable();
            $table->string("project_number")->nullable();
            $table->string("client_name")->nullable();
            $table->string("receiving_type")->nullable();
            $table->integer("warehouselist_id")->references("id")->on("warehouselist")->nullable();
            $table->integer("businessunit_id")->references("id")->on("businessunit")->nullable();
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
        Schema::drop('receivingrequest');
    }

}