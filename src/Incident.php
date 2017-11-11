<?php

namespace WebhookParser;

class Incident implements \JsonSerializable{
    /**
     * @var null|\DateTime
     */
    private $createdAt = null;

    /**
     * @var null|string
     */
    private $service = null;

    /**
     * @var null|string
     */
    private $title = null;

    public function createdAt() {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $time) {
        $this->createdAt = $time;
        $this->createdAt->setTimeZone(new \DateTimeZone('UTC'));
    }

    /**
     * @return null|string
     */
    public function service()
    {
        return $this->service;
    }

    /**
     * @param null|string $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle( $title )
    {
        $this->title = $title;
    }


    public function isValid() {
        return  $this->title !== null &&
                $this->createdAt !== null
            ;
    }

    public function jsonSerialize()
    {
        return [
            'serviceId' => $this->service,
            'createdAt' => $this->createdAt->format('c'),
            'title' => $this->title,
        ];
    }
}