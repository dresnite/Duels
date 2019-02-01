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
use room17\Duels\cmd\DuelsCommandMap;
use room17\Duels\queue\QueueManager;
use room17\Duels\session\Session;

class DuelsQueueCommand implements DuelsCommand {
    
    /** @var QueueManager */
    private $queueManager;
    
    /**
     * DuelsQueueCommand constructor.
     * @param DuelsCommandMap $commandMap
     */
    public function __construct(DuelsCommandMap $commandMap) {
        $this->queueManager = $commandMap->getLoader()->getQueueManager();
    }
    
    /**
     * @return string
     */
    public function getName(): string {
        return "queue";
    }
    
    /**
     * @return array
     */
    public function getAliases(): array {
        return ["search"];
    }

    /**
     * @param Session $session
     * @param array $args
     * @throws \ReflectionException
     */
    public function onCommand(Session $session, array $args = []): void {
        if($session->hasMatch()) {
            $session->sendLocalizedMessage("YOU_ARE_ALREADY_PLAYING");
        } elseif($this->queueManager->isIn($session)) {
            $this->queueManager->remove($session);
            $session->sendLocalizedMessage("REMOVED_FROM_THE_QUEUE");
        } else {
            $this->queueManager->add($session);
            $session->sendLocalizedMessage("ADDED_TO_THE_QUEUE");
        }
    }
    
}