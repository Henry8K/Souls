<?php

namespace Henry8K\Souls\Utils;

use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class PluginUtils {

    public static function playSound(Player $player, string $soundName = " ", float $volume = 0, float $pitch = 0) {
        $pk = new PlaySoundPacket();
        $pk->x = $player->getPosition()->getX();
        $pk->y = $player->getPosition()->getY();
        $pk->z = $player->getPosition()->getZ();
        $pk->soundName = $soundName;
        $pk->volume = $volume;
        $pk->pitch = $pitch;
        $player->getNetworkSession()->sendDataPacket($pk);
    }
}