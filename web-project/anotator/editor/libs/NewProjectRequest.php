<?php

class NewProjectRequest {

    private string $id;

    private string $name;

    private string $annotationType;

    private string $content;

    private string $idk;

    public function __construct(array $projectData) {

        $this->id = isset($projectData['id']) ? $projectData['id'] : uniqid();
        $this->name = isset($projectData['name']) ? $projectData['name'] : "";
        $this->annotationType = isset($projectData['annotationType']) ? $projectData['annotationType'] : "";
        $this->content = isset($projectData['content']) ? $projectData['content'] : "";
        $this->idk = isset($projectData['idk']) ? $projectData['idk'] : "";
    }

    public function getId(): string {
        return $this->id;
    }

    public function validate(): void {

        $errors = [];

        // $this->validateNonEmpty('id', $errors);
        $this->validateNonEmpty('name', $errors);
        // $this->validateNonEmpty('annotationType', $errors);
        // $this->validateNonEmpty('content', $errors);
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
            'idk' => $this->idk,
        ];
    }

}
