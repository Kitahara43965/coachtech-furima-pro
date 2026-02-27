export class PostedAlmostSafeFileSize {
    static getPostedAlmostSafeFileSize(
        commonConfigs,
        doubleFileSizeSafeRate = null,
    ) {
        let settingsPhpConfig = null;
        let phpConfig = null;
        let transactionPhpConfig = null;
        let transactionCommentPhpConfig = null;
        let transactionImagePhpConfig = null;
        let userImagePhpConfig = null;
        let autoSaveTimers = null;
        let newDoubleSafeRate = 0.0;
        let phpIniArgumentNames = null;
        let phpIniSettingSizesInBytes = null;
        let uploadMaxFilesizeInBytes = 0;
        let postMaxSizeInBytes = 0;
        let memoryLimitInBytes = 0;
        let postedAlmostSafeFileSize = 0;

        if (doubleFileSizeSafeRate) {
            newDoubleSafeRate = doubleFileSizeSafeRate;
        } else {
            newDoubleSafeRate = 1.0;
        }

        if (commonConfigs && typeof commonConfigs === "object") {
            settingsPhpConfig = commonConfigs.settingsPhpConfig;
            phpConfig = commonConfigs.phpConfig;
            transactionPhpConfig = commonConfigs.transactionPhpConfig;
            transactionCommentPhpConfig =
                commonConfigs.transactionCommentPhpConfig;
            transactionImagePhpConfig = commonConfigs.transactionImagePhpConfig;
            userImagePhpConfig = commonConfigs.userImagePhpConfig;
            autoSaveTimers = commonConfigs.autoSaveTimers;
        } //commonConfigs

        if (settingsPhpConfig && typeof settingsPhpConfig === "object") {
            phpIniArgumentNames = settingsPhpConfig.phpIniArgumentNames;
            phpIniSettingSizesInBytes =
                settingsPhpConfig.phpIniSettingSizesInBytes;
        }

        if (phpIniArgumentNames && typeof phpIniArgumentNames === "object") {
            if (
                phpIniSettingSizesInBytes &&
                typeof phpIniSettingSizesInBytes === "object"
            ) {
                uploadMaxFilesizeInBytes =
                    phpIniSettingSizesInBytes[
                        phpIniArgumentNames.UPLOAD_MAX_FILESIZE
                    ];
                postMaxSizeInBytes =
                    phpIniSettingSizesInBytes[
                        phpIniArgumentNames.POST_MAX_SIZE
                    ];
                memoryLimitInBytes =
                    phpIniSettingSizesInBytes[phpIniArgumentNames.MEMORY_LIMIT];
            } //phpIniSettingSizesInBytes
        } //phpIniArgumentNames

        postedAlmostSafeFileSize = uploadMaxFilesizeInBytes * newDoubleSafeRate;

        return postedAlmostSafeFileSize;
    } //getPostedAlmostSafeFileSize

    static getStringFileSize(fileSize) {
        let absFileSize = 0;
        let coefficientOfAbsFileSize = 0;
        let flooredCoefficientOfAbsFileSize = 0;
        let fileSizeUnit = "";
        let stringPlusOrMinusSign = "";
        let stringFileSize = null;

        if (Number.isInteger(fileSize)) {
            if (fileSize < 0) {
                absFileSize = -1 * fileSize;
                stringPlusOrMinusSign = "-";
            } else if (fileSize >= 0) {
                absFileSize = fileSize;
                stringPlusOrMinusSign = "";
            } //fileSize
        } else {
            absFileSize = 0;
            stringPlusOrMinusSign = "";
        }

        if (absFileSize >= 1024 * 1024 * 1024 * 1024) {
            coefficientOfAbsFileSize =
                absFileSize / (1024 * 1024 * 1024 * 1024);
            fileSizeUnit = "TB";
        } else if (absFileSize >= 1024 * 1024 * 1024) {
            coefficientOfAbsFileSize = absFileSize / (1024 * 1024 * 1024);
            fileSizeUnit = "GB";
        } else if (absFileSize >= 1024 * 1024) {
            coefficientOfAbsFileSize = absFileSize / (1024 * 1024);
            fileSizeUnit = "MB";
        } else if (absFileSize >= 1024) {
            coefficientOfAbsFileSize = absFileSize / 1024;
            fileSizeUnit = "KB";
        } else {
            coefficientOfAbsFileSize = absFileSize;
            fileSizeUnit = "byte";
        } //absFileSize

        flooredCoefficientOfAbsFileSize = Math.floor(coefficientOfAbsFileSize);

        stringFileSize =
            stringPlusOrMinusSign +
            flooredCoefficientOfAbsFileSize +
            fileSizeUnit;

        return stringFileSize;
    }

    static getStringPostedAlmostSafeFileSize(
        commonConfigs,
        doubleFileSizeSafeRate = null,
    ) {
        let postedAlmostSafeFileSize = 0;
        let stringPostedAlmostSafeFileSize = null;

        postedAlmostSafeFileSize = this.getPostedAlmostSafeFileSize(
            commonConfigs,
            doubleFileSizeSafeRate,
        );

        stringPostedAlmostSafeFileSize = this.getStringFileSize(
            postedAlmostSafeFileSize,
        );

        return stringPostedAlmostSafeFileSize;
    } //getStringPostedAlmostSafeFileSize
} //PostedAlmostSafeFileSize
