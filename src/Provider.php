<?php

namespace WebhookParser;

use Illuminate\Http\Request;

abstract class Provider {

    /**
     * @var null|\Illuminate\Http\Request
     */
    protected $request = null;

    public function __construct($request)
    {
        $this->request = $request;
    }

    abstract public function isMatch();
    abstract public function extract();
}