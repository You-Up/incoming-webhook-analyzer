<?php

namespace WebhookParser;

use Illuminate\Http\Request;

class Main {
    static function run(Request $request) {
        $providerRootDir = __DIR__ . '/providers';
        $parserProviders = new ParserProvider($providerRootDir, true);
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