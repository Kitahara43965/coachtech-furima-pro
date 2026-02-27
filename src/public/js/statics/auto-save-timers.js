export class AutoSaveTimers {
    static getInitialValues(previewPostTypes) {
        let autoSaveTimers = {};
        let key = null;
        let value = null;

        if (previewPostTypes && typeof previewPostTypes === "object") {
            for (key in previewPostTypes) {
                if (previewPostTypes.hasOwnProperty(key)) {
                    value = previewPostTypes[key];
                    autoSaveTimers[value] = null;
                }
            }
        }

        return autoSaveTimers;
    } //getInitialValues
} //AutoSaveTimer
