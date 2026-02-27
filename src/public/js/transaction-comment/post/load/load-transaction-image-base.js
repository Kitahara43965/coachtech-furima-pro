import { imageUrlToBlob } from "/js/async-tools/image-url-to-file.js";
import { loadTransactionImageHandler } from "./load-transaction-image-handler.js";

export async function loadTransactionImageBase(
    commonConfigs,
    previewPostConfig,
    errorMessages,
    currentTransactionCommentDTO,
    currentTransactionCommentUserKind,
    transactionImageDTO,
) {
    const fileReaderTransactionImage = new FileReader();
    let blob = null;
    let imageUrl = null;

    imageUrl = null;
    if (transactionImageDTO) {
        imageUrl = transactionImageDTO.image_url;
    }

    if (imageUrl) {
        return new Promise(async (resolve, reject) => {
            // onload イベント
            fileReaderTransactionImage.onload = async function (
                eventObjectPreview,
            ) {
                if (typeof loadTransactionImageHandler === "function") {
                    await loadTransactionImageHandler(
                        commonConfigs,
                        previewPostConfig,
                        errorMessages,
                        currentTransactionCommentDTO,
                        currentTransactionCommentUserKind,
                        transactionImageDTO,
                        eventObjectPreview,
                    );
                } //
                resolve();
            };

            fileReaderTransactionImage.onerror = function (error) {
                console.error("画像読み込みエラー:", error);
                reject(error);
            };

            if (typeof imageUrlToBlob === "function") {
                blob = await imageUrlToBlob(imageUrl);
                if (blob) {
                    fileReaderTransactionImage.readAsDataURL(blob);
                }
            }
        });
    }
}
