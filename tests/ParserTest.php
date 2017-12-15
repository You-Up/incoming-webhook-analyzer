<?php
use PHPUnit\Framework\TestCase;

use \WebhookParser\ParserProvider;

class ParserTest extends TestCase
{
    public function testAll()
    {
        $parserProviders = new ParserProvider(true);
        $parserProviders->forEachParserProvider(function($companyName, $providerDir) {
            $testDir = $providerDir . DIRECTORY_SEPARATOR . "tests" . DIRECTORY_SEPARATOR;
            $counter = 1;
            $cStr = "";
            print $testDir.PHP_EOL;
            while(true) {
                if ($counter < 10) {
                    $cStr = "0" . $counter;
                }

                if (!file_exists($testDir . $cStr . "_request.json")) {
                    break;
                }
                $counter++;

                $this->setName($companyName . "_" . $cStr);

                $testRequest = file_get_contents($testDir . $cStr . '_request.json');
                $testRequest = json_decode($testRequest, true);

                $testResults = file_get_contents($testDir . $cStr . '_results.json');
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
                $this->assertInstanceOf(\WebhookParser\WebhookIncident::class, $parsedIncident);

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
            }
        });
    }

    public function testEmpty() {
        $fakeRequest = new \Illuminate\Http\Request();

        $parsedIncident = WebhookParser\Main::run($fakeRequest);
        $this->assertNull($parsedIncident);
    }
}
