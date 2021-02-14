import { deleteCitation, getCitationsByProjectId, getCitationById } from './api.js';
import { onNewCitationClick } from './sidebar.js';
import createElement from './element.js';
import errorMessage from './error-message.js';

let citationsArray = [];
let columnsLength = 0;

const addCitationToTable = (citation) => {
    const table = document.getElementById('div-existingCitation-table');

    const tr = document.createElement('tr');
    
    const td1 = createElement({
        tagName: 'td',
        properties: [
            {name: 'innerHTML', value: citation.sourceType}
        ]
    });

    const td2 = createElement({
        tagName: 'td',
        attributes: [
            {name: 'id', value: citation.id},
            {name: 'draggable', value: true}
        ],
        properties: [
            {name: 'innerHTML', value: citation.formattedCitation}
        ],
        eventListeners: [
            {event: 'dragstart', listener: (evt) => onDragStart(evt)},
            {event: 'dragend', listener: (evt) => onDragEnd(evt)}
        ]
    });

    const td3 = createElement({
        tagName: 'td',
        properties: [
            {name: 'innerHTML', value: citation.quote}
        ]
    });

    tr.appendChild(td1);
    tr.appendChild(td2);
    tr.appendChild(td3);

    if(columnsLength === 4) {
        const td4 = createElement({
            tagName: 'td',
            attributes: [
                {name: 'id', value: citation.id},
                {name: 'draggable', value: true}
            ],
            properties: [
                {name: 'innerHTML', value: 'бележка'}
            ],
            eventListeners: [
                {event: 'dragstart', listener: (evt) => onDragStartInTextNote(evt)},
                {event: 'dragend', listener: (evt) => onDragEnd(evt)}
            ]
        });

        tr.appendChild(td4);
    }

    const tdButtonData = {
        tagName: 'td'
    };

    const deleteButtonData = {
        tagName: 'button',
        attributes: [
            {name: 'id', value: `button-delete-${citation.id}`},
            {name: 'class', value: `deleteButton`},
        ],
        properties: [
            {name: 'innerHTML', value: 'Изтрий'}
        ],
        eventListeners: [
            {event: 'click', listener: (evt) => onDeleteClick(evt)}
        ]
    };

    const editButtonData = {
        tagName: 'button',
        attributes: [
            {name: 'id', value: `button-edit-${citation.id}`},
            {name: 'class', value: `editButton`},
        ],
        properties: [
            {name: 'innerHTML', value: 'Редактирай'}
        ],
        eventListeners: [
            {event: 'click', listener: (evt) => onEditClick(evt, citation.id)}
        ]
    };

    const tdButtons = createElement(tdButtonData);

    const buttonDelete = createElement(deleteButtonData);
    const buttonEdit = createElement(editButtonData);

    tdButtons.appendChild(buttonDelete);
    tdButtons.appendChild(buttonEdit);
    tr.appendChild(tdButtons);

    table.appendChild(tr);
};

const createCitationsTable = (tableColumns) => {

    const divExistingCitation = document.getElementById('div-existingCitation');

    const divData = {
        tagName: 'div',
        attributes: [
            {name: 'id', value: 'div-existingCitation-div'},
            {name: 'class', value: 'existingCitation'}
        ],
    };

    const tableData = {
        tagName: 'table',
        attributes: [
            {name: 'id', value: 'div-existingCitation-table'},
        ],
    };

    const div = createElement(divData);
    const table = createElement(tableData);

    const tr = document.createElement('tr');
    tableColumns.forEach(column => {
        const th1 = document.createElement('th');
        th1.appendChild(document.createTextNode(column));
        tr.appendChild(th1);
    });

    columnsLength = tableColumns.length;

    table.appendChild(tr);

    div.appendChild(table);

    divExistingCitation.appendChild(div);
};

const newTable = async (tableColumns, projectId, type) => {
    createCitationsTable(tableColumns);
    await renderCitationsByProject(projectId);
};

const onDeleteClick = async (evt) => {
    const sourceId = evt.target.id.split('-');
    if(!sourceId[2]) {
        return;
    }

    const citation = citationsArray.find(citation => citation.id === sourceId[2]);

    try {
        const response = await deleteCitation(sourceId[2]);

        const citationsTable = document.getElementById('div-existingCitation-table');

        var tableRows = citationsTable.getElementsByTagName('tr');
        var rowCount = tableRows.length;

        for (let i = rowCount - 1; i > 0; i--) {
            citationsTable.removeChild(tableRows[i]);
        }

        renderCitationsByProject(citation.projectId);
    } catch (err) {
        errorMessage(err);
    }
};

const onEditClick = async (evt, citationId) => {
    const citationData = await getCitationById(citationId);
    onNewCitationClick('editexistingcitation', citationData);
}

const onDragEnd = (evt) => {
    evt.currentTarget.style.backgroundColor = 'white';
}

const onDragStart = (evt) => {
    const sourceId = evt.target.id;

    const citation = citationsArray.find(citation => citation.id === sourceId);

    const inTextCitation = citation?.inTextCitation;
    const citationIndex = citation?.footnoteIndex;

    let result = inTextCitation;

    if(columnsLength === 4) {
        result = `(${citationIndex}) ${inTextCitation}`;
    }

    result = `<customCitationTag id='${sourceId}'>${result}</customCitationTag>`;

    evt.dataTransfer.setData('text/html', result);

    evt.currentTarget.style.backgroundColor = '#dec8b5';
};

const onDragStartInTextNote = (evt) => {
    const sourceId = evt.target.id;

    const citation = citationsArray.find(citation => citation.id === sourceId);

    const citationIndex = citation?.footnoteIndex;
    const quote = citation?.quote;
    
    let result = `(${citationIndex})`;

    if(quote) {
        result = `${result} "${quote}"`;
    }

    result = `<customCitationTag id='${sourceId}'>${result}</customCitationTag>`;

    evt.dataTransfer.setData('text/html', result);

    evt.currentTarget.style.backgroundColor = '#dec8b5';
};

const renderCitationsByProject = async (projectId) => {
    const response = await getCitationsByProjectId(projectId);
    citationsArray = response;
    let index = 0;

    citationsArray.forEach(citation => {
        citation.footnoteIndex = index++;
    });

    citationsArray.forEach(citation => {
        addCitationToTable(citation);
    });
};

export default newTable;