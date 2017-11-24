<?php

namespace Webhook\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\Question;

class ScaffoldCommand extends Command
{
    const ROOT_PROVIDER_PATH = "src/Providers";

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
        }

        $nameIncrement = $this->detectFileCount($path);

        $this->createProviderTemplate($companyName, $nameIncrement);
        $this->createParsedResultTemplate($companyName, $nameIncrement);
        $this->createRequestTemplate($companyName, $nameIncrement);

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

    private function detectFileCount($path) {
        $iterator = new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS);
        $count = 1;
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->getExtension() === 'php') {
                $count++;
            }
        }
        if ($count < 10) {
            $count = "0" . $count;
        }

        return "" . $count;
    }

    private function createProviderTemplate($companyName, $nameIncrement) {
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

    private function createRequestTemplate($companyName, $nameIncrement) {
        $this->writeFile( implode(DIRECTORY_SEPARATOR, [
            self::ROOT_PROVIDER_PATH,
            $companyName,
            "{$companyName}_{$nameIncrement}_test_request.json"
        ]), "{}");
    }

    private function createParsedResultTemplate($companyName, $nameIncrement) {
        $this->writeFile( implode(DIRECTORY_SEPARATOR, [
            self::ROOT_PROVIDER_PATH,
            $companyName,
            "{$companyName}_{$nameIncrement}_parsed_results.json"
        ]), json_encode([
            "createdAt" => "",
            "title" => "",
            "parserType" => $companyName,
            "parserVersion" => $nameIncrement,
            "link" => null,
        ], JSON_PRETTY_PRINT));
    }

    private function writeFile($path, $content) {
        file_put_contents($path, $content);
        $this->io->text("Wrote file '$path'");
    }
}