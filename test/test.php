<?php
declare(strict_types=1);

$runInstances = (int) ($argv[1] ?? 3);
$numberOfTriesToFindServices = (int) ($argv[2] ?? 40);
$consulUrl = $argv[3] ?? 'http://consul:8500';
$nginxUrl = $argv[4] ?? 'http://nginx';

// Wait for register all services in consul
\sleep(20);

$content = \file_get_contents(
    \sprintf('%s/v1/agent/services', $consulUrl)
);
$services = \json_decode($content, true);

$registerServices = [];
$foundedServices = [];
foreach ($services as $service) {
    $registerServices[$service['ID']] = $service['Address'];
    $foundedServices[$service['ID']] = false;
}

$countRegisterServices = \count($registerServices);
if ($countRegisterServices !== $runInstances) {
    throw new \Exception(
        \sprintf('Some PHP services is not running. Running %d expected %d.', $countRegisterServices, $runInstances)
    );
}

$isScalable = false;
$i = 1;
while ($i <= $numberOfTriesToFindServices) {
    echo \sprintf('Iteration %d from %d', $i, $numberOfTriesToFindServices).PHP_EOL;

    $data = getIndexPage($nginxUrl);
    if (isset($foundedServices[$data['containerId']])) {
        $foundedServices[$data['containerId']] = true;
    }

    if (checkIfServicesAreScalable($runInstances, $foundedServices)) {
        $isScalable = true;
        break;
    }

    ++$i;
}

if (!$isScalable) {
    throw new \Exception('Service is not scalable');
}

function getIndexPage(string $url): array 
{
    $ch = \curl_init(); 
    \curl_setopt($ch, CURLOPT_URL, $url); 
    \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $output = \curl_exec($ch); 
    \curl_close($ch);    
    
    $data = \json_decode($output, true);
    if (!isset($data['containerId']) && !isset($data['containerIP']) && !isset($data['status'])) {
        throw new \Exception('Wrong response from PHP service.');
    }

    return $data;
}

function checkIfServicesAreScalable(int $runInstances, array $services): bool
{
    $countFoundedServices = \count(\array_filter($services));
    if ($countFoundedServices !== $runInstances) {
        return false;
    }

    return true;
}