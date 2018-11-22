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

class DuelsInviteCommand implements DuelsCommand {
    
    /** @var Duels */
    private $loader;
    
    /**
     * DuelsInviteCommand constructor.
     * @param DuelsCommandMap $commandMap
     */
    public function __construct(DuelsCommandMap $commandMap) {
        $this->loader = $commandMap->getLoader();
    }
    
    /**
     * @return string
     */
    public function getName(): string {
        return "invite";
    }
    
    /**
     * @return array
     */
    public function getAliases(): array {
        return ["inv"];
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
            $session->sendLocalizedMessage("TYPE_A_VALID_PLAYER");
            return;
        }
        $player = $this->loader->getServer()->getPlayer($args[0]);
        if($player == null) {
            $session->sendLocalizedMessage("NOT_AN_ONLINE_PLAYER", [
                "name" => $args[0]
            ]);
            return;
        } elseif($player === $session->getOwner()) {
            $session->sendLocalizedMessage("CANNOT_INVITE_YOURSELF");
            return;
        }
        $playerSession = $this->loader->getSessionManager()->getSession($player);
        if($playerSession->hasMatch()) {
            $session->sendLocalizedMessage("THE_PLAYER_IS_IN_A_MATCH", [
                "name" => $playerSession
            ]);
        } else {
            $playerSession->addInvitationFrom($session);
            $session->sendLocalizedMessage("YOU_SENT_AN_INVITATION",  [
                "name" => $playerSession
            ]);
            $playerSession->sendLocalizedMessage("YOU_RECEIVED_AN_INVITATION", [
                "from" => $session
            ]);
        }
    }
    
}