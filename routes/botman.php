<?php

use App\Http\Controllers\BotManController;
use Mpociot\BotMan\BotMan;

// Don't use the Facade in here to support the RTM API too :)
$botman = resolve('botman');

$botman->hears('check {domain}', BotManController::class . '@checkDomain');
$botman->hears('/subscribe', BotManController::class . '@subscribe');
$botman->hears('/unsubscribe', BotManController::class . '@unsubscribe');

$botman->fallback(function(BotMan $bot) {
    $bot->types();
    $bot->reply('Sorry, I did not understand these commands. Please retype again...');
});