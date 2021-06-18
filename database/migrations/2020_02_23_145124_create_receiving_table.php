<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateReceivingTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('receiving',function(Blueprint $table){
            $table->increments("id");
            $table->string("transaction_number")->nullable();
            $table->string("source_document")->nullable();
            $table->string("received_date")->nullable();
            $table->string("contact_person")->nullable();
            $table->string("receiving_warehouse_id")->nullable();
            $table->string("status")->nullable();
            $table->string("prepared_by")->nullable();
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
        Schema::drop('receiving');
    }

}