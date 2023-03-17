<?php

namespace Henry8K\Souls;

use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use Henry8K\Souls\API\SoulsAPI;
use Henry8K\Souls\Events\PlayerAdd;
use Henry8K\Souls\Events\PlayerEvent;

use Henry8K\Souls\Commands\SoulsViewCommand;
use Henry8K\Souls\Commands\SoulsSellCommand;

class Main extends PluginBase implements Listener {

    /** @var Config */
    private Config $config;

    /** @var Config */
    private Config $souls;

    /** @var SoulsAPI */
    private SoulsAPI $soulsAPI;

    // ===================================
    //       GENERAL ENABLE FUNCTION
    // ===================================

    public function onEnable(): void {
        $this->saveDefaultConfig();

        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->souls = new Config($this->getDataFolder() . "souls.yml", Config::YAML);

        $this->getServer()->getPluginManager()->registerEvents(new PlayerAdd($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEvent($this), $this);

        $this->soulsAPI = new SoulsAPI($this);
        $this->getServer()->getCommandMap()->register($this->getDescription()->getName(), new SoulsViewCommand($this));
        $this->getServer()->getCommandMap()->register($this->getDescription()->getName(), new SoulsSellCommand($this));       
    }

    // ===================================
    //       GENERAL DISABLE FUNCTION
    // ===================================

    public function onDisable(): void {
        $this->souls->save();
    }

    // ===================================
    //      GET GENERAL API FUNCTION
    // ===================================

    public function getSoulsAPI(): SoulsAPI {
        return $this->soulsAPI;
    }

    // ===================================
    //        GET CONFIG FUNCTION
    // ===================================

    public function getConfig(): Config {
        return $this->config;
    }

    // ===================================
    //        GET SOULS FUNCTION
    // ===================================

    public function getSouls(): Config {
        return $this->souls;
    }
}