<?php

declare(strict_types=1);

namespace BenMenEs\MeClicker;

use onebone\economyapi\EconomyAPI;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;

class EventListener implements Listener
{
	
	private $main;
	
	public function __construct(Main $main)
	{
		$this->main = $main;
	}
	
	public function onJoin(PlayerJoinEvent $event) : void
	{
		$nick = $event->getPlayer()->getName();
		
		if($this->main->getStats($nick) === null)
		{
			$this->main->registerPlayer($nick);
		}
	}
	
	public function onInteract(PlayerInteractEvent $event) : void
	{
		if(isset($this->main->config["clicker_position"]))
		{
			$pos = $event->getBlock()->asVector3();
			$pos1 = $this->main->config["clicker_position"];
			if((int) round($pos->x) == $pos1["x"] and (int) round($pos->y) == $pos1["y"] and (int) round($pos->z) == $pos1["z"])
			{
				$api = $this->main;
				$player = $event->getPlayer();
				$nick = $player->getName();
				$stats = $api->getStats($nick);
				$cfg = $api->config;
				
				$api->setStats($nick, "clicks", $stats["clicks"] + 1);
				$api->setStats($nick, "xp", $stats["xp"] + mt_rand($cfg["xp_min"], $cfg["xp_max"] + $stats["level"] * $cfg["level_xp"]));
				$money = mt_rand($cfg["money_min"], $cfg["money_max"] + $stats["level"] * $cfg["level_money"]);
				EconomyAPI::getInstance()->addMoney($nick, $money);
				$api->setStats($nick, "total", $stats["total"] + $money);
				$player->sendTip(str_replace("{money}", $money, $cfg["tip"]));
				
				if($stats["xp"] >= $cfg["need_xp"])
				{
					if($cfg["max_level"] > $stats["level"])
					{
						$api->setStats($nick, "level", $stats["level"] + 1);
						$api->setStats($nick, "xp", 0);
						$player->sendMessage($cfg["level_up"]);
					}
				}
			}
		}
	}
}