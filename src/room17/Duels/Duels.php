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


use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use room17\Duels\arena\ArenaManager;
use room17\Duels\cmd\DuelsCommandMap;
use room17\Duels\match\MatchManager;
use room17\Duels\queue\QueueManager;
use room17\Duels\session\SessionManager;

class Duels extends PluginBase {
    
    /** @var Duels */
    private static $instance;
    
    /** @var SessionManager */
    private $sessionManager;
    
    /** @var ArenaManager */
    private $arenaManager;
    
    /** @var MatchManager */
    private $matchManager;
    
    /** @var QueueManager */
    private $queueManager;
    
    /** @var DuelsCommandMap */
    private $commandMap;
    
    /** @var DuelsSettings */
    private $settings;
    
    public function onLoad(): void {
        self::$instance = $this;
        $this->saveResource(DuelsSettings::MESSAGE_FILE);
        $this->saveResource(DuelsSettings::SETTINGS_FILE);
    }
    
    public function onEnable(): void {
        $this->sessionManager = new SessionManager($this);
        $this->arenaManager = new ArenaManager($this);
        $this->matchManager = new MatchManager($this);
        $this->queueManager = new QueueManager($this);
        $this->commandMap = new DuelsCommandMap($this);
        $this->settings = new DuelsSettings($this);
        if($this->isEnabled()) {
            $this->getLogger()->info("Duels has been enabled");
        }
    }
    
    public function onDisable(): void {
        $this->getLogger()->info("Duels has been disabled");
    }
    
    /**
     * @return Duels
     */
    public static function getInstance(): Duels {
        return self::$instance;
    }
    
    /**
     * @return SessionManager
     */
    public function getSessionManager(): SessionManager {
        return $this->sessionManager;
    }
    
    /**
     * @return ArenaManager
     */
    public function getArenaManager(): ArenaManager {
        return $this->arenaManager;
    }
    
    /**
     * @return MatchManager
     */
    public function getMatchManager(): MatchManager {
        return $this->matchManager;
    }
    
    /**
     * @return QueueManager
     */
    public function getQueueManager(): QueueManager {
        return $this->queueManager;
    }
    
    /**
     * @return DuelsCommandMap
     */
    public function getCommandMap(): DuelsCommandMap {
        return $this->commandMap;
    }
    
    /**
     * @return DuelsSettings
     */
    public function getSettings(): DuelsSettings {
        return $this->settings;
    }
    
    /**
     * @param string $message
     * @return string
     */
    public static function translateColors(string $message): string {
        $message = str_replace("{BLACK}", TextFormat::BLACK, $message);
        $message = str_replace("{DARK_BLUE}", TextFormat::DARK_BLUE, $message);
        $message = str_replace("{DARK_GREEN}", TextFormat::DARK_GREEN, $message);
        $message = str_replace("{DARK_AQUA}", TextFormat::DARK_AQUA, $message);
        $message = str_replace("{DARK_RED}", TextFormat::DARK_RED, $message);
        $message = str_replace("{DARK_PURPLE}", TextFormat::DARK_PURPLE, $message);
        $message = str_replace("{ORANGE}", TextFormat::GOLD, $message);
        $message = str_replace("{GRAY}", TextFormat::GRAY, $message);
        $message = str_replace("{DARK_GRAY}", TextFormat::DARK_GRAY, $message);
        $message = str_replace("{BLUE}", TextFormat::BLUE, $message);
        $message = str_replace("{GREEN}", TextFormat::GREEN, $message);
        $message = str_replace("{AQUA}", TextFormat::AQUA, $message);
        $message = str_replace("{RED}", TextFormat::RED, $message);
        $message = str_replace("{LIGHT_PURPLE}", TextFormat::LIGHT_PURPLE, $message);
        $message = str_replace("{YELLOW}", TextFormat::YELLOW, $message);
        $message = str_replace("{WHITE}", TextFormat::WHITE, $message);
        $message = str_replace("{OBFUSCATED}", TextFormat::OBFUSCATED, $message);
        $message = str_replace("{BOLD}", TextFormat::BOLD, $message);
        $message = str_replace("{STRIKETHROUGH}", TextFormat::STRIKETHROUGH, $message);
        $message = str_replace("{UNDERLINE}", TextFormat::UNDERLINE, $message);
        $message = str_replace("{ITALIC}", TextFormat::ITALIC, $message);
        $message = str_replace("{RESET}", TextFormat::RESET, $message);
        return $message;
    }
    
    /**
     * @param string $possibleVector
     * @return null|Vector3
     */
    public static function parseVector3(string $possibleVector): ?Vector3 {
        $pieces = explode(",", $possibleVector);
        if(isset($pieces[2])) {
            foreach($pieces as &$piece) {
                if(is_numeric($piece)) {
                    $piece = (int) $piece;
                } else {
                    return null;
                }
            }
            return new Vector3($pieces[0], $pieces[1], $pieces[2]);
        }
        return null;
    }
    
}