<?php

class Project {

    private string $id;

    private string $name;

    private string $annotationType;

    private string $content;

    private string $idk;

    public function __construct(string $id, string $name, string $annotationType, string $content, string $idk) {
        $this->id = $id;
        $this->name = $name;
        $this->annotationType = $annotationType;
        $this->content = $content;
        $this->idk = $idk;
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

    public function getIdk(): string {
        return $this->idk;
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'annotationType' => $this->annotationType,
            'content' => $this->content,
            'idk' => $this->idk,
        ];
    }

    public static function createFromArray(array $projects): Project {
        return new Project($projects['id'], $projects['name'], $projects['annotationType'], $projects['content'], $projects['idk']);
    }
}
