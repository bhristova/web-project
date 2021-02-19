<?php

class NewCitationRequest {

    private string $id;

    private string $authorFirstName;

    private string $authorLastName;

    private string $source;

    private string $containerTitle;

    private string $otherContributors;

    private string $version;

    private string $number;

    private string $page;

    private string $publisher;

    private string $publicationDate;

    private string $location;

    private string $annotationType;

    private string $sourceType;

    private string $projectId;
    
    private string $quote;

    private string $dateOfAccess;

    private string $titleOfWebsite;

    private string $inTextCitation;

    private string $formattedCitation;

    private string $linkOnline;

    private string $linkArchive;

    private string $linkLibrary;

    public function __construct(array $projectData) {

        $this->id = isset($projectData['id']) ? $projectData['id'] : uniqid();
        $this->authorFirstName = isset($projectData['authorFirstName']) ? $projectData['authorFirstName'] : '';
        $this->authorLastName = isset($projectData['authorLastName']) ? $projectData['authorLastName'] : '';
        $this->source = isset($projectData['source']) ? $projectData['source'] : '';
        $this->containerTitle = isset($projectData['containerTitle']) ? $projectData['containerTitle'] : '';
        $this->otherContributors = isset($projectData['otherContributors']) ? $projectData['otherContributors'] : '';
        $this->version = isset($projectData['version']) ? $projectData['version'] : '';
        $this->number = isset($projectData['number']) ? $projectData['number'] : '';
        $this->page = isset($projectData['page']) ? $projectData['page'] : '';
        $this->publisher = isset($projectData['publisher']) ? $projectData['publisher'] : '';
        $this->publicationDate = isset($projectData['publicationDate']) ? $projectData['publicationDate'] : '';
        $this->location = isset($projectData['location']) ? $projectData['location'] : '';
        $this->annotationType = isset($projectData['annotationType']) ? $projectData['annotationType'] : '';
        $this->sourceType = isset($projectData['sourceType']) ? $projectData['sourceType'] : '';
        $this->projectId = isset($projectData['projectId']) ? $projectData['projectId'] : '';
        $this->quote = isset($projectData['quote']) ? $projectData['quote'] : '';
        $this->dateOfAccess = isset($projectData['dateOfAccess']) ? $projectData['dateOfAccess'] : '';
        $this->titleOfWebsite = isset($projectData['titleOfWebsite']) ? $projectData['titleOfWebsite'] : '';
        $this->inTextCitation = isset($projectData['inTextCitation']) ? $projectData['inTextCitation'] : '';
        $this->formattedCitation = isset($projectData['formattedCitation']) ? $projectData['formattedCitation'] : '';
        $this->linkOnline = isset($projectData['linkOnline']) ? $projectData['linkOnline'] : '';
        $this->linkArchive = isset($projectData['linkArchive']) ? $projectData['linkArchive'] : '';
        $this->linkLibrary = isset($projectData['linkLibrary']) ? $projectData['linkLibrary'] : '';
    }

    public function validate(): void {

        $errors = [];

        if(mb_strtolower($this->annotationType) == 'mla') {
            $this->validateNonEmpty('authorLastName', $errors);
            $this->validateNonEmpty('source', $errors);
            $this->validateNonEmpty('publicationDate', $errors);
        }

        if(mb_strtolower($this->annotationType) == 'apa') {
            $this->validateNonEmpty('authorLastName', $errors);
            $this->validateNonEmpty('source', $errors);
        }

        if(mb_strtolower($this->annotationType) == 'chicago') {
            $this->validateNonEmpty('authorLastName', $errors);
            $this->validateNonEmpty('source', $errors);
            $this->validateNonEmpty('page', $errors);
            $this->validateNonEmpty('publicationDate', $errors);

            if(mb_strtolower($this->sourceType == 'списание')) {
                $this->validateNonEmpty('titleOfWebsite', $errors);
            }

            if(mb_strtolower($this->sourceType == 'линк')) {
                $this->validateNonEmpty('containerTitle', $errors);
            }

        }

        if ($errors) {
            throw new RequestValidationException($errors);
        }
    }

    private function validateNonEmpty($fieldName, &$errors) {

        if (!$this->$fieldName) {
            $errors[$fieldName] = 'Field should not be empty';
        }

    }

    public function getProjectId(): string {
        return $this->projectId;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'authorFirstName' => $this->authorFirstName,
            'authorLastName' => $this->authorLastName,
            'source' => $this->source,
            'containerTitle' => $this->containerTitle,
            'otherContributors' => $this->otherContributors,
            'version' => $this->version,
            'number' => $this->number,
            'page' => $this->page,
            'publisher' => $this->publisher,
            'publicationDate' => $this->publicationDate,
            'location' => $this->location,
            'annotationType' => $this->annotationType,
            'sourceType' => $this->sourceType,
            'projectId' => $this->projectId,
            'quote' => $this->quote,
            'dateOfAccess' => $this->dateOfAccess,
            'titleOfWebsite' => $this->titleOfWebsite,
            'inTextCitation' => $this->inTextCitation,
            'formattedCitation' => $this->formattedCitation,
            'linkOnline' => $this->linkOnline,
            'linkArchive' => $this->linkArchive,
            'linkLibrary' => $this->linkLibrary,
        ];
    }
}
