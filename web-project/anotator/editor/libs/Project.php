<?php

class Project {

    private string $id;

    private string $name;

    private string $annotationType;

    private string $content;

    public function __construct(string $id, string $name, string $annotationType, string $content) {
        $this->id = $id;
        $this->name = $name;
        $this->annotationType = $annotationType;
        $this->content = $content;
    }

    public function getId() : string {
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getannotationType() : string {
        return $this->annotationType;
    }

    public function getContent() : string {
        return $this->content;
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'annotationType' => $this->annotationType,
            'content' => $this->content,
        ];
    }

    public static function createFromArray(array $projects): Project {
        return new Project($projects['id'], $projects['name'], $projects['annotationType'], $projects['content']);
    }
}
