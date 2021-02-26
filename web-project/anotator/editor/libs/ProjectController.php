<?php
declare(strict_types=1);

class ProjectController {

    public string $userId = 'cf8695c3-6e24-454a-b333-eb63c37ed7df'; //TODO: create login and add some sort of authentication, for example JWToken (if possible) and keep the userId there

    public function getAllProjects(): array {
        $projects = [];
        $userId = $this->userId;
        try {

            $connection = (new Db())->getConnection();

            $selectStatement = $connection->prepare("SELECT id, name FROM project WHERE createdBy=:userId");
            $selectStatement->execute(array(':userId' => $userId));

            $projects = $selectStatement->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }
        
        return $projects;
    }

    public function getProjectById(string $id): array {
        $projects = [];
        $userId = $this->userId;

        try {

            $connection = (new Db())->getConnection();

            $selectStatement = $connection->prepare("SELECT * FROM `project` WHERE id=:id AND createdBy=:userId");
            $selectStatement->execute(array(':userId' => $userId, ':id' => $id));

            $projects = $selectStatement->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }
        
        return $projects;
    }

    public function addNewProject(NewProjectRequest $projectRequest): bool {
        $projectRequestArray =  $projectRequest->toArray();
        $projectRequestArray['userId'] = $this->userId;

        try {
            $connection = (new Db())->getConnection();

            $insertStatement = $connection->prepare("
                INSERT INTO `project` (id, name, annotationType, content, createdBy)
                    VALUES (:id, :name, :annotationType, :content, :userId)
            ");

            $result = $insertStatement->execute($projectRequestArray);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $result;
    }

    public function updateProject(NewProjectRequest $projectRequest): bool {
        
        try {
            $connection = (new Db())->getConnection();

            $insertStatement = $connection->prepare("
                UPDATE `project`
                SET id=:id, name=:name, annotationType=:annotationType, content=:content
                WHERE id=:id
            ");

            $result = $insertStatement->execute($projectRequest->toArray());
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $result;
    }

    public function deleteProject($id)
    {
        try {
            $connection = (new Db())->getConnection();

            $deleteStatement = $connection->prepare("
                DELETE FROM `project`
                WHERE id = :id
            ");

            $deleteStatement->bindValue(':id', $id);

            $result = $deleteStatement->execute();

            $deleteStatementCitations = $connection->prepare("
                DELETE FROM `citation`
                WHERE projectId = :id
            ");

            $deleteStatementCitations->bindValue(':id', $id);

            $result = $deleteStatementCitations->execute();
            
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $result;
    }

}