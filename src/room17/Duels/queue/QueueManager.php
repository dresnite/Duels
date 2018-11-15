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

namespace room17\Duels\queue;


use room17\Duels\Duels;
use room17\Duels\event\queue\QueueMatchEvent;
use room17\Duels\session\Session;

class QueueManager {
    
    /** @var Duels */
    private $loader;
    
    /** @var array */
    private $queue = [];
    
    /**
     * QueueManager constructor.
     * @param Duels $loader
     */
   public function __construct(Duels $loader) {
       $this->loader = $loader;
       $loader->getServer()->getPluginManager()->registerEvents(new QueueListener($this), $loader);
   }
    
    /**
     * @return Duels
     */
    public function getLoader(): Duels {
        return $this->loader;
    }
    
    /**
     * @return array
     */
    public function getQueue(): array {
        return $this->queue;
    }
    
    /**
     * @param Session $session
     * @return bool
     */
    public function isIn(Session $session): bool {
        return isset($this->queue[$session->getUsername()]);
    }
    
    /**
     * @param Session[] ...$sessions
     */
    public function add(Session ...$sessions): void {
        foreach($sessions as $session) {
            if(isset($this->queue[$username = $session->getUsername()])) {
                $this->loader->getLogger()->warning("Adding a player $session who was already in the queue");
            }
            $session->removeSentInvitations();
            $this->queue[$session->getUsername()] = $session;
            $this->searchPossibleMatches();
        }
    }
    
    /**
     * @param Session[] ...$sessions
     */
    public function remove(Session ...$sessions): void {
        foreach($sessions as $session) {
            if(isset($this->queue[$username = $session->getUsername()])) {
                unset($this->queue[$username]);
            } else {
                $this->loader->getLogger()->warning("Couldn't remove $username from the queue because he or she was not in!");
            }
        }
    }
    
    public function searchPossibleMatches(): void {
        for($i = 0; $i < floor(count($this->queue) / 2); $i++) {
            $firstSession = array_shift($this->queue);
            $secondSession = array_shift($this->queue);
            $started = $this->loader->getMatchManager()->startMatch($firstSession, $secondSession);
            if($started) {
                $this->loader->getServer()->getPluginManager()->callEvent(new QueueMatchEvent($firstSession, $secondSession));
            } else {
                $this->add($firstSession, $secondSession);
            }
        }
    }
   
}