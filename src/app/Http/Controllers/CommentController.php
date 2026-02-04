<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function itemCommentsItemId(CommentRequest $request, $item_id)
    {
        $itemId = (int)$item_id;
        $item  = Item::findOrFail($itemId);
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'コメントするにはログインが必要です。');
        }

        // コメントを作成
        $comment = Comment::create([
            'user_id' => $user->id,
            'description' => $request->input('description'),
        ]);

        // 商品とコメントの紐付け
        $item->comments()->attach($comment->id);

        return back()->with('status', 'コメントを追加しました！');
    }
}