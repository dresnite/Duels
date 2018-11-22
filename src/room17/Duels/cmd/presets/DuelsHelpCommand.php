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

namespace room17\Duels\cmd\presets;


use room17\Duels\cmd\DuelsCommand;
use room17\Duels\session\Session;

class DuelsHelpCommand implements DuelsCommand {
    
    /**
     * @return string
     */
    public function getName(): string {
        return "help";
    }
    
    /**
     * @return array
     */
    public function getAliases(): array {
        return ["?"];
    }
    
    /**
     * @param Session $session
     * @param array $args
     */
    public function onCommand(Session $session, array $args = []): void {
        $session->sendLocalizedMessage("HELP_MESSAGE");
    }
    
}