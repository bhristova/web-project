import citation from './citations-new.js';
import table from './citations-table.js';

const allowedTypesCitations = ['apa', 'mla', 'chicago'];

const citationFieldsMapping = {
    mla: [
        {number: 2, name: 'authorFirstName', id: 'authorFirstName', label: 'Име на автор'},
        {number: 1, name: 'authorLastName', id: 'authorLastName', label: 'Фамилия на автор', required: true},
        {number: 3, name: 'source', id: 'source', label: 'Име на източник', required: true},
        {number: 4, name: 'containerTitle', id: 'containerTitle', label: 'Име на контейнер'},
        {number: 5, name: 'otherContributors', id: 'otherContributors', label: 'Други сътрудници'},
        {number: 6, name: 'version', id: 'version', label: 'Версия'},
        {number: 7, name: 'number', id: 'number', label: 'Номер'},
        {number: 9, name: 'publisher', id: 'publisher', label: 'Издател'},
        {number: 10, name: 'publicationDate', id: 'publicationDate', label: 'Дата на издаване', required: true},
        {number: 11, name: 'location', id: 'location', label: 'Местоположение'},
    ],
    apa: [
        {number: 2, name: 'authorFirstName', id: 'authorFirstName', label: 'Име на автор'},
        {number: 1, name: 'authorLastName', id: 'authorLastName', label: 'Фамилия на автор', required: true},
        {number: 3, name: 'source', id: 'source', label: 'Име на източник', required: true},
        {number: 6, name: 'version', id: 'version', label: 'Версия'},
        {number: 7, name: 'number', id: 'number', label: 'Номер'},
        {number: 8, name: 'page', id: 'page', label: 'Страници'},
        {number: 8, name: 'publisher', id: 'publisher', label: 'Издател'},
        {number: 9, name: 'publicationDate', id: 'publicationDate', label: 'Дата на издаване'},
    ],
    chicago: [
        {number: 2, name: 'authorFirstName', id: 'authorFirstName', label: 'Име на автор'},
        {number: 1, name: 'authorLastName', id: 'authorLastName', label: 'Фамилия на автор', required: true},
        {number: 3, name: 'source', id: 'source', label: 'Име на източник', required: true},
        {number: 4, name: 'containerTitle', id: 'containerTitle', label: 'Име на списание', required: true,  condition: 'списание'},
        {number: 5, name: 'titleOfWebsite', id: 'titleOfWebsite', label: 'Име на уебсайт', required: true, condition: 'линк'},
        {number: 6, name: 'version', id: 'version', label: 'Версия'}, //3rd ed
        {number: 7, name: 'number', id: 'number', label: 'Номер'}, //vol. 6, no. 2
        {number: 8, name: 'page', id: 'page', label: 'Страници', required: true},
        {number: 8, name: 'publisher', id: 'publisher', label: 'Издател'},
        {number: 9, name: 'publicationDate', id: 'publicationDate', label: 'Дата на издаване', required: true},
        {number: 10, name: 'dateOfAccess', id: 'dateOfAccess', label: 'Дата на достъп'},
        {number: 11, name: 'location', id: 'location', label: 'Местоположение'},
    ],
}

const citationSourceTypesMapping = {
    mla: ['Книга', 'Линк', 'Списание'],
    apa: ['Книга', 'Линк', 'Списание'],
    chicago: ['Книга', 'Линк', 'Списание'],
}

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

const newCitation = (type, projectId) => {
    const citationType = allowedTypesCitations.find(allowedType => allowedType === type.toLowerCase());

    if(!citationType) {
        return;
        //maybe error?
    }

    const citationFields = citationFieldsMapping[citationType];
    const citationSourceTypes = citationSourceTypesMapping[citationType];
    const citationCitationsType = citationCitationTypesMapping[citationType];
    citation(citationSourceTypes, citationCitationsType, citationFields, projectId, type);
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