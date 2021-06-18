<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateProductListTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();
        Schema::create('productlist',function(Blueprint $table){
            $table->increments("id");
            $table->string("name");
            $table->text("description")->nullable();
            $table->integer("productcategories_id")->references("id")->on("productcategories");
            $table->tinyInteger("can_be_sold")->default(1)->nullable();
            $table->tinyInteger("can_be_purchased")->default(1)->nullable();
            $table->string("image")->nullable();
            $table->tinyInteger("status")->default(1)->nullable();
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
        Schema::drop('productlist');
    }

}