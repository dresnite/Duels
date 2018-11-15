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

namespace room17\Duels\session;


use pocketmine\Player;
use room17\Duels\Duels;
use room17\Duels\event\session\SessionCloseEvent;
use room17\Duels\event\session\SessionOpenEvent;

class SessionManager {
    
    /** @var Duels */
    private $loader;
    
    /** @var Session[] */
    private $sessions = [];
    
    /**
     * SessionManager constructor.
     * @param Duels $loader
     */
    public function __construct(Duels $loader) {
        $this->loader = $loader;
        $loader->getServer()->getPluginManager()->registerEvents(new SessionListener($this), $loader);
    }
    
    /**
     * @return Duels
     */
    public function getLoader(): Duels {
        return $this->loader;
    }
    
    /**
     * @return Session[]
     */
    public function getSessions(): array {
        return $this->sessions;
    }
    
    /**
     * @param Player $player
     * @return null|Session
     */
    public function getSession(Player $player): ?Session {
        if(isset($this->sessions[$username = $player->getName()])) {
            return $this->sessions[$username];
        }
        $this->loader->getLogger()->error("Couldn't find an active session for $username, this might be caused by external plugins");
        return null;
    }
    
    /**
     * @param string $username
     * @return null|Session
     */
    public function getSessionByName(string $username): ?Session {
        $player = $this->loader->getServer()->getPlayer($username);
        if($player != null) {
            return $this->getSession($player);
        }
        return null;
    }
    
    /**
     * @internal
     * @param Player $player
     */
    public function openSession(Player $player): void {
        if(isset($this->sessions[$username = $player->getName()])) {
            $this->loader->getLogger()->warning("Overwriting session for $username, this might cause unexpected behaviour");
        }
        $session = new Session($this, $player);
        $this->sessions[$username] = $session;
        $this->loader->getServer()->getPluginManager()->callEvent(new SessionOpenEvent($session));
    }
    
    /**
     * @internal
     * @param Player $player
     */
    public function closeSession(Player $player): void {
        if(isset($this->sessions[$username = $player->getName()])) {
            $this->loader->getServer()->getPluginManager()->callEvent(new SessionCloseEvent($this->sessions[$username]));
            $this->sessions[$username]->removeSentInvitations();
            unset($this->sessions[$username]);
        } else {
            $this->loader->getLogger()->error("Trying to remove a not existing session for $username");
        }
    }
    
}