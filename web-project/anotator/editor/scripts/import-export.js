import createElement from './element.js';
import {importCitations} from './api.js';
import errorMessage from './error-message.js';
import successMessage from './success-message.js';
import {onExistingCitationClick} from './sidebar.js';

const beforeSubmitExportForm = (evt, type, id, formId) => {
    evt.preventDefault();
    const form = document.getElementById(formId);

    const projectId = document.createElement('input');
    projectId.setAttribute('name', 'projectId');
    projectId.setAttribute('value', id);
    projectId.setAttribute('type', 'hidden');

    const typeElem = document.createElement('input');
    typeElem.setAttribute('name', 'exportType');
    typeElem.setAttribute('value', type);
    typeElem.setAttribute('type', 'hidden');

    form.appendChild(projectId);
    form.appendChild(typeElem);

    form.submit();
};

const beforeSubmitImportForm = async (evt, id, annotationType) => {
    evt.preventDefault();

    const formData = new FormData();
    const fileField = document.querySelector('input[type="file"]');

    formData.append('uploadedFile', fileField.files[0]);
    formData.append('projectId', id);
    
    try {
        const response = await importCitations(formData);
        if(!response.success) {
            errorMessage('Error when trying to import citations');
            return;
        }

        successMessage('Успешно създадохте нов цитат!');
        onExistingCitationClick(annotationType);
    } catch (err) {
        errorMessage(err);
    }
};

const createExportButton = (target, type, projectId) => {
    const targetDiv = document.getElementById(`div-${target}`);
    
    const divData = {
        tagName: 'div',
        attributes: [
            {name: 'id', value: `div-${target}-div`},
            {name: 'class', value: 'importExportCitations'},
        ]
    };

    const downloadFileFormData = {
        tagName: 'form',
        attributes: [
            {name: 'id', value: `form-${target}`},
            {name: 'method', value: 'get'},
            {name: 'action', value: 'api/importExport.php'},
        ],
        eventListeners: [
            {event: 'submit', listener: (evt) => beforeSubmitExportForm(evt, type, projectId, `form-${target}`)}
        ]
    };

    const downloadFileButtonData = {
        tagName: 'input',
        attributes: [
            {name: 'type', value: 'submit'},
        ],
        properties: [
            {name: 'value', value: 'Свали файл'},
        ]
    };

    const projectIdData = {
        tagName: 'input',
        attributes: [
            {name: 'name', value: 'projectId'}
        ],
        properties: [
            {name: 'type', value: 'hidden'},
            {name: 'projectId', value: projectId}
        ]
    }

    const div = createElement(divData);
    const downloadFileForm = createElement(downloadFileFormData);
    const projectIdElem = createElement(projectIdData);
    const downloadFileButton = createElement(downloadFileButtonData);

    downloadFileForm.appendChild(projectIdElem);
    downloadFileForm.appendChild(downloadFileButton);

    div.appendChild(downloadFileForm);

    targetDiv.appendChild(div);
};

const createImportHelpElement = (projectId, annotationType) => {
    const importCitationsDiv = document.getElementById('div-importCitations');
    
    const divData = {
        tagName: 'div',
        attributes: [
            {name: 'id', value: 'div-importCitations-div'},
            {name: 'class', value: 'importExportCitations'},
        ],
    };

    const helperDivData = {
        tagName: 'div',
        properties: [
            {name: 'innerHTML', value: 'Моля качете файл във формат csv, който отговаря на следните условия: на всеки ред седи отделен запис. За всеки запис се изисква следната информация:\
            <br>1. вид на източника, който цитирате ( в момента се поддържат само източниците книга, линк и списание); \
            <br>2. цитирания източник (колона от таблицата с цитати с името "Цитирана работа");\
            <br>3. начин по който бележката за цитирания източник трябва да изглежда, когато се drag-and-drop-не в текста.\
            <br>4. цитат (ако има такъв).\
            Трите низа трябва да са в единични кавички. Пример: ако таблицата изглежда така:\
            <br><br><img src="./files/help-1.png"><br><br>\
            и бележката в текста изглежда така:\
            <br><br><img src="./files/help-2.png"><br><br>\
            файлът ви трябва да изглежда по този начин:\
            <br><br><img src="./files/help-3.png"><br>'},
        ],
    };

    const uploadFileFormData = {
        tagName: 'form',
        attributes: [
            {name: 'id', value: 'form-importCitations'},
            {name: 'method', value: 'post'},
            {name: 'enctype', value: 'multipart/form-data'},
            {name: 'action', value: 'api/importExport.php'},
        ],
        eventListeners: [
            {event: 'submit', listener: (evt) => beforeSubmitImportForm(evt, projectId, annotationType)}
        ]
    };

    const uploadFileInputData = {
        tagName: 'input',
        attributes: [
            {name: 'id', value: 'uploadedFile'},
            {name: 'name', value: 'uploadedFile'},
            {name: 'type', value: 'file'},
        ],
    };

    const uploadFileSubmitData = {
        tagName: 'input',
        attributes: [
            {name: 'type', value: 'submit'},
        ],
        properties: [
            {name: 'value', value: 'Качи файл'},
        ]
    };

    const div = createElement(divData);
    const helperDiv = createElement(helperDivData);
    const uploadFileForm = createElement(uploadFileFormData);
    const uploadFileInput = createElement(uploadFileInputData);
    const uploadFileSubmit = createElement(uploadFileSubmitData);

    uploadFileForm.appendChild(uploadFileInput);
    uploadFileForm.appendChild(uploadFileSubmit);

    div.appendChild(uploadFileForm);
    div.appendChild(helperDiv);

    importCitationsDiv.appendChild(div);
};

export {createImportHelpElement, createExportButton};