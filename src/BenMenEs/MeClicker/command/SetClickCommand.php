<?php

declare(strict_types=1);

namespace BenMenEs\MeClicker\command;

use BenMenEs\MeClicker\Main;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class SetClickCommand extends Command
{
	
	private $main;
	
	public function __construct(Main $main, string $name, string $description, string $permission)
	{
		$this->main = $main;
		
		parent::__construct($name, $description);
		$this->setPermission($permission);
	}
	
	public function execute(CommandSender $sender, string $label, array $args)
	{
		if(!$this->testPermission($sender)) return;
		
		if(!$sender instanceof Player) return $sender->sendMessage("§cНюхай бэбру");
		
		$this->main->getConfig()->set("clicker_position", ["x" => $sender->getFloorX(), "y" => $sender->getFloorY(), "z" => $sender->getFloorX()]);
		$this->main->getConfig()->save();
		$this->main->config["clicker_position"] = ["x" => $sender->getFloorX(), "y" => $sender->getFloorY(), "z" => $sender->getFloorX()];
		$sender->sendMessage("Вы успешно установили кликер!");
	}
}