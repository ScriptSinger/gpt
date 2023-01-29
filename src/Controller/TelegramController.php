<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Telegram\Bot\Api;

class TelegramController extends AbstractController
{
    #[Route('/', name: 'app_telegram')]
    public function index(): Response
    {
        $telegramBotToken = $_ENV['TELEGRAM_BOT_TOKEN'];


        $telegram = new Api($telegramBotToken);
        $updates = $telegram->getUpdates();
        foreach ($updates as $update) {
            $message = $update->getMessage();
            $text = $message->getText();
            $chatId = $message->getChat()->getId();
            if ($text == '/start') {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'I am a ChatGPT bot!'
                ]);
            }
        }
        return $this->render('');
    }
}
