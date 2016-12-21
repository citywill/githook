<?php
/**
 * windows 下的 git hook
 */

function hookLog($logPath = 'logs', $project = null, $cmd = null, $outputArr = array())
{
    $client_ip = $_SERVER['REMOTE_ADDR'];

    $projectPath = $project ? '/' . $project : '';

    $logPath = $logPath . $projectPath;

    if (!is_dir($logPath)) {
        mkdir($logPath);
    }

    $logFile = $logPath . '/' . date('Y-m-d') . '.log';

    $fs = fopen($logFile, 'a');

    fwrite($fs, 'Request on [' . date("Y-m-d H:i:s") . '] from [' . $client_ip . ']' . PHP_EOL);

    $json = file_get_contents('php://input');

    $data = json_decode($json, true);

    fwrite($fs, 'Data: ' . print_r($data, true) . PHP_EOL);

    fwrite($fs, 'CMD: ' . print_r($cmd, true) . PHP_EOL);

    fwrite($fs, 'Output: ' . print_r($outputArr, true) . PHP_EOL);

    fwrite($fs, '===========' . PHP_EOL);

    $fs and fclose($fs);
}

$root = 'D:/wamp/www';

$project = $_GET['project'];
$branch = isset($_GET['branch']) ? $_GET['branch'] : 'develop';

$projectPath = $root . '/' . $project;

$cmd = 'D: cd ' . $projectPath . ' && git pull origin ' . $branch;

exec($cmd, $outputArr);

hookLog('logs', $project, $cmd, $outputArr);
