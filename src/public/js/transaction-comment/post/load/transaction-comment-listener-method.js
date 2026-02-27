import { previewPost } from "/js/transaction-comment/post/preview-post.js";
import { PreviewLock } from "/js/statics/states/preview-lock.js";
import { AddedTransactionImageFiles } from "/js/statics/added-transaction-image-files.js";
import { AttributeTool } from "/js/statics/attribute-tool.js";

export async function transactionCommentListenerMethod(
    commonConfigs,
    currentTransactionCommentDTO,
    selectedElementIdPrefix,
    eventObjectPreview,
) {
    let settingsPhpConfig = null;
    let phpConfig = null;
    let transactionPhpConfig = null;
    let transactionCommentPhpConfig = null;
    let transactionImagePhpConfig = null;
    let userImagePhpConfig = null;
    let ratingModalPhpConfig = null;
    let autoSaveTimers = null;
    let csrfToken = null;
    let dataFieldArgument = null;
    let dataIdArgument = null;
    let routeLogin = null;
    let routeItemDealItemId = null;
    let routeTransactionSend = null;
    let previewPostTypes = null;
    let userKinds = null;
    let transactionCommentStatuses = null;
    let selectedItemId = null;
    let postedUserDTO = null;
    let counterpartUserDTO = null;
    let selectedItemBuyerId = null;
    let selectedItemSellerId = null;
    let transactionCommentEditButtonIdPrefix = null;
    let transactionCommentDeleteButtonIdPrefix = null;
    let transactionCommentUploadInputIdPrefix = null;
    let transactionCommentSendButtonIdPrefix = null;
    let previewPostType = null;
    let targetTransactionCommentId = null;
    let newCommentTextareaInputTargetTransactionCommentComment = null;
    let previewPostConfig = null;
    let deletedTransactionCommentIds = [];
    let deletedTransactionImageIds = [];
    let addedTransactionImageFiles = [];
    let selectedElementId = null;
    let selectedElement = null;

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

    if (
        transactionCommentPhpConfig &&
        typeof transactionCommentPhpConfig === "object"
    ) {
        transactionCommentEditButtonIdPrefix =
            transactionCommentPhpConfig.transactionCommentEditButtonIdPrefix;
        transactionCommentDeleteButtonIdPrefix =
            transactionCommentPhpConfig.transactionCommentDeleteButtonIdPrefix;
        transactionCommentUploadInputIdPrefix =
            transactionCommentPhpConfig.transactionCommentUploadInputIdPrefix;
        transactionCommentSendButtonIdPrefix =
            transactionCommentPhpConfig.transactionCommentSendButtonIdPrefix;
    } //transactionCommentPhpConfig

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

    previewPostType = null;
    if (previewPostTypes) {
        if (selectedElementIdPrefix === transactionCommentSendButtonIdPrefix) {
            previewPostType = previewPostTypes.STORE;
        } else if (
            selectedElementIdPrefix === transactionCommentEditButtonIdPrefix
        ) {
            previewPostType = previewPostTypes.COMMENT_EDIT;
        } else if (
            selectedElementIdPrefix === transactionCommentDeleteButtonIdPrefix
        ) {
            previewPostType = previewPostTypes.COMMENT_DELETE;
        } else if (
            selectedElementIdPrefix === transactionCommentUploadInputIdPrefix
        ) {
            previewPostType = previewPostTypes.NEW_IMAGE_UPLOAD;
        } //selectedElementIdPrefix
    } //previewPostTypes

    if (currentTransactionCommentDTO) {
        targetTransactionCommentId =
            currentTransactionCommentDTO.transaction_comment_id;
    } //currentTransactionCommentDTO

    if (
        AttributeTool &&
        typeof AttributeTool.getAttributeFromAttributePrefixAndDBId ===
            "function"
    ) {
        selectedElementId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                selectedElementIdPrefix,
                targetTransactionCommentId,
            );
    }

    if (selectedElementId) {
        selectedElement = document.getElementById(selectedElementId);
    } //selectedElementId

    if (selectedElement && selectedElement.disabled === false) {
        if (previewPostTypes) {
            if (previewPostType === previewPostTypes.STORE) {
            } else if (previewPostType === previewPostTypes.NEW_IMAGE_UPLOAD) {
                if (
                    AddedTransactionImageFiles &&
                    typeof AddedTransactionImageFiles.getAddedTransactionImageFiles ===
                        "function"
                ) {
                    addedTransactionImageFiles =
                        AddedTransactionImageFiles.getAddedTransactionImageFiles(
                            eventObjectPreview,
                        );
                }
            } else if (previewPostType === previewPostTypes.COMMENT_EDIT) {
            } else if (previewPostType === previewPostTypes.COMMENT_DELETE) {
                deletedTransactionCommentIds = new Array(1);
                deletedTransactionCommentIds[0] = targetTransactionCommentId;
            } //previewPostType
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

        if (previewPostType) {
            if (autoSaveTimers && typeof autoSaveTimers === "object") {
                if (autoSaveTimers[previewPostType]) {
                    clearTimeout(autoSaveTimers[previewPostType]);
                } //autoSaveTimers[previewPostType]
            } //autoSaveTimers

            if (PreviewLock) {
                if (PreviewLock.isLocked === false) {
                    PreviewLock.isLocked = true;
                    selectedElement.disabled = true;

                    try {
                        if (typeof previewPost === "function") {
                            await previewPost(commonConfigs, previewPostConfig);
                        } //previewPost
                    } finally {
                        PreviewLock.isLocked = false;
                        selectedElement.disabled = false;
                    }
                } //PreviewLock.isLocked
            }
        } //previewPostType
    } //selectedElement
}
