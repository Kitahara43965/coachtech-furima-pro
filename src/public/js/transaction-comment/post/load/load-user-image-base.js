import { imageUrlToBlob } from "/js/async-tools/image-url-to-file.js";
import { loadUserImageHandler } from "./load-user-image-handler.js";

export async function loadUserImageBase(
    commonConfigs,
    previewPostConfig,
    errorMessages,
    currentTransactionCommentDTO,
    currentTransactionCommentUserKind,
    currentTransactionCommentUserDTO,
) {
    const fileReaderUserImage = new FileReader();
    let blob = null;
    let imageUrl = null;

    if (currentTransactionCommentUserDTO) {
        imageUrl = currentTransactionCommentUserDTO.image_url;
    } //currentTransactionCommentUserDTO

    if (imageUrl) {
        return new Promise(async (resolve, reject) => {
            // onload イベント
            fileReaderUserImage.onload = async function (eventObjectPreview) {
                if (typeof loadUserImageHandler === "function") {
                    await loadUserImageHandler(
                        commonConfigs,
                        previewPostConfig,
                        errorMessages,
                        currentTransactionCommentDTO,
                        currentTransactionCommentUserKind,
                        currentTransactionCommentUserDTO,
                        eventObjectPreview,
                    );
                } //
                resolve();
            };

            fileReaderUserImage.onerror = function (error) {
                console.error("画像読み込みエラー:", error);
                reject(error);
            };

            if (typeof imageUrlToBlob === "function") {
                blob = await imageUrlToBlob(imageUrl);
                if (blob) {
                    fileReaderUserImage.readAsDataURL(blob); // Blob を読み込み
                }
            }
        });
    } //imageUrl
}
