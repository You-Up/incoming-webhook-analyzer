<?php

namespace WebhookParser;

use Illuminate\Http\Request;

class Main {
    /**
     * @param \Illuminate\Http\Request $request
     * @return null|\WebhookParser\WebhookIncident
     */
    static function run(Request $request) {
        $parserProviders = new ParserProvider(true);
        foreach ($parserProviders->getClassList() as $providerClass) {
            /** @var \WebhookParser\Provider $cls */
            $cls = new $providerClass($request);

            if ($cls->isMatch()) {
                return $cls->extract();
            }
        }
        return null;
    }
}