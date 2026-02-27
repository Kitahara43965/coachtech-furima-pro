<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\TransactionCommentStatus;

class CreateTransactionCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('transaction_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_item_id')->constrained('user_item')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text("comment")->nullable();
            $table->string('status')->default(TransactionCommentStatus::DRAFT);
            $table->boolean('is_watched')->default(false);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('transaction_comments');
    }
}
