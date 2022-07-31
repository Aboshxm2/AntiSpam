<?php

declare(strict_types=1);

namespace Aboshxm2\AntiSpam;

use pocketmine\player\Player;
use pocketmine\utils\EnumTrait;

/**
 * @method static Punishment Nothing()
 * @method static Punishment Kick()
 */
class Punishment
{
    use EnumTrait {
        __construct as Enum___construct;
    }


    protected static function setup(): void
    {
        self::registerAll(
            new self("Nothing"),
            new self("Kick")
        );
    }

    public function __construct(string $actionName){
        $this->Enum___construct($actionName);
    }

    public function punish(Player $player)
    {
        switch ($this->name()) {
            case "kick":
                $player->kick(Main::$kickMessage);
                break;
        }
    }
}