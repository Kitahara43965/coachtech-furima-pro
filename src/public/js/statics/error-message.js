export class ErrorMessage {
    static getTargetErrorMessagesFromErrorMessagesAndErrorMessageName(
        errorMessages,
        errorMessageName,
    ) {
        let targetErrorMessages = [];

        if (errorMessages) {
            for (const [attribute, dummyErrorMessages] of Object.entries(
                errorMessages,
            )) {
                if (attribute === errorMessageName) {
                    targetErrorMessages = dummyErrorMessages;
                } //attribute
            }
        } //errorMessages

        return targetErrorMessages;
    } //getTargetErrorMessagesFromErrorMessagesAndErrorMessageName

    static getMergedErrorMessageFromTargetErrorMessages(targetErrorMessages) {
        let maxTargetErrorMessageNumber = 0;
        let targetErrorMessageNumber = 0;
        let targetErrorMessage = null;
        let mergedErrorMessage = "";

        if (targetErrorMessages) {
            maxTargetErrorMessageNumber = targetErrorMessages.length;
        } //targetErrorMessages

        for (
            targetErrorMessageNumber = 1;
            targetErrorMessageNumber <= maxTargetErrorMessageNumber;
            targetErrorMessageNumber++
        ) {
            targetErrorMessage =
                targetErrorMessages[targetErrorMessageNumber - 1];
            if (targetErrorMessage) {
                if (targetErrorMessageNumber === 1) {
                    mergedErrorMessage = targetErrorMessage;
                } else {
                    mergedErrorMessage =
                        mergedErrorMessage +
                        String.fromCharCode(10) +
                        targetErrorMessage;
                } //targetErrorMessageNumber
            } //targetErrorMessage
        }

        return mergedErrorMessage;
    } //getMergedErrorMessageFromTargetErrorMessages

    static getMergedErrorMessageFromErrorMessagesAndErrorMessageName(
        errorMessages,
        errorMessageName,
    ) {
        const targetErrorMessages =
            this.getTargetErrorMessagesFromErrorMessagesAndErrorMessageName(
                errorMessages,
                errorMessageName,
            );
        const mergedErrorMessage =
            this.getMergedErrorMessageFromTargetErrorMessages(
                targetErrorMessages,
            );
        return mergedErrorMessage;
    }
} //ErrorMessage
