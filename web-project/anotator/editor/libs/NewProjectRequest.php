<?php

class NewProjectRequest {

    private string $id;

    private string $name;

    private string $annotationType;

    private string $content;

    public function __construct(array $projectData) {

        $this->id = isset($projectData['id']) ? $projectData['id'] : uniqid();
        $this->name = isset($projectData['name']) ? $projectData['name'] : "";
        $this->annotationType = isset($projectData['annotationType']) ? $projectData['annotationType'] : "";
        $this->content = isset($projectData['content']) ? $projectData['content'] : "";
    }

    public function getId(): string {
        return $this->id;
    }

    public function validate(): void {

        $errors = [];

        $this->validateNonEmpty('name', $errors);
        if ($errors) {
            throw new RequestValidationException($errors);
        }
    }

    private function validateNonEmpty($fieldName, &$errors) {

        if (!$this->$fieldName) {
            $errors[$fieldName] = 'Field should not be empty';
        }

    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'annotationType' => $this->annotationType,
            'content' => $this->content,
        ];
    }

}
