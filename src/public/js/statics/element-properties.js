import { AttributeTool } from "/js/statics/attribute-tool.js";

export class ElementProperties {
    static getElementConfigByElementNamePrefix(elementNamePrefix) {
        let elements = [];
        let elementNumber = 0;
        let maxElementNumber = 0;
        let element = null;
        let elementName = null;
        let elementValue = null;
        let elementDBId = null;
        let elementValues = [];
        let elementNames = [];
        let elementDBIds = [];
        let elementConfig = null;

        if (elementNamePrefix) {
            elements = document.querySelectorAll(
                `textarea[name^="${elementNamePrefix}"]`,
            );
        } //elementNamePrefix

        if (elements) {
            maxElementNumber = elements.length;
        } //elements

        if (maxElementNumber >= 1) {
            elementValues = new Array(maxElementNumber);
            elementNames = new Array(maxElementNumber);
            elementDBIds = new Array(maxElementNumber);
        } //maxElementNumber&1

        for (
            elementNumber = 1;
            elementNumber <= maxElementNumber;
            elementNumber++
        ) {
            element = elements[elementNumber - 1];
            elementName = element.getAttribute("name");
            elementValue = element.value;
            elementDBId = null;
            if (
                AttributeTool &&
                typeof AttributeTool.getDBIdFromAttributePrefixAndAttribute ===
                    "function"
            ) {
                elementDBId =
                    AttributeTool.getDBIdFromAttributePrefixAndAttribute(
                        elementNamePrefix,
                        elementName,
                    );
            }

            elementNames[elementNumber - 1] = elementName;
            elementValues[elementNumber - 1] = elementValue;
            elementDBIds[elementNumber - 1] = elementDBId;
        }

        elementConfig = {
            elementNames: elementNames,
            elementValues: elementValues,
            elementDBIds: elementDBIds,
        };

        return elementConfig;
    }

    static getElementNamesByElementNamePrefix(elementNamePrefix) {
        const elementConfig =
            this.getElementConfigByElementNamePrefix(elementNamePrefix);
        const elementNames = elementConfig ? elementConfig.elementNames : [];
        return elementNames;
    } //getElementConfigByElementNamePrefix

    static getElementValuesByElementNamePrefix(elementNamePrefix) {
        const elementConfig =
            this.getElementConfigByElementNamePrefix(elementNamePrefix);
        const elementValues = elementConfig ? elementConfig.elementValues : [];
        return elementValues;
    } //getElementConfigByElementNamePrefix

    static getElementDBIdsByElementNamePrefix(elementNamePrefix) {
        const elementConfig =
            this.getElementConfigByElementNamePrefix(elementNamePrefix);
        const elementDBIds = elementConfig ? elementConfig.elementDBIds : [];
        return elementDBIds;
    } //getElementConfigByElementNamePrefix
}
