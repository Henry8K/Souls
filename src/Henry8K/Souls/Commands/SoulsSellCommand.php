<?php

namespace Henry8K\Souls\Commands;

use Henry8K\Souls\Main;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use Henry8K\Souls\API\SoulsAPI;
use Henry8K\Souls\Utils\PluginUtils;

use Vecnavium\FormsUI\Form;
use davidglitch04\libEco\libEco;
use Vecnavium\FormsUI\SimpleForm;

class SoulsSellCommand extends Command {

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

        parent::__construct($this->config->get("souls-sell-command-name"));
        $this->setDescription($this->config->get("souls-sell-command-description"));
        $this->setPermission("souls.sell.command");
        $this->setAliases(["soulss", "ss"]);
    }

    //==============================
    //     COMMAND EXECUTION
    //==============================
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if(!$sender instanceof Player) {
            $sender->sendMessage($this->config->get("in-game-message"));
            return true;
        }

        if(!$sender->hasPermission("souls.sell.command")) {
            $sender->sendMessage($this->config->get("no-perms-message"));
            return true;
        }

        if(!$this->config->get("souls-sell-command")) {
            $sender->sendMessage($this->config->get("command-not-available"));
        } else {
            $this->opensellUI($sender);
        }
        return true;
    }

    //==============================
    //        FORM GENERATOR
    //==============================

    public function opensellUI(Player $player): void {
        $form = new SimpleForm(function(Player $player, ?int $data) {
            if($data === null) {
                return;
            }

            switch($data) {
                case 0:
                    $soulprice = $this->config->get("general-price-per-soul");
                    $souls = $this->soulsAPI->getSouls($player);
                    $price = $souls * $soulprice;

                    if($this->config->get("general-souls-sell-mode") == 1) {
                        if($price > 0) {
                            libEco::addMoney($player, $price);
                            $this->soulsAPI->setSouls($player, 0);
							$player->sendMessage($this->config->get("message-success-when-selling"));
                        } else {
							$player->sendMessage($this->config->get("message-not-enough-souls"));
						}
                    } else {
                        if($price > 0) {
                            $player->getXpManager()->addXpLevels($price);
                            $this->soulsAPI->setSouls($player, 0);
							$player->sendMessage($this->config->get("message-success-when-selling"));
                        
                        } else {
                            $player->sendMessage($this->config->get("message-not-enough-souls"));
                        }
                    }
                break;

                case 1:
                    PluginUtils::playSound($player, "random.pop", 1, 1);
                break;
            }
        });

        $content = $this->config->get("form-souls-sell-description");
        $playersouls = $this->soulsAPI->getSouls($player);
        $playername = $player->getName();
        
        $content = str_replace("{player_souls}", $playersouls, $content);
        $content = str_replace("{player_name}", $playername, $content);
        
        $form->setTitle($this->config->get("form-souls-sell-title"));
        $form->setContent($content);
        $form->addButton($this->config->get("form-souls-sell-button"));
        $form->addButton($this->config->get("form-souls-sell-exit-button"));
        
        $player->sendForm($form);        
    }
}