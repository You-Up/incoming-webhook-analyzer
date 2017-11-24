<?php

namespace WebhookParser;

class WebhookIncident implements \JsonSerializable{
    /**
     * @var null|\DateTime
     */
    private $createdAt = null;

    /**
     * @var null|string
     */
    private $title = null;

    /** @var string */
    private $parserVersion = null;

    /** @var string */
    private $parserType = null;

    /** @var string */
    private $link = null;

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
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle( $title )
    {
        $this->title = $title;
    }

    public function link() {
        return $this->link;
    }

    public function setLink($value) {
        $this->link = $value;
    }


    /**
     * @return bool
     */
    public function isValid()
    {
        return  $this->title !== null &&
                $this->createdAt !== null
            ;
    }

    public function jsonSerialize()
    {
        return [
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'title' => $this->title,
            'parserType' => $this->parserType,
            'parserVersion' => $this->parserVersion,
            'link' => $this->link
        ];
    }
}