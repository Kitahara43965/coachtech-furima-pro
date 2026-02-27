import { AttributeTool } from "/js/statics/attribute-tool.js";

export class DeleteCells {
    static deleteCells(commonConfigs, previewPostConfig) {
        const undefinedIdKind = 0;
        const transactionCommentIdKind = 1;
        const transactionImageIdKind = 2;
        const maxIdKind = 2;
        let settingsPhpConfig = null;
        let phpConfig = null;
        let transactionPhpConfig = null;
        let transactionCommentPhpConfig = null;
        let transactionImagePhpConfig = null;
        let userImagePhpConfig = null;
        let autoSaveTimers = null;
        let transactionCommentCellIdPrefix = null;
        let transactionImageCellIdPrefix = null;
        let previewPostType = null;
        let targetTransactionCommentId = null;
        let newCommentTextareaInputTargetTransactionCommentComment = null;
        let deletedTransactionCommentIds = [];
        let deletedTransactionImageIds = [];
        let addedTransactionImageFiles = [];
        let idKind = undefinedIdKind;
        let deletedId = null;
        let deletedCellIdPrefix = null;
        let deletedCellId = null;
        let deletedCell = null;
        let maxDeletedIdNumber = 0;
        let deletedIdNumber = 0;

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

        if (
            transactionCommentPhpConfig &&
            typeof transactionCommentPhpConfig === "object"
        ) {
            transactionCommentCellIdPrefix =
                transactionCommentPhpConfig.transactionCommentCellIdPrefix;
        } //transactionCommentPhpConfig

        if (
            transactionImagePhpConfig &&
            typeof transactionImagePhpConfig === "object"
        ) {
            transactionImageCellIdPrefix =
                transactionImagePhpConfig.transactionImageCellIdPrefix;
        }

        if (previewPostConfig && typeof previewPostConfig === "object") {
            previewPostType = previewPostConfig.previewPostType;
            targetTransactionCommentId =
                previewPostConfig.targetTransactionCommentId;
            newCommentTextareaInputTargetTransactionCommentComment =
                previewPostConfig.newCommentTextareaInputTargetTransactionCommentComment;
            deletedTransactionCommentIds =
                previewPostConfig.deletedTransactionCommentIds;
            deletedTransactionImageIds =
                previewPostConfig.deletedTransactionImageIds;
            addedTransactionImageFiles =
                previewPostConfig.addedTransactionImageFiles;
        } //previewPostConfig

        for (idKind = 1; idKind <= maxIdKind; idKind++) {
            deletedCellIdPrefix = null;
            maxDeletedIdNumber = 0;
            if (idKind === transactionCommentIdKind) {
                deletedCellIdPrefix = transactionCommentCellIdPrefix;
                if (deletedCellIdPrefix && deletedTransactionCommentIds) {
                    maxDeletedIdNumber = deletedTransactionCommentIds.length;
                } //deletedTransactionCommentIds
            } else if (idKind === transactionImageIdKind) {
                deletedCellIdPrefix = transactionImageCellIdPrefix;
                if (deletedCellIdPrefix && deletedTransactionImageIds) {
                    maxDeletedIdNumber = deletedTransactionImageIds.length;
                } //deletedTransactionImageIds
            } //idKind
            for (
                deletedIdNumber = 1;
                deletedIdNumber <= maxDeletedIdNumber;
                deletedIdNumber++
            ) {
                deletedId = null;
                if (idKind === transactionCommentIdKind) {
                    deletedId =
                        deletedTransactionCommentIds[deletedIdNumber - 1];
                } else if (idKind === transactionImageIdKind) {
                    deletedId = deletedTransactionImageIds[deletedIdNumber - 1];
                } //idKind

                deletedCellId =
                    AttributeTool.getAttributeFromAttributePrefixAndDBId(
                        deletedCellIdPrefix,
                        deletedId,
                    );

                if (deletedCellId) {
                    deletedCell = document.getElementById(deletedCellId);
                } //deletedCellId

                if (deletedCell) {
                    deletedCell.remove();
                } //deletedCell
            } //deletedIdNumber
        } //idKind
    } //deleteCells
}
