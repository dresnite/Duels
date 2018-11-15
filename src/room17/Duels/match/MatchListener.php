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

namespace room17\Duels\match;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;

class MatchListener implements Listener {
    
    /** @var MatchManager */
    private $manager;
    
    /**
     * MatchListener constructor.
     * @param MatchManager $manager
     */
    public function __construct(MatchManager $manager) {
        $this->manager = $manager;
    }
    
    /**
     * @param PlayerDeathEvent $event
     */
    public function onDeath(PlayerDeathEvent $event): void {
        $session = $this->manager->getLoader()->getSessionManager()->getSession($event->getPlayer());
        if($session->hasMatch()) {
            $session->getMatch()->removePlayer($session);
        }
    }
    
    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event): void {
        $session = $this->manager->getLoader()->getSessionManager()->getSession($event->getPlayer());
        if($session->hasMatch()) {
            $session->getMatch()->removePlayer($session);
        }
    }
    
}