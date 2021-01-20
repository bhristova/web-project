<?php

session_start();

spl_autoload_register(function($className) {
    require_once("../libs/$className.php");
});

$projectController = new ProjectController();

switch ($_SERVER['REQUEST_METHOD']) {
    
    case 'GET': {
        $id = $_REQUEST['id'] ?? null;
        if(!empty($id)) {
            echo json_encode($projectController->getProjectById($id), JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode($projectController->getAllProjects(), JSON_UNESCAPED_UNICODE);
        }
        break;
    }

    case 'POST': {

        $projectRequest = new NewProjectRequest($_POST);

        try {
            $projectRequest->validate();
        } catch (RequestValidationException $ex) {
            echo json_encode(['success' => false, 'message' => $ex->getErrors()]);
            return;
        }

        $added = $projectController->addNewProject($projectRequest);
        
        if(!$added) {
            echo json_encode(['success' => false || $added, 'message' => 'Error when trying to save']);
            return;
        }
        echo json_encode(['success' => true, 'projectId' => $projectRequest->getId() ]);
        break;
    }

    case 'PUT': {
        parse_str(file_get_contents("php://input"), $body);
        $projectRequest = new NewProjectRequest($body);

        try {
            $projectRequest->validate();
        } catch (RequestValidationException $ex) {
            echo json_encode(['success' => false, 'message' => $ex->getErrors()]);
            return;
        }

        $updated = $projectController->updateProject($projectRequest);
        $added = false;
        
        if(!$updated) {
            $added = $projectController->addNewProject($projectRequest);
        }

        if(!$updated && !$added) {
            echo json_encode(['success' => false || $added, 'message' => 'Error when trying to save']);
            return;
        }
        echo json_encode(['success' => true ]);

        break;
    }

    case 'DELETE': {
        $projectId = $_REQUEST['id'] ?? null;
        $deleted = true;

        if(!empty($projectId)) {
            $deleted = $projectController->deleteProject($projectId);
        }
        
        echo json_encode(['success' => $deleted]);

        break;
    }
    return;
}
