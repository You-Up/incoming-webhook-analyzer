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

    private $parserVersion = null;
    private $parserType = null;

    public function createdAt() {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $time) {
        $this->createdAt = $time;
        $this->createdAt->setTimeZone(new \DateTimeZone('UTC'));
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
        ];
    }
}