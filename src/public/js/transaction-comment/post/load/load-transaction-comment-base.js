import { loadTransactionImageBase } from "./load-transaction-image-base.js";
import { loadUserImageBase } from "./load-user-image-base.js";
import { AttributeTool } from "/js/statics/attribute-tool.js";
import { ErrorMessage } from "/js/statics/error-message.js";
import { TransactionCommentSendButtonSvg } from "/js/statics/transaction-comment-send-button-svg.js";
import { StateId } from "/js/statics/states/state-id.js";

export async function loadTransactionCommentBase(
    commonConfigs,
    previewPostConfig,
    errorMessages,
    currentTransactionCommentDTO,
) {
    const transactionCommentCell = document.createElement(`div`);
    const transactionCommentPreviewContainer = document.createElement(`div`);
    const transactionCommentPreview = document.createElement(`div`);
    const transactionCommentEditButtonContainer = document.createElement(`div`);
    const transactionCommentEditButton = document.createElement(`button`);
    const transactionCommentDeleteButton = document.createElement(`button`);
    const transactionCommentUploadInput = document.createElement(`input`);
    const transactionCommentUploadLabel = document.createElement(`label`);
    const transactionCommentSendButton = document.createElement(`button`);
    const transactionCommentErrorMessage = document.createElement(`div`);
    const transactionCommentUserImagePreview = document.createElement(`div`);
    const transactionCommentCommentTextareaContainer =
        document.createElement(`div`);
    const transactionCommentCommentTextarea =
        document.createElement(`textarea`);
    const transactionCommentCommentTextareaPlaceholder =
        "取引メッセージを記入してください";
    const draftTransactionCommentCommentTextareaWidth = "70%";
    const publishedTransactionCommentCommentTextareaWidth = "250px";
    let originalTransactionCommentCell = null;
    let originalTransactionCommentErrorMessage = null;
    let originalTransactionCommentSendButton = null;
    let originalTransactionCommentCommentTextarea = null;
    let originalTransactionCommentUploadLabel = null;
    let originalTransactionCommentEditButtonContainer = null;
    let transactionCommentSendButtonSvgWidth = 0;
    let transactionCommentSendButtonSvgHeight = 0;
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
    let transactionImageDTOs = null;
    let maxTransactionImageDTONumber = 0;
    let transactionImageDTONumber = 0;
    let transactionImageDTO = null;
    let currentTransactionCommentId = null;
    let currentTransactionCommentUserName = null;
    let currentTransactionCommentComment = null;
    let transactionCommentCellId = null;
    let transactionCommentPreviewContainerId = null;
    let transactionCommentPreviewId = null;
    let transactionCommentEditButtonContainerId = null;
    let transactionCommentEditButtonId = null;
    let transactionCommentDeleteButtonId = null;
    let transactionCommentUploadInputId = null;
    let transactionCommentUploadLabelId = null;
    let transactionCommentSendButtonId = null;
    let transactionCommentErrorMessageId = null;
    let transactionCommentErrorMessageName = null;
    let transactionCommentUserImagePreviewId = null;
    let transactionCommentCommentTextareaContainerId = null;
    let transactionCommentCommentTextareaId = null;
    let transactionCommentCommentTextareaName = null;
    let transactionCell = null;
    let transactionCommentMergedErrorMessage = null;
    let currentTransactionCommentUserId = null;
    let postedUserId = null;
    let counterpartUserId = null;
    let currentTransactionCommentUserDTO = null;
    let currentTransactionCommentUserKind = null;
    let currentTransactionCommentStatus = null;
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

    if (transactionPhpConfig && typeof transactionPhpConfig === "object") {
        transactionCellContainerId =
            transactionPhpConfig.transactionCellContainerId;
        transactionCellContainerClass =
            transactionPhpConfig.transactionCellContainerClass;
        transactionCellId = transactionPhpConfig.transactionCellId;
        transactionCellClass = transactionPhpConfig.transactionCellClass;
        transactionErrorMessageId =
            transactionPhpConfig.transactionErrorMessageId;
        transactionErrorMessageClass =
            transactionPhpConfig.transactionErrorMessageClass;
    } //transactionPhpConfig

    if (
        transactionCommentPhpConfig &&
        typeof transactionCommentPhpConfig === "object"
    ) {
        transactionCommentCellIdPrefix =
            transactionCommentPhpConfig.transactionCommentCellIdPrefix;
        transactionCommentCellClass =
            transactionCommentPhpConfig.transactionCommentCellClass;
        transactionCommentPreviewContainerIdPrefix =
            transactionCommentPhpConfig.transactionCommentPreviewContainerIdPrefix;
        transactionCommentPreviewContainerClass =
            transactionCommentPhpConfig.transactionCommentPreviewContainerClass;
        transactionCommentPreviewIdPrefix =
            transactionCommentPhpConfig.transactionCommentPreviewIdPrefix;
        transactionCommentPreviewClass =
            transactionCommentPhpConfig.transactionCommentPreviewClass;
        transactionCommentEditButtonContainerIdPrefix =
            transactionCommentPhpConfig.transactionCommentEditButtonContainerIdPrefix;
        transactionCommentEditButtonContainerClass =
            transactionCommentPhpConfig.transactionCommentEditButtonContainerClass;
        transactionCommentEditButtonIdPrefix =
            transactionCommentPhpConfig.transactionCommentEditButtonIdPrefix;
        transactionCommentEditButtonClass =
            transactionCommentPhpConfig.transactionCommentEditButtonClass;
        transactionCommentDeleteButtonIdPrefix =
            transactionCommentPhpConfig.transactionCommentDeleteButtonIdPrefix;
        transactionCommentDeleteButtonClass =
            transactionCommentPhpConfig.transactionCommentDeleteButtonClass;
        transactionCommentUploadInputIdPrefix =
            transactionCommentPhpConfig.transactionCommentUploadInputIdPrefix;
        transactionCommentUploadInputClass =
            transactionCommentPhpConfig.transactionCommentUploadInputClass;
        transactionCommentUploadLabelIdPrefix =
            transactionCommentPhpConfig.transactionCommentUploadLabelIdPrefix;
        transactionCommentUploadLabelClass =
            transactionCommentPhpConfig.transactionCommentUploadLabelClass;
        transactionCommentSendButtonIdPrefix =
            transactionCommentPhpConfig.transactionCommentSendButtonIdPrefix;
        transactionCommentSendButtonClass =
            transactionCommentPhpConfig.transactionCommentSendButtonClass;
        transactionCommentErrorMessageIdPrefix =
            transactionCommentPhpConfig.transactionCommentErrorMessageIdPrefix;
        transactionCommentErrorMessageNamePrefix =
            transactionCommentPhpConfig.transactionCommentErrorMessageNamePrefix;
        transactionCommentErrorMessageClass =
            transactionCommentPhpConfig.transactionCommentErrorMessageClass;
        transactionCommentUserImagePreviewIdPrefix =
            transactionCommentPhpConfig.transactionCommentUserImagePreviewIdPrefix;
        transactionCommentUserImagePreviewClass =
            transactionCommentPhpConfig.transactionCommentUserImagePreviewClass;
        transactionCommentCommentTextareaContainerIdPrefix =
            transactionCommentPhpConfig.transactionCommentCommentTextareaContainerIdPrefix;
        transactionCommentCommentTextareaContainerClass =
            transactionCommentPhpConfig.transactionCommentCommentTextareaContainerClass;
        transactionCommentCommentTextareaIdPrefix =
            transactionCommentPhpConfig.transactionCommentCommentTextareaIdPrefix;
        transactionCommentCommentTextareaClass =
            transactionCommentPhpConfig.transactionCommentCommentTextareaClass;
        transactionCommentCommentTextareaNamePrefix =
            transactionCommentPhpConfig.transactionCommentCommentTextareaNamePrefix;
    } //transactionCommentPhpConfig

    if (StateId) {
        editingPublishedTransactionCommentId =
            StateId.editingPublishedTransactionCommentId;
    } //StateId

    if (currentTransactionCommentDTO) {
        currentTransactionCommentId =
            currentTransactionCommentDTO.transaction_comment_id;
        currentTransactionCommentStatus = currentTransactionCommentDTO.status;
        currentTransactionCommentUserId = currentTransactionCommentDTO.user_id;
        currentTransactionCommentUserName =
            currentTransactionCommentDTO.user_name;
        currentTransactionCommentComment = currentTransactionCommentDTO.comment;
    } //currentTransactionCommentDTO

    if (postedUserDTO) {
        postedUserId = postedUserDTO.user_id;
    } //postedUserDTO
    if (counterpartUserDTO) {
        counterpartUserId = counterpartUserDTO.user_id;
    } //counterpartUserDTO

    if (currentTransactionCommentUserId) {
        if (currentTransactionCommentUserId === postedUserId) {
            if (userKinds) {
                currentTransactionCommentUserKind = userKinds.POSTED_USER;
            } //userKinds
            currentTransactionCommentUserDTO = postedUserDTO;
        } else if (currentTransactionCommentUserId === counterpartUserId) {
            if (userKinds) {
                currentTransactionCommentUserKind = userKinds.COUNTERPART_USER;
            } //userKinds
            currentTransactionCommentUserDTO = counterpartUserDTO;
        } //currentTransactionCommentUserId
    } //currentTransactionCommentUserId

    if (
        AttributeTool &&
        typeof AttributeTool.getAttributeFromAttributePrefixAndDBId ===
            "function"
    ) {
        transactionCommentCellId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentCellIdPrefix,
                currentTransactionCommentId,
            );

        transactionCommentPreviewContainerId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentPreviewContainerIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentPreviewId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentPreviewIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentEditButtonContainerId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentEditButtonContainerIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentEditButtonId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentEditButtonIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentDeleteButtonId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentDeleteButtonIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentUploadInputId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentUploadInputIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentUploadLabelId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentUploadLabelIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentSendButtonId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentSendButtonIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentErrorMessageId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentErrorMessageIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentErrorMessageName =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentErrorMessageNamePrefix,
                currentTransactionCommentId,
            );

        transactionCommentUserImagePreviewId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentUserImagePreviewIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentCommentTextareaContainerId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentCommentTextareaContainerIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentCommentTextareaId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentCommentTextareaIdPrefix,
                currentTransactionCommentId,
            );
        transactionCommentCommentTextareaName =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentCommentTextareaNamePrefix,
                currentTransactionCommentId,
            );
    } //AttributeTool

    if (transactionCellId) {
        transactionCell = document.getElementById(transactionCellId);
    } //transactionCellId

    if (transactionCommentCellId) {
        originalTransactionCommentCell = document.getElementById(
            transactionCommentCellId,
        );
    } //transactionCommentCellId

    if (transactionCommentErrorMessageId) {
        originalTransactionCommentErrorMessage = document.getElementById(
            transactionCommentErrorMessageId,
        );
    } //transactionCommentErrorMessageId

    if (transactionCommentSendButtonId) {
        originalTransactionCommentSendButton = document.getElementById(
            transactionCommentSendButtonId,
        );
    }

    if (transactionCommentCommentTextareaId) {
        originalTransactionCommentCommentTextarea = document.getElementById(
            transactionCommentCommentTextareaId,
        );
    }

    if (transactionCommentUploadLabelId) {
        originalTransactionCommentUploadLabel = document.getElementById(
            transactionCommentUploadLabelId,
        );
    } //transactionCommentUploadLabelId

    if (transactionCommentEditButtonContainerId) {
        originalTransactionCommentEditButtonContainer = document.getElementById(
            transactionCommentEditButtonContainerId,
        );
    } //transactionCommentEditButtonContainer

    if (
        ErrorMessage &&
        typeof ErrorMessage.getMergedErrorMessageFromErrorMessagesAndErrorMessageName ===
            "function"
    ) {
        transactionCommentMergedErrorMessage =
            ErrorMessage.getMergedErrorMessageFromErrorMessagesAndErrorMessageName(
                errorMessages,
                transactionCommentErrorMessageName,
            );
    }
    if (originalTransactionCommentErrorMessage) {
        originalTransactionCommentErrorMessage.textContent =
            transactionCommentMergedErrorMessage;
    } //originalTransactionCommentErrorMessage

    if (currentTransactionCommentUserKind && userKinds) {
        if (currentTransactionCommentUserKind === userKinds.POSTED_USER) {
            if (currentTransactionCommentStatus && transactionCommentStatuses) {
                if (
                    currentTransactionCommentStatus ===
                    transactionCommentStatuses.DRAFT
                ) {
                    if (originalTransactionCommentSendButton) {
                        originalTransactionCommentSendButton.style.display =
                            "block";
                    } //originalTransactionCommentSendButton
                    if (originalTransactionCommentEditButtonContainer) {
                        originalTransactionCommentEditButtonContainer.style.display =
                            "none";
                    } //originalTransactionCommentEditButtonContainer
                    if (originalTransactionCommentCommentTextarea) {
                        originalTransactionCommentCommentTextarea.disabled = false;
                        originalTransactionCommentCommentTextarea.placeholder =
                            transactionCommentCommentTextareaPlaceholder;
                        originalTransactionCommentCommentTextarea.style.width =
                            draftTransactionCommentCommentTextareaWidth;
                    } //originalTransactionCommentCommentTextarea
                    if (originalTransactionCommentUploadLabel) {
                        originalTransactionCommentUploadLabel.style.display =
                            "block";
                    } //originalTransactionCommentUploadLabel
                } else if (
                    currentTransactionCommentStatus ===
                    transactionCommentStatuses.PUBLISHED
                ) {
                    if (originalTransactionCommentSendButton) {
                        originalTransactionCommentSendButton.style.display =
                            "none";
                    } //originalTransactionCommentSendButton
                    if (originalTransactionCommentEditButtonContainer) {
                        originalTransactionCommentEditButtonContainer.style.display =
                            "block";
                    } //originalTransactionCommentEditButtonContainer

                    if (
                        currentTransactionCommentId ===
                        editingPublishedTransactionCommentId
                    ) {
                        if (originalTransactionCommentCommentTextarea) {
                            originalTransactionCommentCommentTextarea.disabled = false;
                            originalTransactionCommentCommentTextarea.placeholder =
                                transactionCommentCommentTextareaPlaceholder;
                            originalTransactionCommentCommentTextarea.style.width =
                                publishedTransactionCommentCommentTextareaWidth;
                        } //originalTransactionCommentCommentTextarea
                        if (originalTransactionCommentUploadLabel) {
                            originalTransactionCommentUploadLabel.style.display =
                                "block";
                        } //originalTransactionCommentUploadLabel
                    } else {
                        if (originalTransactionCommentCommentTextarea) {
                            originalTransactionCommentCommentTextarea.disabled = true;
                            originalTransactionCommentCommentTextarea.placeholder =
                                "";
                            originalTransactionCommentCommentTextarea.style.width =
                                publishedTransactionCommentCommentTextareaWidth;
                        } //originalTransactionCommentCommentTextarea
                        if (originalTransactionCommentUploadLabel) {
                            originalTransactionCommentUploadLabel.style.display =
                                "none";
                        } //originalTransactionCommentUploadLabel
                    }
                } //currentTransactionCommentStatus
            }
        } //userKinds
    } //currentTransactionCommentUserKind

    if (originalTransactionCommentCell === null) {
        transactionCommentCell.id = transactionCommentCellId;
        transactionCommentCell.className = transactionCommentCellClass;

        transactionCommentPreviewContainer.id =
            transactionCommentPreviewContainerId;
        transactionCommentPreviewContainer.className =
            transactionCommentPreviewContainerClass;
        transactionCommentPreview.id = transactionCommentPreviewId;
        transactionCommentPreview.className = transactionCommentPreviewClass;

        transactionCommentEditButtonContainer.id =
            transactionCommentEditButtonContainerId;
        transactionCommentEditButtonContainer.className =
            transactionCommentEditButtonContainerClass;

        transactionCommentEditButton.id = transactionCommentEditButtonId;
        transactionCommentEditButton.className =
            transactionCommentEditButtonClass;
        transactionCommentEditButton.textContent = "編集";
        if (transactionCommentEditButton) {
            transactionCommentEditButton.addEventListener(
                "click",
                async (eventObjectPreview) => {
                    let moduleChange =
                        await import("./transaction-comment-listener-method.js");
                    if (
                        moduleChange &&
                        typeof moduleChange.transactionCommentListenerMethod ===
                            "function"
                    ) {
                        await moduleChange.transactionCommentListenerMethod(
                            commonConfigs,
                            currentTransactionCommentDTO,
                            transactionCommentEditButtonIdPrefix,
                            eventObjectPreview,
                        );
                    } //transactionCommentListenerMethod
                },
            );
        }

        transactionCommentDeleteButton.id = transactionCommentDeleteButtonId;
        transactionCommentDeleteButton.className =
            transactionCommentDeleteButtonClass;
        transactionCommentDeleteButton.textContent = "削除";
        if (transactionCommentDeleteButton) {
            transactionCommentDeleteButton.addEventListener(
                "click",
                async (eventObjectPreview) => {
                    let moduleChange =
                        await import("./transaction-comment-listener-method.js");
                    if (
                        moduleChange &&
                        typeof moduleChange.transactionCommentListenerMethod ===
                            "function"
                    ) {
                        await moduleChange.transactionCommentListenerMethod(
                            commonConfigs,
                            currentTransactionCommentDTO,
                            transactionCommentDeleteButtonIdPrefix,
                            eventObjectPreview,
                        );
                    } //transactionCommentListenerMethod
                },
            );
        }

        transactionCommentUserImagePreview.id =
            transactionCommentUserImagePreviewId;
        transactionCommentUserImagePreview.className =
            transactionCommentUserImagePreviewClass;

        transactionCommentErrorMessage.id = transactionCommentErrorMessageId;
        transactionCommentErrorMessage.name =
            transactionCommentErrorMessageName;
        transactionCommentErrorMessage.className =
            transactionCommentErrorMessageClass;

        transactionCommentCommentTextareaContainer.id =
            transactionCommentCommentTextareaContainerId;
        transactionCommentCommentTextareaContainer.className =
            transactionCommentCommentTextareaContainerClass;

        transactionCommentCommentTextarea.id =
            transactionCommentCommentTextareaId;
        transactionCommentCommentTextarea.className =
            transactionCommentCommentTextareaClass;
        transactionCommentCommentTextarea.textContent =
            currentTransactionCommentComment;
        transactionCommentCommentTextarea.name =
            transactionCommentCommentTextareaName;
        if (transactionCommentCommentTextarea) {
            transactionCommentCommentTextarea.addEventListener(
                "input",
                async function (eventObjectPreview) {
                    let moduleInput =
                        await import("./comment-textarea-input.js");
                    if (
                        moduleInput &&
                        typeof moduleInput.commentTextareaInput === "function"
                    ) {
                        await moduleInput.commentTextareaInput(
                            commonConfigs,
                            currentTransactionCommentDTO,
                            transactionCommentCommentTextareaIdPrefix,
                            eventObjectPreview,
                        );
                    }
                },
            );
        } //transactionCommentCommentTextarea

        transactionCommentUploadInput.id = transactionCommentUploadInputId;
        transactionCommentUploadInput.className =
            transactionCommentUploadInputClass;
        transactionCommentUploadInput.type = "file";
        transactionCommentUploadInput.accept = "image/*";
        transactionCommentUploadInput.multiple = true;

        if (transactionCommentUploadInput) {
            transactionCommentUploadInput.addEventListener(
                "change",
                async function (eventObjectPreview) {
                    let moduleChange =
                        await import("./transaction-comment-listener-method.js");
                    if (
                        moduleChange &&
                        typeof moduleChange.transactionCommentListenerMethod ===
                            "function"
                    ) {
                        await moduleChange.transactionCommentListenerMethod(
                            commonConfigs,
                            currentTransactionCommentDTO,
                            transactionCommentUploadInputIdPrefix,
                            eventObjectPreview,
                        );
                    } //transactionCommentListenerMethod
                },
            );
        }

        transactionCommentUploadLabel.id = transactionCommentUploadLabelId;
        transactionCommentUploadLabel.className =
            transactionCommentUploadLabelClass;
        transactionCommentUploadLabel.setAttribute(
            "for",
            transactionCommentUploadInputId,
        );
        transactionCommentUploadLabel.textContent = "画像を追加";

        transactionCommentSendButton.id = transactionCommentSendButtonId;
        transactionCommentSendButton.className =
            transactionCommentSendButtonClass;

        transactionCommentSendButtonSvgWidth = 30;
        transactionCommentSendButtonSvgHeight = 30;

        if (
            TransactionCommentSendButtonSvg &&
            typeof TransactionCommentSendButtonSvg.getTransactionCommentSendButtonSvg ===
                "function"
        ) {
            transactionCommentSendButton.innerHTML =
                TransactionCommentSendButtonSvg.getTransactionCommentSendButtonSvg(
                    transactionCommentSendButtonSvgWidth,
                    transactionCommentSendButtonSvgHeight,
                );
        } //TransactionCommentSendButtonSvg

        transactionCommentSendButton.addEventListener(
            "click",
            async function (eventObjectPreview) {
                let moduleChange =
                    await import("./transaction-comment-listener-method.js");
                if (
                    moduleChange &&
                    typeof moduleChange.transactionCommentListenerMethod ===
                        "function"
                ) {
                    await moduleChange.transactionCommentListenerMethod(
                        commonConfigs,
                        currentTransactionCommentDTO,
                        transactionCommentSendButtonIdPrefix,
                        eventObjectPreview,
                    );
                } //transactionCommentListenerMethod
            },
        );

        if (currentTransactionCommentUserKind && userKinds) {
            if (currentTransactionCommentUserKind === userKinds.POSTED_USER) {
                transactionCommentCommentTextareaContainer.style.display =
                    "flex";
                transactionCommentCommentTextareaContainer.style.justifyContent =
                    "flex-end";

                if (
                    currentTransactionCommentStatus &&
                    transactionCommentStatuses
                ) {
                    if (
                        currentTransactionCommentStatus ===
                        transactionCommentStatuses.DRAFT
                    ) {
                        transactionCommentCell.style.alignItems = "flex-end";
                        transactionCommentEditButtonContainer.style.display =
                            "none";
                        transactionCommentSendButton.style.display = "block";
                        transactionCommentCommentTextarea.disabled = false;
                        transactionCommentCommentTextarea.placeholder =
                            transactionCommentCommentTextareaPlaceholder;
                        transactionCommentCommentTextarea.style.width =
                            draftTransactionCommentCommentTextareaWidth;
                        transactionCommentUploadLabel.style.display = "block";
                    } else if (
                        currentTransactionCommentStatus ===
                        transactionCommentStatuses.PUBLISHED
                    ) {
                        transactionCommentCell.style.alignItems = "flex-end";
                        transactionCommentEditButtonContainer.style.display =
                            "block";

                        transactionCommentSendButton.style.display = "none";
                        if (
                            currentTransactionCommentId ===
                            editingPublishedTransactionCommentId
                        ) {
                            transactionCommentCommentTextarea.disabled = false;
                            transactionCommentCommentTextarea.placeholder =
                                transactionCommentCommentTextareaPlaceholder;
                            transactionCommentCommentTextarea.style.width =
                                publishedTransactionCommentCommentTextareaWidth;
                            transactionCommentUploadLabel.style.display =
                                "block";
                        } else {
                            transactionCommentCommentTextarea.disabled = true;
                            transactionCommentCommentTextarea.placeholder = "";
                            transactionCommentCommentTextarea.style.width =
                                publishedTransactionCommentCommentTextareaWidth;
                            transactionCommentUploadLabel.style.display =
                                "none";
                        }
                    }
                } //currentTransactionCommentStatus
            } else if (
                currentTransactionCommentUserKind === userKinds.COUNTERPART_USER
            ) {
                transactionCommentCommentTextareaContainer.style.display =
                    "flex";
                transactionCommentCommentTextareaContainer.style.justifyContent =
                    "flex-start";
                transactionCommentCell.style.alignItems = "flex-start";
                transactionCommentEditButtonContainer.style.display = "none";
                transactionCommentUploadLabel.style.display = "none";
                transactionCommentCommentTextarea.disabled = true;
                transactionCommentCommentTextarea.placeholder = "";
                transactionCommentCommentTextarea.style.width =
                    publishedTransactionCommentCommentTextareaWidth;
                transactionCommentSendButton.style.display = "none";
            }
        } //userKinds

        transactionCommentPreviewContainer.appendChild(
            transactionCommentPreview,
        );
        transactionCommentCell.appendChild(transactionCommentUserImagePreview);

        transactionCommentCell.appendChild(transactionCommentPreviewContainer);

        transactionCommentCell.appendChild(transactionCommentErrorMessage);

        transactionCommentCommentTextareaContainer.appendChild(
            transactionCommentCommentTextarea,
        );
        transactionCommentCommentTextareaContainer.appendChild(
            transactionCommentUploadInput,
        );
        transactionCommentCommentTextareaContainer.appendChild(
            transactionCommentUploadLabel,
        );
        transactionCommentCommentTextareaContainer.appendChild(
            transactionCommentSendButton,
        );

        transactionCommentCell.appendChild(
            transactionCommentCommentTextareaContainer,
        );

        transactionCommentEditButtonContainer.appendChild(
            transactionCommentEditButton,
        );
        transactionCommentEditButtonContainer.appendChild(
            transactionCommentDeleteButton,
        );

        transactionCommentCell.appendChild(
            transactionCommentEditButtonContainer,
        );

        if (transactionCell) {
            transactionCell.appendChild(transactionCommentCell);
        } //transactionCell
    } //originalTransactionCommentCell&null

    transactionImageDTOs = null;
    if (currentTransactionCommentDTO) {
        transactionImageDTOs =
            currentTransactionCommentDTO.transaction_image_dtos;
    } //currentTransactionCommentDTO
    maxTransactionImageDTONumber = 0;
    if (transactionImageDTOs) {
        maxTransactionImageDTONumber = transactionImageDTOs.length;
    } //transactionImageDTOs

    if (typeof loadUserImageBase === "function") {
        await loadUserImageBase(
            commonConfigs,
            previewPostConfig,
            errorMessages,
            currentTransactionCommentDTO,
            currentTransactionCommentUserKind,
            currentTransactionCommentUserDTO,
        );
    } //loadUserImageBase

    for (
        transactionImageDTONumber = 1;
        transactionImageDTONumber <= maxTransactionImageDTONumber;
        transactionImageDTONumber++
    ) {
        transactionImageDTO =
            transactionImageDTOs[transactionImageDTONumber - 1];

        if (typeof loadTransactionImageBase === "function") {
            await loadTransactionImageBase(
                commonConfigs,
                previewPostConfig,
                errorMessages,
                currentTransactionCommentDTO,
                currentTransactionCommentUserKind,
                transactionImageDTO,
            );
        }
    }
}
