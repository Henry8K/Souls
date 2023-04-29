<?php

namespace Henry8K\Souls\API;

use Henry8K\Souls\Main;
use pocketmine\utils\Config;
use pocketmine\player\Player;

class SoulsAPI {

    private Config $data;
    private array $players;

    //==================
    //    CONSTRUCTOR
    //==================
    // Creates the basis for some parts of the API

    public function __construct(Main $main) {
        $this->data = $main->getData();
        $this->players = $this->data->getNested("players", []);
    }

    //==================
    //    GET SOULS
    //==================
    // Searches and checks the number of Souls of the player.

    public function getSouls(Player $player): int {
        return $this->players[$player->getName()]["souls"] ?? 0;
    }
    
    //==================
    //    SET SOULS
    //==================
    // Basis for setting a number of souls for players.

    public function setSouls(Player $player, int $amount): void {
        $name = $player->getName();
        $this->players[$name]["souls"] = $amount;
        $this->data->setNested("players.$name.souls", $amount);
        $this->data->save();
    }

    //==================
    //    ADD SOULS
    //==================
    // Function to add souls for a certain player.

    public function addSouls(Player $player, int $amount): void {
        $newSouls = $this->getSouls($player) + $amount;
        $this->setSouls($player, $newSouls);
    }

    //==================
    //   REMOVE SOULS
    //==================
    // Function to remove souls from player.

    public function removeSouls(Player $player, int $amount): void {
        $newSouls = max($this->getSouls($player) - $amount, 0);
        $this->setSouls($player, $newSouls);
    }

    //==================
    //    RESET SOULS
    //==================
    // Function to reset all players souls.

    public function resetSouls(): void {
        foreach ($this->players as $player => $data) {
            $this->setSouls($player, 0);
        }
    }

    //==================
    //  TRANSFER SOULS
    //==================
    // Function to transfer souls from a player to another.

    public function transferSouls(Player $fromPlayer, Player $toPlayer, int $amount): bool {
        $fromSouls = $this->getSouls($fromPlayer);
    
        if($fromSouls < $amount) {
            return false;
        }
    
        $toSouls = $this->getSouls($toPlayer);
        $this->removeSouls($fromPlayer, $amount);
        $this->addSouls($toPlayer, $amount);   
        return true;
    }
    
    //==================
    //   GET TOP SOULS
    //==================
    // Get the top server players with more souls.

    public function getTopSouls(int $limit = 20): array {
        $playersBySouls = $this->players;
        uasort($playersBySouls, static function (array $a, array $b): int {
            return $b["souls"] <=> $a["souls"];
        });
    
        $topPlayers = array_keys(array_slice($playersBySouls, 0, $limit, true));
        return $topPlayers;
    }
}