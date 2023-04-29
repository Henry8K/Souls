<?php

namespace Henry8K\Souls;

use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;

use Henry8K\Souls\API\SoulsAPI;
use Henry8K\Souls\Events\PlayerEvent;

class Main extends PluginBase {

    private Config $data;
    private SoulsAPI $API;
    private Config $config;
    private Config $messages;

    //==================
    //      ENABLE
    //==================
    // loads the plugin configuration and sets up the event listener.

    public function onEnable(): void {
        $this->loadConfig();
        $this->loadEvent();
    }

    //==================
    //     CONFIGS
    //==================
    // This function loads the plugin configuration files.

    private function loadConfig(): void {
        $this->saveDefaultConfig();
        
        $this->saveResource("data.yml");
        $this->saveResource("config.yml");
        $this->saveResource("messages.yml");
        
        $this->data = new Config($this->getDataFolder() . "data.yml", Config::YAML);
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->messages = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
    }

    //==================
    //      EVENTS
    //==================
    // This function sets up the event listener.

    private function loadEvent(): void {
        $this->API = new SoulsAPI($this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEvent($this), $this);
    }

    //==================
    //   GET FUNCTIONS
    //==================
    // These functions return a reference to the plugin configuration files or API'S.

    public function getData(): Config {
        return $this->data;
    }

    public function getConfig(): Config {
        return $this->config;
    }

    public function getMessages(): Config {
        return $this->messages;
    }

    public function getAPI(): SoulsAPI {
        return $this->API;
    }
}