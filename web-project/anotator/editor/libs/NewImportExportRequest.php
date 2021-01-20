<?php

class NewImportExportRequest {

    private string $id;

    private string $sourceType;

    private string $inTextCitation;

    private string $formattedCitation;

    private string $quote;

    private string $projectId;

    public function __construct($fileContent, $projectId) {
        $this->id = uniqid();
        $this->sourceType = $this->getParsedData($fileContent, 1);
        $this->inTextCitation = $this->getParsedData($fileContent, 5);
        $this->formattedCitation = $this->getParsedData($fileContent, 3);
        $this->quote = $this->getParsedData($fileContent, 7);
        $this->projectId = $projectId;
    }

    public function validate(): void {

        $errors = [];

        $this->validateNonEmpty('sourceType', $errors);
        $this->validateNonEmpty('inTextCitation', $errors);
        $this->validateNonEmpty('formattedCitation', $errors);

        $this->validateSourceType($errors);

        // $this->validateNonEmpty('projectId', $errors);
        // $this->validateNonEmpty('idk', $errors);

        // if (filter_var($this->email, FILTER_VALIDATE_EMAIL) == false) {
        //     $errors['email'] = "Invalid email!";
        // }

        // // if (preg_match("/^[а-яА-Я ]*$/", $this->name) == false) {
        // //     $errors['name'] = "Invalid user name";
        // // }

        // if ($this->status != "owner" && $this->status != "tenant") {
        //    $errors['status'] = "Invalid status";
        // }

        if ($errors) {
            throw new RequestValidationException($errors);
        }
    }

    private function getParsedData($fileContent, $index): string {
        $parts = explode('\'', $fileContent);
        if($parts && isset($parts[$index])) {
            return $parts[$index];
        }
        return '';
    }

    private function validateSourceType(&$errors) {

        $validSourceTypes = array('книга', 'линк', 'списание');
        $sourceType = mb_strtolower($this->sourceType);
        if (empty($sourceType) || !in_array($sourceType, $validSourceTypes)) {
            $errors['sourceType'] = 'Supported sources are book, link and magazine';
        }

    }

    private function validateNonEmpty($fieldName, &$errors) {

        if (!$this->$fieldName || empty($this->$fieldName)) {
            $errors[$fieldName] = 'Field should not be empty';
        }

    }

    public function getProjectId(): string {
        return $this->projectId;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'sourceType' => $this->sourceType,
            'inTextCitation' => $this->inTextCitation,
            'formattedCitation' => $this->formattedCitation,
            'quote' => $this->quote,
            'projectId' => $this->projectId,
        ];
    }

}
