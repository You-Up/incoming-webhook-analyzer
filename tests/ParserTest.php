<?php
use PHPUnit\Framework\TestCase;

use \WebhookParser\ParserProvider;

class ParserTest extends TestCase
{
    public function testAll()
    {
        $parserProviders = new ParserProvider(true);
        $parserProviders->forEachParser(function ($clsWithNameSpace, $parserInfo, $companyName, $parserName) {
            $this->setName($parserName);

            // Load the saved value
            $testRequest = file_get_contents($parserInfo->getPath() . DIRECTORY_SEPARATOR . $parserName . '_test_request.json');
            $testRequest = json_decode($testRequest, true);

            $testResults = file_get_contents($parserInfo->getPath() . DIRECTORY_SEPARATOR . $parserName . '_parsed_results.json');
            $testResults = json_decode($testResults, true);

            $fakeRequest = new \Illuminate\Http\Request(
                $testRequest['get'],
                $testRequest['post'],
                [],
                [],
                [],
                $testRequest['server']
            );

            $parsedIncident = WebhookParser\Main::run($fakeRequest);
            $jsonIncident = $parsedIncident->jsonSerialize();

            $jsonIncidentKeys = array_keys($jsonIncident);
            $testResultsKeys  = array_keys($testResults);

            foreach($jsonIncidentKeys as $key) {
                $this->assertArrayHasKey($key, $testResults, "Parsed value has an extra key '$key' ");
            }

            foreach($testResultsKeys as $key) {
                $this->assertArrayHasKey($key, $jsonIncident, "Test parsed value has an extra key '$key' ");
            }

            foreach ($jsonIncidentKeys as $key) {
                $this->assertEquals($testResults[$key], $jsonIncident[$key]);
            }
        });
    }
}
