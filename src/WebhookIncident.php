<?php

namespace WebhookParser;

class WebhookIncident implements \JsonSerializable {
    const ACTION_CREATE = "CREATE";
    const ACTION_RESOLVE = "RESOLVE";

    /**
     * @var null|\DateTime
     */
    private $createdAt = null;

    /**
     * @var null|string
     */
    private $summary = null;

    /** @var string */
    private $parserVersion = null;

    /** @var string */
    private $parserType = null;

    /** @var string */
    private $link = null;

    /** @var string */
    private $action = null;

    /** @var null @var string */
    private $externalId = null;

    /**
     * @return \DateTime|null
     */
    public function createdAt() {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $time) {
        $this->createdAt = $time;
        $this->createdAt->setTimeZone(new \DateTimeZone('UTC'));
    }

    /**
     * @return string
     */
    public function parserVersion() {
        return $this->parserVersion;
    }

    /**
     * @return string
     */
    public function parserType() {
        return $this->parserType;
    }

    public function setParser($company, $version) {
        $this->parserType = $company;
        $this->parserVersion = $version;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary( $summary )
    {
        $this->summary = $summary;
    }

    public function link() {
        return $this->link;
    }

    public function setLink($value) {
        $this->link = $value;
    }

    public function action()
    {
        return $this->action;
    }

    public function setAction($value)
    {
        if (!in_array($value, [self::ACTION_CREATE, self::ACTION_RESOLVE])) {
            throw new \UnexpectedValueException();
        }
        $this->action = $value;
    }
<<<<<<< HEAD

    public function externalId()
    {
        return $this->externalId;
    }

    public function setExternalId($value)
    {
        return $this->externalId = $value;
    }
=======
>>>>>>> a81665121b1926cfa893a3823eb6f8048763da54

    /**
     * @return bool
     */
    public function isValid()
    {
        return  $this->summary !== null &&
                $this->createdAt !== null
            ;
    }

    public function jsonSerialize()
    {
        return [
            'action' => $this->action,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'summary' => $this->summary,
            'parserType' => $this->parserType,
            'parserVersion' => $this->parserVersion,
            'link' => $this->link,
            'externalId' => $this->externalId,
        ];
    }
}