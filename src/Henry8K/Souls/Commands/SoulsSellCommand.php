<?php

namespace Henry8K\Souls\Commands;

use Henry8K\Souls\Main;
use Henry8K\Souls\API\SoulsAPI;
use Henry8K\Souls\Utils\PluginUtils;

use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\CommandSender;

use Vecnavium\FormsUI\Form;
use davidglitch04\libEco\libEco;
use Vecnavium\FormsUI\SimpleForm;

class SoulsSellCommand extends Command implements PluginOwned {

    /** @var Main */
    private Main $main;

    /** @var Config */
    private Config $config;

    /** @var SoulsAPI */
    private SoulsAPI $soulsAPI;

    //==============================
    //     COMMAND CONSTRUCTOR
    //==============================    

    public function __construct(Main $main) {
        $this->main = $main;
        $this->config = $main->getConfig();
        $this->soulsAPI = new SoulsAPI($main);

        parent::__construct($this->config->get("souls-sell-command-name"));
        $this->setDescription($this->config->get("souls-sell-command-description"));
        $this->setPermission("souls.sell.command");
    }

    //==============================
    //       COMMAND EXECUTION
    //==============================    

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if(!$sender instanceof Player) {
            $sender->sendMessage($this->config->get("in-game-message"));
            return true;
        }

        if(!$sender->hasPermission($this->getPermission())) {
            $sender->sendMessage($this->config->get("no-perms-message"));
            return true;
        }

        if(!$this->config->get("souls-sell-command")) {
            $sender->sendMessage($this->config->get("command-not-available"));
            return true;
        }

        $this->openSellUI($sender);
        return true;
    }

    //==============================
    //       FORM CONSTRUCTOR
    //==============================

    private function openSellUI(Player $player): void {
        $form = new SimpleForm(function(Player $player, ?int $data): void {
            if ($data === null) {
                return;
            }

            switch ($data) {
                case 0:
                    $soulPrice = $this->config->get("general-price-per-soul");
                    $souls = $this->soulsAPI->getSouls($player);
                    $price = $souls * $soulPrice;

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
        $playerSouls = $this->soulsAPI->getSouls($player);
        $playerName = $player->getName();
        
        $content = str_replace("{player_souls}", $playerSouls, $content);
        $content = str_replace("{player_name}", $playerName, $content);
        
        $form->setTitle($this->config->get("form-souls-sell-title"));
        $form->setContent($content);
        $form->addButton($this->config->get("form-souls-sell-button"));
        $form->addButton($this->config->get("form-souls-sell-exit-button"));
        
        $player->sendForm($form);
    }

    //==============================
    //        PLUGIN OWNED
    //==============================

    public function getOwningPlugin(): Main {
        return $this->main;
    }    
}