<?php
use PHPUnit\Framework\TestCase;

use \WebhookParser\ParserProvider;

class InfoTest extends TestCase
{
    /**
     * @dataProvider allProvider
     */
    public function testAll($companyName, $filePath)
    {
        $c = file_get_contents($filePath);
        $info = json_decode($c, true);
        $this->assertNotNull($info);
        $this->assertTrue(count($info) > 0);

        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('homepage_url', $info);
        $this->assertArrayHasKey('description', $info);
        $this->assertArrayHasKey('documentation_url', $info);
        $this->assertArrayHasKey('favicon', $info);

        $this->assertArrayHasKey('icon_svg_url', $info);

        $this->assertArrayHasKey('feature', $info);
        $this->assertArrayHasKey('create', $info['feature']);
        $this->assertArrayHasKey('close', $info['feature']);
    }

    public function allProvider()
    {
        $data = [];
        $parserProviders = new ParserProvider(true);
        $parserProviders->forEachParserProvider(function($companyName, $providerDir) use (&$data){
            $infoFile = $providerDir . DIRECTORY_SEPARATOR . "_info.json";
            $data[] = [$companyName, $infoFile];
        });
        return $data;
    }
}
