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

    public function getFieldConfigByName($name): array {
        $configs = [];

        $query = (new Db())->getConnection()->query("SELECT * FROM `l_citationtypes_l_citationsources`  lclc
            INNER JOIN `l_citationtypes` lc on  lclc.id1_L_citationType = lc.id
            WHERE lc.name = $name") or die("failed!");
        while ($configs = $query->fetch()) {
            $configs[] = $configs;
        }
        
        return $configs;
    }

    public function addNewFieldConfig(NewFieldConfigRequest $fieldConfigRequest): bool {        
        try {
            $connection = (new Db())->getConnection();

            $insertStatement = $connection->prepare('
                INSERT INTO `l_citationtypes_l_citationsources` (id1_L_citationType, id2_L_citationSource, config)
                    VALUES (:id1_L_citationType, :id2_L_citationSource, :config)
            ');

            $result = $insertStatement->execute($fieldConfigRequest->toArray());
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $e->getMessage();
        }

        return $result;

    }

}