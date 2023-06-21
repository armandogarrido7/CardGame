<?php
namespace App\Service;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MercureService
{
    private HubInterface $hub;
    private const BASE_TOPIC_URL = 'https://card-game.com';
    public const GAME_STATE = '/game/state/';
    public const GAME_LIST = '/game/list';
    public const UPDATE_ACCOUNT = '/account/update/';
    public function __construct(HubInterface $hub)
    {
        $this->hub = $hub;
    }

    public function publishUpdate(string $partial_topic, string $data):void
    {
        $topic = MercureService::BASE_TOPIC_URL.$partial_topic;
        $update = new Update($topic, $data);
        $this->hub->publish($update);
    }
}
