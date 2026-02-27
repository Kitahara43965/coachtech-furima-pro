import { PreviewLock } from "/js/statics/states/preview-lock.js";
import { previewPost } from "/js/transaction-comment/post/preview-post.js";
import { AttributeTool } from "/js/statics/attribute-tool.js";

export async function commentTextareaInput(
    commonConfigs,
    currentTransactionCommentDTO,
    transactionCommentCommentTextareaIdPrefix,
    eventObjectPreview,
) {
    const commentTextareaInputAutoSaveInterval = 200;
    let settingsPhpConfig = null;
    let phpConfig = null;
    let transactionPhpConfig = null;
    let transactionCommentPhpConfig = null;
    let transactionImagePhpConfig = null;
    let userImagePhpConfig = null;
    let ratingModalPhpConfig = null;
    let autoSaveTimers = null;
    let previewPostTypes = null;
    let userKinds = null;
    let transactionCommentStatuses = null;
    let csrfToken = null;
    let dataFieldArgument = null;
    let dataIdArgument = null;
    let routeLogin = null;
    let routeItemDealItemId = null;
    let routeTransactionSend = null;
    let selectedItemId = null;
    let postedUserDTO = null;
    let counterpartUserDTO = null;
    let selectedItemBuyerId = null;
    let selectedItemSellerId = null;
    let previewPostType = null;
    let targetTransactionCommentId = null;
    let newCommentTextareaInputTargetTransactionCommentComment = null;
    let previewPostConfig = null;
    let deletedTransactionCommentIds = [];
    let deletedTransactionImageIds = [];
    let addedTransactionImageFiles = [];
    let commentTextareaInputTransactionCommentCommentTextareaId = null;
    let commentTextareaInputTransactionCommentCommentTextarea = null;

    if (commonConfigs && typeof commonConfigs === "object") {
        settingsPhpConfig = commonConfigs.settingsPhpConfig;
        phpConfig = commonConfigs.phpConfig;
        transactionPhpConfig = commonConfigs.transactionPhpConfig;
        transactionCommentPhpConfig = commonConfigs.transactionCommentPhpConfig;
        transactionImagePhpConfig = commonConfigs.transactionImagePhpConfig;
        userImagePhpConfig = commonConfigs.userImagePhpConfig;
        ratingModalPhpConfig = commonConfigs.ratingModalPhpConfig;
        autoSaveTimers = commonConfigs.autoSaveTimers;
    } //commonConfigs

    if (phpConfig && typeof phpConfig === "object") {
        previewPostTypes = phpConfig.previewPostTypes;
        userKinds = phpConfig.userKinds;
        transactionCommentStatuses = phpConfig.transactionCommentStatuses;
        csrfToken = phpConfig.csrfToken;
        dataFieldArgument = phpConfig.dataFieldArgument;
        dataIdArgument = phpConfig.dataIdArgument;
        routeLogin = phpConfig.routeLogin;
        routeItemDealItemId = phpConfig.routeItemDealItemId;
        routeTransactionSend = phpConfig.routeTransactionSend;
        selectedItemId = phpConfig.selectedItemId;
        postedUserDTO = phpConfig.postedUserDTO;
        counterpartUserDTO = phpConfig.counterpartUserDTO;
        selectedItemBuyerId = phpConfig.selectedItemBuyerId;
        selectedItemSellerId = phpConfig.selectedItemSellerId;
    } //phpConfig

    if (currentTransactionCommentDTO) {
        targetTransactionCommentId =
            currentTransactionCommentDTO.transaction_comment_id;
    } //currentTransactionCommentDTO

    commentTextareaInputTransactionCommentCommentTextareaId =
        AttributeTool.getAttributeFromAttributePrefixAndDBId(
            transactionCommentCommentTextareaIdPrefix,
            targetTransactionCommentId,
        );

    //transactionCommentCommentTextareaIdPrefix

    if (commentTextareaInputTransactionCommentCommentTextareaId) {
        commentTextareaInputTransactionCommentCommentTextarea =
            document.getElementById(
                commentTextareaInputTransactionCommentCommentTextareaId,
            );
    } //commentTextareaInputTransactionCommentCommentTextareaId

    if (commentTextareaInputTransactionCommentCommentTextarea) {
        newCommentTextareaInputTargetTransactionCommentComment =
            commentTextareaInputTransactionCommentCommentTextarea.value;
    } //commentTextareaInputTransactionCommentCommentTextarea

    if (previewPostTypes) {
        previewPostType = previewPostTypes.DRAFT;
    } //previewPostTypes

    previewPostConfig = {
        previewPostType: previewPostType,
        targetTransactionCommentId: targetTransactionCommentId,
        newCommentTextareaInputTargetTransactionCommentComment:
            newCommentTextareaInputTargetTransactionCommentComment,
        deletedTransactionCommentIds: deletedTransactionCommentIds,
        deletedTransactionImageIds: deletedTransactionImageIds,
        addedTransactionImageFiles: addedTransactionImageFiles,
    };

    if (autoSaveTimers) {
        if (previewPostType) {
            if (autoSaveTimers[previewPostType]) {
                clearTimeout(autoSaveTimers[previewPostType]);
            }

            autoSaveTimers[previewPostType] = setTimeout(async () => {
                if (PreviewLock) {
                    if (PreviewLock.isLocked === false) {
                        PreviewLock.isLocked = true;

                        try {
                            if (typeof previewPost === "function") {
                                await previewPost(
                                    commonConfigs,
                                    previewPostConfig,
                                );
                            } //typeof previewPost
                        } finally {
                            PreviewLock.isLocked = false;
                        }
                    }
                } //PreviewLock
            }, commentTextareaInputAutoSaveInterval);
        } //previewPostType
    } //autoSaveTimers
}
