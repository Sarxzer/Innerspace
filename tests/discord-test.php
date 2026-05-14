<?php
// test_webhook.php
require __DIR__ . '/../src/php/discord.php';

$webhook = new DiscordWebhook('https://discord.com/api/webhooks/910548850584989726/XTSS_6lvyrVGBr2YR40KbC3rVGP2VDs020DpH0pUFwnQHXk2SCCP8HMAXUokOWrwIovL');
$response = $webhook->sendMessage('hello from CLI 👋');
var_dump($response);

$testlog = $webhook->log('info', 'This is an info log message.');
var_dump($testlog);

$testlog2 = $webhook->log('error', 'This is an error log message.', [
    ['name' => 'Test Error', 'value' => 42, 'inline' => true,],
    ['name' => 'Details', 'value' => 'Something went wrong.', 'inline' => true,],
] ,true);
var_dump($testlog2);