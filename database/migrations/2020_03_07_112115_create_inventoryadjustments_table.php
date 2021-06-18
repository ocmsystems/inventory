<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateInventoryAdjustmentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('inventoryadjustments',function(Blueprint $table){
            $table->increments("id");
            $table->string("transaction_number")->nullable();
            $table->date("date");
            $table->string("warehouse_id");
            $table->string("contact_person")->nullable();
            $table->string("prepared_by");
            $table->string("source_document")->nullable();
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
        Schema::drop('inventoryadjustments');
    }

}