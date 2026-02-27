import { loadCurrentTransactionCommentsBase } from "./load/load-current-transaction-comments-base.js";
import { PostedAlmostSafeFileSize } from "/js/statics/transaction-tools/posted-almost-safe-file-size.js";
import { ElementProperties } from "/js/statics/element-properties.js";
import { StateId } from "/js/statics/states/state-id.js";

export async function previewPost(commonConfigs, previewPostConfig) {
    const doubleFileSizeSafeRate = 0.8;
    let settingsPhpConfig = null;
    let phpConfig = null;
    let transactionPhpConfig = null;
    let transactionCommentPhpConfig = null;
    let transactionImagePhpConfig = null;
    let userImagePhpConfig = null;
    let ratingModalPhpConfig = null;
    let autoSaveTimers = null;
    let formData = null;
    let data = null;
    let errorMessages = null;
    let errorMessage = null;
    let response = null;
    let phpIniArgumentNames = null;
    let phpIniSettingSizesInBytes = null;
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
    let transactionCommentCommentTextareaNamePrefix = null;
    let previewPostType = null;
    let deletedTransactionCommentIds = [];
    let deletedTransactionImageIds = [];
    let addedTransactionImageFiles = [];
    let targetTransactionCommentId = null;
    let newCommentTextareaInputTargetTransactionCommentComment = null;
    let responseDataGetSuccessMarker = 0;
    let stringPostedAlmostSafeFileSize = "";
    let currentTransactionCommentDTOs = null;
    let routeLoginMarker = 0;
    let routeReturn = null;
    let commentTextareaTransactionCommentComments = [];
    let commentTextareaTransactionCommentIds = [];
    let nameField = null;
    let responseErrorMessage = null;
    let cellDrawResponseStatusMarker = 0;
    let postedUserId = null;
    let editingPublishedTransactionCommentId = null;

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

    if (settingsPhpConfig && typeof settingsPhpConfig === "function") {
        phpIniArgumentNames = settingsPhpConfig.phpIniArgumentNames;
        phpIniSettingSizesInBytes = settingsPhpConfig.phpIniSettingSizesInBytes;
    }

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

    if (previewPostConfig && typeof previewPostConfig === "object") {
        previewPostType = previewPostConfig.previewPostType;
        targetTransactionCommentId =
            previewPostConfig.targetTransactionCommentId;
        newCommentTextareaInputTargetTransactionCommentComment =
            previewPostConfig.newCommentTextareaInputTargetTransactionCommentComment;
        deletedTransactionCommentIds =
            previewPostConfig.deletedTransactionCommentIds;
        deletedTransactionImageIds =
            previewPostConfig.deletedTransactionImageIds;
        addedTransactionImageFiles =
            previewPostConfig.addedTransactionImageFiles;
    } //previewPostConfig

    if (previewPostType && previewPostTypes) {
        if (previewPostType === previewPostTypes.COMMENT_EDIT) {
            editingPublishedTransactionCommentId = targetTransactionCommentId;
            if (StateId) {
                StateId.editingPublishedTransactionCommentId =
                    editingPublishedTransactionCommentId;
            } //StateId
        } //previewPostType
    } //previewPostType

    if (
        transactionCommentPhpConfig &&
        typeof transactionCommentPhpConfig === "object"
    ) {
        transactionCommentCommentTextareaNamePrefix =
            transactionCommentPhpConfig.transactionCommentCommentTextareaNamePrefix;
    } //transactionCommentPhpConfig

    if (postedUserDTO) {
        postedUserId = postedUserDTO.user_id;
    } //postedUserDTO

    if (
        PostedAlmostSafeFileSize &&
        typeof PostedAlmostSafeFileSize.getStringPostedAlmostSafeFileSize ===
            "function"
    ) {
        stringPostedAlmostSafeFileSize =
            PostedAlmostSafeFileSize.getStringPostedAlmostSafeFileSize(
                commonConfigs,
                doubleFileSizeSafeRate,
            );
    } //PostedAlmostSafeFileSize.getStringPostedAlmostSafeFileSize

    if (ElementProperties) {
        if (
            typeof ElementProperties.getElementDBIdsByElementNamePrefix ===
            "function"
        ) {
            commentTextareaTransactionCommentIds =
                ElementProperties.getElementDBIdsByElementNamePrefix(
                    transactionCommentCommentTextareaNamePrefix,
                );
        }
        if (
            typeof ElementProperties.getElementValuesByElementNamePrefix ===
            "function"
        ) {
            commentTextareaTransactionCommentComments =
                ElementProperties.getElementValuesByElementNamePrefix(
                    transactionCommentCommentTextareaNamePrefix,
                );
        }
    } //ElementProperties

    formData = new FormData();
    formData.append("selectedItemId", selectedItemId);
    formData.append("postedUserId", postedUserId);
    formData.append("previewPostType", previewPostType);
    formData.append("targetTransactionCommentId", targetTransactionCommentId);
    formData.append(
        "newCommentTextareaInputTargetTransactionCommentComment",
        newCommentTextareaInputTargetTransactionCommentComment,
    );

    if (commentTextareaTransactionCommentIds) {
        commentTextareaTransactionCommentIds.forEach((id) => {
            formData.append("commentTextareaTransactionCommentIds[]", id);
        });
    } //commentTextareaTransactionCommentIds
    if (commentTextareaTransactionCommentComments) {
        commentTextareaTransactionCommentComments.forEach((comment) => {
            formData.append(
                "commentTextareaTransactionCommentComments[]",
                comment,
            );
        });
    } //commentTextareaTransactionCommentComments
    if (deletedTransactionCommentIds) {
        deletedTransactionCommentIds.forEach((id) => {
            formData.append("deletedTransactionCommentIds[]", id);
        });
    } //deletedTransactionCommentIds
    if (deletedTransactionImageIds) {
        deletedTransactionImageIds.forEach((id) => {
            formData.append("deletedTransactionImageIds[]", id);
        });
    } //deletedTransactionImageIds
    if (addedTransactionImageFiles) {
        addedTransactionImageFiles.forEach((file) => {
            formData.append("addedTransactionImageFiles[]", file);
        });
    } //addedTransactionImageFiles

    if (routeTransactionSend) {
        response = await fetch(routeTransactionSend, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });

        responseDataGetSuccessMarker = 0;
        if (response.status === 200) {
            responseDataGetSuccessMarker = 1;
        } else if (response.status === 422) {
            responseDataGetSuccessMarker = 2;
        } //response.status
        if (response.status === 404) {
            routeLoginMarker = 1;
        } else if (response.status === 419) {
            routeLoginMarker = 2;
        } //response.status
        if (responseDataGetSuccessMarker !== 0) {
            data = await response.json();
        } //responseDataGetSuccessMarker&0

        if (response.status === 200) {
            cellDrawResponseStatusMarker = 1;
            console.log("保存成功", data);
        } else if (response.status === 422) {
            cellDrawResponseStatusMarker = 2;
            console.log("バリデーションエラー", data);
        } else {
            console.error("サーバーエラー", data);
        } //response.status

        errorMessage = "";
        if (data) {
            errorMessages = data.errorMessages;
            currentTransactionCommentDTOs = data.currentTransactionCommentDTOs;
        } //data

        if (response.status === 413) {
            responseErrorMessage =
                "送信された入力（画像・コメント）のサイズが大きすぎます。全部で" +
                stringPostedAlmostSafeFileSize +
                "程度までのファイルを送信してください。";
        } //response.status

        if (typeof loadCurrentTransactionCommentsBase === "function") {
            await loadCurrentTransactionCommentsBase(
                commonConfigs,
                previewPostConfig,
                errorMessages,
                responseErrorMessage,
                currentTransactionCommentDTOs,
            );
        } //loadCurrentTransactionComments
    } //routeTransactionSend

    if (routeLoginMarker !== 0) {
        routeReturn = routeLogin;
    }

    if (routeReturn) {
        window.location.href = routeReturn;
    } //routeReturn
}
