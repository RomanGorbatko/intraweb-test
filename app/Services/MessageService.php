<?php
/**
 * Created by PhpStorm.
 * User: romangorbatko
 * Date: 6/15/17
 * Time: 12:19 AM
 */

namespace App\Services;

use App\Models\Subscription;
use App\User;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\Drivers\TelegramDriver;

/**
 * Class MessageService
 * @package App\Services
 */
class MessageService
{
    /**
     * @var BotMan
     */
    private $botMan;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->botMan = resolve('botman');
    }

    /**
     * @param string $message
     */
    public function batch(string $message)
    {
        $batch = Subscription::all();

        /** @var Subscription $item */
        foreach ($batch as $item) {
            $this->send($item, $message);
            sleep(1);
        }
    }

    /**
     * @param Subscription $subscription
     * @param string $message
     */
    public function send(Subscription $subscription, string $message)
    {
        $this->botMan->say($message, $subscription->chat_id, TelegramDriver::class);
    }
}