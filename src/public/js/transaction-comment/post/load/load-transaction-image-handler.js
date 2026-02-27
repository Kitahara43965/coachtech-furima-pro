import { AttributeTool } from "/js/statics/attribute-tool.js";
import { StateId } from "/js/statics/states/state-id.js";

export async function loadTransactionImageHandler(
    commonConfigs,
    previewPostConfig,
    errorMessages,
    currentTransactionCommentDTO,
    currentTransactionCommentUserKind,
    transactionImageDTO,
    eventObjectPreview,
) {
    const transactionImageCell = document.createElement(`div`);
    const transactionImagePreviewContainer = document.createElement(`div`);
    const transactionImageImageDiv = document.createElement(`div`);
    const transactionImagePreview = document.createElement(`img`);
    const transactionImageRemoveButton = document.createElement(`button`);
    let originalTransactionImageRemoveButton = null;
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
    let transactionCommentPreviewIdPrefix = null;
    let transactionImageCellIdPrefix = null;
    let transactionImageCellClass = null;
    let transactionImageRemoveButtonIdPrefix = null;
    let transactionImageRemoveButtonClass = null;
    let transactionImagePreviewContainerIdPrefix = null;
    let transactionImagePreviewContainerClass = null;
    let transactionImageImageDivClass = null;
    let transactionImageImageDivIdPrefix = null;
    let transactionImagePreviewIdPrefix = null;
    let transactionImagePreviewClass = null;
    let transactionImageErrorMessageIdPrefix = null;
    let transactionImageImageDivId = null;
    let transactionImageRemoveButtonId = null;
    let transactionImageCellId = null;
    let transactionImageErrorMessageId = null;
    let transactionCommentPreviewId = null;
    let transactionCommentPreview = null;
    let transactionImagePreviewId = null;
    let transactionCommentCell = null;
    let currentTransactionCommentId = null;
    let currentTransactionCommentStatus = null;
    let transactionImageId = null;
    let imageUrl = null;
    let image = null;
    let originalTransactionImageCell = null;
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

    if (
        transactionCommentPhpConfig &&
        typeof transactionCommentPhpConfig === "object"
    ) {
        transactionCommentPreviewIdPrefix =
            transactionCommentPhpConfig.transactionCommentPreviewIdPrefix;
    } //transactionCommentPhpConfig

    if (
        transactionImagePhpConfig &&
        typeof transactionImagePhpConfig === "object"
    ) {
        transactionImageCellIdPrefix =
            transactionImagePhpConfig.transactionImageCellIdPrefix;
        transactionImageCellClass =
            transactionImagePhpConfig.transactionImageCellClass;
        transactionImageRemoveButtonIdPrefix =
            transactionImagePhpConfig.transactionImageRemoveButtonIdPrefix;
        transactionImageRemoveButtonClass =
            transactionImagePhpConfig.transactionImageRemoveButtonClass;
        transactionImagePreviewContainerIdPrefix =
            transactionImagePhpConfig.transactionImagePreviewContainerIdPrefix;
        transactionImagePreviewContainerClass =
            transactionImagePhpConfig.transactionImagePreviewContainerClass;
        transactionImageImageDivClass =
            transactionImagePhpConfig.transactionImageImageDivClass;
        transactionImageImageDivIdPrefix =
            transactionImagePhpConfig.transactionImageImageDivIdPrefix;
        transactionImagePreviewIdPrefix =
            transactionImagePhpConfig.transactionImagePreviewIdPrefix;
        transactionImagePreviewClass =
            transactionImagePhpConfig.transactionImagePreviewClass;
        transactionImageErrorMessageIdPrefix =
            transactionImagePhpConfig.transactionImageErrorMessageIdPrefix;
    }

    if (StateId) {
        editingPublishedTransactionCommentId =
            StateId.editingPublishedTransactionCommentId;
    } //StateId

    if (currentTransactionCommentDTO) {
        currentTransactionCommentId =
            currentTransactionCommentDTO.transaction_comment_id;
        currentTransactionCommentStatus = currentTransactionCommentDTO.status;
    } //currentTransactionCommentDTO

    if (transactionImageDTO) {
        transactionImageId = transactionImageDTO.transaction_image_id;
        imageUrl = transactionImageDTO.image_url;
        image = transactionImageDTO.image;
    } //transactionImageDTO

    if (
        AttributeTool &&
        typeof AttributeTool.getAttributeFromAttributePrefixAndDBId ===
            "function"
    ) {
        transactionCommentPreviewId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentPreviewIdPrefix,
                currentTransactionCommentId,
            );

        transactionImageCellId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionImageCellIdPrefix,
                transactionImageId,
            );
        transactionImageRemoveButtonId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionImageRemoveButtonIdPrefix,
                transactionImageId,
            );
        transactionImageImageDivId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionImageImageDivIdPrefix,
                transactionImageId,
            );
        transactionImagePreviewId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionImagePreviewIdPrefix,
                transactionImageId,
            );
        transactionImageErrorMessageId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionImageErrorMessageIdPrefix,
                transactionImageId,
            );
    } //AttributeTool

    if (transactionCommentPreviewId) {
        transactionCommentPreview = document.getElementById(
            transactionCommentPreviewId,
        );
    } //transactionCommentPreviewId

    if (transactionImageCellId) {
        originalTransactionImageCell = document.getElementById(
            transactionImageCellId,
        );
    } //transactionImageCellId

    if (transactionImageRemoveButtonId) {
        originalTransactionImageRemoveButton = document.getElementById(
            transactionImageRemoveButtonId,
        );
    }

    if (currentTransactionCommentUserKind && userKinds) {
        if (currentTransactionCommentUserKind === userKinds.POSTED_USER) {
            if (currentTransactionCommentStatus && transactionCommentStatuses) {
                if (
                    currentTransactionCommentStatus ===
                    transactionCommentStatuses.DRAFT
                ) {
                    if (originalTransactionImageRemoveButton) {
                        originalTransactionImageRemoveButton.style.display =
                            "block";
                    } //originalTransactionImageRemoveButton
                } else if (
                    currentTransactionCommentStatus ===
                    transactionCommentStatuses.PUBLISHED
                ) {
                    if (
                        currentTransactionCommentId ===
                        editingPublishedTransactionCommentId
                    ) {
                        if (originalTransactionImageRemoveButton) {
                            originalTransactionImageRemoveButton.style.display =
                                "block";
                        } //originalTransactionImageRemoveButton
                    } else {
                        if (originalTransactionImageRemoveButton) {
                            originalTransactionImageRemoveButton.style.display =
                                "none";
                        } //originalTransactionImageRemoveButton
                    }
                }
            }
        } //currentTransactionCommentUserKind
    }

    if (originalTransactionImageCell === null) {
        transactionImagePreview.id = transactionImagePreviewId;
        transactionImagePreview.src = imageUrl;
        transactionImagePreview.className = transactionImagePreviewClass;
        transactionImageImageDiv.id = transactionImageImageDivId;
        transactionImageImageDiv.className = transactionImageImageDivClass;
        transactionImageImageDiv.textContent = image;
        transactionImageRemoveButton.id = transactionImageRemoveButtonId;
        transactionImageRemoveButton.className =
            transactionImageRemoveButtonClass;
        transactionImageRemoveButton.textContent = "×";
        transactionImageRemoveButton.addEventListener("click", async () => {
            let moduleClick =
                await import("./transaction-image-remove-button-click.js");

            if (
                moduleClick &&
                typeof moduleClick.transactionImageRemoveButtonClick ===
                    "function"
            ) {
                await moduleClick.transactionImageRemoveButtonClick(
                    commonConfigs,
                    transactionImageDTO,
                    transactionImageRemoveButtonIdPrefix,
                );
            }
        });

        transactionImagePreviewContainer.className =
            transactionImagePreviewContainerClass;

        if (currentTransactionCommentUserKind && userKinds) {
            if (currentTransactionCommentUserKind === userKinds.POSTED_USER) {
                if (
                    currentTransactionCommentStatus &&
                    transactionCommentStatuses
                ) {
                    if (
                        currentTransactionCommentStatus ===
                        transactionCommentStatuses.DRAFT
                    ) {
                        transactionImageRemoveButton.style.display = "block";
                    } else if (
                        currentTransactionCommentStatus ===
                        transactionCommentStatuses.PUBLISHED
                    ) {
                        if (
                            currentTransactionCommentId ===
                            editingPublishedTransactionCommentId
                        ) {
                            transactionImageRemoveButton.style.display =
                                "block";
                        } else {
                            transactionImageRemoveButton.style.display = "none";
                        }
                    }
                }
            } else if (
                currentTransactionCommentUserKind === userKinds.COUNTERPART_USER
            ) {
                transactionImageRemoveButton.style.display = "none";
            } //currentTransactionCommentUserKind
        }

        transactionImagePreviewContainer.appendChild(transactionImagePreview);
        transactionImagePreviewContainer.appendChild(
            transactionImageRemoveButton,
        );
        transactionImageCell.id = transactionImageCellId;
        transactionImageCell.className = transactionImageCellClass;
        transactionImageCell.appendChild(transactionImagePreviewContainer);
        transactionImageCell.appendChild(transactionImageImageDiv);

        if (transactionCommentPreview) {
            transactionCommentPreview.appendChild(transactionImageCell);
        } //transactionCommentPreview
    } //originalTransactionImageCell&null
} //onLoadTransactionImage
