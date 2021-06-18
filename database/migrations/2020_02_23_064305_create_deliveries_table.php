<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateDeliveriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('deliveries',function(Blueprint $table){
            $table->increments("id");
            $table->string("transaction_number")->nullable();
            $table->string("destination_warehouse_id")->nullable();
            $table->string("source_warehouse_id")->nullable();
            $table->string("source_document")->nullable();
            $table->date("delivery_date")->nullable();
            $table->string("prepared_by")->nullable();
            $table->string("contact_person")->nullable();
            $table->string("status")->nullable();
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
        Schema::drop('deliveries');
    }

}