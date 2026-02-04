document.addEventListener("DOMContentLoaded", function () {
    let categorySelectConfig = window.categorySelectConfig;
    let categoryButtonClassElements = null;
    let selectedCategoryIdElement = null;
    let categoryButtonAppendingClass = null;
    let categoryButtonSelectDenialMarker = 0;
    let categoryButtonClass = null;
    let selectedCategoryId = null;
    let categoryButtonClassSelector = null;
    let selectedCategoryIdSelector = null;

    if (categorySelectConfig) {
        categoryButtonClass = categorySelectConfig.categoryButtonClass;
        selectedCategoryId = categorySelectConfig.selectedCategoryId;
        categoryButtonAppendingClass =
            categorySelectConfig.categoryButtonAppendingClass;
    } //categorySelectConfig

    categoryButtonClassSelector = `.${categoryButtonClass}`;
    selectedCategoryIdSelector = `#${selectedCategoryId}`;

    if (selectedCategoryIdSelector) {
        selectedCategoryIdElement = document.querySelector(
            selectedCategoryIdSelector,
        );
    } //selectedCategoryIdSelector
    if (categoryButtonClassSelector) {
        categoryButtonClassElements = document.querySelectorAll(
            categoryButtonClassSelector,
        );
    } //categoryButtonClassSelector

    if (
        categoryButtonClassElements === null ||
        categoryButtonClassElements.length <= 0
    ) {
        categoryButtonSelectDenialMarker = 1;
    }

    if (categoryButtonAppendingClass === null) {
        categoryButtonSelectDenialMarker = 2;
    } //categoryButtonAppendingClass

    if (selectedCategoryIdElement === null) {
        categoryButtonSelectDenialMarker = 3;
    } //selectedCategoryIdElement

    if (categoryButtonSelectDenialMarker === 0) {
        categoryButtonClassElements.forEach((categoryButtonClassElement) => {
            categoryButtonClassElement.addEventListener("click", function () {
                this.classList.toggle(categoryButtonAppendingClass);

                const selectedIds = Array.from(categoryButtonClassElements)
                    .filter((btn) =>
                        btn.classList.contains(categoryButtonAppendingClass),
                    )
                    .map((btn) => btn.dataset.id);

                if (selectedCategoryIdElement) {
                    selectedCategoryIdElement.value = selectedIds.join(",");
                } //selectedCategoryIdElement
            });
        });
    } //categoryButtonClassElements
});
