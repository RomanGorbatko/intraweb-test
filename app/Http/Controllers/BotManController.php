<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use App\Models\Subscription;
use App\Services\DomainSSL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;
use Slack\Bot;

/**
 * Class BotManController
 * @package App\Http\Controllers
 */
class BotManController extends Controller
{
    /**
     * @param Request $request
     */
    public function handle(Request $request)
    {
        /** @var BotMan $botman */
        resolve('botman')->listen();
    }

    /**
     * @param BotMan $bot
     * @param string $domain
     * @return BotMan
     */
    public function checkDomain(BotMan $bot, string $domain): BotMan
    {
        $bot->typesAndWaits(2);

        try {
            $domainSSL = new DomainSSL($domain);

            return $bot->reply($domainSSL->getInfo());
        } catch (\Exception $exception) {
            return $bot->reply('Error! Check domain again.');
        }
    }

    /**
     * @param BotMan $bot
     * @return BotMan
     */
    public function subscribe(BotMan $bot): BotMan
    {
        $user = $bot->getUser();

        $subscription = Subscription::where([
            'chat_id' => $user->getId()
        ])->first();

        if (!$subscription instanceof Model) {
            $subscription = new Subscription();
            $subscription->chat_id = $user->getId();
            $subscription->username = $user->getUsername() ?: 'username_' . $user->getId();
            $subscription->save();

            return $bot->reply('Thanks for new subscribe');
        }

        return $bot->reply('You already have subscribed');
    }

    /**
     * @param BotMan $bot
     * @return BotMan
     */
    public function unsubscribe(BotMan $bot): BotMan
    {
        $user = $bot->getUser();

        $subscription = Subscription::where([
            'chat_id' => $user->getId()
        ])->first();

        if ($subscription instanceof Model) {
            $subscription->delete();

            return $bot->reply('Unsubscribe');
        }

        return $bot->reply('You are not subscribed');
    }
}
