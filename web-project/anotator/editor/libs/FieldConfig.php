<?php

class FieldConfig {

    private string $idCitationType;

    private string $idCitationSource;

    private string $config;

    public function __construct($idCitationType, $idCitationSource, $config) {
        $this->idCitationType = $idCitationType;
        $this->idCitationSource = $idCitationSource;
        $this->config = $config;
    }

    public function validate(): void {

        $errors = [];

        $this->validateNonEmpty('idCitationType', $errors);
        $this->validateNonEmpty('idCitationSource', $errors);
        $this->validateNonEmpty('config', $errors);

        if ($errors) {
            throw new RequestValidationException($errors);
        }
    }

    private function validateNonEmpty($fieldName, &$errors) {

        if (!$this->$fieldName || empty($this->$fieldName)) {
            $errors[$fieldName] = 'Field should not be empty';
        }

    }

    public function getIdCitationType(): string {
        return $this->idCitationType;
    }

    public function getIdCitationSource(): string {
        return $this->idCitationSource;
    }

    public function getConfig(): string {
        return $this->config;
    }

    public function jsonSerialize(): array {
        return [
            'idCitationType' => $this->idCitationType,
            'idCitationSource' => $this->idCitationSource,
            'config' => $this->config,
        ];
    }

    public static function createFromArray(array $fieldConfig): Project {
        return new FieldConfig($fieldConfig['idCitationType'], $fieldConfig['idCitationSource'], $fieldConfig['config']);
    }
}
