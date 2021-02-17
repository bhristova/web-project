import { createProject, deleteProject, getProjects } from './api.js';
import newSourceType from './source-new.js';
import createElement from './element.js';
import errorMessage from './error-message.js';
import successMessage from './success-message.js';

const addNewProject = async (evt) => {
    evt.preventDefault();

    try {
        const response = await createProject(new URLSearchParams(new FormData(evt.target)));
        if(!response.success) {
            errorMessage(response.message);
        }

        if(response.projectId) {
            window.location = `./editor.html?id=${response.projectId}`;
        }
    } catch (err) {
        errorMessage(err);
    }
}

const getDisplayElement = (project) => {
    const divData = {
        tagName: 'div',
        attributes: [
            {name: 'id', value: `div-projects-${project.id}`},
            {name: 'class', value: `projectContainer`},
        ]
    };

    const projectNameData = {
        tagName: 'p',
        attributes: [
            {name: 'class', value: `projectName`},
        ],
        properties: [
            {name: 'innerHTML', value: project.name},
        ],
        eventListeners: [
            {event: 'click', listener: () => {document.location = `./editor.html?id=${project.id}`}}
        ]
    };

    const deleteButtonData = {
        tagName: 'button',
        attributes: [
            {name: 'type', value: `button`},
            {name: 'class', value: `deleteButton`},
        ],
        properties: [
            {name: 'innerHTML', value: 'Изтрий'},
        ],
        eventListeners: [
            {event: 'click', listener: async (evt) => await onDeleteClick(evt, project.id)}
        ]
    };

    const div = createElement(divData);
    const projectName = createElement(projectNameData);
    const deleteButton = createElement(deleteButtonData);

    div.appendChild(projectName);
    div.appendChild(deleteButton);

    return div;
};

const onAllCitationsClick = () => {
    document.location = './summary.html'
};

const onAllProjects = async () => {
    const buttonsForm = document.getElementById('buttonsForm');
    buttonsForm.style.height = '400px';
    
    const projectDataContainer = document.getElementById('projectDataContainer');

    const projectChooser = document.getElementById('projectChooser');
    const newSourceType = document.getElementById('newSourceType');
    if(projectChooser) {
        return;
    }
    if(newSourceType) {
        projectDataContainer.removeChild(newSourceType);
    }

    const newProject = document.getElementById('newProject');
    if(newProject) {
        projectDataContainer.removeChild(newProject);
    }

    try {
        const response = await getProjects();

        const projectChooserData = {
            tagName: 'div',
            attributes: [
                {name: 'id', value: 'projectChooser'},
                {name: 'class', value: 'multifield'},
            ]
        };

        const projectChooser = createElement(projectChooserData);
        projectDataContainer.appendChild(projectChooser);

        response.forEach(proj => {
            const project = getDisplayElement(proj);
            projectChooser.appendChild(project);
        });
    } catch (err) {
        errorMessage(err);
    }
};

const onDeleteClick = async (evt, projectId) => {

    try {
        const response = await deleteProject(projectId);
        const projectsContainer = document.getElementById('projectChooser');
        const projectContainer = document.getElementById(`div-projects-${projectId}`);

        projectsContainer.removeChild(projectContainer);
        successMessage('Успешно изтрихте проект!');
    } catch (err) {
        errorMessage(err);
    }
};

const onNewForm = () => {
    const buttonsForm = document.getElementById('buttonsForm');
    buttonsForm.style.height = '400px';

    const projectDataContainer = document.getElementById('projectDataContainer');

    const projectChooser = document.getElementById('projectChooser');
    const newSourceType = document.getElementById('newSourceType');
    if(projectChooser) {
        projectDataContainer.removeChild(projectChooser);
    }
    if(newSourceType) {
        projectDataContainer.removeChild(newSourceType);
    }

    let div = document.getElementById('newProject');
    if(div) {
        return;
    }
    const annotationValues = ['APA', 'MLA', 'Chicago']

    const divData = {
        tagName: 'form',
        attributes: [
            {name: 'id', value: `newProject`},
            {name: 'class', value: `newForm`},
        ]
    };

    const inputProjectNameData = {
        tagName: 'input',
        attributes: [
            {name: 'name', value: `name`},
            {name: 'id', value: `input-projectName`},
            {name: 'placeholder', value: `Име на проект`},
            {name: 'class', value: `field`},
            {name: 'required', value: true},
        ]
    };

    const inputProjectLabelData = {
        tagName: 'label',
        attributes: [
            {name: 'class', value: `field`},
        ],
        properties: [
            {name: 'innerHTML', value: `Име на проект*:`},
            {name: 'htmlFor', value: `input-projectName`},
        ]
    };

    const inputProjectAnnotationData = {
        tagName: 'select',
        attributes: [
            {name: 'name', value: 'annotationType'},
            {name: 'id', value: 'input-projectAnnotation'},
            {name: 'class', value: `field`},
            {name: 'required', value: true},
        ],
        options: annotationValues.map(elem => ({value: elem}))
    };

    const inputProjectAnnotationLabelData = {
        tagName: 'label',
        attributes: [
            {name: 'class', value: `field`},
        ],
        properties: [
            {name: 'innerHTML', value: 'Тип на анотацията*:'},
            {name: 'htmlFor', value: 'input-projectAnnotation'},
        ]
    }

    const buttonData = {
        tagName: 'input',
        attributes: [
            {name: 'type', value: 'submit'},
            {name: 'class', value: `field`},
        ],
        properties: [
            {name: 'value', value: 'Създай'},
        ],
    };

    div = createElement(divData);
    const inputProjectName = createElement(inputProjectNameData);
    const inputProjectNameLabel = createElement(inputProjectLabelData);
    const inputProjectAnnotation = createElement(inputProjectAnnotationData);
    const inputProjectAnnotationLabel = createElement(inputProjectAnnotationLabelData);
    const button = createElement(buttonData);

    div.appendChild(inputProjectNameLabel);
    div.appendChild(inputProjectName);

    div.appendChild(inputProjectAnnotationLabel);
    div.appendChild(inputProjectAnnotation);

    div.appendChild(button);

    div.addEventListener('submit', (evt) => addNewProject(evt))


    projectDataContainer.appendChild(div);
};

const onNewSource = () => {
    const projectDataContainer = document.getElementById('projectDataContainer');

    const projectChooser = document.getElementById('projectChooser');
    const newProject = document.getElementById('newProject');
    if(projectChooser) {
        projectDataContainer.removeChild(projectChooser);
    }
    if(newProject) {
        projectDataContainer.removeChild(newProject);
    }

    const buttonsForm = document.getElementById('buttonsForm');
    buttonsForm.style.height = '700px';

    newSourceType();
};

const imageNewProject = document.getElementById('imageNewProject');
imageNewProject.addEventListener('mouseover', () => imageNewProject.setAttribute('src', './files/project-new-hover.png'));
imageNewProject.addEventListener('mouseout', () => imageNewProject.setAttribute('src', './files/project-new.png'));
imageNewProject.addEventListener('click', () => onNewForm());

const imageExistingProject = document.getElementById('imageExistingProject');
imageExistingProject.addEventListener('mouseover', () => imageExistingProject.setAttribute('src', './files/project-existing-hover.png'));
imageExistingProject.addEventListener('mouseout', () => imageExistingProject.setAttribute('src', './files/project-existing.png'));
imageExistingProject.addEventListener('click', () => onAllProjects());

const imageSummary = document.getElementById('imageSummary');
imageSummary.addEventListener('mouseover', () => imageSummary.setAttribute('src', './files/project-summary-hover.png'));
imageSummary.addEventListener('mouseout', () => imageSummary.setAttribute('src', './files/project-summary.png'));
imageSummary.addEventListener('click', () => onAllCitationsClick());

const imageNewSource = document.getElementById('imageNewSource');
imageNewSource.addEventListener('mouseover', () => imageNewSource.setAttribute('src', './files/project-existing-hover.png'));
imageNewSource.addEventListener('mouseout', () => imageNewSource.setAttribute('src', './files/project-new.png'));
imageNewSource.addEventListener('click', () => onNewSource());

// const buttonsForm = document.getElementById('newProject');
// buttonsForm.addEventListener('submit', (evt) => addNewProject(evt))
