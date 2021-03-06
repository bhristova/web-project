<?php

session_start();

spl_autoload_register(function($className) {
    require_once("../libs/$className.php");
});

$citationsController = new CitationsController();

switch ($_SERVER['REQUEST_METHOD']) {
    
    case 'GET': {
        $id = $_REQUEST['id'] ?? null;
        $projectId = $_REQUEST['projectId'] ?? null;
        if(!empty($id)) {
            echo json_encode($citationsController->getCitationsById($id), JSON_UNESCAPED_UNICODE);
        } else if(!empty($projectId)) {
            echo json_encode($citationsController->getCitationsByProjectId($projectId), JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode($citationsController->getAllCitations(), JSON_UNESCAPED_UNICODE);
        }
        break;
    }

    case 'POST': {

        $citationRequest = [];
        $citationSourceData = [];
        $added = false;
        $fieldsConfigController = new FieldsConfigController();
        $citationSourceData = $fieldsConfigController->getCitationSourceByName($_POST['sourceType']);

        if (!empty($citationSourceData)) {
            $inTextCitation = $citationSourceData[0]['inTextCitation'] ?? null;
            $bibliographyCitation = $citationSourceData[0]['bibliographyCitation'] ?? null;
        }

        if(empty($citationSourceData) || ((is_null($inTextCitation) || empty($inTextCitation)) && (is_null($bibliographyCitation) || empty($bibliographyCitation)))) {
            try {
                $citationRequest = new NewCitationRequest($_POST);
                $citationRequest->validate();
            } catch (RequestValidationException $ex) {
                echo json_encode(['success' => false, 'message' => $ex->getErrors()]);
                return;
            }
            $added = $citationsController->addNewCitation($citationRequest);
        } else {
            $citationRequest = new NewImportExportRequest($_POST, '', $citationSourceData[0]['inTextCitation'], $citationSourceData[0]['bibliographyCitation']);
            $added = $citationsController->importNewCitation($citationRequest);
        }

        echo json_encode(['success' => $added]);

        break;
    }

    case 'PUT': {
        parse_str(file_get_contents("php://input"), $body);
        $citationRequest = new NewCitationRequest($body);

        try {
            $citationRequest->validate();
        } catch (RequestValidationException $ex) {
            echo json_encode(['success' => false, 'message' => $ex->getErrors()]);
            return;
        }

        $updated = $citationsController->updateCitation($citationRequest);
        
        echo json_encode(['success' => $updated ]);
        break;
    }

    case 'DELETE': {
        $id = $_REQUEST['id'] ?? null;

        if(!empty($id)) {
            $deleted = $citationsController->deleteCitation($id);
        }

        echo json_encode(['success' => $deleted]);

        break;
    }
    return;
}
