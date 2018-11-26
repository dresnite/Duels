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


use room17\Duels\arena\Arena;
use room17\Duels\session\Session;

class Match {
    
    /** @var MatchManager */
    private $manager;
    
    /** @var int */
    private $identifier;
    
    /** @var Arena */
    private $arena;
    
    /** @var Session */
    private $firstSession;
    
    /** @var Session */
    private $secondSession;
    
    /** @var string */
    private $stage = self::STAGE_COUNTDOWN;
    
    const STAGE_COUNTDOWN = "countdown";
    const STAGE_FIGHTING = "fighting";
    
    /** @var int */
    private $countdown = 10;
    
    /** @var null|Session */
    private $winner = null;
    
    /**
     * Match constructor.
     * @param MatchManager $manager
     * @param int $identifier
     * @param Arena $arena
     * @param Session $firstSession
     * @param Session $secondSession
     */
    public function __construct(MatchManager $manager, int $identifier, Arena $arena, Session $firstSession, Session $secondSession) {
        $this->manager = $manager;
        $this->identifier = $identifier;
        $this->arena = $arena;
        $this->firstSession = $firstSession;
        $this->secondSession = $secondSession;
    }
    
    /**
     * @return int
     */
    public function getIdentifier(): int {
        return $this->identifier;
    }
    
    /**
     * @return Arena
     */
    public function getArena(): Arena {
        return $this->arena;
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
    
    /**
     * @return string
     */
    public function getStage(): string {
        return $this->stage;
    }
    
    /**
     * @return null|Session
     */
    public function getWinner(): ?Session {
        return $this->winner;
    }
    
    /**
     * @param string $stage
     */
    public function setStage(string $stage): void {
        $this->stage = $stage;
    }
    
    /**
     * @param int $countdown
     */
    public function setCountdown(int $countdown): void {
        $this->countdown = $countdown;
    }
    
    /**
     * @internal
     * @param Session $session
     */
    public function removePlayer(Session $session): void {
        $winner = ($session === $this->firstSession) ? $this->secondSession : $this->firstSession;
        
        /** @var Session $participant */
        foreach([$winner, $session] as $participant) {
            $participant->getOwner()->teleport($participant->getOriginalLocation());
            $participant->setMatch(null);
            $participant->setOriginalLocation(null);
        }
        
        foreach($this->manager->getLoader()->getSessionManager()->getSessions() as $playerSession) {
            if($playerSession !== $session) {
                $playerSession->sendLocalizedMessage("MATCH_VICTORY", [
                    "winner" => $winner,
                    "loser" => $session
                ]);
            } else {
                $session->sendLocalizedMessage("YOU_LOST", [
                    "winner" => $winner
                ]);
            }
        }
        
        $this->winner = $winner;
        
        $this->manager->stopMatch($this->identifier);
    }
    
    public function doTick(): void {
        switch($this->stage) {
            case self::STAGE_COUNTDOWN:
                $this->countdown--;
                if($this->countdown > 0) {
                    $arena = $this->getArena();
                    
                    $firstSpawn = $arena->getFirstSpawn();
                    $secondSpawn = $arena->getSecondSpawn();
                    
                    $firstPlayer = $this->firstSession->getOwner();
                    if($firstPlayer->getFloorX() != $firstSpawn->getFloorX() or $firstPlayer->getFloorY() != $firstSpawn->getFloorY()) {
                        $this->firstSession->getOwner()->teleport($firstSpawn);
                    }
    
                    $secondPlayer = $this->secondSession->getOwner();
                    if($secondPlayer->getFloorX() != $secondSpawn->getFloorX() or $secondPlayer->getFloorY() != $secondSpawn->getFloorY()) {
                        $this->secondSession->getOwner()->teleport($firstSpawn);
                    }
                    
                    $this->broadcastPopup("COUNTDOWN_MESSAGE", [
                        "time" => $this->countdown
                    ]);
                } else {
                    $this->broadcastPopup("GAME_STARTED");
                    $this->setStage(self::STAGE_FIGHTING);
                }
                break;
            case self::STAGE_FIGHTING:
                //todo
                break;
        }
    }
    
    /**
     * @param string $identifier
     * @param array $args
     */
    public function broadcastMessage(string $identifier, array $args = []): void {
        $this->firstSession->sendLocalizedMessage($identifier, $args);
        $this->secondSession->sendLocalizedMessage($identifier, $args);
    }
    
    /**
     * @param string $identifier
     * @param array $args
     */
    public function broadcastPopup(string $identifier, array $args = []): void {
        $this->firstSession->sendLocalizedPopup($identifier, $args);
        $this->secondSession->sendLocalizedPopup($identifier, $args);
    }
    
    /**
     * @param string $identifier
     * @param array $args
     */
    public function broadcastTip(string $identifier, array $args = []): void {
        $this->firstSession->sendLocalizedTip($identifier, $args);
        $this->secondSession->sendLocalizedTip($identifier, $args);
    }
    
}