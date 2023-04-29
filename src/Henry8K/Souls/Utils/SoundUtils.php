<?php

namespace Henry8K\Souls\Utils;

use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class SoundUtils {

    public static function playSound(Player $player, string $soundName, float $volume = 1.0, float $pitch = 1.0): void {
        $pk = new LevelSoundEventPacket();
        $pk->sound = LevelSoundEventPacket::getSoundId($soundName);
        $pk->position = $player->getPosition();
        $pk->volume = $volume;
        $pk->pitch = $pitch;
        $player->getNetworkSession()->sendDataPacket($pk);
    }
}