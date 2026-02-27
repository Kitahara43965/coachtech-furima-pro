export class AttributeTool {
    static getAttributeFromAttributePrefixAndDBId(attributePrefix, dBId) {
        let attribute = null;
        if (dBId) {
            attribute = `${attributePrefix}${dBId}`;
        } //dBId
        return attribute;
    }

    static getDBIdFromAttributePrefixAndAttribute(attributePrefix, attribute) {
        let stringDBId = null;
        let candidateDBId = null;
        let dBId = null;
        if (attribute) {
            if (attributePrefix) {
                stringDBId = attribute.slice(attributePrefix.length);
            } else {
                stringDBId = attribute;
            }
            candidateDBId = parseInt(stringDBId, 10);
            if (isNaN(candidateDBId)) {
                dBId = null;
            } else {
                dBId = candidateDBId;
            }
        }
        return dBId;
    } //getDBIdFromAttributePrefixAndAttribute
}
//IdOperator
