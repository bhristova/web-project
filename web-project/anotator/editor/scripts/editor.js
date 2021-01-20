import { updateProject, getProjectById } from './api.js';
import {createSidebar} from './sidebar.js';
import createElement from './element.js';
import errorMessage from './error-message.js';

let projectData = [];

const backToAllProjects = () => {
    window.location = 'homepage.html';
};

const getId = () => {
    const urlParams = new URLSearchParams(window.location.search)
    return urlParams?.get('id');
};

const getProjectContent = () => {
    const projectContent = document.getElementById('projectContent');
    return projectContent?.value;
};

const getProjectName = () => {
    const projectName = document.getElementById('projectName');
    return projectName?.value;
};

const loadData = (data) => {
    projectData = data;

    const projectContent = document.getElementById('projectContent');
    projectContent.value = data.content || '';

    const projectName = document.getElementById('projectName');
    projectName.value = data.name || '';

    setTopBar(projectData.annotationType);
};

const onCloseSidebar = () => {
    const sidebar = document.getElementById('sidebar');

    ['input-projectAnnotation', 'div-newCitation', 'div-existingCitation', 'div-importCitations', 'div-exportCitations'].forEach(id => {
        removeElement(sidebar, id);
    });
};

const removeElement = (source, elementId) => {
    const element = document.getElementById(elementId);
    if(element) {
        source.removeChild(element);
    }
};

const saveProject = async () => {
    const headers = new Headers({
        "Content-Type":"application/x-form-urlencoded"
    });

    const data = {
        id: getId(), 
        name: getProjectName(), 
        content: getProjectContent(), 
        annotationType: projectData['annotationType'],
        idk: projectData['idk']
    };

    const params = [];
    for(let i in data){
        params.push(i + "=" + encodeURIComponent(data[i]));
    }
    
    try {
        const projects = await updateProject(params, headers);
        if(!projects.success) {
        errorMessage(projects.message);
        }
    } catch (err) {
        errorMessage(err);
    }
};

const setTopBar = (type) => {
    const topBar = document.getElementById('topBar');

    const titleElementData = {
        tagName: 'h3',
        attributes: [
            {name: 'class', value: 'title'},
        ],
        properties: [
            {name: 'innerHTML', value: type}
        ]
    };

    const titleElement = createElement(titleElementData);
    topBar.appendChild(titleElement);
};

const showAddCitation = () => {
    const sidebar = document.getElementById('sidebar');
    const button = document.getElementById('button-addCitation');

    if(sidebar.style.width == '450px') {
        sidebar.style.width = '120px';
        button.innerHTML = 'Управление на цитати...';
        onCloseSidebar();
    } else {
        sidebar.style.width = '450px';
        button.innerHTML = 'Затваряне'
        createSidebar(projectData.annotationType, projectData.id);
    }
};

document.getElementById('button-addCitation').addEventListener('click', () => showAddCitation());
document.getElementById('button-backToProjects').addEventListener('click', () => backToAllProjects());
document.getElementById('projectSaveButton').addEventListener('click', async () => await saveProject());

(async () => {
    try {
        const response = await getProjectById(getId());
        loadData(response[0]);
    } catch (err) {
        errorMessage(err);
    }
})();