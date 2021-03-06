<?php
declare(strict_types=1);

class FieldsConfigController {

    public function getAllFieldConfigs(): array {
        $configs = [];

        $query = (new Db())->getConnection()->query("SELECT * FROM `l_citationtypes_l_citationsources`") or die("failed!");
        while ($config = $query->fetch()) {
            $configs[] = $config;
        }
        
        return $configs;
    }

    public function getCitationSourceByName($name): array {
        $configs = [];

        try {
            $connection = (new Db())->getConnection();

            $selectStatement = $connection->prepare("SELECT * from l_citationsources WHERE name = :name");
            $selectStatement->execute(array(':name' => $name));

            $configs = $selectStatement->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $configs;
    }

    public function getFieldConfigByName($name, $source): array {
        $configs = [];

        try {
            $connection = (new Db())->getConnection();

            if(!is_null($source)) {
                $selectStatement = $connection->prepare("SELECT lclc.config, lc.name as CitationType, lcs.name as SourceName FROM `l_citationtypes_l_citationsources`  lclc
                INNER JOIN `l_citationtypes` lc on  lclc.id1_L_citationType = lc.id
                INNER JOIN `l_citationsources` lcs on  lclc.id2_L_citationSource = lcs.id
                WHERE lc.name = :name AND lcs.name = :source");
                $selectStatement->execute(array(':name' => $name, ':source' => $source));
            } else {
                $selectStatement = $connection->prepare("SELECT lclc.config, lc.name as CitationType, lcs.name as SourceName FROM `l_citationtypes_l_citationsources`  lclc
                INNER JOIN `l_citationtypes` lc on  lclc.id1_L_citationType = lc.id
                INNER JOIN `l_citationsources` lcs on  lclc.id2_L_citationSource = lcs.id
                WHERE lc.name = :name");
                $selectStatement->execute(array(':name' => $name));
            }

            $configs = $selectStatement->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }
        
        return $configs;
    }

    public function addNewFieldConfig(array $fieldConfigRequest): bool {        
        try {
            $connection = (new Db())->getConnection();

            foreach ($fieldConfigRequest as $request) {
                $insertStatement = $connection->prepare('
                    INSERT INTO `l_citationtypes_l_citationsources` (id1_L_citationType, id2_L_citationSource, config)
                        VALUES (:id1_L_citationType, :id2_L_citationSource, :config)
                ');
    
                $result = $insertStatement->execute($request);
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $result;
    }

    public function addNewCitationSource(array $fieldConfigRequest): bool {        
        try {
            $connection = (new Db())->getConnection();

            $insertStatement = $connection->prepare('
                INSERT INTO `l_citationsources` (id, name, inTextCitation, bibliographyCitation)
                    VALUES (:id, :name, :inTextCitation, :bibliographyCitation)
            ');

            $result = $insertStatement->execute($fieldConfigRequest);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $result;
    }

}