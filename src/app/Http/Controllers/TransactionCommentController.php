<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TransactionCommentRequest;
use App\Constants\PreviewPostType;
use App\Constants\PreviewErrorStatus;
use App\Constants\TransactionCommentStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TransactionComment;
use App\Models\UserItem;
use App\Services\TransactionCommentService;

class TransactionCommentController extends Controller
{
    public function onEdit($request,$itemId,$previewPostType,$commentTextareaValue){
        $authUser = Auth::user();
        $authUserId = $authUser ? $authUser->id : null;

        $authUserItem = null;
        if($authUserId && $itemId){
            $authUserItem = UserItem::where(function ($query) use ($authUserId, $itemId) {
                    $query->where('user_id', $authUserId)
                        ->where('item_id', $itemId);
                })
                ->where('type', 'purchase')
                ->first();
        }

        $authUserItemId = TransactionCommentService::getUserItemIdForBothUsersFromUserIdAndItemId($authUserId,$itemId);


        $draftTransactionComment = TransactionComment::where('user_item_id', $authUserItemId)
                        ->where('user_id', $authUserId)
                        ->where('status',TransactionCommentStatus::DRAFT)
                        ->first();

        if($authUserItemId){
            if($previewPostType === PreviewPostType::DRAFT){
                if($draftTransactionComment){
                    $draftTransactionComment->update([
                        'user_item_id' => $authUserItemId,
                        'user_id' => $authUserId,
                        'comment' => $commentTextareaValue,
                        'status' => TransactionCommentStatus::DRAFT,
                    ]);
                }else{//$draftTransactionComment
                    TransactionComment::create([
                        'user_item_id' => $authUserItemId,
                        'user_id' => $authUserId,
                        'comment' => $commentTextareaValue,
                        'status' => TransactionCommentStatus::DRAFT,
                    ]);
                }//$draftTransactionComment
            }else if($previewPostType === PreviewPostType::STORE){
                TransactionComment::create([
                    'user_item_id' => $authUserItemId,
                    'user_id' => $authUserId,
                    'comment' => $commentTextareaValue,
                    'status' => TransactionCommentStatus::PUBLISHED,
                ]);
            }//$previewPostType
        }//$authUserItemId

    }//onEdit

    public function transactionCommentUpdateItemId(Request $request,$item_id)
    {
        $itemId = (int)$item_id;
        $jsCodeNumber = 200;
        $previewPostType = $request->input('previewPostType');
        $commentTextareaValue = $request->input('commentTextareaValue');

        $this->onEdit($request,$itemId,$previewPostType,$commentTextareaValue);

        $results = [
            'jsCodeNumber' => $jsCodeNumber,
            'previewPostType' => $previewPostType,
            'commentTextareaValue' => $commentTextareaValue,
        ];

        return response()->json($results);
    }

    public function transactionCommentSendItemId(TransactionCommentRequest $request,$item_id)
    {
        $itemId = (int)$item_id;
        $previewPostType = PreviewPostType::STORE;
        $commentTextareaValue = $request->input(PreviewErrorStatus::TRANSACTION_COMMENT_NAME);

        $this->onEdit($request,$itemId,$previewPostType,$commentTextareaValue);

        return redirect()->route("item.deal.itemId", ["item_id" => $itemId]);
    }

    public function commentEditItemId(Request $request,$item_id){
        $itemId = (int)$item_id;
        
        $transactionComment = TransactionComment::find($data['transaction_comment_id']);
        

        return redirect()->route("item.deal.itemId", ["item_id" => $itemId]);
    }
}
