document.addEventListener("DOMContentLoaded", function () {
    const ratingModalConfig = window.ratingModalConfig;
    let openRatingModalButtonId = null;
    let openRatingModalButton = null;
    let ratingModalId = null;
    let ratingModal = null;

    if (ratingModalConfig) {
        openRatingModalButtonId = ratingModalConfig.openRatingModalButtonId;
        ratingModalId = ratingModalConfig.ratingModalId;
    } //ratingModalConfig

    if (openRatingModalButtonId) {
        openRatingModalButton = document.getElementById(
            openRatingModalButtonId,
        );
    } //openRatingModalButtonId
    if (ratingModalId) {
        ratingModal = document.getElementById(ratingModalId);
    } //ratingModalId

    if (openRatingModalButton) {
        openRatingModalButton.addEventListener("click", function () {
            ratingModal.style.display = "flex";
        });
    } //openRatingModalButton

    window.addEventListener("click", function (e) {
        if (ratingModal) {
            if (e.target === ratingModal) {
                ratingModal.style.display = "none";
            }
        } //ratingModal
    });
});
