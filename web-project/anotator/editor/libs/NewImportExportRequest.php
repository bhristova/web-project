<?php

class NewImportExportRequest {

    private string $id;

    private string $sourceType;

    private string $inTextCitation;

    private string $formattedCitation;

    private string $quote;

    private string $projectId;

    public function __construct($fileContent, $projectId = '', $templateInTextCitation = '', $templateBibliographyCitation = '') {
        $this->id = uniqid();

        if (!empty($projectId)) {
            $this->sourceType = $this->getParsedData($fileContent, 1);
            $this->inTextCitation = $this->getParsedData($fileContent, 5);
            $this->formattedCitation = $this->getParsedData($fileContent, 3);
            $this->quote = $this->getParsedData($fileContent, 7);
            $this->projectId = $projectId;
        } else {
            $this->sourceType = isset($fileContent['sourceType']) ? $fileContent['sourceType'] : '';
            $this->inTextCitation = $this->replaceValuesInTemplate($fileContent, $templateInTextCitation);
            $this->formattedCitation = $this->replaceValuesInTemplate($fileContent, $templateBibliographyCitation);
            $this->quote = isset($fileContent['quote']) ? $fileContent['quote'] : '';
            $this->projectId = isset($fileContent['projectId']) ? $fileContent['projectId'] : '';
        }
    }

    public function validate(): void {

        $errors = [];

        $this->validateNonEmpty('sourceType', $errors);
        $this->validateNonEmpty('inTextCitation', $errors);
        $this->validateNonEmpty('formattedCitation', $errors);

        if ($errors) {
            throw new RequestValidationException($errors);
        }
    }

    private function replaceValuesInTemplate($fileContent, $template): string {
        $result = $template;
        foreach ($fileContent as $key=>$item) {
            $result = str_replace("{{$key}}", $item, $result);
        }

        $pattern = "/({.*?})/mi";
        $result = preg_replace($pattern, "", $result);

        return $result;
    }

    private function getParsedData($fileContent, $index): string {
        $parts = explode('\'', $fileContent);
        if($parts && isset($parts[$index])) {
            return $parts[$index];
        }
        return '';
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
