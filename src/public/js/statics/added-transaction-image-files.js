import { DistinctFiles } from "/js/statics/distinct-files.js";

export class AddedTransactionImageFiles {
    static getRedundantAddedTransactionImageFiles(eventObjectPreview) {
        let redundantAddedTransactionImageFiles = [];

        if (eventObjectPreview) {
            redundantAddedTransactionImageFiles = Array.from(
                eventObjectPreview.target.files,
            );
        }

        return redundantAddedTransactionImageFiles;
    }

    static getAddedTransactionImageFiles(eventObjectPreview) {
        let redundantAddedTransactionImageFiles = [];
        let addedTransactionImageFiles = [];

        if (
            AddedTransactionImageFiles &&
            typeof AddedTransactionImageFiles.getRedundantAddedTransactionImageFiles ===
                "function"
        ) {
            redundantAddedTransactionImageFiles =
                AddedTransactionImageFiles.getRedundantAddedTransactionImageFiles(
                    eventObjectPreview,
                );
        }

        if (
            DistinctFiles &&
            typeof DistinctFiles.getDistinctFiles === "function"
        ) {
            addedTransactionImageFiles = DistinctFiles.getDistinctFiles(
                redundantAddedTransactionImageFiles,
            );
        } //DistinctFiles

        return addedTransactionImageFiles;
    }
}
