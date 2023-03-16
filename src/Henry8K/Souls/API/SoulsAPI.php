<?php

namespace Henry8K\Souls\API;

use Henry8K\Souls\Main;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class SoulsAPI {

    /** @var Main */
    private Main $main;

    /** @var Config */
    private Config $config;

    /** @var Config */
    private Config $souls;

    //==============================
    //     LISTENER CONSTRUCTOR
    //==============================    

    public function __construct(Main $main) {
        $this->main = $main;
        $this->config = $this->main->getConfig();
        $this->souls = $this->main->getSouls();
    }

    //==============================
    //      ADD SOULS FUNCTION
    //==============================    

    public function addSouls(Player $player, int $amount): int {
        $playerName = $player->getName();
        $soulsAmount = $this->souls->getNested("players.$playerName", 0);
        $newSouls = $soulsAmount + $amount;
        $this->souls->setNested("players.$playerName", $newSouls);
        $this->souls->save();

        return $newSouls;
    }

    //==============================
    //    REMOVE SOULS FUNCTION
    //==============================    

    public function removeSouls(Player $player, int $amount): int {
        $playerName = $player->getName();
        $soulsAmount = $this->souls->getNested("players.$playerName", 0);
        $newSouls = max($soulsAmount - $amount, 0);
        $this->souls->setNested("players.$playerName", $newSouls);
        $this->souls->save();

        return $newSouls;
    }

    //==============================
    //      SET SOULS FUNCTION
    //==============================    

    public function setSouls(Player $player, int $amount): int {
        $playerName = $player->getName();
        $this->souls->setNested("players.$playerName", $amount);
        $this->souls->save();

        return $amount;
    }

    //==============================
    //      GET SOULS FUNCTION
    //==============================    

    public function getSouls(Player $player): int {
        $playerName = $player->getName();
        return $this->souls->getNested("players.$playerName", 0);
    }    
}