export async function imageUrlToBlob(imageUrl) {
    let response = null;
    let blob = null;

    if (imageUrl) {
        response = await fetch(imageUrl);
        blob = await response.blob();
    } //imageUrl

    return blob;
} //imageUrlToFile
