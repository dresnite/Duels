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

namespace room17\Duels\event\queue;


use room17\Duels\event\DuelsEvent;
use room17\Duels\session\Session;

class QueueEvent extends DuelsEvent {
    
    /** @var Session */
    private $firstSession;
    
    /** @var Session */
    private $secondSession;
    
    /**
     * QueueEvent constructor.
     * @param Session $firstSession
     * @param Session $secondSession
     */
    public function __construct(Session $firstSession, Session $secondSession) {
        $this->firstSession = $firstSession;
        $this->secondSession = $secondSession;
    }
    
    /**
     * @return Session
     */
    public function getFirstSession(): Session {
        return $this->firstSession;
    }
    
    /**
     * @return Session
     */
    public function getSecondSession(): Session {
        return $this->secondSession;
    }
    
}