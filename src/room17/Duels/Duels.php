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


use pocketmine\plugin\PluginBase;
use room17\Duels\arena\ArenaManager;
use room17\Duels\match\MatchManager;
use room17\Duels\queue\QueueManager;
use room17\Duels\session\SessionManager;

class Duels extends PluginBase {
    
    /** @var Duels */
    private static $instance;
    
    /** @var DuelsSettings */
    private $settings;
    
    /** @var SessionManager */
    private $sessionManager;
    
    /** @var ArenaManager */
    private $arenaManager;
    
    /** @var MatchManager */
    private $matchManager;
    
    /** @var QueueManager */
    private $queueManager;
    
    public function onLoad(): void {
        self::$instance = $this;
        $this->saveResource(DuelsSettings::MESSAGE_FILE);
        $this->saveResource(DuelsSettings::SETTINGS_FILE);
    }
    
    public function onEnable(): void {
        $this->settings = new DuelsSettings($this);
        $this->sessionManager = new SessionManager($this);
        $this->arenaManager = new ArenaManager($this);
        $this->matchManager = new MatchManager($this);
        $this->queueManager = new QueueManager($this);
        $this->getLogger()->info("Duels has been enabled");
    }
    
    public function onDisable(): void {
        $this->getLogger()->info("Duels has been disabled");
    }
    
    /**
     * @return DuelsSettings
     */
    public function getSettings(): DuelsSettings {
        return $this->settings;
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
    
}