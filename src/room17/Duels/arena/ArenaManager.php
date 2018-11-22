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


use pocketmine\level\Level;
use pocketmine\math\Vector3;
use room17\Duels\Duels;
use room17\Duels\event\arena\ArenaCreationEvent;

class ArenaManager {
    
    /** @var Duels */
    private $loader;
    
    /** @var Arena[] */
    private $arenas = [];
    
    /**
     * ArenaManager constructor.
     * @param Duels $loader
     */
    public function __construct(Duels $loader) {
        $this->loader = $loader;
        $loader->getServer()->getPluginManager()->registerEvents(new ArenaListener($this), $loader);
    }
    
    /**
     * @return Arena[]
     */
    public function getArenas(): array {
        return $this->arenas;
    }
    
    /**
     * @param string $identifier
     * @return null|Arena
     */
    public function getArena(string $identifier): ?Arena {
        return $this->arenas[$identifier] ?? null;
    }
    
    /**
     * @return Arena
     */
    public function getRandomArena(): Arena {
        return $this->arenas[array_rand($this->arenas)];
    }
    
    /**
     * @param string $name
     * @return null|Arena
     */
    public function getArenaByName(string $name): ?Arena {
        foreach($this->arenas as $arena) {
            if($arena->getName() == $name) {
                return $arena;
            }
        }
        return null;
    }
    
    /**
     * @param Level $level
     * @return null|Arena
     */
    public function getArenaByLevel(Level $level): ?Arena {
        foreach($this->arenas as $arena) {
            if($arena->getLevel() === $level) {
                return $arena;
            }
        }
        return null;
    }
    
    /**
     * @param string $identifier
     * @param string $name
     * @param string $author
     * @param string $description
     * @param Level $level
     * @param Vector3 $firstSpawn
     * @param Vector3 $secondSpawn
     */
    public function registerArena(string $identifier, string $name, string $author, string $description, Level $level,
        Vector3 $firstSpawn, Vector3 $secondSpawn): void {
        if(isset($this->arenas[$identifier])) {
            $this->loader->getLogger()->warning("Overwriting arena {$identifier}, this might cause unexpected behaviour");
        }
        $event = new ArenaCreationEvent($arena = new Arena($identifier, $name, $author, $description, $level, $firstSpawn, $secondSpawn));
        $this->loader->getServer()->getPluginManager()->callEvent($event);
        if(!$event->isCancelled()) {
            $this->arenas[$identifier] = $arena;
        } else {
            $this->loader->getLogger()->debug("Couldn't create the arena $identifier because the event was cancelled");
        }
    }
    
}