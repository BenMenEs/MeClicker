<?php

declare(strict_types=1);

namespace BenMenEs\MeClicker\form;

use BenMenEs\MeClicker\Main;

use jojoe77777\FormAPI\SimpleForm;

use pocketmine\Player;

class StatsForm extends SimpleForm
{
	
	public function __construct(string $player, array $config)
	{
		parent::__construct(function(Player $player, int $data = null)
		{
			return;
        });
        
        $stats = Main::getInstance()->getStats($player);
        $this->setTitle($config["form"]["title"]);
        $this->setContent(str_replace(["{level}", "{xp}", "{total}", "{clicks}"], [$stats["level"], $stats["xp"], $stats["total"], $stats["clicks"]], $config["form"]["stats"]));
        $this->addButton($config["form"]["exit"]);
	}
}