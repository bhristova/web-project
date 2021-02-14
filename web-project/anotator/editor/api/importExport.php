<?php

session_start();

spl_autoload_register(function($className) {
    require_once("../libs/$className.php");
});

$citationsController = new CitationsController();

switch ($_SERVER['REQUEST_METHOD']) {
    
    case 'GET': {
        foreach(glob('citations-*.txt') as $file )
        {
            unlink($file);
        }

        foreach(glob('bibliography-*.txt') as $file )
        {
            unlink($file);
        }

        $projectId = $_REQUEST['projectId'] ?? null;
        $exportType = $_REQUEST['exportType'] ?? null;
        
        if(!empty($projectId) && !empty($exportType)) {

            if($exportType == 'citations') {
                $fileName =  $citationsController->gitCitationsFile($projectId);
            }

            if($exportType == 'bibliography') {
                $fileName =  $citationsController->gitBibliographyFile($projectId);
            }

            header("Content-Disposition: attachment; filename=\"" . $fileName . "\"");
            header("Content-Type: application/force-download");
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Type: text/plain');
        }

        break;
    }

    case 'POST': {
        $projectId = $_POST['projectId'] ?? null;
        if ($_FILES['uploadedFile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['uploadedFile']['tmp_name'])) { //checks that file is uploaded
            $fileContent = file_get_contents($_FILES['uploadedFile']['tmp_name']); 

            if(mb_substr($fileContent, -1) == ';') {
                $fileContent = mb_substr($fileContent, 0, -1);
            }

            $rows = explode(';', $fileContent);

            foreach ($rows as &$row) {
                $citationRequest =  new NewImportExportRequest($row, $projectId);

                try {
                    $citationRequest->validate();
                } catch (RequestValidationException $ex) {
                    echo json_encode(['success' => false, 'message' => $ex->getErrors() ]);
                    return;
                }

                $added = $citationsController->importNewCitation($citationRequest);
                if(!$added) {
                    echo json_encode(['success' => false, 'message' => 'Error when trying to save' ]);
                    return;
                }
            }
            
            echo json_encode(['success' => true ]);
            return;
        }
        echo json_encode(['success' => false, 'message' => 'File is not uploaded' ]);
    }
    
    return;
}
