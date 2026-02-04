export async function previewPost(
    commentTextareaValue,
    previewPostType,
    inputConfig,
) {
    let csrfToken = null;
    let formData = null;
    let isPreviewDraft = false;
    let routeTransactionCommentUpdateItemId = null;
    let previewPostTypes = null;
    let data = null;
    let errors = null;
    let errorMessage = null;

    if (inputConfig) {
        csrfToken = inputConfig.csrfToken;
        routeTransactionCommentUpdateItemId =
            inputConfig.routeTransactionCommentUpdateItemId;
        previewPostTypes = inputConfig.previewPostTypes;
    } //inputConfig

    formData = {
        commentTextareaValue: commentTextareaValue,
        previewPostType: previewPostType,
    };

    if (routeTransactionCommentUpdateItemId) {
        try {
            const response = await fetch(routeTransactionCommentUpdateItemId, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(formData),
            });

            data = await response.json();

            errors = data.errors;
            errorMessage = "";

            if (response.ok) {
                console.log("保存成功", data);
            } else {
                console.error("サーバーエラー", data);
            }
        } catch (err) {
            console.error("保存失敗", err);
        }
    } //routeTransactionCommentUpdateItemId
}
