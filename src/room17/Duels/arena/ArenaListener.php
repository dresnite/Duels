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

namespace room17\Duels\arena;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class ArenaListener implements Listener {
    
    /** @var ArenaManager */
    private $manager;
    
    /**
     * ArenaListener constructor.
     * @param ArenaManager $manager
     */
    public function __construct(ArenaManager $manager) {
        $this->manager = $manager;
    }
    
    /**
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event): void {
        $player = $event->getPlayer();
        if($this->manager->getArenaByLevel($player->getLevel()) != null and !($player->isOp())) {
            $event->setCancelled();
        }
    }
    
    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        if($this->manager->getArenaByLevel($player->getLevel()) != null and !($player->isOp())) {
            $event->setCancelled();
        }
    }
    
}