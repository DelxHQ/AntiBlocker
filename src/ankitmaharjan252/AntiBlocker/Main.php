<?php
namespace ankitmaharjan252\AntiBlocker;

use BukkitPE\event\Listener;
use BukkitPE\event\player\PlayerChatEvent;
use BukkitPE\plugin\PluginBase;
use BukkitPE\utils\Config;
use BukkitPE\utils\TextFormat as Color;
use BukkitPE\command\Command;
use BukkitPE\command\CommandSender;

class Main extends PluginBase implements Listener{
	private $prifex = Color::WHITE . "[" . Color::YELLOW . "Anti" . Color::RED . "Blocker" . Color::WHITE . "] ";
	public function onEnable(){
		$this->saveDefaultConfig();
		$this->config = (new Config($this->getDataFolder() . "config.yml", Config::YAML))->getAll();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("AntiBlocker has been enabled");
	}
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		if(strtolower($cmd->getName()) == "antiblocker"){
			if($sender->hasPermission("antiblocker.command.antiblocker")){
				if(isset($args[0])){
					if(strtolower($args[0]) == "block"){
							if(isset($args[1])){
								$words = $args[1];
								$this->getConfig()->set("words", $words);
								$sender->sendMessage($this->prifex .$args[1]." has been added to blocking list");
							} else {
								$sender->sendMessage(Color::RED . "Usage: /antiblocker <block> <word>");
							}	
						} else {
							$sender->sendMessage(Color::RED . "Usage: /antiblocker <block> <word>");
						}
				} else {
					$sender->sendMessage(Color::RED . "Usage: /antiblocker <block> <word>");
				}
			}
		}
	}
	public function onChat(PlayerChatEvent $e){
		$p = $e->getPlayer();
		$msg = $e->getMessage();
		$words = $this->getConfig()->get("words");
		$search = strpos($msg, $words);
		if($search === true){
			$p->sendMessage($this->prifex . Color::RED . "That word is blocked in this server");
		}
	}
	public function onDisable(){
		$this->saveConfig();
		$this->getLogger()->info("AntiBlocker has been disabled");
	}
}