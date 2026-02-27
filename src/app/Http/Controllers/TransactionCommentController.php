<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionCommentRequest;
use App\Constants\PreviewPostType;
use App\Constants\PreviewErrorStatus;
use App\Constants\TransactionCommentStatus;
use App\Constants\Files\FileName;
use App\Models\User;
use App\Models\UserItem;
use App\Models\TransactionComment;
use App\Models\TransactionImage;
use App\Services\FileService;
use App\Services\TransactionCommentService;
use App\Services\ImageFileNumberCountService;
use App\Services\TransactionCommentDTOService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TransactionCommentController extends Controller
{
    public static function getNextAddedTransactionImageFileNames(
        $purchasedPostedUserItemId,
        $postedUserId,
        $addedTransactionImageFiles
    ){

        $maxAddedTransactionImageIdNumber = 0;
        if($addedTransactionImageFiles){
            $maxAddedTransactionImageIdNumber = count($addedTransactionImageFiles);
        }

        $nextAddedTransactionImageFileNames = null;
        if($maxAddedTransactionImageIdNumber >= 1){
            $nextAddedTransactionImageFileNames = array_fill(0,$maxAddedTransactionImageIdNumber,null);
        }//$maxAddedTransactionImageIdNumber&1

        $userItemTransactionImageDirectoryPath
             = FileService::getUserItemTransactionImageDirectoryPathFromUserItemId($purchasedPostedUserItemId);

        $totalAddedTransactionImageIdNumber = ImageFileNumberCountService::getPublicImageNumber(
            $userItemTransactionImageDirectoryPath,
            FileName::TRANSACTION_IMAGE_PREFIX
        );

        for (
            $addedTransactionImageIdNumber = 1; 
            $addedTransactionImageIdNumber <= $maxAddedTransactionImageIdNumber;
            $addedTransactionImageIdNumber ++
        ) {
            $addedTransactionImageFile = $addedTransactionImageFiles[$addedTransactionImageIdNumber - 1];
            $wholeAddedTransactionImageIdNumber = $totalAddedTransactionImageIdNumber + $addedTransactionImageIdNumber;

            $nextAddedTransactionImageFileNameExtension = $addedTransactionImageFile->getClientOriginalExtension();
            $nextAddedTransactionImageFileName 
                = FileName::TRANSACTION_IMAGE_PREFIX.$wholeAddedTransactionImageIdNumber.".".$nextAddedTransactionImageFileNameExtension;
            
            $nextAddedTransactionImageFileNames[$addedTransactionImageIdNumber - 1] = $nextAddedTransactionImageFileName;
        }

        return($nextAddedTransactionImageFileNames);

    }

    public function transactionCommentFileUpload(
        $purchasedPostedUserItemId,
        $addedTransactionImageFiles,
        $nextAddedTransactionImageFileNames,
    ){

        $userItemDirectoryPath 
             = FileService::getUserItemDirectoryPathFromUserItemId($purchasedPostedUserItemId);

        $userItemTransactionImageDirectoryPath
             = FileService::getUserItemTransactionImageDirectoryPathFromUserItemId($purchasedPostedUserItemId);

        if (!Storage::disk('public')->exists($userItemDirectoryPath)) {
            Storage::disk('public')->makeDirectory($userItemDirectoryPath);
        }
        if (!Storage::disk('public')->exists($userItemTransactionImageDirectoryPath)) {
            Storage::disk('public')->makeDirectory($userItemTransactionImageDirectoryPath);
        }


        $maxAddedTransactionImageIdNumber = 0;
        if($addedTransactionImageFiles){
            $maxAddedTransactionImageIdNumber = count($addedTransactionImageFiles);
        }

        for (
            $addedTransactionImageIdNumber = 1; 
            $addedTransactionImageIdNumber <= $maxAddedTransactionImageIdNumber;
            $addedTransactionImageIdNumber ++
        ) {
            $addedTransactionImageFile = $addedTransactionImageFiles[$addedTransactionImageIdNumber - 1];
            $nextAddedTransactionImageFileName = $nextAddedTransactionImageFileNames[$addedTransactionImageIdNumber - 1];
            $addedTransactionImageFileError = $addedTransactionImageFile->getError();

            if ($addedTransactionImageFileError === UPLOAD_ERR_OK) {
                $addedTransactionImageFile->storeAs($userItemTransactionImageDirectoryPath, $nextAddedTransactionImageFileName, 'public');
            }//$addedTransactionImageFileError
        }
    }

    public function transactionCommentDBChange(
        $selectedItemId,
        $postedUserId,
        $previewPostType,
        $targetTransactionCommentId,
        $newCommentTextareaInputTargetTransactionCommentComment,
        $deletedTransactionCommentIds,
        $deletedTransactionImageIds,
        $addedTransactionImageFiles,
        $jsCodeNumber,
        $purchasedPostedUserItemId,
        $nextAddedTransactionImageFileNames,
    ){
        DB::transaction(function () use (
            $selectedItemId,
            $postedUserId,
            $previewPostType,
            $targetTransactionCommentId,
            $newCommentTextareaInputTargetTransactionCommentComment,
            $deletedTransactionCommentIds,
            $deletedTransactionImageIds,
            $addedTransactionImageFiles,
            $jsCodeNumber,
            $purchasedPostedUserItemId,
            $nextAddedTransactionImageFileNames,
        ) {

            $originalDraftTransactionComment = TransactionCommentService::getDraftTransactionCommentFromUserItemIdAndUserId(
                $purchasedPostedUserItemId,
                $postedUserId
            );

            $targetTransactionComment = TransactionComment::find($targetTransactionCommentId);

            $maxDeletedTransactionCommentIdNumber = 0;
            if($deletedTransactionCommentIds){
                $maxDeletedTransactionCommentIdNumber = count($deletedTransactionCommentIds);
            }//$deletedTransactionCommentIds

            $transactionCommentDTOService = new TransactionCommentDTOService();

            $originalTransactionCommentDTOs = null;
            if($transactionCommentDTOService){
                $originalTransactionCommentDTOs = $transactionCommentDTOService
                    ->getCurrentTransactionCommentDTOsFromUserItemIdAndUserId(
                        $purchasedPostedUserItemId,
                        $postedUserId
                    );
            }//$transactionCommentDTOService

            $maxOriginalTransactionCommentDTONumber = 0;
            if($originalTransactionCommentDTOs){
                $maxOriginalTransactionCommentDTONumber = count($originalTransactionCommentDTOs);
            }//$originalTransactionCommentDTOs

            if($purchasedPostedUserItemId){
                if($previewPostType === PreviewPostType::INITIAL_PREVIEW){
                    if(!$originalDraftTransactionComment){
                        TransactionComment::create([
                            'user_item_id' => $purchasedPostedUserItemId,
                            'user_id' => $postedUserId,
                        ]);
                    }//$originalDraftTransactionComment
                    for(
                        $originalTransactionCommentDTONumber = 1;
                        $originalTransactionCommentDTONumber <= $maxOriginalTransactionCommentDTONumber;
                        $originalTransactionCommentDTONumber ++
                    ){
                        $originalTransactionCommentDTO = $originalTransactionCommentDTOs[$originalTransactionCommentDTONumber - 1];
                        $originalTransactionCommentId = null;
                        $originalTransactionCommentUserId = $originalTransactionCommentDTO->user_id;
                        if($originalTransactionCommentDTO){
                            $originalTransactionCommentId = $originalTransactionCommentDTO->transaction_comment_id;
                        }//$originalTransactionCommentDTO
                        $originalTransactionComment = TransactionComment::find($originalTransactionCommentId);
                        if($postedUserId !== $originalTransactionCommentUserId){
                            if($originalTransactionComment){
                                $originalTransactionComment->update([
                                    'is_watched' => true,
                                ]);
                            }//$originalTransactionComment
                        }

                    }
                }else if($previewPostType === PreviewPostType::DRAFT){
                    if($targetTransactionComment){
                        $targetTransactionComment->update([
                            'comment' => $newCommentTextareaInputTargetTransactionCommentComment,
                        ]);
                    }//$targetTransactionComment
                }else if($previewPostType === PreviewPostType::STORE){
                    if($originalDraftTransactionComment){
                        $originalDraftTransactionComment->update([
                            'status' => TransactionCommentStatus::PUBLISHED,
                        ]);
                    }//$originalDraftTransactionComment
                    TransactionComment::create([
                        'user_item_id' => $purchasedPostedUserItemId,
                        'user_id' => $postedUserId,
                    ]);
                }else if($previewPostType === PreviewPostType::COMMENT_EDIT){
                }else if($previewPostType === PreviewPostType::COMMENT_DELETE){
                    if($maxDeletedTransactionCommentIdNumber >= 1){
                        for(
                            $deletedTransactionCommentIdNumber = 1;
                            $deletedTransactionCommentIdNumber <= $maxDeletedTransactionCommentIdNumber;
                            $deletedTransactionCommentIdNumber ++
                        ){
                            $deletedTransactionCommentId
                                = $deletedTransactionCommentIds[$deletedTransactionCommentIdNumber - 1];
                            $deletedTransactionComment = TransactionComment::find($deletedTransactionCommentId);
                            if($deletedTransactionComment){
                                $deletedTransactionComment->delete();
                            }//$deletedTransactionComment
                        }
                    }//$maxDeletedTransactionCommentIdNumber&1
                }else if($previewPostType === PreviewPostType::NEW_IMAGE_UPLOAD){

                }else if($previewPostType === PreviewPostType::NEW_IMAGE_DELETE){

                }//$previewPostType
            }//$purchasedPostedUserItemId

            $maxDeletedTransactionImageIdNumber = 0;
            if($deletedTransactionImageIds){
                $maxDeletedTransactionImageIdNumber = count($deletedTransactionImageIds);
            }//$deletedTransactionImageIds

            for(
                $deletedTransactionImageIdNumber = 1;
                $deletedTransactionImageIdNumber <= $maxDeletedTransactionImageIdNumber;
                $deletedTransactionImageIdNumber++
            ){
                $deletedTransactionImageId = $deletedTransactionImageIds[$deletedTransactionImageIdNumber - 1];
                $deletedTransactionImage = null;
                if($deletedTransactionImageId){
                    $deletedTransactionImage = TransactionImage::find($deletedTransactionImageId);
                    if($deletedTransactionImage){
                        $deletedTransactionImage->delete();
                    }//$deletedTransactionImage
                }//$deletedTransactionImageId
            }

            $maxAddedTransactionImageIdNumber = 0;
            if($addedTransactionImageFiles){
                $maxAddedTransactionImageIdNumber = count($addedTransactionImageFiles);
            }

            $userItemTransactionImageDirectoryPath
                = FileService::getUserItemTransactionImageDirectoryPathFromUserItemId(
                    $purchasedPostedUserItemId
                );

            $totalAddedTransactionImageIdNumber = ImageFileNumberCountService::getPublicImageNumber(
                $userItemTransactionImageDirectoryPath,
                FileName::TRANSACTION_IMAGE_PREFIX
            );

            for (
                $addedTransactionImageIdNumber = 1; 
                $addedTransactionImageIdNumber <= $maxAddedTransactionImageIdNumber;
                $addedTransactionImageIdNumber ++
            ) {
                $addedTransactionImageFile = $addedTransactionImageFiles[$addedTransactionImageIdNumber - 1];
                $nextAddedTransactionImageFileName = $nextAddedTransactionImageFileNames[$addedTransactionImageIdNumber - 1];
                $addedTransactionImageFileTmpName = $addedTransactionImageFile->getRealPath();
                $addedTransactionImageFileCreationTime = filectime($addedTransactionImageFileTmpName);

                $addedTransactionImageFileError = $addedTransactionImageFile->getError();

                if ($addedTransactionImageFileError === UPLOAD_ERR_OK) {
                    if($targetTransactionComment){
                        $newTransactionImage = new TransactionImage();
                        $newTransactionImage->transaction_comment_id = $targetTransactionComment->id;
                        $newTransactionImage->image = $nextAddedTransactionImageFileName;
                        $newTransactionImage->created_at = date('Y-m-d H:i:s', $addedTransactionImageFileCreationTime);
                        $newTransactionImage->save();
                    }//$targetTransactionComment
                }//$addedTransactionImageFileError
            }//$addedTransactionImageIdNumber
        });
    }//transactionCommentDBChange

    public function onEdit(
        $request,
        $selectedItemId,
        $postedUserId,
        $previewPostType,
        $targetTransactionCommentId,
        $newCommentTextareaInputTargetTransactionCommentComment,
        $deletedTransactionCommentIds,
        $deletedTransactionImageIds,
        $addedTransactionImageFiles,
        $jsCodeNumber
    ){
        $purchasedPostedUserItemId = TransactionCommentService::getPurchasedUserItemIdFromItemIdAndUserId(
            $selectedItemId,
            $postedUserId
        );

        $nextAddedTransactionImageFileNames = self::getNextAddedTransactionImageFileNames(
            $purchasedPostedUserItemId,
            $postedUserId,
            $addedTransactionImageFiles
        );

        $this->transactionCommentFileUpload(
            $purchasedPostedUserItemId,
            $addedTransactionImageFiles,
            $nextAddedTransactionImageFileNames
        );

        $this->transactionCommentDBChange(
            $selectedItemId,
            $postedUserId,
            $previewPostType,
            $targetTransactionCommentId,
            $newCommentTextareaInputTargetTransactionCommentComment,
            $deletedTransactionCommentIds,
            $deletedTransactionImageIds,
            $addedTransactionImageFiles,
            $jsCodeNumber,
            $purchasedPostedUserItemId,
            $nextAddedTransactionImageFileNames,
        );

        $transactionCommentDTOService = new TransactionCommentDTOService();

        $currentTransactionCommentDTOs = null;
        if($transactionCommentDTOService){
            $currentTransactionCommentDTOs = $transactionCommentDTOService
                ->getCurrentTransactionCommentDTOsFromUserItemIdAndUserId(
                    $purchasedPostedUserItemId,
                    $postedUserId
                );
        }//$transactionCommentDTOService

        $onEditResults = [
            "currentTransactionCommentDTOs" => $currentTransactionCommentDTOs,
        ];
        return($onEditResults);

    }//onEdit

    public function transactionSend(TransactionCommentRequest $request)
    {
        $selectedItemId = (int)$request->selectedItemId();
        $postedUserId = (int)$request->postedUserId();
        $previewPostType = $request->previewPostType();
        $targetTransactionCommentId = $request->targetTransactionCommentId();
        $newCommentTextareaInputTargetTransactionCommentComment
            = $request->newCommentTextareaInputTargetTransactionCommentComment();
        $deletedTransactionCommentIds
            = $request->deletedTransactionCommentIds();
        $deletedTransactionImageIds
            = $request->deletedTransactionImageIds();
        $addedTransactionImageFiles = $request->addedTransactionImageFiles();
        $jsCodeNumber = $request->jsCodeNumber();

        $onEditResults = $this->onEdit(
            $request,
            $selectedItemId,
            $postedUserId,
            $previewPostType,
            $targetTransactionCommentId,
            $newCommentTextareaInputTargetTransactionCommentComment,
            $deletedTransactionCommentIds,
            $deletedTransactionImageIds,
            $addedTransactionImageFiles,
            $jsCodeNumber
        );
        $currentTransactionCommentDTOs = $onEditResults["currentTransactionCommentDTOs"];
        $errorMessages = null;

        $results = [
            "jsCodeNumber" => $jsCodeNumber,
            'selectedItemId' => $selectedItemId,
            'postedUserId' => $postedUserId,
            'previewPostType' => $previewPostType,
            'currentTransactionCommentDTOs' => $currentTransactionCommentDTOs,
            'errorMessages' => $errorMessages,
        ];

        return response()->json($results);
    }

}
