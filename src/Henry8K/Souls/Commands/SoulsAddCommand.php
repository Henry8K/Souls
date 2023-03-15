<?php

namespace Henry8K\Souls\Commands;

use Henry8K\Souls\Main;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use Henry8K\Souls\API\SoulsAPI;
use Henry8K\Souls\Utils\PluginUtils;

class SoulsAddCommand extends Command {

    /** @var Main */
    private $main;

    /** @var Config */
    private $config;

    /** @var SoulsAPI */
    private $soulsAPI;    

    //==============================
    //     COMMAND CONSTRUCTOR
    //==============================

    public function __construct(Main $main) {
        $this->main = $main;
        $this->config = $this->main->getPluginConfig();
        $this->soulsAPI = new SoulsAPI($main);

        parent::__construct($this->config->get("souls-add-command-name"));
        $this->setDescription($this->config->get("souls-add-command-description"));
        $this->setPermission("souls.manage.command");
    }
}