<?php
require_once "./vendor/autoload.php";
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
// init service container
$containerBuilder = new ContainerBuilder();

// add service into the service container
//$containerBuilder->register("services.parser", Services\Parser\ParserService::class);
// fetch service from the service container

$loaderConfig = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
$loaderConfig->load('config/settings.global.yaml');
/**
 * @var $parserService Services\Parser\IParserService
 */
$parserService = $containerBuilder->get("service.parser");
$short_options = "f:";
$long_options = ["filename:"];
$options = getopt($short_options, $long_options);

$fileName = "data/data_light.xml";

if(isset($options["f"]) || isset($options["filename"])) {
    $fileName = $options["f"] ?? $options["filename"];
}
try {
    $importResult = $parserService->processFile($fileName);
    echo "Импорт файла `$fileName`".PHP_EOL;
    foreach ($importResult as $row){
        echo $row["title"], " : ", $row['count'], PHP_EOL;
    }
} catch (Throwable $throwable){
    echo $throwable->getMessage().PHP_EOL;
}