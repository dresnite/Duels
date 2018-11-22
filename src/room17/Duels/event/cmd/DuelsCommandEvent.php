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

namespace room17\Duels\event\cmd;


use room17\Duels\cmd\DuelsCommand;
use room17\Duels\event\DuelsEvent;

class DuelsCommandEvent extends DuelsEvent {
    
    /** @var DuelsCommand */
    private $command;
    
    /**
     * DuelsCommandEvent constructor.
     * @param DuelsCommand $command
     */
    public function __construct(DuelsCommand $command) {
        $this->command = $command;
    }
    
    /**
     * @return DuelsCommand
     */
    public function getCommand(): DuelsCommand {
        return $this->command;
    }
    
}