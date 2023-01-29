<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Telegram\Bot\Api;

class TelegramBotController extends AbstractController
{
    /**
     * @Route("/telegram-bot/handle-message", name="telegram_bot_handle_message")
     */
    #[Route('/', name: 'app_telegram')]
    public function handleMessage(Request $request)
    {
        $telegramBotToken = $_ENV['TELEGRAM_BOT_TOKEN'];
        $apiKey = $_ENV['API_KEY'];
        $bot = new Api($telegramBotToken);
        $message = $request->get('message');
        $chatId = $request->get('chat_id');

        // send message text to gpt3 api
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/engines/davinci/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'prompt' => $message,
                'temperature' => 0.5,
                'max_tokens' => 100,
            ],
        ]);

        $gpt3Response = json_decode($response->getBody()->getContents(), true);

        // send gpt3 response to telegram
        $bot->sendMessage($chatId, $gpt3Response['choices'][0]['text']);

        return new Response();
    }
}
