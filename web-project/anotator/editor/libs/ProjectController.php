<?php
declare(strict_types=1);

class ProjectController {

    public function getAllProjects(): array {

        $projects = [];

        $query = (new Db())->getConnection()->query("SELECT id, name FROM `project`") or die("failed!");

        while ($project = $query->fetch()) {
            $projects[] = $project;
        }
        
        return $projects;
    }

    public function getProjectById(string $id): array {

        $projects = [];

        $query = (new Db())->getConnection()->query("SELECT * FROM `project` WHERE id='$id'") or die("failed!");
        while ($project = $query->fetch()) {
            $projects[] = $project;
        }
        
        return $projects;
    }

    public function addNewProject(NewProjectRequest $projectRequest): bool {
        
        try {
            $connection = (new Db())->getConnection();

            $insertStatement = $connection->prepare("
                INSERT INTO `project` (id, name, annotationType, content, idk)
                    VALUES (:id, :name, :annotationType, :content, :idk)
            ");

            $result = $insertStatement->execute($projectRequest->toArray());
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
                SET id=:id, name=:name, annotationType=:annotationType, content=:content, idk=:idk
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
            
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $result;
    }

}