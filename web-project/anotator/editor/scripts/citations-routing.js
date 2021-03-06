import citation from './citations-new.js';
import table from './citations-table.js';
import {getFieldConfig} from './api.js';
import errorMessage from './error-message.js';

const allowedTypesCitations = ['apa', 'mla', 'chicago', 'existingCitation'];

const citationCitationTypesMapping = {
    mla: ['Парафразиране', 'Цитат'],
    apa: ['Парафразиране', 'Цитат'],
    chicago: ['Парафразиране', 'Цитат'],
}

const tableColumnsMapping = ['Вид на източника', 'Цитирана работа', 'Цитат', 'Формат в библиография'];

const newCitation = async (type, projectId, citationSource, data) => {
    const annType = data && !! data.annotationType ? data.annotationType : type;
    const citationType = allowedTypesCitations.find(allowedType => allowedType.toLowerCase() === annType.toLowerCase());
    
    if(!citationType) {
        return;
    }

    let citationFields = [], result = [];
    try {
        result = await getFieldConfig(citationType, citationSource);
        if(!result || result.length === 0) {
            errorMessage();
            return;
        }
        citationFields = JSON.parse(result[0].config);
    } catch (err) {
        errorMessage(err);
        return;
    }

    const citationSourceTypes = data ? [data.sourceType] : result.map(elem => elem.SourceName).sort();
    const citationCitationsType = citationCitationTypesMapping[citationType];
    citation(citationSourceTypes, citationCitationsType, result, projectId, citationType, data);
}

const newTable = (type, projectId) => {
    const citationType = allowedTypesCitations.find(allowedType => allowedType.toLowerCase() === type.toLowerCase());
    
    if(!citationType) {
        errorMessage("Ivalid annotation type!");
        return;
    }

    table(tableColumnsMapping, projectId);
}

export {newCitation, newTable};