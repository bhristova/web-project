import createElement from './element.js';
import {createFieldConfig} from './api.js';
import errorMessage from './error-message.js';
import successMessage from './success-message.js';

const dbFields = ['authorFirstName', 'authorLastName', 'source', 'id', 'containerTitle', 'otherContributors', 'version', 'number', 'publisher', 'publicationDate', 'location'];

const newSourceType = () => {

    let div = document.getElementById('newSourceType');
    if(div) {
        return;
    }
    const annotationValues = [
        {name: 'APA', id: '89ed9c72-6614-4f8e-8cc2-838f84882421'}, 
        {name: 'MLA', id: 'cc618339-0496-4d52-8f51-0e8b7c912afc'}, 
        {name: 'Chicago', id: '5e6d3b92-0260-4b41-bfde-dc7687338f8c'}, 
    ];

    const divData = {
        tagName: 'form',
        attributes: [
            {name: 'id', value: `newSourceType`},
            {name: 'class', value: `newForm`},
        ]
    };
    div = createElement(divData);

    const inputSourceTypeData = {
        tagName: 'input',
        attributes: [
            {name: 'name', value: `sourceType`},
            {name: 'id', value: `input-sourceType`},
            {name: 'placeholder', value: `Име на източник`},
            {name: 'class', value: `field`},
            {name: 'required', value: true},
        ]
    };

    const labelSourceTypeData = {
        tagName: 'label',
        attributes: [
            {name: 'class', value: `field`},
        ],
        properties: [
            {name: 'innerHTML', value: `Име на източник*:`},
            {name: 'htmlFor', value: `input-sourceType`},
        ]
    };

    const inputSourceType = createElement(inputSourceTypeData);
    const labelSourceType = createElement(labelSourceTypeData);

    div.appendChild(labelSourceType);
    div.appendChild(inputSourceType);

    const checkboxesParentDivData = {
        tagName: 'div',
        attributes: [
            {name: 'class', value: 'field'},
        ]
    }
    const labelCheckboxesParentDivData = {
        tagName: 'label',
        attributes: [
            {name: 'class', value: 'field'},
        ],
        properties: [
            {name: 'innerHTML', value: 'За тип анотация*: '},
        ]
    }
    const labelCheckboxesParentDiv = createElement(labelCheckboxesParentDivData);
    const checkboxesParentDiv = createElement(checkboxesParentDivData);

    annotationValues.map(elem => {
        
        const inputAnotatonTypeData = {
            tagName: 'input',
            attributes: [
                {name: 'type', value: 'checkbox'},
                {name: 'id', value: `input-anotationType-${elem.name}`},
                {name: 'name', value: `anotationType${elem.name}`},
                {name: 'value', value: elem.id},
            ]
        };

        const labelAnotatonTypeData = {
            tagName: 'label',
            attributes: [
                {name: 'for', value: `input-anotationType-${elem.name}`},
            ],
            properties: [
                {name: 'innerHTML', value: elem.name},
            ]
        };

        const inputAnotatonType = createElement(inputAnotatonTypeData);
        const labelAnotatonType = createElement(labelAnotatonTypeData);

        checkboxesParentDiv.appendChild(inputAnotatonType);
        checkboxesParentDiv.appendChild(labelAnotatonType);
    });

    div.appendChild(labelCheckboxesParentDiv);
    div.appendChild(checkboxesParentDiv);

    const labelMappingContainerData = {
        tagName: 'label',
        attributes: [
            {name: 'class', value: `field`},
        ],
        properties: [
            {name: 'innerHTML', value: 'Разпределение на полетата:'},
        ]
    };

    const labelMappingContainer = createElement(labelMappingContainerData);
    const mappingContainer = createMappingContainer();

    div.appendChild(labelMappingContainer);
    div.appendChild(mappingContainer);

    const dragAndDropContainer = createDragAndDropContainer();
    div.appendChild(dragAndDropContainer);

    const dragAndDropTextareas = createDragAndDropTextareas();
    div.appendChild(dragAndDropTextareas);

    const buttonData = {
        tagName: 'input',
        attributes: [
            {name: 'type', value: 'submit'},
            {name: 'class', value: `field`},
        ],
        properties: [
            {name: 'value', value: 'Създайs'},
        ],
    };
    const button = createElement(buttonData);

    div.appendChild(button);

    div.addEventListener('submit', evt => beforeSubmit(evt));

    projectDataContainer.appendChild(div);
};

const beforeSubmit = async (evt) => {
    evt.preventDefault();

    try {
        const response = await createFieldConfig(new URLSearchParams(new FormData(evt.target)));
        if(!response.success) {
            errorMessage(response.message);
        }
        successMessage('Успешно създадохте нов темплейт за цитиране!');

    } catch (err) {
        errorMessage(err);
    }
}

const createMappingContainer = () => {
    const divMappingContainerData = {
        tagName: 'div',
        style: [
            {name: 'backgroundColor', value: 'rgb(233, 233, 233)'},
            {name: 'overflow-y', value: 'auto'},
            {name: 'height', value: '100px'},
        ],
        attributes: [
            {name: 'id', value: 'div-fieldsMappingContainer'},
            {name: 'class', value: 'field'},
        ],
    };
    const mappingContainer = createElement(divMappingContainerData);

    const divHeadersData = {
        tagName: 'div',
        style: [
            {name: 'display', value: 'flex'},
            {name: 'justify-content', value: 'space-between'},
            {name: 'font-style', value: 'italic'},
        ],
    };
    const divHeader = createElement(divHeadersData);
    
    ['Поле в базата', 'Име на полето', 'Задължително'].map(elem => {
        const labelHeadersData = {
            tagName: 'label',
            properties: [
                {name: 'innerHTML', value: elem},
            ]
        };
    
        const labelHeaders = createElement(labelHeadersData);
        divHeader.appendChild(labelHeaders);
    });    
    mappingContainer.appendChild(divHeader);

    dbFields.map(elem => {
        const fieldDivData = {
            tagName: 'div',
            attributes: [
                {name: 'class', value: 'mappingDiv'}
            ]
        };

        const inputFieldData = {
            tagName: 'input',
            attributes: [
                {name: 'id', value: `input-fieldsMapping-${elem}`},
                {name: 'name', value: `${elem}`},
            ]
        };

        const labelFieldData = {
            tagName: 'label',
            attributes: [
                {name: 'for', value: `input-fieldsMapping-${elem}`},
            ],
            properties: [
                {name: 'innerHTML', value: elem},
            ]
        };

        const checkboxFieldData = {
            tagName: 'input',
            attributes: [
                {name: 'type', value: 'checkbox'},
                {name: 'name', value: `checkbox${elem}`},
            ]
        };

        const fieldDiv = createElement(fieldDivData);

        const inputField = createElement(inputFieldData);
        const labelField = createElement(labelFieldData);
        const checkboxField = createElement(checkboxFieldData);

        fieldDiv.appendChild(labelField);
        fieldDiv.appendChild(inputField);
        fieldDiv.appendChild(checkboxField);

        mappingContainer.appendChild(fieldDiv);
    });

    return mappingContainer;
}

const createDragAndDropContainer = () => {
    const onDragStart = (evt, text) => evt.dataTransfer.setData('text/plain', `{${text}}`);
    
    const divDragAndDropData = {
        tagName: 'div',
        attributes: [
            {name: 'class', value: `field`},
        ],
        style: [
            {name: 'height', value: '80px'},
            {name: 'backgroundColor', value: 'rgb(233, 233, 233)'},
        ],
    }
    const divDragAndDrop = createElement(divDragAndDropData);

    [...dbFields, 'quote'].forEach(elem => {
        const pField = {
            tagName: 'a',
            properties: [
                {name: 'innerHTML', value: elem},
                {name: 'draggable', value: true}
            ],
            attributes: [
                {name: 'class', value: `dragAndDropElement`},
            ],
            eventListeners: [
                {event: 'dragstart', listener: (evt) => onDragStart(evt, elem)},
            ]
        };

        const field = createElement(pField);
        divDragAndDrop.appendChild(field);
    });

    return divDragAndDrop;
};

const createDragAndDropTextareas = () => {
    const dragAndDropTextAreasData = {
        tagName: 'div'
    };

    const labelInTextCitationData = {
        tagName: 'label',
        attributes: [
            {name: 'class', value: `field`},
        ],
        properties: [
            {name: 'innerHTML', value: 'InTextCitation'},
        ]
    };

    const textareaInTextCitationData = {
        tagName: 'textarea',
        attributes: [
            {name: 'name', value: 'inTextCitation'}
        ],
        style: [
            {name: 'width', value: '100%'},
        ],
    };

    const labelBibliographyCitationData = {
        tagName: 'label',
        attributes: [
            {name: 'class', value: `field`},
        ],
        properties: [
            {name: 'innerHTML', value: 'BibliographyCitation'},
        ]
    };

    const textareaBibliographyCitationData = {
        tagName: 'textarea',
        attributes: [
            {name: 'name', value: 'bibliographyCitation'}
        ],
        style: [
            {name: 'width', value: '100%'},
        ],
    };

    const dragAndDropTextAreas = createElement(dragAndDropTextAreasData);

    const labelInTextCitation = createElement(labelInTextCitationData);
    const textareaInTextCitation = createElement(textareaInTextCitationData);
    
    const labelBibliographyCitation = createElement(labelBibliographyCitationData);
    const textareaBibliographyCitation = createElement(textareaBibliographyCitationData);

    dragAndDropTextAreas.appendChild(labelInTextCitation);
    dragAndDropTextAreas.appendChild(textareaInTextCitation);
    
    dragAndDropTextAreas.appendChild(labelBibliographyCitation);
    dragAndDropTextAreas.appendChild(textareaBibliographyCitation);

    return dragAndDropTextAreas;
}

export default newSourceType;