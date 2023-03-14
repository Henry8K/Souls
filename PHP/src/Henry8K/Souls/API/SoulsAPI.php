<?php

namespace Henry8K\Souls\API;

use Henry8K\Souls\Main;
use pocketmine\utils\Config;
use pocketmine\player\Player;

class SoulsAPI {

    /** @var Main */
    private $main;

    /** @var Config */
    private $config;

    /** @var Config */
    private $souls;

    //==============================
    //     LISTENER CONSTRUCTOR
    //==============================     

    public function __construct(Main $main) {
        $this->main = $main;
        $this->config = $this->main->getConfig();
        $this->souls = $this->main->getSouls();
    }

    //==============================
    //        ADD SOULS API
    //==============================     

    public function addSouls(Player $player, int $amount) : int {
        $playername = $player->getName();
        $soulsAmount = $this->souls->getNested("players." . $playername);
        $newsouls = $soulsAmount + $amount;
        $this->souls->setNested("players." . $playername, $newsouls);
        $this->souls->save();

        return $newsouls;
    }

    //==============================
    //      REMOVE SOULS API
    //==============================     

    public function removeSouls(Player $player, int $amount) : int {
        $playername = $player->getName();
        $soulsAmount = $this->souls->getNested("players." . $playername);
        $newsouls = $soulsAmount - $amount;
        if($newsouls < 0) {
            $newsouls = 0;
        }
        $this->souls->setNested("players." . $playername, $newsouls);
        $this->souls->save();

        return $newsouls;
    }

    //==============================
    //       SET SOULS API
    //==============================     

    public function setSouls(Player $player, int $amount) : int {
        $playername = $player->getName();
        $this->souls->setNested("players." . $playername, $amount);
        $this->souls->save();

        return $amount;
    }

    //==============================
    //       GET SOULS API
    //==============================

    public function getSouls(Player $player) : int {
        $playername = $player->getName();
        return $this->souls->getNested("players." . $playername, 0);
    }    
}