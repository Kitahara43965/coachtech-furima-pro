import { FileTool } from "/js/statics/file-tool.js";

export class DistinctFiles {
    static getFileNumbers(files) {
        let fileNumber = 0;
        let dummyFileNumber = 0;
        let maxFileNumber = 0;
        let isFileArray = false;
        let loopTime = 0;
        let sameFileMarkers = [];
        let sameFileNumbers = [];
        let copiedFileNumbers = [];
        let file = null;
        let dummyFile = null;
        let sameFileNumber = 0;
        let copiedFileNumber = 0;
        let getFileNumbersConfig = null;

        if (FileTool && typeof FileTool.getIsFileArray === "function") {
            isFileArray = FileTool.getIsFileArray(files);
        }

        if (isFileArray === true) {
            maxFileNumber = files.length;
            if (maxFileNumber >= 1) {
                sameFileMarkers = new Array(maxFileNumber);
                sameFileNumbers = new Array(maxFileNumber);
                copiedFileNumbers = new Array(maxFileNumber);
            } //maxFileNumber
        } //isFileArray

        if (maxFileNumber >= 1) {
            for (
                dummyFileNumber = 1;
                dummyFileNumber <= maxFileNumber;
                dummyFileNumber++
            ) {
                sameFileMarkers[dummyFileNumber - 1] = 0;
                sameFileNumbers[dummyFileNumber - 1] = 0;
                copiedFileNumbers[dummyFileNumber - 1] = 0;
            }
            for (fileNumber = 1; fileNumber <= maxFileNumber; fileNumber++) {
                sameFileNumber = 0;
                file = files[fileNumber - 1];
                if (sameFileMarkers[fileNumber - 1] === 0) {
                    for (
                        dummyFileNumber = fileNumber;
                        dummyFileNumber <= maxFileNumber;
                        dummyFileNumber++
                    ) {
                        dummyFile = files[dummyFileNumber - 1];

                        if (file.name === dummyFile.name) {
                            sameFileNumber = sameFileNumber + 1;
                            sameFileMarkers[dummyFileNumber - 1] = 1;
                            sameFileNumbers[dummyFileNumber - 1] =
                                sameFileNumber;
                            copiedFileNumbers[dummyFileNumber - 1] = fileNumber;
                        }
                    } //dummyFileNumber
                } //sameFileMarkers[fileNumber - 1]&0
            } //fileNumber
        } //maxFileNumber&1

        getFileNumbersConfig = {
            maxFileNumber: maxFileNumber,
            sameFileNumbers: sameFileNumbers,
            copiedFileNumbers: copiedFileNumbers,
        };
        return getFileNumbersConfig;
    } //getCandidateDistinctFiles

    static setCandidateDistinctFileNames(files, getFileNumbersConfig) {
        let fileNumber = 0;
        let maxFileNumber = 0;
        let sameFileNumbers = null;
        let copiedFileNumbers = null;
        let sameFileNumber = 0;
        let copiedFileNumber = 0;
        let file = null;
        let stringFileName = null;
        let stringFileExtension = null;
        let stringFileBaseName = null;
        let candidateDistinctFiles = [];
        let candidateDistinctFile = null;
        let stringCandidateDistinctFileName = null;
        let fileNameChangeDenialMarker = 0;
        let fileNameChangeTimeNumber = 0;
        let setCandidateDistinctFileNamesConfig = null;

        if (getFileNumbersConfig) {
            maxFileNumber = getFileNumbersConfig.maxFileNumber;
            sameFileNumbers = getFileNumbersConfig.sameFileNumbers;
            copiedFileNumbers = getFileNumbersConfig.copiedFileNumbers;
        } //getFileNumbersConfig

        if (maxFileNumber >= 1) {
            candidateDistinctFiles = new Array(maxFileNumber);
        } //maxFileNumber

        for (fileNumber = 1; fileNumber <= maxFileNumber; fileNumber++) {
            file = files[fileNumber - 1];
            sameFileNumber = sameFileNumbers[fileNumber - 1];
            copiedFileNumber = copiedFileNumbers[fileNumber - 1];
            stringFileName = file.name;
            stringFileExtension = stringFileName.split(".").pop();
            stringFileBaseName = stringFileName.substring(
                0,
                stringFileName.lastIndexOf("."),
            );

            fileNameChangeDenialMarker = 0;
            if (Number.isInteger(sameFileNumber)) {
                if (sameFileNumber == 1) {
                    fileNameChangeDenialMarker = 1;
                } else if (sameFileNumber >= 2) {
                    fileNameChangeDenialMarker = 0;
                } //sameFileNumber
            } else {
                fileNameChangeDenialMarker = 2;
            } //sameFileNumber instanceof

            if (fileNameChangeDenialMarker === 0) {
                fileNameChangeTimeNumber = fileNameChangeTimeNumber + 1;
                stringCandidateDistinctFileName = `${stringFileBaseName}(${sameFileNumber}).${stringFileExtension}`;
            } else {
                stringCandidateDistinctFileName = `${stringFileBaseName}.${stringFileExtension}`;
            } //fileNameChangeDenialMarker

            candidateDistinctFile = new File(
                [file],
                stringCandidateDistinctFileName,
                {
                    type: file.type,
                },
            );

            candidateDistinctFiles[fileNumber - 1] = candidateDistinctFile;
        } //fileNumber

        setCandidateDistinctFileNamesConfig = {
            candidateDistinctFiles: candidateDistinctFiles,
            fileNameChangeTimeNumber: fileNameChangeTimeNumber,
        };

        return setCandidateDistinctFileNamesConfig;
    } //setCandidateDistinctFileNames

    static getDistinctFiles(files) {
        let getFileNumbersConfig = null;
        let setCandidateDistinctFileNamesConfig = null;
        let candidateDistinctFiles = [];
        let fileNameChangeTimeNumber = 0;

        if (FileTool && typeof FileTool.getIsFileArray === "function") {
            candidateDistinctFiles = files.slice();
        }

        do {
            if (
                DistinctFiles &&
                typeof DistinctFiles.getFileNumbers === "function"
            ) {
                getFileNumbersConfig = DistinctFiles.getFileNumbers(
                    candidateDistinctFiles,
                );
            }

            if (
                DistinctFiles &&
                typeof DistinctFiles.setCandidateDistinctFileNames ===
                    "function"
            ) {
                setCandidateDistinctFileNamesConfig =
                    DistinctFiles.setCandidateDistinctFileNames(
                        candidateDistinctFiles,
                        getFileNumbersConfig,
                    );
            }

            if (candidateDistinctFiles) {
                candidateDistinctFiles = [];
            } //candidateDistinctFiles

            if (setCandidateDistinctFileNamesConfig) {
                candidateDistinctFiles =
                    setCandidateDistinctFileNamesConfig.candidateDistinctFiles;
                fileNameChangeTimeNumber =
                    setCandidateDistinctFileNamesConfig.fileNameChangeTimeNumber;
            } //setCandidateDistinctFileNamesConfig
        } while (fileNameChangeTimeNumber >= 1);

        return candidateDistinctFiles;
    }
} //CandidateDistinctFiles
