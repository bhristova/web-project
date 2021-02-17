import citation from './citations-new.js';
import table from './citations-table.js';
import {getFieldConfig} from './api.js';

const allowedTypesCitations = ['apa', 'mla', 'chicago', 'existingCitation'];

const citationCitationTypesMapping = {
    mla: ['Парафразиране', 'Цитат'],
    apa: ['Парафразиране', 'Цитат'],
    chicago: ['Парафразиране', 'Цитат'],
}

const tableColumnsMapping = {
    mla: ['Вид на източника', 'Цитирана работа', 'Цитат'],
    apa: ['Вид на източника', 'Цитирана работа', 'Цитат'],
    chicago: ['Вид на източника', 'Цитирана работа', 'Цитат', 'Бележка под линия'],
}

const newCitation = async (type, projectId, data) => {
    const annType = data && !! data.annotationType ? data.annotationType : type;
    const citationType = allowedTypesCitations.find(allowedType => allowedType.toLowerCase() === annType.toLowerCase());
    
    if(!citationType) {
        return;
    }

    let citationFields = [], result = [];
    try {
        result = await getFieldConfig(citationType);
        citationFields = JSON.parse(result[0].config);
    } catch (err) {
        debugger
    }

    const citationSourceTypes = data ? [data.sourceType] : result.map(elem => elem.SourceName).sort();
    const citationCitationsType = citationCitationTypesMapping[citationType];
    citation(citationSourceTypes, citationCitationsType, result, projectId, citationType, data);
}

const newTable = (type, projectId) => {
    const citationType = allowedTypesCitations.find(allowedType => allowedType === type.toLowerCase());
    
    if(!citationType) {
        return;
        //maybe error?
    }

    const tableColumns = tableColumnsMapping[citationType];
    table(tableColumns, projectId);
}

export {newCitation, newTable};