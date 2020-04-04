<?php
declare(strict_types=1);

\header('Content-Type: application/json');
echo \json_encode(
    [
        'containerId' => \exec('echo $CONTAINER_ID'),
        'containerIP' => \exec('echo $CONTAINER_IP'),
        'status' => 'OK',
    ]
);

exit;