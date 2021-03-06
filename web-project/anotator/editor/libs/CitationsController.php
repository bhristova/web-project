<?php
declare(strict_types=1);

class CitationsController {

    public function getAllCitations(): array {
        $citations = [];

        $query = (new Db())->getConnection()->query("SELECT * FROM `citation`") or die("failed!");
        while ($citation = $query->fetch()) {
            $citations[] = $citation;
        }
        
        return $citations;
    }

    public function getCitationsById($citationIds): array {
        $citations = [];
        $citationIdsString = '';
        $func = function($value) {
            return "'$value'";
        };
        
        if (is_array($citationIds)) {
            $citationIds = array_map($func, $citationIds);
            $citationIdsString = implode(',', $citationIds);
        } else {
            $citationIdsString = $citationIds;
        }

        try {

            $connection = (new Db())->getConnection();

            $selectStatement = $connection->prepare("SELECT * FROM `citation` WHERE id IN (:citationIdsString) AND formattedCitation IS NOT NULL AND formattedCitation <> ''");
            $selectStatement->execute(array(':citationIdsString' => $citationIdsString));

            $citations = $selectStatement->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }
        
        return $citations;
    }

    public function getCitationsByProjectId(string $projectId): array {

        $citations = [];

        try {

            $connection = (new Db())->getConnection();

            $selectStatement = $connection->prepare("SELECT * FROM `citation` WHERE projectId=:projectId");
            $selectStatement->execute(array(':projectId' => $projectId));

            $citations = $selectStatement->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $citations;
    }

    public function gitBibliographyFile(string $projectId): string {
        $projectController = new ProjectController();

        $projects = $projectController->getProjectById($projectId);
        $project = $projects[0];

        $projectContent = $project['content'];
        $projectName = $project['name'];
        
        $regexCitationIds = '/(.*)(<customcitationtag id=")(?<citationId>.*)(\">)/U';

        $matches = [];
        $content = "Bibliography\r\n";
        $content .= "\"$projectName\"\r\n\r\n";

        preg_match_all($regexCitationIds, $projectContent, $matches);

        if(!empty($matches) && !empty($matches['citationId'])) {
            $citations = $this->getCitationsById($matches['citationId']);

            if(!empty($citations)) {
                foreach ($citations as $key=>$citation) {
                    $formattedCitation = $citation['formattedCitation'];
                    $content .= "$key. $formattedCitation;\r\n";
                }
            }
        }

        $namefile = 'bibliography-' . uniqid() . '.txt';
        echo $content;

        $file = fopen($namefile, "w") or die("Unable to open file!");
        fwrite($file, $content);
        fclose($file);

        return $namefile;
    }

    public function gitCitationsFile(string $projectId): string {

        $citationsArray =  $this->getCitationsByProjectId($projectId);
        $content = '';
        
        foreach ($citationsArray as &$row) {
            $content .= '\'' . $row['sourceType'] . '\', \'' . $row['formattedCitation'] . '\', \'' . $row['inTextCitation'] . '\', \'' . $row['quote'] . '\';';
        }

        $content = mb_substr($content, 0, -1);
        $namefile = 'citations-' . uniqid() . '.txt';
        echo $content;

        $file = fopen($namefile, "w") or die("Unable to open file!");
        fwrite($file, $content);
        fclose($file);

        return $namefile;
    }

    public function addNewCitation(NewCitationRequest $citationRequest): bool {
        $cit = Citation::createFromArray($citationRequest->toArray());
        $citationRequestArray = $cit->jsonSerialize();
        
        try {
            $connection = (new Db())->getConnection();

            $insertStatement = $connection->prepare('
                INSERT INTO `citation` (id, authorFirstName, authorLastName, source, containerTitle, otherContributors, version,
                number, page, publisher, publicationDate, location, annotationType, sourceType, projectId, formattedCitation, inTextCitation, quote, dateOfAccess, titleOfWebsite,
                linkOnline, linkArchive, linkLibrary)
                    VALUES (:id, :authorFirstName, :authorLastName, :source, :containerTitle, :otherContributors, :version,
                    :number, :page, :publisher, :publicationDate, :location, :annotationType, :sourceType, :projectId, :formattedCitation, :inTextCitation, :quote, :dateOfAccess, :titleOfWebsite,
                    :linkOnline, :linkArchive, :linkLibrary)
            ');

            $result = $insertStatement->execute($citationRequestArray);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $result;

    }

    public function updateCitation(NewCitationRequest $citationRequest): bool {
        $cit = Citation::createFromArray($citationRequest->toArray());
        $citationRequestArray = $cit->jsonSerialize();
        
        try {
            $connection = (new Db())->getConnection();

            $insertStatement = $connection->prepare('
                UPDATE `citation` 
                SET id=:id, authorFirstName=:authorFirstName, authorLastName=:authorLastName, source=:source, containerTitle=:containerTitle, otherContributors=:otherContributors, version=:version,
                number=:number, page=:page, publisher=:publisher, publicationDate=:publicationDate, location=:location, annotationType=:annotationType, sourceType=:sourceType, 
                projectId=:projectId, formattedCitation=:formattedCitation, inTextCitation=:inTextCitation, quote=:quote, dateOfAccess=:dateOfAccess, titleOfWebsite=:titleOfWebsite,
                linkOnline=:linkOnline, linkArchive=:linkArchive, linkLibrary=:linkLibrary
                WHERE id=:id
            ');

            $result = $insertStatement->execute($citationRequestArray);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $result;

    }

    //import
    public function importNewCitation(NewImportExportRequest $importExport): bool {

        $arr = $importExport->toArray();
        try {
            $connection = (new Db())->getConnection();

            $insertStatement = $connection->prepare('
            INSERT INTO `citation` (id, projectId, sourceType, formattedCitation, inTextCitation, quote)
                VALUES (:id, :projectId, :sourceType, :formattedCitation, :inTextCitation, :quote)
            ');

            $result = $insertStatement->execute($arr);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $result;

    }

    public function deleteCitation($id)
    {
        try {
            $connection = (new Db())->getConnection();

            $deleteStatement = $connection->prepare('
                DELETE FROM `citation`
                WHERE id = :id
            ');

            $deleteStatement->bindValue(':id', $id);

            $result = $deleteStatement->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $result;
    }

}