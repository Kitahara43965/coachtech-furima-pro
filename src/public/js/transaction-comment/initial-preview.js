import { previewPost } from "/js/transaction-comment/post/preview-post.js";
import { PreviewLock } from "/js/statics/states/preview-lock.js";

export async function initialPreview(commonConfigs) {
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

    if (previewPostTypes) {
        previewPostType = previewPostTypes.INITIAL_PREVIEW;
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
    } //previewPostType

    if (PreviewLock) {
        if (PreviewLock.isLocked === false) {
            PreviewLock.isLocked = true;

            try {
                if (typeof previewPost === "function") {
                    await previewPost(commonConfigs, previewPostConfig);
                } //previewPost
            } finally {
                PreviewLock.isLocked = false;
            }
        } //PreviewLock.isLocked
    } //previewLock
}
