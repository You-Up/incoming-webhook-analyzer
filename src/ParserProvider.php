<?php

namespace WebhookParser;

class ParserProvider {

    private $rootPath = "";
    private $useFileSystem = false;

    public function __construct($path, $useFileSystem)
    {
        $this->rootPath      = $path;
        $this->useFileSystem = $useFileSystem;
    }

    public function getClassList() {
        $list = [];
        $this->traverseFiles(function($classWithNamespace) use (&$list) {
            $list[] = $classWithNamespace;
        });
        return $list;
    }

    private function traverseFiles(callable $executor){
        foreach (new \DirectoryIterator($this->rootPath) as $rootItem) {
            if ($rootItem->isDot() ) {
                continue;
            }

            if (!$rootItem->isDir()) {
                print "Error " . $rootItem->getPathname() . " is not a directory" . PHP_EOL;
                continue;
            }
            $providerName = $rootItem->getBasename();

            $providerDir = $this->rootPath . DIRECTORY_SEPARATOR .  $providerName;
            foreach (new \DirectoryIterator($providerDir) as $provider) {
                if ($rootItem->isDot() || $provider->getExtension() !== 'php') {
                    continue;
                }
                $className = $provider->getBasename('.' . $provider->getExtension());
                $classWithNamespace = "WebhookParser\Providers\\$providerName\\$className";
                call_user_func($executor, $classWithNamespace);
            }
        }
    }
}