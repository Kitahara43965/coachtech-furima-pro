export class FileTool {
    static getIsFileArray(files) {
        let fileNumber = 0;
        let isNotFileArray = false;
        let isFileArray = false;

        if (Array.isArray(files)) {
            for (fileNumber = 1; fileNumber <= files.length; fileNumber++) {
                if (!(files[fileNumber - 1] instanceof File)) {
                    isNotFileArray = true;
                }
            }
        } else {
            isNotFileArray = true;
        } //Array.isArray(files)

        isFileArray = !isNotFileArray;

        return isFileArray;
    } //getIsFileArray
} //FileTool
