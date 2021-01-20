import {getCitations} from './api.js';
import createElement from './element.js';
import errorMessage from './error-message.js';

const barColors = [
    {color: '#CB997C', hover:'#EDDCD2', used: 0}, 
    {color: '#FF8B33', hover:'#FFF1E6', used: 0}, 
    {color: '#F2404C', hover:'#FDE2E4', used: 0}, 
    {color: '#ED5A90', hover:'#FAD2E1', used: 0}, 
    {color: '#7DB5B3', hover:'#C5DEDD', used: 0}, 
    {color: '#85ADA3', hover:'#DBE7E4', used: 0}, 
    {color: '#B0AB96', hover:'#F0EFEB', used: 0}, 
    {color: '#88ABBF', hover:'#D6E2E9', used: 0}, 
    {color: '#5B96C2', hover:'#BCD4E6', used: 0}, 
    {color: '#468FC3', hover:'#99C1DE', used: 0} 
];

const backToAllProjects = () => {
    window.location = 'homepage.html';
};
    
const addCitationsToStatistics = (citations) => {
    const container = document.getElementById('graph');
    const typesOfSources = ['Книга', 'Линк', 'Списание'];
    
    const maxHeight = 500;
    const singleRecordHeight = maxHeight / citations.length;

    typesOfSources.forEach(type => {
        const currentTypeCount = citations
            .filter(citation => citation.sourceType === type)
            .length;
        
        
        const color = generateColor();
        const currentTypeHeight = currentTypeCount * singleRecordHeight;
        const mainDiv = document.createElement('div');

        const divOnMouseOver = () => {
            const div = document.getElementById(`id-${type}`);
            div.style.backgroundColor = color.hover;
            div.innerHTML = `${(100 * (currentTypeCount / citations.length)).toFixed(2)}%`;
        };

        const divOnMouseOut = () => {
            const div = document.getElementById(`id-${type}`);
            div.style.backgroundColor = color.color;
            div.innerHTML = '';
        };

        const divData = {
            tagName: 'div',
            attributes: [
                {name: 'id', value: `id-${type}`},
                {name: 'class', value: `bar`},
            ],
            style: [
                {name: 'backgroundColor', value: color.color},
                {name: 'height', value: `${currentTypeHeight}px`},
            ],
            eventListeners: [
                {event: 'mouseover', listener: divOnMouseOver},
                {event: 'mouseout', listener: divOnMouseOut},
            ]
        };

        const labelData = {
            tagName: 'p',
            attributes: [
                {name: 'class', value: 'label'}
            ],
            properties: [
                {name: 'innerHTML', value: type}
            ]
        };

        const div = createElement(divData);
        const label = createElement(labelData);
        
        mainDiv.appendChild(div);
        mainDiv.appendChild(label);

        container.appendChild(mainDiv);

    });

};

const addCitationToTable = (citation) => {
    const table = document.getElementById('citations-table');
    const tr = document.createElement('tr');

    let quote = citation.quote;
    const quoteStart = quote.slice(0, 1);
    const quoteEnd = quote.slice(quote.length - 1);

    if(quoteStart != '"') {
        quote = `"${quote}`;
    }
    if(quoteEnd != '"') {
        quote = `${quote}"`;
    }

    const td1 = createElement({
        tagName: 'td',
        properties: [
            {name: 'innerHTML', value: quote || ''}
        ]
    });
    
    const td2 = createElement({
        tagName: 'td',
        properties: [
            {name: 'innerHTML', value: `${citation.authorFirstName || ''} ${citation.authorLastName || ''}`}
        ]
    });

    const td3 = createElement({
        tagName: 'td',
        properties: [
            {name: 'innerHTML', value: citation.source || ''}
        ]
    });

    tr.appendChild(td1);
    tr.appendChild(td2);
    tr.appendChild(td3);

    table.appendChild(tr);

};

const generateColor = () => {
    let colorIndex = Math.floor(Math.random() * barColors.length);
    while(barColors[colorIndex].used) {
        colorIndex = Math.floor(Math.random() * barColors.length);
    }

    barColors[colorIndex].used = 1;
    return barColors[colorIndex];
}

(async () => {
    const buttonNewForm = document.getElementById('button-backToProjects');
    buttonNewForm.addEventListener('click', () => backToAllProjects())

    try {
        const response = await getCitations();

        const citationsArray = response.filter(citation => citation.quote !== "");
            citationsArray.sort((citationA, citationB) => {
                if (citationA.quote.toLowerCase() > citationB.quote.toLowerCase()) {
                    return 1;
                } if (citationA.quote.toLowerCase() < citationB.quote.toLowerCase()) {
                    return -1;
                }
                return 0;
            });

            citationsArray.forEach(citation => {
                addCitationToTable(citation);
            });

            addCitationsToStatistics(citationsArray);
    } catch (err) {
        errorMessage(err);
    }
})();