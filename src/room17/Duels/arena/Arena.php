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
use pocketmine\level\Position;
use pocketmine\math\Vector3;

class Arena {
    
    /** @var string */
    private $identifier;
    
    /** @var string */
    private $name;
    
    /** @var string */
    private $author;
    
    /** @var string */
    private $description;
    
    /** @var Level */
    private $level;
    
    /** @var Position */
    private $firstSpawn;
    
    /** @var Position */
    private $secondSpawn;
    
    /**
     * Arena constructor.
     * @param string $identifier
     * @param string $name
     * @param string $author
     * @param string $description
     * @param Level $level
     * @param Vector3 $firstSpawn
     * @param Vector3 $secondSpawn
     */
    public function __construct(string $identifier, string $name, string $author, string $description, Level $level,
        Vector3 $firstSpawn, Vector3 $secondSpawn) {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->author = $author;
        $this->description = $description;
        $this->level = $level;
        $this->setFirstSpawn($firstSpawn);
        $this->setSecondSpawn($secondSpawn);
    }
    
    /**
     * @return string
     */
    public function getIdentifier(): string {
        return $this->identifier;
    }
    
    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getAuthor(): string {
        return $this->author;
    }
    
    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }
    
    /**
     * @return Level
     */
    public function getLevel(): Level {
        return $this->level;
    }
    
    /**
     * @return Position
     */
    public function getFirstSpawn(): Position {
        return $this->firstSpawn;
    }
    
    /**
     * @return Position
     */
    public function getSecondSpawn(): Position {
        return $this->secondSpawn;
    }
    
    /**
     * @param Vector3 $firstSpawn
     */
    public function setFirstSpawn(Vector3 $firstSpawn): void {
        $this->firstSpawn = new Position($firstSpawn->x, $firstSpawn->y, $firstSpawn->z, $this->level);
    }
    
    /**
     * @param Vector3 $secondSpawn
     */
    public function setSecondSpawn(Vector3 $secondSpawn): void {
        $this->secondSpawn = new Position($secondSpawn->x, $secondSpawn->y, $secondSpawn->z, $this->level);
    }
    
}