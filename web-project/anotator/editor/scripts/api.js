const apiRoute = './api/';

const createProject = async (params) => {
    const response = await fetch(`${apiRoute}projects.php`, {
        method: 'POST',
        body: params,
    });
    
    return response.json();
}

const updateProject = async (params, headers) => {
    const response = await fetch(`${apiRoute}projects.php`, {
        method: 'PUT',
        body: params.join('&'),
        headers: headers || '',
        mode: 'cors'
    });
    
    return response.json();
}

const getProjectById = async (id) => {
    const response = await fetch(`${apiRoute}projects.php?id=${id}`);
    return response.json();
};

const getProjects = async () => {
    const response = await fetch(`${apiRoute}projects.php`, {
        method: 'GET'
    });

    return response.json();
};

const deleteProject = async (id) => {
    const response = await fetch(`${apiRoute}projects.php?id=${id}`, {
        method: 'DELETE',
    });

    return response.json();
};

const createCitation = async (params) => {
    const response = await fetch(`${apiRoute}citations.php`, {
        method: 'POST',
        body: params,
    });
    
    return response.json();
};

const importCitations = async (params) => {
    const response = await fetch(`${apiRoute}importExport.php`, {
        method: 'POST',
        body: params,
    });
    
    return response.json();
};

const getCitations = async () => {
    const response = await fetch(`${apiRoute}citations.php`);
    
    return response.json();
};

const getCitationsByProjectId = async (id) => {
    const response = await fetch(`${apiRoute}citations.php?projectId=${id}`);
    
    return response.json();
};

const deleteCitation = async (id) => {
    const response = await fetch(`${apiRoute}citations.php?id=${id}`, {
        method: 'DELETE',
    });
    
    return response.text();
};

export {createProject, updateProject, getProjectById, deleteProject, getProjects, createCitation, importCitations, getCitations, getCitationsByProjectId, deleteCitation};
