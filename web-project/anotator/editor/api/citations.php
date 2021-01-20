<?php

session_start();

spl_autoload_register(function($className) {
    require_once("../libs/$className.php");
});

$projectController = new CitationsController();

switch ($_SERVER['REQUEST_METHOD']) {
    
    case 'GET': {
        $id = $_REQUEST['id'] ?? null;
        $projectId = $_REQUEST['projectId'] ?? null;
        if(!empty($id)) {
            echo json_encode($projectController->getCitationsById($id), JSON_UNESCAPED_UNICODE);
        } else if(!empty($projectId)) {
            echo json_encode($projectController->getCitationsByProjectId($projectId), JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode($projectController->getAllCitations(), JSON_UNESCAPED_UNICODE);
        }
        break;
    }

    case 'POST': {

        try {
            $citationRequest = new NewCitationRequest($_POST);
            $citationRequest->validate();
        } catch (RequestValidationException $ex) {
            echo json_encode(['success' => false, 'message' => $ex->getErrors()]);
            return;
        }

        $added = $projectController->addNewCitation($citationRequest);

        echo json_encode(['success' => $added]);

        break;
    }

    case 'DELETE': {
        $id = $_REQUEST['id'] ?? null;

        if(!empty($id)) {
            $deleted = $projectController->deleteCitation($id);
        }

        echo json_encode(['success' => $deleted]);

        break;
    }
    return;
}
