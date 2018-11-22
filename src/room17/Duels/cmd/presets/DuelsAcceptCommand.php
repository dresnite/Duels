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
use room17\Duels\Duels;
use room17\Duels\session\Session;

class DuelsAcceptCommand implements DuelsCommand {
    
    /** @var Duels */
    private $loader;
    
    /**
     * DuelsAcceptCommand constructor.
     * @param DuelsCommandMap $commandMap
     */
    public function __construct(DuelsCommandMap $commandMap) {
        $this->loader = $commandMap->getLoader();
    }
    
    /**
     * @return string
     */
    public function getName(): string {
        return "accept";
    }
    
    /**
     * @return array
     */
    public function getAliases(): array {
        return ["acc"];
    }
    
    /**
     * @param Session $session
     * @param array $args
     */
    public function onCommand(Session $session, array $args = []): void {
        if($session->hasMatch()) {
            $session->sendLocalizedMessage("YOU_ARE_ALREADY_PLAYING");
            return;
        } elseif(!isset($args[0])) {
            $lastOwner = $session->getLastInvitationOwner();
            if($lastOwner != null) {
                $session->clearInvitationFrom($lastOwner);
                $this->loader->getMatchManager()->startMatch($session, $lastOwner);
            } else {
                $session->sendLocalizedMessage("TYPE_THE_NAME_OF_THE_PLAYER");
            }
            return;
        }
        $player = $this->loader->getServer()->getPlayer($args[0]);
        if($player == null) {
            $session->sendLocalizedMessage("NOT_AN_ONLINE_PLAYER", [
                "name" => $args[0]
            ]);
            return;
        } elseif($player === $session->getOwner()) {
            $session->sendLocalizedMessage("CANNOT_DO_THIS_TO_YOURSELF");
            return;
        }
        $playerSession = $this->loader->getSessionManager()->getSession($player);
        if($playerSession->hasMatch()) {
            $session->sendLocalizedMessage("THE_PLAYER_IS_IN_A_MATCH", [
                "name" => $playerSession
            ]);
        } elseif($session->hasInvitationFrom($playerSession)) {
            $session->clearInvitationFrom($playerSession);
            $this->loader->getMatchManager()->startMatch($session, $playerSession);
        } else {
            $session->sendLocalizedMessage("THAT_INVITATION_DOES_NOT_EXIST", [
                "name" => $playerSession
            ]);
        }
    }
    
}