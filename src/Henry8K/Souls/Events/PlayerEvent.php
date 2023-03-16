<?php

namespace Henry8K\Souls\Events;

use Henry8K\Souls\Main;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use Henry8K\Souls\API\SoulsAPI;

use pocketmine\player\Player;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class PlayerEvent implements Listener {

    /** @var Main */
    private Main $main;

    /** @var Config */
    private Config $config;

    /** @var SoulsAPI */
    private SoulsAPI $soulsAPI;

    //==============================
    //     LISTENER CONSTRUCTOR
    //==============================   

    public function __construct(Main $main) {
        $this->main = $main;
        $this->config = $this->main->getConfig();
        $this->soulsAPI = new SoulsAPI($main);
    }

    //==============================
    //     PLAYER KILL EVENT ADD
    //==============================       

    public function onDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        $cause = $player->getLastDamageCause();
        $worldName = $player->getWorld()->getFolderName();

        if(!in_array($worldName, $this->config->get("souls-give-worlds", []))) {
            return true;
        }

        if($cause instanceof EntityDamageByEntityEvent) {
            $killer = $cause->getDamager();
            if($killer instanceof Player) {
                $killername = $killer->getName();
                $amount = $this->config->get("souls-per-player-kill");
                $this->soulsAPI->addSouls($killername, $amount);
            }
        }
    }
}