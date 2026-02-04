import { previewPost } from "./preview-post.js";
import { editModal } from "./edit-modal.js";

document.addEventListener("DOMContentLoaded", function () {
    const previewConfig = window.previewConfig;
    const autoSaveInterval = 1000;
    let csrfToken = null;
    let previewImageInputId = null;
    let previewImageInput = null;
    let previewGridId = null;
    let previewRemoveButtonClass = null;
    let previewCellClass = null;
    let previewGridClass = null;
    let routeTransactionCommentUpdateItemId = null;
    let selectedItemId = null;
    let transactionCommentName = null;
    let selectedFiles = null;
    let commentTextarea = null;
    let commentTextareaValue = null;
    let autoSaveTimer = null;
    let previewPostTypes = null;
    let previewPostType = null;
    let previewValidationMessageName = null;
    let previewCommentSendButtonId = null;
    let previewCommentSendButton = null;
    let previewPostInputConfig = null;
    let isSaving = false;

    if (previewConfig) {
        csrfToken = previewConfig.csrfToken;
        previewImageInputId = previewConfig.previewImageInputId;
        previewGridId = previewConfig.previewGridId;
        previewRemoveButtonClass = previewConfig.previewRemoveButtonClass;
        previewCellClass = previewConfig.previewCellClass;
        previewGridClass = previewConfig.previewGridClass;
        previewCommentSendButtonId = previewConfig.previewCommentSendButtonId;
        routeTransactionCommentUpdateItemId =
            previewConfig.routeTransactionCommentUpdateItemId;
        previewPostTypes = previewConfig.previewPostTypes;
        previewValidationMessageName =
            previewConfig.previewValidationMessageName;
        selectedItemId = previewConfig.selectedItemId;
        transactionCommentName = previewConfig.transactionCommentName;
    } //previewConfig

    if (transactionCommentName) {
        commentTextarea = document.querySelector(
            `textarea[name="${transactionCommentName}"]`,
        );
    } //transactionCommentName
    if (previewCommentSendButtonId) {
        previewCommentSendButton = document.getElementById(
            previewCommentSendButtonId,
        );
    } //previewCommentSendButtonId

    previewPostInputConfig = {
        csrfToken: csrfToken,
        routeTransactionCommentUpdateItemId:
            routeTransactionCommentUpdateItemId,
        previewValidationMessageName: previewValidationMessageName,
    };

    if (commentTextarea) {
        commentTextarea.addEventListener("input", () => {
            commentTextareaValue = commentTextarea.value;

            if (autoSaveTimer) {
                clearTimeout(autoSaveTimer);
            }

            autoSaveTimer = setTimeout(async () => {
                if (isSaving === false) {
                    isSaving = true;

                    try {
                        if (previewPostTypes) {
                            previewPostType = previewPostTypes.DRAFT;
                        } //previewPostTypes
                        if (typeof previewPost === "function") {
                            await previewPost(
                                commentTextareaValue,
                                previewPostType,
                                previewPostInputConfig,
                            );
                        } //
                    } finally {
                        isSaving = false;
                    }
                } //isSaving
            }, autoSaveInterval);
        });
    }

    if (previewCommentSendButton) {
        previewCommentSendButton.addEventListener("click", async () => {
            if (autoSaveTimer) {
                clearTimeout(autoSaveTimer);
            }

            if (!previewCommentSendButton.disabled && isSaving === false) {
                previewCommentSendButton.disabled = true;

                if (commentTextarea) {
                    commentTextareaValue = commentTextarea.value;
                }

                if (previewPostTypes) {
                    previewPostType = previewPostTypes.STORE;
                } //previewPostTypes

                try {
                    if (typeof previewPost === "function") {
                        await previewPost(
                            commentTextareaValue,
                            previewPostType,
                            previewPostInputConfig,
                        );
                    }
                } finally {
                    previewCommentSendButton.disabled = false;
                }
            }
        });
    }

    if (typeof editModal === "function") {
        editModal();
    }
});
