<?php

namespace Henry8K\Souls\Commands;

use Henry8K\Souls\Main;
use pocketmine\utils\Config;
use Henry8K\Souls\API\SoulsAPI;
use Henry8K\Souls\Utils\PluginUtils;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SoulsViewCommand extends Command {

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
        $this->config = $this->main->getConfig();
        $this->soulsAPI = new SoulsAPI($main);

        parent::__construct($this->config->get("souls-view-command-name"));
        $this->setDescription($this->config->get("souls-view-command-description"));
        $this->setPermission("souls.view.command");
    }

    //==============================
    //     COMMAND EXECUTION
    //==============================
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if(!$sender instanceof Player) {
            $sender->sendMessage($this->config->get("in-game-message"));
            return true;
        }

        if(!$sender->hasPermission("souls.view.command")) {
            $sender->sendMessage($this->config->get("no-perms-message"));
            return true;
        }

        if(!$this->config->get("souls-view-command")) {
            $sender->sendMessage($this->config->get("command-not-available"));
        } else {
            $playersouls = $this->soulsAPI->getSouls($sender);
            $playername = $sender->getName();
            $sender->sendMessage(str_replace(["{player_souls}", "{player_name}"], [$playersouls, $playername], $this->config->get("souls-view-message-command")));
        }
        return true;
    }
}