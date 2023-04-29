<?php

namespace Henry8K\Souls\Events;

use Henry8K\Souls\Main;
use Henry8K\Souls\API\SoulsAPI;

use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerEvent implements Listener {

    private Config $data;
    private SoulsAPI $API;
    private array $players;
    private Config $config;

    //==================
    //   CONSTRUCTOR
    //==================
    // The plugin's base constructor used in events.

    public function __construct(Main $main) {
        $this->API = $main->getAPI();
        $this->data = $main->getData();
        $this->config = $main->getConfig();
        $this->players = $this->data->getNested("players", []);
    }

    //==================
    //      EVENTS
    //==================
    // Events that manage player interactions on the server.

    public function onBreak(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $this->API->addSouls($player, 1);
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $playerName = $event->getPlayer()->getName();
        if(!$this->hasPlayerData($playerName)) {
            $this->setPlayerData($playerName, ["souls" => 0, "level" => 0]);
        }
    }

    public function onDisable(): void {
        $this->data->setNested("players", $this->players);
        $this->data->save();
    }

    //==================
    //     UTILITY
    //==================
    // Private event functions managers.

    private function hasPlayerData(string $playerName): bool {
        return isset($this->players[$playerName]["souls"], $this->players[$playerName]["level"]);
    }

    private function setPlayerData(string $playerName, array $data): void {
        $this->players[$playerName] = $data;
        $this->data->setNested("players.$playerName", $data);
        $this->data->save();
    }
}