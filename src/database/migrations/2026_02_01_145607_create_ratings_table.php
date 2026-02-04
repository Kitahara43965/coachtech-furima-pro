<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\RatingType;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete(); // 評価するユーザー
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();   // 評価されるユーザー
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();             // 対象の商品
            $table->tinyInteger('rating_value');  // 1〜5
            $table->text('comment')->nullable(); // コメント
            $table->enum('type', [RatingType::BUYER_TO_SELLER, RatingType::SELLER_TO_BUYER]); // 誰から誰への評価
            $table->timestamps();

            // 1取引1方向につき1回だけ評価可能
            $table->unique(['from_user_id', 'to_user_id', 'item_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
