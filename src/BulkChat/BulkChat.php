<?php

namespace BulkChat;
/*
   ___  __  ____   __ __
  / _ )/ / / / /  / //_/
 / _  / /_/ / /__/ ,<   
/____/\____/____/_/|_|  

     Bulk Developer
Wednesday July 27, 2016
*/

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;

class BulkChat extends PluginBase implements Listener{

  public $muted;
  public $words;
  public $warns;
  public $maxWarns

  public function onEnable(){
  $this->getServer()->getPluginManager()->registerEvents($this, $this);
  $this->muted = [];
  $this->warns = [];
  $this->words = $this->getConfig()->get("Words");
  $this->maxWarns = $this->getConfig()->get("Max-Warns");
  }
/*


 /$$   /$$                            /$$$$$$  /$$$$$$$  /$$$$$$
| $$$ | $$                           /$$__  $$| $$__  $$|_  $$_/
| $$$$| $$  /$$$$$$  /$$$$$$$       | $$  \ $$| $$  \ $$  | $$  
| $$ $$ $$ /$$__  $$| $$__  $$      | $$$$$$$$| $$$$$$$/  | $$  
| $$  $$$$| $$  \ $$| $$  \ $$      | $$__  $$| $$____/   | $$  
| $$\  $$$| $$  | $$| $$  | $$      | $$  | $$| $$        | $$  
| $$ \  $$|  $$$$$$/| $$  | $$      | $$  | $$| $$       /$$$$$$
|__/  \__/ \______/ |__/  |__/      |__/  |__/|__/      |______/
                       Bulk Developer
                   Wednesday July 27, 2016                                                                
                                                                
                                                                
*/

  public function onChat(PlayerChatEvent $event){
$p = $event->getName();
$msg = $event->getMessage();
  if($this->isChatFilterOn() == true){
  if(strpos($this->words, strtolower($msg))){
  	$p->sendMessage(C::RED."You cannot say that!");
  	$event->setCancelled();
  	if($this->warnsOn == true){
  	$this->addWarn($p);
  	if($this->getWarns($p) >= $this->maxWarns){
  		$p->sendMessage(C::RED."You have been automatically muted due to you reaching max warns!");
  		
  	}
  	}
  }
  }
  }
  
  
  if($this->isMuted($p) == true){
  $mutedBy = $this->getMutedBy($p);
  $event->setCancelled(true);
  $p->sendMessage(C::RED."You cannot chat, you were muted by " . C::AQUA . $mutedBy);
  }
  }
  
  	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
			switch($command->getName()){
				case "mute":
				if($sender->hasPermission("bulkchat.mute"){
					if(isset($args[0])){
						$p = $this->getServer()->getPlayer($args[0]);
						if($p instanceof Player){
						if($this->isMuted($p) == false{
						$sender->sendMessage(C::AQUA."Muted " . C::RED . $p->getName());
						$this->muted[$p->getName()] = $sender->getName();
						$p->sendMessage(C::RED."You have been muted by " . C::AQUA . $sender->getName() . C::RED . ", You cannot chat until you're unmuted!");
						}else{
						$sender->sendMessage(C::RED . $args[0] . " is already muted!");
						}
						}else{
						$sender->sendMessage(C::RED . $args[0] . " is not online!");
						}
					}else{
					$sender->sendMessage(C::RED."Use /mute [player]");
					}
				}else{
				$sender->sendMessage(C::RED."No permission to use this!");
				}
				break;
				case "unmute":
				if($sender->hasPermission("bulkchat.unmute"){
					if(isset($args[0])){
						$p = $this->getServer()->getPlayer($args[0]);
						if($p instanceof Player){
						if($this->isMuted($p) == true){
						$sender->sendMessage(C::AQUA."Unuted " . C::RED . $p->getName());
						$p->sendMessage(C::AQUA."You have been unmuted by " . C::GREEN . $sender->getName());
						unset($this->muted[$p->getName()]);
						}else{
						$sender->sendMessage(C::RED . $args[0] . " is not muted!");
						}
						}else{
						$sender->sendMessage(C::RED . $args[0] . " is not online!");
						}
					}else{
					$sender->sendMessage(C::RED."Use /mute [player]");
					}
				}else{
				$sender->sendMessage(C::RED."No permission to use this!");
				}
				break;
			}
	}
	
	
	/*
	



 .----------------.  .----------------.  .----------------. 
| .--------------. || .--------------. || .--------------. |
| |      __      | || |   ______     | || |     _____    | |
| |     /  \     | || |  |_   __ \   | || |    |_   _|   | |
| |    / /\ \    | || |    | |__) |  | || |      | |     | |
| |   / ____ \   | || |    |  ___/   | || |      | |     | |
| | _/ /    \ \_ | || |   _| |_      | || |     _| |_    | |
| ||____|  |____|| || |  |_____|     | || |    |_____|   | |
| |              | || |              | || |              | |
| '--------------' || '--------------' || '--------------' |
 '----------------'  '----------------'  '----------------' 
                       Bulk Developer
                   Wednesday July 27, 2016



*/
  public function isMuted(Player $p){
  if(isset($this->muted[$p->getName()])){
  return true;
  }else{
  return false;
  }
  }
  
  public function getMutedBy(Player $p){
  $mutedBy = $this->muted[$p->getName()];
  return $mutedBy;
  }
 
 public function isChatFilterOn(){
 if($this->getConfig()->get("Enable-ChatFilter") == true){
 	return true;
 }else{
 	return false;
 }
 }
 
 public function getWarns(Player $p){
 	return $this->warns[$p->getName()];
 }
 
 public function addWarn(Player $p){
 	if(isset($this->warns[$p->getName()]){
 	$count = $this->warns[$p->getName()];
 	unset($this->warns[$p->getName()]);
 	$this->warns[$p->getName()] = $count + 1;
 	}else{
 	$this->warns[$p->getName()] = 1;	
 	}
 	
 }

 }
