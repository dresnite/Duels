<?php
/**
 *  _____    ____    ____   __  __  __  ______
 * |  __ \  / __ \  / __ \ |  \/  |/_ ||____  |
 * | |__) || |  | || |  | || \  / | | |    / /
 * |  _  / | |  | || |  | || |\/| | | |   / /
 * | | \ \ | |__| || |__| || |  | | | |  / /
 * |_|  \_\ \____/  \____/ |_|  |_| |_| /_/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 */

declare(strict_types=1);

namespace room17\Duels;


use pocketmine\utils\Config;

class DuelsSettings {
    
    const MESSAGE_FILE = "messages.json";
    const SETTINGS_FILE = "settings.json";
    
    /** @var Duels */
    private $loader;
    
    /** @var string[] */
    private $messages;
    
    /** @var Config */
    private $settings;
    
    /** @var bool */
    private $keepInventory;
    
    /**
     * DuelsSettings constructor.
     * @param Duels $loader
     */
    public function __construct(Duels $loader) {
        $this->loader = $loader;
        $this->refreshData();
    }
    
    /**
     * @return Duels
     */
    public function getLoader(): Duels {
        return $this->loader;
    }
    /**
     * @return string[]
     */
    public function getMessages(): array {
        return $this->messages;
    }
    
    /**
     * @param string $identifier
     * @param array $args
     * @return string
     */
    public function getMessage(string $identifier, array $args = []): string {
        $message = $this->messages[$identifier] ?? "Message ($identifier) not found";
        foreach($args as $arg => $value) {
            $message = str_replace("{" . $arg . "}", $value, $message);
        }
        return $message;
    }
    
    /**
     * @return bool
     */
    public function getKeepInventory(): bool {
        return $this->keepInventory;
    }
    
    public function refreshArenas(): void {
        $arenaManager = $this->loader->getArenaManager();
        $server = $this->loader->getServer();
        foreach($this->settings->get("arenas") as $identifier => $arenaData) {
            if(!isset($arenaData["name"], $arenaData["author"], $arenaData["description"], $arenaData["levelName"],
                $arenaData["firstSpawn"], $arenaData["secondSpawn"])) {
                $this->loader->getLogger()->error("Couldn't load arena " . $arenaData["name"] ?? "Unknown");
                continue;
            }
            $firstSpawn = Duels::parseVector3($arenaData["firstSpawn"]);
            $secondSpawn = Duels::parseVector3($arenaData["secondSpawn"]);
            if($firstSpawn != null and $secondSpawn != null) {
                if(!$server->isLevelLoaded($arenaData["levelName"])) {
                    $server->loadLevel($arenaData["levelName"]);
                }
                $level = $server->getLevelByName($arenaData["levelName"]);
                if($level != null) {
                    $arenaManager->registerArena($identifier, $arenaData["name"], $arenaData["author"],
                        $arenaData["description"], $level, $firstSpawn, $secondSpawn);
                } else {
                    $this->loader->getLogger()->error("Couldn't load arena {$arenaData["name"]} because it didn't have a valid level");
                }
            } else {
                $this->loader->getLogger()->error("Couldn't load arena {$arenaData["name"]} because the positions weren't properly formatted");
            }
        }
        
        if(empty($arenaManager->getArenas())) {
            $this->loader->getLogger()->error("Couldn't start Duels because there weren't any arenas available");
            $server->getPluginManager()->disablePlugin($this->loader);
        }
    }
    
    public function refreshData(): void {
        $this->messages = json_decode(file_get_contents($this->loader->getDataFolder() . self::MESSAGE_FILE), true);
        $this->messages = array_map(array($this->loader, "translateColors"), $this->messages);
        $this->settings = new Config($this->loader->getDataFolder() . self::SETTINGS_FILE);
        $this->keepInventory = $this->settings->get("keepInventory", true);
        $this->refreshArenas();
    }
    
}