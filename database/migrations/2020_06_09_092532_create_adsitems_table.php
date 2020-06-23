<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adsitems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('address_id');
            $table->bigInteger('category_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->decimal('price');
            $table->integer('business_days');
            $table->integer('business_hours');
            $table->integer('delivery_methods');
            $table->integer('delivery_time_frame');
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
        Schema::dropIfExists('adsitems');
    }
}
