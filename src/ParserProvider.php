<?php

namespace WebhookParser;

class ParserProvider {

    private $rootPath = "";
    private $useFileSystem = false;

    public function __construct($useFileSystem)
    {
        $this->rootPath      = __DIR__ . DIRECTORY_SEPARATOR . "Parsers";
        $this->useFileSystem = $useFileSystem;
    }

    public function getClassList() {
        $list = [];
        $this->forEachParser(function($classWithNamespace) use (&$list) {
            $list[] = $classWithNamespace;
        });
        return $list;
    }

    public function forEachParserProvider(callable $callback) {
        foreach (new \DirectoryIterator($this->rootPath) as $rootItem) {
            if ($rootItem->isDot() ) {
                continue;
            }

            $companyName = $rootItem->getBasename();
            if ($companyName === ".DS_Store") {
                continue;
            }

            if (!$rootItem->isDir()) {
                print "Error " . $rootItem->getPathname() . " is not a directory" . PHP_EOL;
                continue;
            }
            $providerDir = $this->rootPath . DIRECTORY_SEPARATOR .  $companyName;
            $callback($companyName, $providerDir);
        }
    }

    public function forEachParser(callable $executor){
        $this->forEachParserProvider(function($companyName, $providerDir) use ($executor) {
            foreach (new \DirectoryIterator($providerDir) as $parser) {
                if ($parser->isDot() || $parser->getExtension() !== 'php') {
                    continue;
                }
                $parserName = $parser->getBasename('.php');
                $namespacedCls = "WebhookParser\Parsers\\$companyName\\$parserName";

                call_user_func($executor,
                    $namespacedCls,
                    $parser,
                    $companyName,
                    $parserName);
            }
        });
    }
}