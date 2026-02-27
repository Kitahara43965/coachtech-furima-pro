import { AutoSaveTimers } from "/js/statics/auto-save-timers.js";
import { PreviewLock } from "/js/statics/states/preview-lock.js";
import { initialPreview } from "./initial-preview.js";
import { ratingModal } from "./rating-modal.js";

document.addEventListener("DOMContentLoaded", async function () {
    const previewConfig = window.previewConfig;
    let phpIniArgumentNames = null;
    let phpIniSettingSizesInBytes = null;
    let commonConfigs = null;
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
    let transactionCellContainerId = null;
    let transactionCellContainerClass = null;
    let transactionCellId = null;
    let transactionCellClass = null;
    let transactionErrorMessageId = null;
    let transactionErrorMessageClass = null;
    let transactionCommentCellIdPrefix = null;
    let transactionCommentCellClass = null;
    let transactionCommentPreviewContainerIdPrefix = null;
    let transactionCommentPreviewContainerClass = null;
    let transactionCommentPreviewIdPrefix = null;
    let transactionCommentPreviewClass = null;
    let transactionCommentEditButtonContainerIdPrefix = null;
    let transactionCommentEditButtonContainerClass = null;
    let transactionCommentEditButtonIdPrefix = null;
    let transactionCommentEditButtonClass = null;
    let transactionCommentDeleteButtonIdPrefix = null;
    let transactionCommentDeleteButtonClass = null;
    let transactionCommentUploadInputIdPrefix = null;
    let transactionCommentUploadInputClass = null;
    let transactionCommentUploadLabelIdPrefix = null;
    let transactionCommentUploadLabelClass = null;
    let transactionCommentSendButtonIdPrefix = null;
    let transactionCommentSendButtonClass = null;
    let transactionCommentErrorMessageIdPrefix = null;
    let transactionCommentErrorMessageNamePrefix = null;
    let transactionCommentErrorMessageClass = null;
    let transactionCommentUserImagePreviewIdPrefix = null;
    let transactionCommentUserImagePreviewClass = null;
    let transactionCommentCommentTextareaContainerIdPrefix = null;
    let transactionCommentCommentTextareaContainerClass = null;
    let transactionCommentCommentTextareaIdPrefix = null;
    let transactionCommentCommentTextareaClass = null;
    let transactionCommentCommentTextareaNamePrefix = null;
    let transactionImageCellIdPrefix = null;
    let transactionImageCellClass = null;
    let transactionImageRemoveButtonIdPrefix = null;
    let transactionImageRemoveButtonClass = null;
    let transactionImagePreviewContainerIdPrefix = null;
    let transactionImagePreviewContainerClass = null;
    let transactionImageImageDivIdPrefix = null;
    let transactionImageImageDivClass = null;
    let transactionImagePreviewIdPrefix = null;
    let transactionImagePreviewClass = null;
    let transactionImageErrorMessageIdPrefix = null;
    let userImageCellIdPrefix = null;
    let userImageCellClass = null;
    let userImagePreviewContainerIdPrefix = null;
    let userImagePreviewContainerClass = null;
    let userImagePreviewIdPrefix = null;
    let userImagePreviewClass = null;
    let userImageImageDivIdPrefix = null;
    let userImageImageDivClass = null;
    let userNameDivIdPrefix = null;
    let userNameDivClass = null;
    let ratingModalOpenButtonId = null;
    let ratingModalId = null;
    let selectedItemIsBuyerCompleted = null;
    let selectedItemIsSellerCompleted = null;

    if (previewConfig) {
        phpIniArgumentNames = previewConfig.phpIniArgumentNames;
        phpIniSettingSizesInBytes = previewConfig.phpIniSettingSizesInBytes;
        previewPostTypes = previewConfig.previewPostTypes;
        userKinds = previewConfig.userKinds;
        transactionCommentStatuses = previewConfig.transactionCommentStatuses;
        csrfToken = previewConfig.csrfToken;
        dataFieldArgument = previewConfig.dataFieldArgument;
        dataIdArgument = previewConfig.dataIdArgument;
        routeLogin = previewConfig.routeLogin;
        routeItemDealItemId = previewConfig.routeItemDealItemId;
        routeTransactionSend = previewConfig.routeTransactionSend;
        selectedItemId = previewConfig.selectedItemId;
        postedUserDTO = previewConfig.postedUserDTO;
        counterpartUserDTO = previewConfig.counterpartUserDTO;
        selectedItemBuyerId = previewConfig.selectedItemBuyerId;
        selectedItemSellerId = previewConfig.selectedItemSellerId;
        transactionCellContainerId = previewConfig.transactionCellContainerId;
        transactionCellContainerClass =
            previewConfig.transactionCellContainerClass;
        transactionCellId = previewConfig.transactionCellId;
        transactionCellClass = previewConfig.transactionCellClass;
        transactionErrorMessageId = previewConfig.transactionErrorMessageId;
        transactionErrorMessageClass =
            previewConfig.transactionErrorMessageClass;
        transactionCommentCellIdPrefix =
            previewConfig.transactionCommentCellIdPrefix;
        transactionCommentCellClass = previewConfig.transactionCommentCellClass;
        transactionCommentPreviewContainerIdPrefix =
            previewConfig.transactionCommentPreviewContainerIdPrefix;
        transactionCommentPreviewContainerClass =
            previewConfig.transactionCommentPreviewContainerClass;
        transactionCommentPreviewIdPrefix =
            previewConfig.transactionCommentPreviewIdPrefix;
        transactionCommentPreviewClass =
            previewConfig.transactionCommentPreviewClass;
        transactionCommentEditButtonContainerIdPrefix =
            previewConfig.transactionCommentEditButtonContainerIdPrefix;
        transactionCommentEditButtonContainerClass =
            previewConfig.transactionCommentEditButtonContainerClass;
        transactionCommentEditButtonIdPrefix =
            previewConfig.transactionCommentEditButtonIdPrefix;
        transactionCommentEditButtonClass =
            previewConfig.transactionCommentEditButtonClass;
        transactionCommentDeleteButtonIdPrefix =
            previewConfig.transactionCommentDeleteButtonIdPrefix;
        transactionCommentDeleteButtonClass =
            previewConfig.transactionCommentDeleteButtonClass;
        transactionCommentUploadInputIdPrefix =
            previewConfig.transactionCommentUploadInputIdPrefix;
        transactionCommentUploadInputClass =
            previewConfig.transactionCommentUploadInputClass;
        transactionCommentUploadLabelIdPrefix =
            previewConfig.transactionCommentUploadLabelIdPrefix;
        transactionCommentUploadLabelClass =
            previewConfig.transactionCommentUploadLabelClass;
        transactionCommentSendButtonIdPrefix =
            previewConfig.transactionCommentSendButtonIdPrefix;
        transactionCommentSendButtonClass =
            previewConfig.transactionCommentSendButtonClass;
        transactionCommentErrorMessageIdPrefix =
            previewConfig.transactionCommentErrorMessageIdPrefix;
        transactionCommentErrorMessageNamePrefix =
            previewConfig.transactionCommentErrorMessageNamePrefix;
        transactionCommentErrorMessageClass =
            previewConfig.transactionCommentErrorMessageClass;
        transactionCommentUserImagePreviewIdPrefix =
            previewConfig.transactionCommentUserImagePreviewIdPrefix;
        transactionCommentUserImagePreviewClass =
            previewConfig.transactionCommentUserImagePreviewClass;
        transactionCommentCommentTextareaContainerIdPrefix =
            previewConfig.transactionCommentCommentTextareaContainerIdPrefix;
        transactionCommentCommentTextareaContainerClass =
            previewConfig.transactionCommentCommentTextareaContainerClass;
        transactionCommentCommentTextareaIdPrefix =
            previewConfig.transactionCommentCommentTextareaIdPrefix;
        transactionCommentCommentTextareaClass =
            previewConfig.transactionCommentCommentTextareaClass;
        transactionCommentCommentTextareaNamePrefix =
            previewConfig.transactionCommentCommentTextareaNamePrefix;
        transactionImageCellIdPrefix =
            previewConfig.transactionImageCellIdPrefix;
        transactionImageCellClass = previewConfig.transactionImageCellClass;
        transactionImageRemoveButtonIdPrefix =
            previewConfig.transactionImageRemoveButtonIdPrefix;
        transactionImageRemoveButtonClass =
            previewConfig.transactionImageRemoveButtonClass;
        transactionImagePreviewContainerIdPrefix =
            previewConfig.transactionImagePreviewContainerIdPrefix;
        transactionImagePreviewContainerClass =
            previewConfig.transactionImagePreviewContainerClass;
        transactionImageImageDivIdPrefix =
            previewConfig.transactionImageImageDivIdPrefix;
        transactionImageImageDivClass =
            previewConfig.transactionImageImageDivClass;
        transactionImagePreviewIdPrefix =
            previewConfig.transactionImagePreviewIdPrefix;
        transactionImagePreviewClass =
            previewConfig.transactionImagePreviewClass;
        transactionImageErrorMessageIdPrefix =
            previewConfig.transactionImageErrorMessageIdPrefix;

        userImageCellIdPrefix = previewConfig.userImageCellIdPrefix;
        userImageCellClass = previewConfig.userImageCellClass;
        userImagePreviewContainerIdPrefix =
            previewConfig.userImagePreviewContainerIdPrefix;
        userImagePreviewContainerClass =
            previewConfig.userImagePreviewContainerClass;
        userImagePreviewIdPrefix = previewConfig.userImagePreviewIdPrefix;
        userImagePreviewClass = previewConfig.userImagePreviewClass;
        userImageImageDivIdPrefix = previewConfig.userImageImageDivIdPrefix;
        userImageImageDivClass = previewConfig.userImageImageDivClass;
        userNameDivIdPrefix = previewConfig.userNameDivIdPrefix;
        userNameDivClass = previewConfig.userNameDivClass;
        ratingModalOpenButtonId = previewConfig.ratingModalOpenButtonId;
        ratingModalId = previewConfig.ratingModalId;
        selectedItemIsBuyerCompleted = Boolean(
            previewConfig.selectedItemIsBuyerCompleted,
        );
        selectedItemIsSellerCompleted = Boolean(
            previewConfig.selectedItemIsSellerCompleted,
        );
    } //previewConfig

    settingsPhpConfig = {
        phpIniArgumentNames: phpIniArgumentNames,
        phpIniSettingSizesInBytes: phpIniSettingSizesInBytes,
    };

    phpConfig = {
        previewPostTypes: previewPostTypes,
        userKinds: userKinds,
        transactionCommentStatuses: transactionCommentStatuses,
        csrfToken: csrfToken,
        dataFieldArgument: dataFieldArgument,
        dataIdArgument: dataIdArgument,
        routeLogin: routeLogin,
        routeItemDealItemId: routeItemDealItemId,
        routeTransactionSend: routeTransactionSend,
        selectedItemId: selectedItemId,
        postedUserDTO: postedUserDTO,
        counterpartUserDTO: counterpartUserDTO,
        selectedItemBuyerId: selectedItemBuyerId,
        selectedItemSellerId: selectedItemSellerId,
    };

    transactionPhpConfig = {
        transactionCellContainerId: transactionCellContainerId,
        transactionCellContainerClass: transactionCellContainerClass,
        transactionCellId: transactionCellId,
        transactionCellClass: transactionCellClass,
        transactionErrorMessageId: transactionErrorMessageId,
        transactionErrorMessageClass: transactionErrorMessageClass,
    };

    transactionCommentPhpConfig = {
        transactionCommentCellIdPrefix: transactionCommentCellIdPrefix,
        transactionCommentCellClass: transactionCommentCellClass,
        transactionCommentPreviewContainerIdPrefix:
            transactionCommentPreviewContainerIdPrefix,
        transactionCommentPreviewContainerClass:
            transactionCommentPreviewContainerClass,
        transactionCommentPreviewIdPrefix: transactionCommentPreviewIdPrefix,
        transactionCommentPreviewClass: transactionCommentPreviewClass,
        transactionCommentEditButtonContainerIdPrefix:
            transactionCommentEditButtonContainerIdPrefix,
        transactionCommentEditButtonContainerClass:
            transactionCommentEditButtonContainerClass,
        transactionCommentEditButtonIdPrefix:
            transactionCommentEditButtonIdPrefix,
        transactionCommentEditButtonClass: transactionCommentEditButtonClass,
        transactionCommentDeleteButtonIdPrefix:
            transactionCommentDeleteButtonIdPrefix,
        transactionCommentDeleteButtonClass:
            transactionCommentDeleteButtonClass,
        transactionCommentUploadInputIdPrefix:
            transactionCommentUploadInputIdPrefix,
        transactionCommentUploadInputClass: transactionCommentUploadInputClass,
        transactionCommentUploadLabelIdPrefix:
            transactionCommentUploadLabelIdPrefix,
        transactionCommentUploadLabelClass: transactionCommentUploadLabelClass,
        transactionCommentSendButtonIdPrefix:
            transactionCommentSendButtonIdPrefix,
        transactionCommentSendButtonClass: transactionCommentSendButtonClass,
        transactionCommentErrorMessageIdPrefix:
            transactionCommentErrorMessageIdPrefix,
        transactionCommentErrorMessageNamePrefix:
            transactionCommentErrorMessageNamePrefix,
        transactionCommentErrorMessageClass:
            transactionCommentErrorMessageClass,
        transactionCommentUserImagePreviewIdPrefix:
            transactionCommentUserImagePreviewIdPrefix,
        transactionCommentUserImagePreviewClass:
            transactionCommentUserImagePreviewClass,
        transactionCommentCommentTextareaContainerIdPrefix:
            transactionCommentCommentTextareaContainerIdPrefix,
        transactionCommentCommentTextareaContainerClass:
            transactionCommentCommentTextareaContainerClass,
        transactionCommentCommentTextareaIdPrefix:
            transactionCommentCommentTextareaIdPrefix,
        transactionCommentCommentTextareaClass:
            transactionCommentCommentTextareaClass,
        transactionCommentCommentTextareaNamePrefix:
            transactionCommentCommentTextareaNamePrefix,
    };

    transactionImagePhpConfig = {
        transactionImageCellIdPrefix: transactionImageCellIdPrefix,
        transactionImageCellClass: transactionImageCellClass,
        transactionImageRemoveButtonIdPrefix:
            transactionImageRemoveButtonIdPrefix,
        transactionImageRemoveButtonClass: transactionImageRemoveButtonClass,
        transactionImagePreviewContainerIdPrefix:
            transactionImagePreviewContainerIdPrefix,
        transactionImagePreviewContainerClass:
            transactionImagePreviewContainerClass,
        transactionImageImageDivIdPrefix: transactionImageImageDivIdPrefix,
        transactionImageImageDivClass: transactionImageImageDivClass,
        transactionImagePreviewIdPrefix: transactionImagePreviewIdPrefix,
        transactionImagePreviewClass: transactionImagePreviewClass,
        transactionImageErrorMessageIdPrefix:
            transactionImageErrorMessageIdPrefix,
    };

    userImagePhpConfig = {
        userImageCellIdPrefix: userImageCellIdPrefix,
        userImageCellClass: userImageCellClass,
        userImagePreviewContainerIdPrefix: userImagePreviewContainerIdPrefix,
        userImagePreviewContainerClass: userImagePreviewContainerClass,
        userImagePreviewIdPrefix: userImagePreviewIdPrefix,
        userImagePreviewClass: userImagePreviewClass,
        userImageImageDivIdPrefix: userImageImageDivIdPrefix,
        userImageImageDivClass: userImageImageDivClass,
        userNameDivIdPrefix: userNameDivIdPrefix,
        userNameDivClass: userNameDivClass,
    };

    ratingModalPhpConfig = {
        ratingModalOpenButtonId: ratingModalOpenButtonId,
        ratingModalId: ratingModalId,
        selectedItemIsBuyerCompleted: selectedItemIsBuyerCompleted,
        selectedItemIsSellerCompleted: selectedItemIsSellerCompleted,
    };

    if (previewPostTypes && typeof previewPostTypes === "object") {
        if (
            AutoSaveTimers &&
            typeof AutoSaveTimers.getInitialValues === "function"
        ) {
            autoSaveTimers = AutoSaveTimers.getInitialValues(previewPostTypes);
        } //AutoSaveTimers
    } //previewPostTypes

    commonConfigs = {
        settingsPhpConfig: settingsPhpConfig,
        phpConfig: phpConfig,
        transactionPhpConfig: transactionPhpConfig,
        transactionCommentPhpConfig: transactionCommentPhpConfig,
        transactionImagePhpConfig: transactionImagePhpConfig,
        userImagePhpConfig: userImagePhpConfig,
        ratingModalPhpConfig: ratingModalPhpConfig,
        autoSaveTimers: autoSaveTimers,
    };

    if (initialPreview && typeof initialPreview === "function") {
        await initialPreview(commonConfigs);
    } //initialPreview

    if (ratingModal && typeof ratingModal === "function") {
        ratingModal(commonConfigs);
    }
});
