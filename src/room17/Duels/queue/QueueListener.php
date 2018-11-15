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


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class QueueListener implements Listener {
    
    /** @var QueueManager */
    private $manager;
    
    /**
     * QueueListener constructor.
     * @param QueueManager $manager
     */
    public function __construct(QueueManager $manager) {
        $this->manager = $manager;
    }
    
    /**
     * @param PlayerQuitEvent $event
     * @priority LOWEST
     */
    public function onQuit(PlayerQuitEvent $event): void {
        $session = $this->manager->getLoader()->getSessionManager()->getSession($event->getPlayer());
        if($this->manager->isIn($session)) {
            $this->manager->remove($session);
        }
    }
    
}