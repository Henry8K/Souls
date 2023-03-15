<?php

namespace Henry8K\Souls\Events;

use Henry8K\Souls\Main;
use pocketmine\utils\Config;
use pocketmine\event\Listener;

use pocketmine\player\Player;
use Henry8K\Souls\API\SoulsAPI;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerAdd implements Listener {

    /** @var Main */
    private $main;

    /** @var Config */
    private $config;
    
    /** @var Config */
    private $souls;

    /** @var SoulsAPI */
    private $soulsAPI;    

    //==============================
    //     LISTENER CONSTRUCTOR
    //==============================

    public function __construct(Main $main) {
        $this->main = $main;
        $this->config = $this->main->getConfig();
        $this->souls = $this->main->getSouls();
        $this->soulsAPI = new SoulsAPI($main);
    }

    //==============================
    //       JOIN PLAYER ADD
    //==============================    
    
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $playername = $player->getName();

        if(!isset($this->souls->getAll()["players"][$playername])) {
            $this->souls->setNested("players.$playername", 0);
            $this->souls->save();
        } 
    }

    //==============================
    //     QUIT PLAYER CHECKER
    //==============================    
    
    public function onQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        $playername = $player->getName();
        
        if($this->souls->exists("players." . $playername)) {
            $this->souls->setNested("players.". $playername, $this->soulsAPI->getSouls($player));
            $this->souls->save();
        }
    }
}