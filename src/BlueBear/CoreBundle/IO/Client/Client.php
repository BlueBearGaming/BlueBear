<?php

namespace BlueBear\CoreBundle\IO\Client;

use Twig_Environment;

/**
 * Client helps manipulation of the node server
 */
class Client
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $serverUrl;

    /**
     * @var string
     */
    protected $chatId;

    public function __construct(Twig_Environment $twig, $serverUrl)
    {
        $this->twig = $twig;
        $this->serverUrl = $serverUrl;
        $this->chatId = uniqid('bluebear_chat_');
    }

    public function render()
    {
        return $this->twig->render('@BlueBearCore/IO/client.html.twig', [
            'serverUrl' => $this->serverUrl,
            'chatId' => $this->chatId
        ]);
    }

    public function renderChat()
    {
        return $this->twig->render('@BlueBearCore/IO/chat.html.twig', [
            'chatId' => $this->chatId
        ]);
    }
}
