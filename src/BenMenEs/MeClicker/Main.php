<?php

declare(strict_types=1);

namespace BenMenEs\MeClicker;

use BenMenEs\MeClicker\command\SetClickCommand;
use BenMenEs\MeClicker\command\DelClickCommand;
use BenMenEs\MeClicker\command\ClickCommand;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\Server;

class Main extends PluginBase
{
	
	/** @var array */
	public $config;
	
	/** @var Config */
	private $stats;
	
	private static $instance = null;
	
	public function onLoad() : void
	{
		$commands =
		[
		  new SetClickCommand($this, "setclick", "Установить кликер", "setclick.cmd"),
		  new DelClickCommand($this, "delclick", "Удалить кликер", "delclick.cmd"),
		  new ClickCommand($this, "click", "Статистика кликера", "click.cmd")
		];
		
		foreach($commands as $command)
		    $this->getServer()->getCommandMap()->register("MeClicker", $command);
	}
	
	public function onEnable() : void
	{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		
		$this->saveDefaultConfig();
		$this->config = $this->getConfig()->getAll();
		
		$this->stats = new Config($this->getDataFolder() . "stats.json", Config::JSON);
		
		self::$instance = $this;
	}
	
	public static function getInstance()
	{
		if(self::$instance === null) self::$instance = $this;
		return self::$instance;
	}
	
	/**
	 * @return array|null
	 * [
	     "level" => (int) $level, - уровень
	     "xp" => (int) $xp, - опыт
	     "total" => (int) $total, - всего заработал на кликере,
	     "clicks" => (int) $clicks - всего сделал кликов за всё время.
	 * ]
	 */
	public function getStats(string $player) 
	{
		$player = strtolower($player);
		return $this->stats->exists($player) ? $this->stats->get($player) : null;
	}
	
	/**
	 * @param string $key - доступные ключи: level, xp, total, clicks.
	 */
	public function setStats(string $player, string $key, int $value) : void
	{
		$player = strtolower($player);
		
		$stats = $this->getStats($player);
		$stats[$key] = $value;
		
		$this->stats->set($player, $stats);
		$this->stats->save();
	}
	
	public function registerPlayer(string $player) : void
	{
		$this->stats->set(strtolower($player), 
        [
          "level" => 0,
          "xp" => 0,
          "total" => 0,
          "clicks" => 0
        ]);
		$this->stats->save();
	}
}