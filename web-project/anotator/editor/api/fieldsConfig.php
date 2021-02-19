<?php

session_start();

spl_autoload_register(function($className) {
    require_once("../libs/$className.php");
});

$fieldsConfigController = new FieldsConfigController();

switch ($_SERVER['REQUEST_METHOD']) {
    
    case 'GET': {
        $name = $_REQUEST['name'] ?? null;

        if(!empty($name)) {
            echo json_encode($fieldsConfigController->getFieldConfigByName($name), JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode($fieldsConfigController->getAllFieldConfigs(), JSON_UNESCAPED_UNICODE);
        }

        break;
    }

    case 'POST': {
        $fieldConfigRequest = new NewFieldConfigRequest($_POST);

        try {
            $fieldConfigRequest->validate();
        } catch (RequestValidationException $ex) {
            echo json_encode(['success' => false, 'message' => $ex->getErrors()]);
            return;
        }

        $propertiesForCitationSources = $fieldConfigRequest->getPropertiesForCitationSources();
        $propertiesForCitationTypes_CitationSources = $fieldConfigRequest->getPropertiesForCitationTypes_CitationSources();

        $addedCitationSource = $fieldsConfigController->addNewCitationSource($propertiesForCitationSources);
        $addedFieldConfig = $fieldsConfigController->addNewFieldConfig($propertiesForCitationTypes_CitationSources);
        
        if(!$addedCitationSource) {
            echo json_encode(['success' => false, 'message' => 'Error 1']);
            return;
        }
        if(!$addedFieldConfig) {
            echo json_encode(['success' => false, 'message' => 'Error 2']);
            return;
        }
        
        echo json_encode(['success' => true]);
        break;
    }
    
    return;
}
