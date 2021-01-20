<?php

session_start();

spl_autoload_register(function($className) {
    require_once("../libs/$className.php");
});

$projectController = new CitationsController();

switch ($_SERVER['REQUEST_METHOD']) {
    
    case 'GET': {
         foreach(glob('citations-*.txt') as $file )
        {
            unlink($file);
        }

        $projectId = $_REQUEST['projectId'] ?? null;
        
        $content = '';
        if(!empty($projectId)) {
            $citationsArray =  $projectController->getCitationsByProjectId($projectId);
        } else {
            $citationsArray = $projectController->getAllCitations();
        }

        foreach ($citationsArray as &$row) {
            $content .= '\'' . $row['sourceType'] . '\', \'' . $row['formattedCitation'] . '\', \'' . $row['inTextCitation'] . '\', \'' . $row['quote'] . '\';';
        }

        $content = mb_substr($content, 0, -1);
        $namefile = 'citations-' . uniqid() . '.txt';
        echo $content;

        $file = fopen($namefile, "w") or die("Unable to open file!");
        fwrite($file, $content);
        fclose($file);

        header("Content-Disposition: attachment; filename=\"" . $namefile . "\"");
        header("Content-Type: application/force-download");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Type: text/plain');

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

                $added = $projectController->importNewCitation($citationRequest);
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
