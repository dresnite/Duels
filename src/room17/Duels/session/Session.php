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

namespace room17\Duels\session;


use pocketmine\Player;
use room17\Duels\match\Match;

class Session {
    
    /** @var SessionManager */
    private $manager;
    
    /** @var Player */
    private $owner;
    
    /** @var null|Match */
    private $match;

    /** @var Session[] */
    private $invitations = [];
    
    /**
     * Session constructor.
     * @param SessionManager $manager
     * @param Player $owner
     */
    public function __construct(SessionManager $manager, Player $owner) {
        $this->manager = $manager;
        $this->owner = $owner;
    }
    
    /**
     * @return string
     */
    public function __toString(): string {
        return $this->owner->getName();
    }
    
    /**
     * @return Player
     */
    public function getOwner(): Player {
        return $this->owner;
    }
    
    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->owner->getName();
    }
    
    /**
     * @return bool
     */
    public function hasMatch(): bool {
        return $this->match != null;
    }
    
    /**
     * @return Match
     */
    public function getMatch(): Match {
        return $this->match;
    }
    
    /**
     * @internal
     * @param null|Match $match
     */
    public function setMatch(?Match $match): void {
        $this->match = $match;
    }

    /**
     * @return string[]
     */
    public function getInvitations(): array {
        return $this->invitations;
    }

    /**
     * @param Session $invitation
     * @return bool
     */
    public function hasInvitationFrom(Session $invitation): bool {
        return in_array($invitation, $this->invitations);
    }

    /**
     * @param Session $invite
     */
    public function addInvitation(Session $invite): void {
        $this->invitations[] = $invite;
    }

    /**
     * Use this in a custom event, when player
     * @param Session $session
     */
    public function removeInvitationFrom(Session $session): void {
        $key = array_search($session, $this->invitations);
        if(isset($this->invitations[$key])) {
            unset($this->invitations[$key]);
        } else {
            $this->manager->getLoader()->getLogger()->error("Couldn't remove an invitation from $session because it doesn't exist");
        }
    }

    public function removeSentInvitations(): void {
        $player = $this->getOwner();
        foreach($this->manager->getSessions() as $session) {
            if($session->hasInvitationFrom($this->manager->getSession($player))) {
                $session->removeInvitationFrom($this->manager->getSession($player));
            }
        }
    }
    
    /**
     * @param string $identifier
     * @param array $args
     * @return string
     */
    public function localizeMessage(string $identifier, array $args = []): string {
        return $this->manager->getLoader()->getSettings()->getMessage($identifier, $args);
    }
    
    /**
     * @param string $identifier
     * @param array $args
     */
    public function sendLocalizedMessage(string $identifier, array $args = []): void {
        $this->owner->sendMessage($this->localizeMessage($identifier, $args));
    }
    
    /**
     * @param string $identifier
     * @param array $args
     */
    public function sendLocalizedPopup(string $identifier, array $args = []): void {
        $this->owner->sendPopup($this->localizeMessage($identifier, $args));
    }
    
    /**
     * @param string $identifier
     * @param array $args
     */
    public function sendLocalizedTip(string $identifier, array $args = []): void {
        $this->owner->sendTip($this->localizeMessage($identifier, $args));
    }
    
}