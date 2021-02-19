<?php

class NewFieldConfigRequest {

    private string $sourceType;
    private string $sourceId;

    private string $anotationTypeAPA;
    private string $anotationTypeMLA;
    private string $anotationTypeChicago;
    private string $anotationTypeEdit;

    private string $config;

    private string $inTextCitation; 
    private string $bibliographyCitation; 

    public function __construct(array $fieldConfigData) {
        $this->sourceType = isset($fieldConfigData['sourceType']) ? $fieldConfigData['sourceType'] : '';
        $this->sourceId = uniqid();

        $this->anotationTypeAPA = isset($fieldConfigData['anotationTypeAPA']) ? $fieldConfigData['anotationTypeAPA'] : '';
        $this->anotationTypeChicago = isset($fieldConfigData['anotationTypeChicago']) ? $fieldConfigData['anotationTypeChicago'] : '';
        $this->anotationTypeMLA = isset($fieldConfigData['anotationTypeMLA']) ? $fieldConfigData['anotationTypeMLA'] : '';
        $this->anotationTypeEdit = isset($fieldConfigData['anotationTypeEdit']) ? $fieldConfigData['anotationTypeEdit'] : '';

        $this->config = $this->buildConfig($fieldConfigData);

        $this->inTextCitation = isset($fieldConfigData['inTextCitation']) ? $fieldConfigData['inTextCitation'] : '';
        $this->bibliographyCitation = isset($fieldConfigData['bibliographyCitation']) ? $fieldConfigData['bibliographyCitation'] : '';
    }

    public function validate(): void {

        $errors = [];

        $this->validateNonEmpty('sourceType', $errors);

        if ($errors) {
            throw new RequestValidationException($errors);
        }
    }

    private function validateNonEmpty($fieldName, &$errors) {

        if (!$this->$fieldName || empty($this->$fieldName)) {
            $errors[$fieldName] = 'Field should not be empty';
        }

    }

    public function getPropertiesForCitationSources(): array {
        return [
            'id' => $this->sourceId,
            'name' => $this->sourceType,
            'inTextCitation' => $this->inTextCitation,
            'bibliographyCitation' => $this->bibliographyCitation 
        ];
    }

    public function getPropertiesForCitationTypes_CitationSources(): array {
        $mainArray = [];

        foreach ([$this->anotationTypeAPA, $this->anotationTypeChicago, $this->anotationTypeMLA, $this->anotationTypeEdit] as $anotationType)
        $mainArray[] = [
            'id1_L_citationType' => $anotationType,
            'id2_L_citationSource' => $this->sourceId,
            'config' => $this->config
        ];

        return $mainArray;
    }

    private function buildConfig($fieldConfigData): string {
        $skipKeys = ['sourceType', 'anotationTypeAPA', 'anotationTypeChicago', 'anotationTypeMLA', 'anotationTypeEdit', 'inTextCitation', 'bibliographyCitation'];
        $result = '[';
        $index = 0;
        foreach($fieldConfigData as $key => $item){
            if (!in_array($key, $skipKeys) && !empty($item) && substr($key, 0, 8 ) != 'checkbox') {
                $result .= "{\"number\": \"" . $index . "\", \"name\": \"" . $key . "\", \"id\": \"" . $key . "\", \"label\": \"" . $item . "\"" . (isset($fieldConfigData["checkbox$key"]) ? ", \"required\": \"true\"" : '') . "},";
                $index += 1;
            }
        }
        $result = substr($result, 0, -1); 
        $result .= ']';
        return $result;
    }
}
