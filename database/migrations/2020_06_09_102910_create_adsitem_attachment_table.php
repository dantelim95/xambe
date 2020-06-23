<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsitemAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adsitem_attachment', function (Blueprint $table) {
            $table->bigInteger('adsitem_id')->unsigned();
            $table->bigInteger('attachment_id')->unsigned();
            $table->foreign('adsitem_id')->references('id')->on('adsitems')
            ->onDelete('cascade');
            $table->foreign('attachment_id')->references('id')->on('attachments')
            ->onDelete('cascade');
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
        Schema::dropIfExists('adsitem_attachment');
    }
}
