<?php
declare(strict_types=1);

class CitationsController {

    public function getAllCitations(): array {
        $citations = [];

        $query = (new Db())->getConnection()->query("SELECT * FROM `citation`") or die("failed!");
        while ($citation = $query->fetch()) {
            $cit = Citation::createFromArray($citation);
            $citations[] = $citation;
        }
        
        return $citations;
    }

    public function getCitationsByProjectId(string $projectId): array {

        $citations = [];

        $query = (new Db())->getConnection()->query("SELECT * FROM `citation` WHERE projectId='$projectId'") or die('failed!');
        while ($citation = $query->fetch()) {
            $cit = Citation::createFromArray($citation);
            $citations[] = $citation;
        }
        
        return $citations;
    }

    public function addNewCitation(NewCitationRequest $citationRequest): bool {
        $cit = Citation::createFromArray($citationRequest->toArray());
        $citationRequestArray = $cit->jsonSerialize();
        
        try {
            $connection = (new Db())->getConnection();

            $insertStatement = $connection->prepare('
                INSERT INTO `citation` (id, authorFirstName, authorLastName, source, containerTitle, otherContributors, version,
                number, page, publisher, publicationDate, location, annotationType, sourceType, projectId, formattedCitation, inTextCitation, quote, dateOfAccess, titleOfWebsite)
                    VALUES (:id, :authorFirstName, :authorLastName, :source, :containerTitle, :otherContributors, :version,
                    :number, :page, :publisher, :publicationDate, :location, :annotationType, :sourceType, :projectId, :formattedCitation, :inTextCitation, :quote, :dateOfAccess, :titleOfWebsite)
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