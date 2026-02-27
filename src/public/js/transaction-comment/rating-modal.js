export function ratingModal(commonConfigs) {
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
    let ratingModalOpenButtonId = null;
    let ratingModalId = null;
    let openRatingModalButton = null;
    let ratingModal = null;
    let selectedItemIsBuyerCompleted = null;
    let selectedItemIsSellerCompleted = null;
    let selectedItemBuyerId = null;
    let selectedItemSellerId = null;
    let postedUserId = null;

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

    if (postedUserDTO) {
        postedUserId = postedUserDTO.user_id;
    } //postedUserDTO

    if (ratingModalPhpConfig) {
        ratingModalOpenButtonId = ratingModalPhpConfig.ratingModalOpenButtonId;
        ratingModalId = ratingModalPhpConfig.ratingModalId;
        selectedItemIsBuyerCompleted =
            ratingModalPhpConfig.selectedItemIsBuyerCompleted;
        selectedItemIsSellerCompleted =
            ratingModalPhpConfig.selectedItemIsSellerCompleted;
    }

    if (ratingModalOpenButtonId) {
        openRatingModalButton = document.getElementById(
            ratingModalOpenButtonId,
        );
    }

    if (ratingModalId) {
        ratingModal = document.getElementById(ratingModalId);
    }

    if (postedUserId === selectedItemSellerId) {
        if (
            selectedItemIsBuyerCompleted === true &&
            selectedItemIsSellerCompleted === false
        ) {
            ratingModal.style.display = "flex";
        }
    } //postedUserId

    if (openRatingModalButton && ratingModal) {
        openRatingModalButton.addEventListener("click", function () {
            ratingModal.style.display = "flex"; // モーダルを表示
        });
    }

    window.addEventListener("click", function (e) {
        if (ratingModal && e.target === ratingModal) {
            ratingModal.style.display = "none"; // モーダルを閉じる
        }
    });
}
