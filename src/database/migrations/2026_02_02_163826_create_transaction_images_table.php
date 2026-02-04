<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionImagesTable extends Migration
{
    public function up()
    {
        Schema::create('transaction_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_comment_id')->constrained('transaction_comments')->cascadeOnDelete();
            $table->string('image');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_images');
    }
}
