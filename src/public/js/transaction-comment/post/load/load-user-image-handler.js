import { AttributeTool } from "/js/statics/attribute-tool.js";
import { StateId } from "/js/statics/states/state-id.js";

export async function loadUserImageHandler(
    commonConfigs,
    previewPostConfig,
    errorMessages,
    currentTransactionCommentDTO,
    currentTransactionCommentUserKind,
    currentTransactionCommentUserDTO,
    eventObjectPreview,
) {
    const userImageCell = document.createElement(`div`);
    const userImagePreviewContainer = document.createElement(`div`);
    const userImagePreview = document.createElement(`img`);
    const userImageImageDiv = document.createElement(`div`);
    const userNameDiv = document.createElement(`div`);
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
    let transactionCommentUserImagePreviewIdPrefix = null;
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
    let currentTransactionCommentId = null;
    let currentTransactionCommentStatus = null;
    let currentTransactionCommentUserUserName = null;
    let currentTransactionCommentUserImage = null;
    let currentTransactionCommentUserImageUrl = null;
    let transactionCommentUserImagePreviewId = null;
    let transactionCommentUserImagePreview = null;
    let userImageCellId = null;
    let userImagePreviewContainerId = null;
    let userImagePreviewId = null;
    let userImageImageDivId = null;
    let userNameDivId = null;
    let originalUserImageCell = null;
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
        transactionCommentUserImagePreviewIdPrefix =
            transactionCommentPhpConfig.transactionCommentUserImagePreviewIdPrefix;
    } //transactionCommentPhpConfig

    if (StateId) {
        editingPublishedTransactionCommentId =
            StateId.editingPublishedTransactionCommentId;
    } //StateId

    if (currentTransactionCommentDTO) {
        currentTransactionCommentId =
            currentTransactionCommentDTO.transaction_comment_id;
        currentTransactionCommentStatus = currentTransactionCommentDTO.status;
        currentTransactionCommentUserUserName =
            currentTransactionCommentDTO.user_username;
    } //currentTransactionCommentDTO

    if (currentTransactionCommentUserDTO) {
        currentTransactionCommentUserImage =
            currentTransactionCommentUserDTO.image;
        currentTransactionCommentUserImageUrl =
            currentTransactionCommentUserDTO.image_url;
    } //currentTransactionCommentUserDTO

    if (userImagePhpConfig) {
        userImageCellIdPrefix = userImagePhpConfig.userImageCellIdPrefix;
        userImageCellClass = userImagePhpConfig.userImageCellClass;
        userImagePreviewContainerIdPrefix =
            userImagePhpConfig.userImagePreviewContainerIdPrefix;
        userImagePreviewContainerClass =
            userImagePhpConfig.userImagePreviewContainerClass;
        userImagePreviewIdPrefix = userImagePhpConfig.userImagePreviewIdPrefix;
        userImagePreviewClass = userImagePhpConfig.userImagePreviewClass;
        userImageImageDivIdPrefix =
            userImagePhpConfig.userImageImageDivIdPrefix;
        userImageImageDivClass = userImagePhpConfig.userImageImageDivClass;
        userNameDivIdPrefix = userImagePhpConfig.userNameDivIdPrefix;
        userNameDivClass = userImagePhpConfig.userNameDivClass;
    } //userImagePhpConfig

    if (
        AttributeTool &&
        typeof AttributeTool.getAttributeFromAttributePrefixAndDBId ===
            "function"
    ) {
        transactionCommentUserImagePreviewId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                transactionCommentUserImagePreviewIdPrefix,
                currentTransactionCommentId,
            );

        userImageCellId = AttributeTool.getAttributeFromAttributePrefixAndDBId(
            userImageCellIdPrefix,
            currentTransactionCommentId,
        );

        userImagePreviewContainerId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                userImagePreviewContainerIdPrefix,
                currentTransactionCommentId,
            );
        userImagePreviewId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                userImagePreviewIdPrefix,
                currentTransactionCommentId,
            );
        userImageImageDivId =
            AttributeTool.getAttributeFromAttributePrefixAndDBId(
                userImageImageDivIdPrefix,
                currentTransactionCommentId,
            );
        userNameDivId = AttributeTool.getAttributeFromAttributePrefixAndDBId(
            userNameDivIdPrefix,
            currentTransactionCommentId,
        );
    } //AttributeTool

    if (userImageCellId) {
        originalUserImageCell = document.getElementById(userImageCellId);
    } //userImageCellId

    if (transactionCommentUserImagePreviewId) {
        transactionCommentUserImagePreview = document.getElementById(
            transactionCommentUserImagePreviewId,
        );
    } //transactionCommentUserImagePreviewId

    if (originalUserImageCell === null) {
        userImageCell.id = userImageCellId;
        userImageCell.className = userImageCellClass;
        userImagePreviewContainer.id = userImagePreviewContainerId;
        userImagePreviewContainer.className = userImagePreviewContainerClass;
        userImagePreview.id = userImagePreviewId;
        userImagePreview.className = userImagePreviewClass;
        userImagePreview.src = currentTransactionCommentUserImageUrl;
        userImageImageDiv.id = userImageImageDivId;
        userImageImageDiv.className = userImageImageDivClass;
        userImageImageDiv.textContent = currentTransactionCommentUserImage;
        userNameDiv.id = userNameDivId;
        userNameDiv.className = userNameDivClass;
        userNameDiv.textContent = currentTransactionCommentUserUserName;

        if (currentTransactionCommentUserKind && userKinds) {
            if (currentTransactionCommentUserKind === userKinds.POSTED_USER) {
                userImagePreviewContainer.appendChild(userNameDiv);
                userImagePreviewContainer.appendChild(userImagePreview);
            } else if (
                currentTransactionCommentUserKind === userKinds.COUNTERPART_USER
            ) {
                userImagePreviewContainer.appendChild(userImagePreview);
                userImagePreviewContainer.appendChild(userNameDiv);
            } else {
                userImagePreviewContainer.appendChild(userNameDiv);
                userImagePreviewContainer.appendChild(userImagePreview);
            } //currentTransactionCommentUserKind
        } else {
            userImagePreviewContainer.appendChild(userNameDiv);
            userImagePreviewContainer.appendChild(userImagePreview);
        }

        userImageCell.appendChild(userImagePreviewContainer);
        userImageCell.appendChild(userImageImageDiv);

        if (transactionCommentUserImagePreview) {
            transactionCommentUserImagePreview.appendChild(userImageCell);
        } //transactionCommentUserImagePreview
    } //originalUserImageCell&null
}
