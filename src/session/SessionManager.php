<?php

declare(strict_types=1);

namespace Aboshxm2\AntiSpam\session;

use pocketmine\player\Player;

class SessionManager
{
    /** @var Session[] */
    public static array $sessions = [];

    public static function get(Player $player): ?Session
    {
        return self::$sessions[$player->getName()] ?? null;
    }

    public static function add(Player $player): void
    {
        if(!isset(self::$sessions[$player->getName()]))
            self::$sessions[$player->getName()] = new Session($player);
    }

    public static function remove(Player $player): void
    {
        if(isset(self::$sessions[$player->getName()]))
            unset(self::$sessions[$player->getName()]);
    }
}