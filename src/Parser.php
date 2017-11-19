<?php

namespace WebhookParser;

use Illuminate\Http\Request;

abstract class Parser {

    /**
     * @var null|\Illuminate\Http\Request
     */
    protected $request = null;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function companyName() {
        $reflect = new \ReflectionClass($this);
        $name = $reflect->getShortName();
        return explode('_', $name)[0];
    }

    public function version() {
        $reflect = new \ReflectionClass($this);
        $name = $reflect->getShortName();
        return explode('_', $name)[1];
    }

    /**
     * @return bool
     */
    abstract public function isMatch();

    /**
     * @return \WebhookParser\WebhookIncident
     */
    abstract public function extract();
}