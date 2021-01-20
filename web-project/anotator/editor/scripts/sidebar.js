import {newCitation, newTable} from './citations-routing.js';
import {createImportHelpElement, createExportButton} from './import-export.js';
import createElement from './element.js';

let projectId = '';

const createSidebar = (annotationType, id) => {
    projectId = id;
    showChooseCitationSource(annotationType);
};

const onExistingCitationClick = async (annotationType) => {
    const divExistingCitation = document.getElementById('div-existingCitation');

    if( divExistingCitation.style.height === '80%') {
        return;
    }

    removeNewCitationElements();
    removeImportCitationsElements();
    removeExportCitationsElements()

    divExistingCitation.style.height = '80%';

    await newTable(annotationType, projectId);
};

const onExportCitationsClick = () => {
    const divExportCitations = document.getElementById('div-exportCitations');

    if( divExportCitations.style.height === '80%') {
        return;
    }

    removeNewCitationElements();
    removeExistingCitationElements();
    removeImportCitationsElements();

    divExportCitations.style.height = '80%';

    createExportButton(projectId);
};

const onImportCitationsClick = (annotationType) => {
    const divImportCitations = document.getElementById('div-importCitations');

    if( divImportCitations.style.height === '80%') {
        return;
    }

    removeNewCitationElements();
    removeExistingCitationElements();
    removeExportCitationsElements();
    
    divImportCitations.style.height = '80%';

    createImportHelpElement(projectId, annotationType);
};

const onNewCitationClick = (annotationType) => {
    const divNewCitation = document.getElementById('div-newCitation');

    if( divNewCitation.style.height === '80%') {
        return;
    }
    
    removeExistingCitationElements();
    removeImportCitationsElements();
    removeExportCitationsElements()
    
    divNewCitation.style.height = '80%';

    newCitation(annotationType, projectId);
};

const removeElement = (source, elementId) => {
    const element = document.getElementById(elementId);
    if(element) {
        source.removeChild(element);
    }
};

const removeExistingCitationElements = () => {
    const divExistingCitation = document.getElementById('div-existingCitation');

    ['div-existingCitation-div'].forEach(id => {
        removeElement(divExistingCitation, id);
    });

    divExistingCitation.style.height = 'auto';
};

const removeExportCitationsElements = () => {
    const divExportCitations = document.getElementById('div-exportCitations');

    ['div-exportCitations-div'].forEach(id => {
        removeElement(divExportCitations, id);
    });

    divExportCitations.style.height = 'auto';
};

const removeImportCitationsElements = () => {
    const divImportCitations = document.getElementById('div-importCitations');

    ['div-importCitations-div'].forEach(id => {
        removeElement(divImportCitations, id);
    });

    divImportCitations.style.height = 'auto';
};

const removeNewCitationElements = () => {
    const divNewCitation = document.getElementById('div-newCitation');
    const divNewCitationForm = document.getElementById('div-newCitation-form');
    
    ['input-quote', 'label-input-quote'].forEach(id => {
        removeElement(divNewCitationForm, id);
    });

    ['div-newCitation-form', 'select-newCitation-typeSource', 'label-newCitation-typeSource', 'select-newCitation-typeCitation', 'label-newCitation-typeCitation'].forEach(id => {
        removeElement(divNewCitation, id);
    });

    divNewCitation.style.height = 'auto';
};

const showChooseCitationSource = (annotationType) => {
    const createButtonInDiv = (name, value, eventListener, className) => {
        const divData = {
            tagName: 'div',
            attributes: [
                {name: 'id', value: `div-${name}`},
                {name: 'class', value: className},
            ]
        };

        const buttonData = {
            tagName: 'button',
            attributes: [
                {name: 'id', value: `button-${name}`},
                {name: 'menamethod', value: `button-${name}`},
                {name: 'type', value: 'button'},
                {name: 'class', value: 'sidebarButton'},
            ],
            properties: [
                {name: 'innerHTML', value: value},
            ],
            eventListeners: [
                {event: 'click', listener: eventListener}
            ]
        };

        const div = createElement(divData);
        const button = createElement(buttonData);

        div.appendChild(button);

        return div;
    }

    const sidebar = document.getElementById('sidebar');

    const divNewCitation = createButtonInDiv('newCitation', 'Нов цитат', () => onNewCitationClick(annotationType));
    const divExistingCitation = createButtonInDiv('existingCitation', 'Избери от списък', async () => await onExistingCitationClick(annotationType));
    const divImportCitations = createButtonInDiv('importCitations', 'Импорт на цитати', () => onImportCitationsClick(annotationType), 'importCitations');
    const divExportCitations = createButtonInDiv('exportCitations', 'Експорт на цитати', () => onExportCitationsClick());

    sidebar.appendChild(divNewCitation);
    sidebar.appendChild(divExistingCitation);
    sidebar.appendChild(divImportCitations);
    sidebar.appendChild(divExportCitations);
};

export {createSidebar, onExistingCitationClick};