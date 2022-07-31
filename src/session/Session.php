<?php

declare(strict_types=1);

namespace Aboshxm2\AntiSpam\session;

use Aboshxm2\AntiSpam\Main;
use Aboshxm2\AntiSpam\Punishment;
use pocketmine\player\Player;

class Session
{
    public int $warns = 0;
    public int $lastWarnTime = 0;
    public int $lastChatTime = 0;

    public function __construct(
        private Player $player
    ){}

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return bool should the event be cancelled
     */
    public function onChat(): bool
    {
        if(!$this->player->hasPermission("AntiSpam.cooldown.bypass") and time() - $this->lastChatTime < Main::$chatDelay) {
            $this->addWarn();
            return true;
        }

        $this->lastChatTime = time();
        return false;
    }


    public function addWarn(): void
    {
        if(time() - $this->lastWarnTime > Main::$removeWarnsDelay) {
            $this->warns = 0;
        }

        $this->lastWarnTime = time();
        $this->warns ++;

        if($this->warns >= Main::$maxWarns and !Main::$punishment->equals(Punishment::Nothing())) {
            Main::$punishment->punish($this->player);
            return;
        }

        $this->player->sendMessage(Main::$warnMessage);
    }
}