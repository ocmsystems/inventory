<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateReturnsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('returns',function(Blueprint $table){
            $table->increments("id");
            $table->integer("productlist_id")->references("id")->on("productlist");
            $table->string("quantity");
            $table->integer("warehouselist_id")->references("id")->on("warehouselist");
            $table->string("datetime_created")->nullable();
            $table->string("prepared_by")->nullable();
            $table->string("source_document")->nullable();
            $table->string("status")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('returns');
    }

}