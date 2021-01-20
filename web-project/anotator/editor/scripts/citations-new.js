import createElement from './element.js';
import {createCitation} from './api.js';
import errorMessage from './error-message.js';
import successMessage from './success-message.js';
import {onExistingCitationClick} from './sidebar.js';

const appendToDiv = (divId, children) => {
    let childrenArray = children;
    if (!Array.isArray(childrenArray)) {
        childrenArray = [childrenArray];
    }

    const div = document.getElementById(divId);
    if(div) {
        childrenArray.forEach(child => {
            div.appendChild(child);
        });
    }
};

const onSubmit = async (evt, id, citationType) => {
    evt.preventDefault();
    const form = document.getElementById('div-newCitation-form');

    const annotationTypeData = {
        tagName: 'input',
        attributes: [
            {name: 'name', value: 'annotationType'},
            {name: 'value', value: citationType},
            {name: 'type', value: 'hidden'},
        ]
    };

    const sourceTypeData = {
        tagName: 'input',
        attributes: [
            {name: 'name', value: 'sourceType'},
            {name: 'value', value: getSourceType()},
            {name: 'type', value: 'hidden'},
        ]
    };

    const projectIdData = {
        tagName: 'input',
        attributes: [
            {name: 'name', value: 'projectId'},
            {name: 'value', value: id},
            {name: 'type', value: 'hidden'},
        ]
    };

    const annotationType = createElement(annotationTypeData);
    const sourceType = createElement(sourceTypeData);
    const projectId = createElement(projectIdData);

    form.appendChild(annotationType);
    form.appendChild(sourceType);
    form.appendChild(projectId);

    try {
        const response = await createCitation(new URLSearchParams(new FormData(evt.target)));
        if(!response.success) {
            errorMessage(response.message);
            return;
        }

        successMessage('Успешно създадохте нов цитат!');
        onExistingCitationClick(citationType);
    } catch (err) {
        errorMessage(err);
    }
};

const createChooseTypeOfCitationElement = (optionsTypeOfCitation) => {
    const divNewCitation = document.getElementById('div-newCitation');

    const selectData = {
        tagName: 'select',
        attributes: [
            {name: 'class', value: 'newCitation'},
            {name: 'id', value: 'select-newCitation-typeCitation'},
        ],
        options: optionsTypeOfCitation.map(elem => ({value: elem})),
        eventListeners: [
            {event: 'change', listener: (evt) => onTypeOfCitationChoosen(evt)}
        ]
    };

    const labelData = {
        tagName: 'label',
        attributes: [
            {name: 'id', value: 'label-newCitation-typeCitation'},
            {name: 'class', value: 'newCitation'},
        ],
        properties: [
            {name: 'htmlFor', value: 'select-newCitation-typeCitation'},
            {name: 'innerHTML', value:  'Вид цитиране'},
        ]
    };

    const select = createElement(selectData);
    const label = createElement(labelData);

    divNewCitation.appendChild(label);
    divNewCitation.appendChild(select);
};


const createChooseTypeOfSourceElement = (optionsTypeOfSource, fields) => {
    const divNewCitation = document.getElementById('div-newCitation');

    const selectEventListener = (evt) => {
        const value = evt.target.value.toLowerCase();
        const field = fieldsWithCondition.find(field => field.condition === value);

        if(field) {
            const fieldId = `input-${field.id}`;
            const labelId = `label-input-${field.id}`;

            const fieldElement = document.getElementById(fieldId);
            const labelElement = document.getElementById(labelId);

            fieldElement.type = '';
            labelElement.hidden = false;
        }

        const otherFields = fieldsWithCondition.filter(field => field.condition !== value);
        otherFields?.forEach(otherField => {
            const otherFieldId = `input-${otherField.id}`;
            const otherLabelId = `label-input-${otherField.id}`;

            const otherFieldElement = document.getElementById(otherFieldId);
            const otherLabelElement = document.getElementById(otherLabelId);

            otherFieldElement.type = 'hidden';
            otherLabelElement.hidden = true;
        });
    };

    const selectData = {
        tagName: 'select',
        attributes: [
            {name: 'class', value: 'newCitation'},
            {name: 'id', value: 'select-newCitation-typeSource'},
        ],
        options: optionsTypeOfSource.map(elem => ({value: elem})),
    };

    const fieldsWithCondition = fields.filter(field => field.condition);
    if(fieldsWithCondition.length) {
        selectData.eventListeners = [{event: 'change', listener: (evt) => selectEventListener(evt)}];
    }

    const labelData = {
        tagName: 'label',
        attributes: [
            {name: 'id', value: 'label-newCitation-typeSource'},
            {name: 'class', value: 'newCitation'},
        ],
        properties: [
            {name: 'htmlFor', value: 'select-newCitation-typeSource'},
            {name: 'innerHTML', value:  'Вид източник'},
        ]
    };

    const select = createElement(selectData);
    const label = createElement(labelData);

    divNewCitation.appendChild(label);
    divNewCitation.appendChild(select);
};

const getSourceType = () => {
    const sourceDiv = document.getElementById('select-newCitation-typeSource');
    return sourceDiv?.value;
};

const newCitation = (optionsTypeOfSource, optionsTypeOfCitation, fields, projectId, citationType) => {
    createChooseTypeOfSourceElement(optionsTypeOfSource, fields);
    createChooseTypeOfCitationElement(optionsTypeOfCitation);

    const divNewCitation = document.getElementById('div-newCitation');

    const divData = {
        tagName: 'form',
        attributes: [
            {name: 'id', value: 'div-newCitation-form'},
            {name: 'class', value: 'newCitation'},
            {name: 'method', value: 'post'},
            {name: 'action', value: 'baligo'},
        ],
        eventListeners: [
            {event: 'submit', listener: (args) => onSubmit(args, projectId, citationType)},
        ]
    };

    const div = createElement(divData);

    divNewCitation.appendChild(div);

    showInputFields(fields);
    showSaveButton();
};

const onTypeOfCitationChoosen = (evt) => {
    const divNewCitationForm = document.getElementById('div-newCitation-form');

    if (evt.target.value.toLowerCase() === 'цитат') {
        const quoteData = {
            tagName: 'input',
            attributes: [
                {name: 'id', value: 'input-quote'},
                {name: 'name', value: 'quote'},
                {name: 'required', value: true}
            ]
        };

        const labelData = {
            tagName: 'label',
            attributes: [
                {name: 'id', value: 'label-input-quote'},
            ],
            properties: [
                {name: 'htmlFor', value: 'input-quote'},
                {name: 'innerHTML', value:  'Цитат*'},
            ]
        };

        const quote = createElement(quoteData);
        const label = createElement(labelData);

        divNewCitationForm.insertBefore(quote, divNewCitationForm.firstChild);
        divNewCitationForm.insertBefore(label, divNewCitationForm.firstChild);
    } else {
        const quote = document.getElementById('input-quote');
        const label = document.getElementById('label-input-quote');

        if(quote) {
            divNewCitationForm.removeChild(label);
            divNewCitationForm.removeChild(quote);
        }
    }
};

const showInputFields = (fields) => {
    fields.forEach(field => {

        const inputData = {
            tagName: 'input',
            attributes: [
                {name: 'name', value: field.id},
                {name: 'id', value: `input-${field.id}`},
            ],
            eventListeners: [
                {event: 'change', listener: field.handler},
            ]
        };

        const labelData = {
            tagName: 'label',
            attributes: [
                {name: 'id', value: `label-input-${field.id}`},
            ],
            properties: [
                {name: 'htmlFor', value: `input-${field.id}`},
                {name: 'innerHTML', value:  `${field.label}${field.required ? '*' : ''}`},
            ]
        };

        if(field.required)
        {
            inputData.attributes.push({name: 'required', value: true});
        }

        if(field.condition) {
            inputData.attributes.push({name: 'type', value: 'hidden'});
            labelData.attributes.push({name: 'hidden', value: 'true'});
        }

        const input = createElement(inputData);
        const label = createElement(labelData);

        appendToDiv('div-newCitation-form', [ label, input]);
    });
};

const showSaveButton = () => {
    const form = document.getElementById('div-newCitation-form');

    const buttonData = {
        tagName: 'input',
        attributes: [
            {name: 'type', value: 'submit'},
            {name: 'id', value: 'button-saveCitation'},
            {name: 'class', value: 'sidebarButton'},
        ],
        properties: [
            {name: 'value', value:  'Запазване'},
        ],
    };

    const button = createElement(buttonData);

    form.appendChild(button);
};

export default newCitation;