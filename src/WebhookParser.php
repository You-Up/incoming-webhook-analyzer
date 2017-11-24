<?php

namespace WebhookParser;

use Illuminate\Http\Request;

class Main {
    /**
     * @param \Illuminate\Http\Request $request
     * @return null|\WebhookParser\WebhookIncident
     */
    static function run(Request $request, $errorCallback = null) {
        $parserProviders = new ParserProvider(true);
        foreach ($parserProviders->getClassList() as $providerClass) {
            try {
                /** @var \WebhookParser\Parser $cls */
                $cls = new $providerClass( $request );

                if ($cls->isMatch()) {
                    $incident = $cls->extract();
                    $incident->setParser( $cls->companyName(), $cls->version() );

                    return $incident;
                }
            } catch(\Exception $e) {
                if (is_callable($errorCallback) ) {
                    $errorCallback($e);
                }
            }
        }
        return null;
    }
}