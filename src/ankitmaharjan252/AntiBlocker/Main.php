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
		$this->words = (new Config($this->getDataFolder() . "config.yml", Config::YAML))->getAll();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("AntiBlocker has been enabled");
		
	}
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		if(strtolower($cmd->getName()) == "antiblocker"){
			if($sender->hasPermission("antiblocker.command.antiblocker")){
				if(isset($args[0])){
					if(strtolower($args[0]) == "add"){
							if(isset($args[1])){
								$words = $this->getConfig()->get("words", []);
								$words[] = $args[1];
								$this->getConfig()->set("words", $words);
								$this->saveConfig();
								$sender->sendMessage($this->prifex .$args[1]." has been added to blocking list");
							} else {
								$sender->sendMessage(Color::RED . "Usage: /antiblocker <add> <word>");
							}	
						} else {
							$sender->sendMessage(Color::RED . "Usage: /antiblocker <add> <word>");
						}
				} else {
					$sender->sendMessage(Color::RED . "Usage: /antiblocker <add> <word>");
				}
			}
		}
	}
 public function onPlayerChat(PlayerChatEvent $event){
    $player = $event->getPlayer();
    $message = $event->getMessage();
    foreach($this->words->get("words") as $word => $replace){
      if($player->hasPermission("antiblocker.bypass") !== true){
        $message = str_ireplace($word, $replace, $message);
        $player->sendMessage($this->prifex . "$replace");
		}
	}
 }
	public function onDisable(){
		$this->saveConfig();
		$this->getLogger()->info("AntiBlocker has been disabled");
	}
}
