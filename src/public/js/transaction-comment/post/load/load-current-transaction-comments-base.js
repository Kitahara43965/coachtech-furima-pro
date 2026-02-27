import { loadTransactionCommentBase } from "./load-transaction-comment-base.js";
import { DeleteCells } from "/js/statics/transaction-tools/delete-cells.js";

export async function loadCurrentTransactionCommentsBase(
    commonConfigs,
    previewPostConfig,
    errorMessages,
    responseErrorMessage,
    currentTransactionCommentDTOs,
) {
    const transactionErrorMessage = document.createElement(`div`);
    const transactionCell = document.createElement(`div`);
    let settingsPhpConfig = null;
    let phpConfig = null;
    let transactionPhpConfig = null;
    let transactionCommentPhpConfig = null;
    let transactionImagePhpConfig = null;
    let userImagePhpConfig = null;
    let ratingModalPhpConfig = null;
    let autoSaveTimers = null;
    let transactionCellContainerId = null;
    let transactionCellContainerClass = null;
    let transactionCellId = null;
    let transactionCellClass = null;
    let transactionErrorMessageId = null;
    let transactionErrorMessageClass = null;
    let transactionCellContainer = null;
    let originalTransactionCell = null;
    let OriginalTransactionErrorMessage = null;
    let maxCurrentTransactionCommentDTONumber = 0;
    let currentTransactionCommentDTONumber = 0;
    let currentTransactionCommentDTO = null;
    let allCellDrawMarker = 0;

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

    if (transactionCellId) {
        originalTransactionCell = document.getElementById(transactionCellId);
    } //transactionCellId
    if (transactionErrorMessageId) {
        OriginalTransactionErrorMessage = document.getElementById(
            transactionErrorMessageId,
        );
    } //transactionErrorMessageId
    if (OriginalTransactionErrorMessage) {
        OriginalTransactionErrorMessage.textContent = responseErrorMessage;
    } //OriginalTransactionErrorMessage

    if (DeleteCells && typeof DeleteCells.deleteCells === "function") {
        DeleteCells.deleteCells(commonConfigs, previewPostConfig);
    } //DeleteCells

    if (originalTransactionCell === null) {
        transactionErrorMessage.id = transactionErrorMessageId;
        transactionErrorMessage.className = transactionErrorMessageClass;
        transactionCell.id = transactionCellId;
        transactionCell.className = transactionCellClass;

        if (transactionCellContainerId) {
            transactionCellContainer = document.getElementById(
                transactionCellContainerId,
            );
        } //transactionCellContainerId
        if (transactionCellContainer) {
            transactionCellContainer.className = transactionCellContainerClass;
        } //transactionCellContainer

        transactionCell.appendChild(transactionErrorMessage);
        if (transactionCellContainer) {
            transactionCellContainer.appendChild(transactionCell);
        } //transactionCellContainer
    } //originalTransactionCell

    maxCurrentTransactionCommentDTONumber = 0;
    if (currentTransactionCommentDTOs) {
        maxCurrentTransactionCommentDTONumber =
            currentTransactionCommentDTOs.length;
    } //currentTransactionCommentDTOs

    for (
        currentTransactionCommentDTONumber = 1;
        currentTransactionCommentDTONumber <=
        maxCurrentTransactionCommentDTONumber;
        currentTransactionCommentDTONumber++
    ) {
        currentTransactionCommentDTO =
            currentTransactionCommentDTOs[
                currentTransactionCommentDTONumber - 1
            ];

        if (typeof loadTransactionCommentBase === "function") {
            await loadTransactionCommentBase(
                commonConfigs,
                previewPostConfig,
                errorMessages,
                currentTransactionCommentDTO,
            );
        }
    }
}
