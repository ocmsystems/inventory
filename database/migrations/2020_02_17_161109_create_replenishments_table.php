<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateReplenishmentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('replenishments',function(Blueprint $table){
            $table->increments("id");
            $table->string("contact_person");
            $table->dateTime("scheduled_date")->nullable();
            $table->integer("warehouselist_id")->references("id")->on("warehouselist");
            $table->string("source_document")->nullable();
            $table->text("notes")->nullable();
            $table->string("priority")->nullable();
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
        Schema::drop('replenishments');
    }

}