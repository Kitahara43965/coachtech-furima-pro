export function editModal() {
    const editModalConfig = window.editModalConfig;
    let editModalId = null;
    let editModal = null;
    let openEditButtonClass = null;
    let openEditButtonSelector = null;
    let openEditButtons = null;
    let closeEditModalButtonId = null;
    let closeEditModalButton = null;
    let editModalCommentId = null;
    let editModalComment = null;
    let editModalMessageId = null;
    let editModalMessage = null;
    let prefixPublishedTransactionCommentId = null;
    let textareas = null;

    if (editModalConfig) {
        editModalId = editModalConfig.editModalId;
        openEditButtonClass = editModalConfig.openEditButtonClass;
        closeEditModalButtonId = editModalConfig.closeEditModalButtonId;
        editModalCommentId = editModalConfig.editModalCommentId;
        editModalMessageId = editModalConfig.editModalMessageId;
        prefixPublishedTransactionCommentId =
            editModalConfig.prefixPublishedTransactionCommentId;
    } //editModalConfig

    if (editModalId) {
        editModal = document.getElementById(editModalId);
    } //editModalId
    if (openEditButtonClass) {
        openEditButtonSelector = "." + openEditButtonClass;
        openEditButtons = document.querySelectorAll(openEditButtonSelector);
    } //openEditButtonClass
    if (closeEditModalButtonId) {
        closeEditModalButton = document.getElementById(closeEditModalButtonId);
    } //closeEditModalButtonId
    if (editModalCommentId) {
        editModalComment = document.getElementById(editModalCommentId);
    } //editModalCommentId
    if (editModalMessageId) {
        editModalMessage = document.getElementById(editModalMessageId);
    } //editModalMessageId

    textareas = document.querySelectorAll("textarea");

    if (openEditButtons) {
        openEditButtons.forEach((openEditButton) => {
            openEditButton.addEventListener("click", () => {
                const transactionId = openEditButton.dataset.transactionId;
                const message = openEditButton.dataset.message;
                let textarea = null;
                if (prefixPublishedTransactionCommentId && transactionId) {
                    textarea = document.getElementById(
                        prefixPublishedTransactionCommentId + transactionId,
                    );
                } //prefixPublishedTransactionCommentId

                if (textareas) {
                    textareas.forEach((dummyTextarea) => {
                        dummyTextarea.style.backgroundColor = ""; // 通常の背景色に戻す
                        dummyTextarea.style.borderColor = ""; // 通常の境界線の色に戻す
                    });
                } //textareas
                if (textarea) {
                    textarea.style.backgroundColor = "#e0f7fa"; // 編集時の背景色
                    textarea.style.borderColor = "#00796b"; // 編集時の境界線の色
                }

                // モーダルに情報をセット
                if (editModalComment) {
                    editModalComment.value = transactionId;
                } //editModalComment
                if (editModalMessage) {
                    editModalMessage.value = message;
                    console.log(editModalMessage.value);
                } //editModalMessage
                if (editModal) {
                    editModal.classList.add("show"); // showクラスを追加
                } //editModal
            });
        });
    } //openEditButtons

    // モーダルを閉じる処理

    if (editModal) {
        if (closeEditModalButton) {
            closeEditModalButton.addEventListener("click", () => {
                editModal.classList.remove("show"); // showクラスを削除
            });
        } //closeEditModalButton

        editModal.addEventListener("click", (e) => {
            if (e.target === editModal) {
                editModal.classList.remove("show");
            }
        });
    } //editModal
}
