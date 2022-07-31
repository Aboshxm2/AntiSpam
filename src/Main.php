<?php

declare(strict_types=1);

namespace Aboshxm2\AntiSpam;

use Aboshxm2\AntiSpam\session\SessionManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    public static Punishment $punishment;

    public static int $chatDelay;
    public static int $removeWarnsDelay;
    public static int $maxWarns;
    public static string $warnMessage;
    public static string $kickMessage;// if the punishment is "Kick"

    protected function onEnable(): void
    {
        $this->saveDefaultConfig();

        self::$chatDelay = $this->getConfig()->get("chat-delay");
        self::$removeWarnsDelay = $this->getConfig()->get("remove-warns-delay");
        self::$maxWarns = $this->getConfig()->get("max-warns");
        self::$warnMessage = $this->getConfig()->get("warn-message");
        switch ($this->getConfig()->get("punishment")) {// TODO ban punishment
            case "nothing":
                self::$punishment = Punishment::Nothing();
                break;
            case "kick":
                self::$kickMessage = $this->getConfig()->get("kick-message");
                self::$punishment = Punishment::Kick();
                break;
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        SessionManager::add($event->getPlayer());
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        SessionManager::remove($event->getPlayer());
    }

    public function onChat(PlayerChatEvent $event)
    {
        if(SessionManager::get($event->getPlayer())?->onChat()) {
            $event->cancel();
        }
    }

    public function onPlayerCommand(CommandEvent $event)
    {
        $sender = $event->getSender();
        if($sender instanceof Player) {
            if(SessionManager::get($sender)?->onChat()) {
                $event->cancel();
            }
        }
    }
}