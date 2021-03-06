<?php

class Citation {

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


    public function __construct(string $id, string $authorFirstName, string $authorLastName, string $source, string $containerTitle, string $otherContributors = '', string $version, string $number, string $page, string $publisher,
         string $publicationDate, string $location = '', string $annotationType, string $sourceType, string $projectId, string $quote, string $dateOfAccess, string $titleOfWebsite, string $inTextCitation, string $formattedCitation,
         string $linkOnline, string $linkArchive, string $linkLibrary) {
        $this->id = $this->trimValues($id);
        $this->authorFirstName = $this->trimValues($authorFirstName);
        $this->authorLastName = $this->trimValues($authorLastName);
        $this->source = $this->trimValues($source);
        $this->containerTitle = $this->trimValues($containerTitle);
        $this->otherContributors = $this->trimValues($otherContributors);
        $this->version = $this->trimValues($version);
        $this->number = $this->trimValues($number);
        $this->page = $this->trimValues($page);
        $this->publisher = $this->trimValues($publisher);
        $this->publicationDate = $this->trimValues($publicationDate);
        $this->location = $this->trimValues($location);
        $this->annotationType = $this->trimValues($annotationType);
        $this->sourceType = $this->trimValues($sourceType);
        $this->projectId = $this->trimValues($projectId);
        $this->quote = $this->trimValues($quote);
        $this->dateOfAccess = $this->trimValues($dateOfAccess);
        $this->titleOfWebsite = $this->trimValues($titleOfWebsite);
        $this->inTextCitation = !empty($this->trimValues($inTextCitation)) ? $this->trimValues($inTextCitation) : $this->constructInTextCitation();
        $this->formattedCitation = !empty($this->trimValues($formattedCitation)) ? $this->trimValues($formattedCitation) : $this->constructCitation();
        $this->linkOnline = $this->trimValues($linkOnline);
        $this->linkArchive = $this->trimValues($linkArchive);
        $this->linkLibrary = $this->trimValues($linkLibrary);
    }

    private function trimValues($value): string {
        $value = trim($value);
        if(substr($value, 0 ,1) == '.' || substr($value, 0 , 1) == ','|| substr($value, 0 , 1) == '"') {
            return $this->trimValues(substr($value, 1, mb_strlen($value) - 1));
        } else if(substr($value, -1) == '.' || substr($value, -1) == ',' || substr($value, -1) == '"') {
            return $this->trimValues(substr($value, 0, mb_strlen($value) - 1));
        }
        else return $value;
    }

    private function constructInTextCitation(): string {
        $annotationType = mb_strtolower($this->getAnnotationType());
        
        switch($annotationType) {
            case 'mla':
                return $this->constructMlaInTextCitation();
            case 'apa':
                return $this->constructApaInTextCitation();
            case 'chicago':
                return $this->constructChicagoInTextCitation();
        }

        return '';
    }

    private function constructChicagoInTextCitation(): string {
        $result = '';

        switch (mb_strtolower($this->getSourceType())) {
            case 'книга':
                {
                    $result .= !empty($this->getAuthorFirstName()) ? $this->getAuthorFirstName() : '';
                    $result .= (empty($result) ? '' : ' ') . !empty($this->getAuthorLastName()) ? $this->getAuthorLastName() : '';

                    if(!empty($result)) {
                        $result .= ', ';
                    }

                    $result .= !empty($this->getSource()) ? '"<i>' . $this->getSource() . '</i>." (' : '';
                    $result .= !empty($this->getLocation()) ? $this->getLocation() . ': ' : '';
                    $result .= !empty($this->getPublisher()) ? $this->getPublisher() . ', ' : '';
                    $result .= !empty($this->getPublicationDate()) ? $this->getPublicationDate() : '';
                    $result .= ')';
                    $result .= !empty($this->getPage()) ? ' ' . $this->getPage() . '.' : '.';
                }
                break;
            case 'линк':
                {
                    $result .= !empty($this->getAuthorFirstName()) ? $this->getAuthorFirstName() : '';
                    $result .= (empty($result) ? '' : ' ') . !empty($this->getAuthorLastName()) ? $this->getAuthorLastName() : '';

                    if(!empty($result)) {
                        $result .= ', ';
                    }

                    $result .= !empty($this->getSource()) ? '"' . $this->getSource() . '."' : '';
                    $result .= !empty($this->getTitleOfWebsite()) ? $this->getTitleOfWebsite() . '. ' : '';
                    $result .= !empty($this->getPublicationDate()) ? $this->getPublicationDate() . '. ' : $this->getDateOfAccess() . '. ';
                    $result .= !empty($this->getLocation()) ? $this->getLocation() . '. ' : '';
                }
                break;
            case 'списание':
                {
                    $result .= !empty($this->getAuthorFirstName()) ? $this->getAuthorFirstName() : '';
                    $result .= (empty($result) ? '' : ' ') . !empty($this->getAuthorLastName()) ? $this->getAuthorLastName() : '';

                    if(!empty($result)) {
                        $result .= ', ';
                    }

                    $result .= !empty($this->getSource()) ? '"' . $this->getSource() . '."' : '';
                    $result .= !empty($this->getContainerTitle()) ? '<i>' . $this->getContainerTitle() . '</i>, ' : '';
                    $result .= !empty($this->getPublicationDate()) ? $this->getPublicationDate() . '. ' : '';
                    $result .= !empty($this->getPage()) ? ' ' . $this->getPage() . '.' : '.';
                }
                break;
        }
        return $result;
    }

    private function constructMlaInTextCitation(): string {
        $result = '';

        $lastName = $this->getAuthorLastName();

        if(!empty($this->getQuote())) {
            if(substr($this->getQuote(),0, 1) != '"') {
                $result = '"';
            }
            
            $result .= $this->getQuote();

            if(substr($this->getQuote(),-1) != '"') {
                $result .= '"';
            }

            $result .= ' ';
        }

        $result .= '(';

        if(mb_strtolower($this->getSourceType()) == 'линк') {
            if(!empty($lastName)) {
                $result .= $lastName . ', ';
            }
            $result .= '"' . $this->getSource() . '")';
            return $result;
        }

        if(!empty($lastName)) {
            $result .= $lastName;

            if(!empty($this->getLocation())) {
                $result .= ' ' . $this->getLocation();
            }

            $result .=')';

            return $result;
        } else {
            $result .= '"' . $this->getSource() . '")';
        }

        return $result;
    }

    private function constructApaInTextCitation(): string {
        $result = '';

        if(!empty($this->getQuote())) {
            if(substr($this->getQuote(),0, 1) != '"') {
                $result .= '"';
            }
            
            $result .= $this->getQuote();

            if(substr($this->getQuote(),-1) != '"') {
                $result .= '"';
            }

            $result .= ' ';
        }

        $lastName = $this->getAuthorLastName();
        $date = $this->getPublicationDate();
        $page = $this->getPage();

        if(!empty($lastName) || !empty($date) || !empty($page)) {
            $result .= '(';

            $result .= empty($lastName) ? '' : $lastName;
            $result .= empty($date) ? '' : (empty($lastName) ? $date : ', ' . $date);
            $result .= empty($page) ? '' : (empty($lastName) && empty($date) ? $page : ', ' . $page);

            $result .= ')';
        }

        return $result;
    }

    private function constructCitation(): string {
        $annotationType = mb_strtolower($this->getAnnotationType());
        
        switch($annotationType) {
            case 'mla':
                return $this->constructMlaCitation();
            case 'apa':
                return $this->constructApaCitation();
            case 'chicago':
                return $this->constructChicagoCitation();
        }

        return '';
    }

    private function constructChicagoCitation(): string {
        $result = '';

        switch (mb_strtolower($this->getSourceType())) {
            case 'книга':
                {
                    $result .= $this->getAuthorLastName();

                    if(!empty($result)) {
                        $result .= ', ';
                    }

                    $result .= !empty($this->getAuthorLastName()) ? $this->getAuthorLastName() . ', ' : '';
                    $result .= !empty($this->getSource()) ? '<i>' . $this->getSource() . '</i>.' : '';
                    $result .= !empty($this->getLocation()) ? $this->getLocation() : '';
                    $result .= !empty($this->getPublisher()) ? $this->getPublisher() . ', ' : '';
                    $result .= !empty($this->getPublicationDate()) ? $this->getPublicationDate() : '';
                }
                break;
            case 'линк':
                {
                    $result .= !empty($this->getAuthorLastName()) ? $this->getAuthorLastName() . ', ' : '';
                    $result .= !empty($this->getAuthorFirstName()) ? $this->getAuthorFirstName() . '. ' : '';
                    $result .= !empty($this->getSource()) ? '"' . $this->getSource() . '"' : '';
                    $result .= !empty($this->getTitleOfWebsite()) ? $this->getTitleOfWebsite() . '. ' : '';
                    $result .= !empty($this->getPublicationDate()) ? $this->getPublicationDate() . '. ' : (!empty($this->getDateOfAccess()) ? $this->getDateOfAccess() . '. ' : '');
                    $result .= !empty($this->getLocation()) ? $this->getLocation() . '. ' : '';
                }
                break;
            case 'списание':
                {
                    $result .= !empty($this->getAuthorLastName()) ? $this->getAuthorLastName() . ', ' : '';
                    $result .= !empty($this->getAuthorFirstName()) ? $this->getAuthorFirstName() . '. ' : '';
                    $result .= !empty($this->getSource()) ? '"' . $this->getSource() . '."' : '';
                    $result .= !empty($this->getContainerTitle()) ? '<i>' . $this->getContainerTitle() . '</i>,' : '';
                    $result .= !empty($this->getPublicationDate()) ? $this->getPublicationDate() . '.' : '';
                    break;
                }
                break;
        }
        return $result;
    }

    private function constructApaCitation(): string {
        $result = '';
        $result .= !empty($this->getAuthorLastName()) ? $this->getAuthorLastName() : '';

        if(!empty($this->getAuthorFirstName())) {
            $names = explode(" ", $this->getAuthorFirstName());
            $result .= ', ';
            foreach ($names as &$name) {
                $result .= mb_strtoupper(substr($name,0,1)) . '. ';
            }
        }

        $result .= !empty($this->getPublicationDate()) ? '(' . $this->getPublicationDate() . '). ' : '';
        $result .= !empty($this->getSource()) ? '<i>' . $this->getSource() . '</i>' : '';

        $edition = $this->getVersion();
        $volume = $this->getNumber();
        $page = $this->getPage();

        if(!empty($edition) || !empty($volume) || !empty($page)) {
            $result .= '(';

            $result .= empty($edition) ? '' : $edition;
            $result .= empty($volume) ? '' : (empty($edition) ? $volume : ', ' . $volume);
            $result .= empty($page) ? '' : (empty($edition) && empty($volume) ? $page : ', ' . $page);

            $result .= ').';
        }

        $location = $this->getLocation();
        $result .= empty($location) ? '' : $location;

        if(!empty($this->getPublisher())) {
            if(!empty($location)) {
                $result .= ': ';
            }
            $result .= $this->getPublisher();
        }

        return $result;
    }

    private function constructMlaCitation(): string {
        $result = '';

        $result .= !empty($this->getAuthorLastName()) ? $this->getAuthorLastName() . ', ' : '';
        $result .= !empty($this->getAuthorFirstName()) ? $this->getAuthorFirstName() . '.' : '';
        $result .= !empty($this->styleSource()) ? $this->styleSource(): '';
        $result .= !empty($this->getContainerTitle()) ? '<i>' . $this->getContainerTitle() . '</i>, ' : '';
        $result .= !empty($this->getOtherContributors()) ? $this->getOtherContributors() . ', ' : '';
        $result .= !empty($this->getVersion()) ? $this->getVersion() . ', ' : '';
        $result .= !empty($this->getNumber()) ? $this->getNumber() . ', ' : '';
        $result .= !empty($this->getPublisher()) ? $this->getPublisher() . ', ' : '';
        $result .= !empty($this->getPublicationDate()) ? $this->getPublicationDate() . ', ' : '';
        $result .= !empty($this->getLocation()) ? $this->getLocation() . ', ' : '';

        if (substr($result, -2) == ', ') {
            $result = substr($result, 0, mb_strlen($result) - 2) . '.';
        }

        if (substr($result, -2) == '..') {
            $result = substr($result, 0, mb_strlen($result) - 1);
        }

        return $result;
    }

    public function styleSource(): string {
        $source = $this->getSource();
        if (!substr($source, -1) == '.') {
            $source .= '.';
        }
        $result = '';
        switch (mb_strtolower($this->getSourceType())) {
            case 'книга':
                $result = '<i>' . $source . '</i>.';
                break;
            case 'линк':
            case 'списание':
                $result = '"' . $source . '"'; 
                break;
        }

        return $result;
    }

    public function getId() : string {
        return $this->id;
    }

    public function getAuthorFirstName() : string {
        return $this->authorFirstName;
    }

    public function getAuthorLastName() : string {
        return $this->authorLastName;
    }

    public function getSource() : string {
        return $this->source;
    }

    public function getContainerTitle(): string {
        return $this->containerTitle;
    }

    public function getOtherContributors(): string {
        return $this->otherContributors;
    }

    public function getVersion(): string {
        return $this->version;
    }

    public function getNumber(): string {
        return $this->number;
    }

    public function getPage(): string {
        return $this->page;
    }

    public function getPublisher(): string {
        return $this->publisher;
    }

    public function getPublicationDate(): string {
        return $this->publicationDate;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function getAnnotationType(): string {
        return $this->annotationType;
    }

    public function getSourceType(): string {
        return $this->sourceType;
    }

    public function getProjectId(): string {
        return $this->projectId;
    }

    public function getQuote(): string {
        return $this->quote;
    }

    public function getDateOfAccess(): string {
        return $this->dateOfAccess;
    }

    public function getTitleOfWebsite(): string {
        return $this->titleOfWebsite;
    }

    public function getInTextCitation(): string {
        return $this->inTextCitation;
    }

    public function getFormattedCitation(): string {
        return $this->formattedCitation;
    }

    public function jsonSerialize(): array {
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

    public static function createFromArray(array $projects): Citation {
        return new Citation($projects['id'], $projects['authorFirstName'], $projects['authorLastName'], $projects['source'], $projects['containerTitle'], $projects['otherContributors'], $projects['version'], $projects['number'], $projects['page'],
        $projects['publisher'], $projects['publicationDate'], $projects['location'], $projects['annotationType'], $projects['sourceType'], $projects['projectId'], $projects['quote'], $projects['dateOfAccess'], $projects['titleOfWebsite'], 
        $projects['inTextCitation'], $projects['formattedCitation'], $projects['linkOnline'], $projects['linkArchive'], $projects['linkLibrary']);
    }
}
