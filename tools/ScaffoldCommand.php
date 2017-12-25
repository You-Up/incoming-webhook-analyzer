<?php

namespace Webhook\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\Question;

class ScaffoldCommand extends Command
{
    const ROOT_PROVIDER_PATH = "src/Parsers";

    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    private $io = null;

    protected function configure()
    {
        $this
            ->setName( 'scaffold' )
            ->setDescription( 'Create the files necessary to handle a new Webhook.' )
            ->setHelp( 'This command creates the files necessary to add support of a new Webhook.' );
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('Webhook Scaffolder');

        $suggestions = $this->getProviderSuggestion();
        $question = new Question('What is the name of the provider?');
        $question->setAutocompleterValues($suggestions);

        $companyName = $this->io->askQuestion($question);
        $companyName = ucfirst($companyName);

        $path = self::ROOT_PROVIDER_PATH . DIRECTORY_SEPARATOR . $companyName;
        if (!in_array($companyName, $suggestions)) {
            mkdir($path);
            copy(
                implode(DIRECTORY_SEPARATOR, [__DIR__, 'templates', '_info.json']),
                $path.DIRECTORY_SEPARATOR .'_info.json'
            );
        }

        $this->createProviderTemplate($path, $companyName);

        $testDir = $this->createTestDir($path);
        $nameIncrement = count(scandir($testDir)) / 2;
        $nameIncrement = $nameIncrement < 10 ? "0".$nameIncrement : $nameIncrement;

        $this->createParsedResultTemplate($testDir, $companyName, $nameIncrement);
        $this->createRequestTemplate($testDir, $nameIncrement);

        $this->io->newLine();
        $this->io->text("Command finished with success. Please Read the Readme to know what to do next.");
    }

    private function getProviderSuggestion() {
        $iterator = new \FilesystemIterator(self::ROOT_PROVIDER_PATH, \FilesystemIterator::SKIP_DOTS);
        $suggestions = [];
        foreach ($iterator as $fileInfo) {
            $suggestions[] = $fileInfo->getFilename();
        }
        return $suggestions;
    }

    private function createTestDir($path) {
        $testDir = $path . DIRECTORY_SEPARATOR . "tests" . DIRECTORY_SEPARATOR;
        if (!file_exists($testDir)) {
            mkdir($testDir);
        }
        return $testDir;
    }

    private function createProviderTemplate($path, $companyName) {
        $nameIncrement = count(scandir($path)) - 1;
        $nameIncrement = $nameIncrement < 10 ? "0".$nameIncrement : $nameIncrement;

        $content = file_get_contents(implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            'templates',
            'provider.template'
        ]));

        $elements = [
            '@@companyName' => $companyName,
            '@@nameIncrement' => $nameIncrement,
            '@@date' => date('Y-m-d')
        ];
        foreach ($elements as $macro => $value) {
            $content = str_replace($macro, $value, $content);
        }

        $this->writeFile( implode(DIRECTORY_SEPARATOR, [
            self::ROOT_PROVIDER_PATH,
            $companyName,
            "{$companyName}_{$nameIncrement}.php"
        ]), $content);
    }

    private function createRequestTemplate($path, $nameIncrement) {
        $this->writeFile($path . "{$nameIncrement}_request.json", "{}");
    }

    private function createParsedResultTemplate($path, $companyName, $nameIncrement) {
        $this->writeFile($path . "{$nameIncrement}_results.json", json_encode([
            "action" => "wrong",
            "createdAt" => "",
            "externalId" => "@",
            "link" => null,
            "parserType" => $companyName,
            "parserVersion" => "0",
            "summary" => "",
            ], JSON_PRETTY_PRINT)
        );
    }

    private function writeFile($path, $content) {
        file_put_contents($path, $content);
        $this->io->text("Wrote file '$path'");
    }
}