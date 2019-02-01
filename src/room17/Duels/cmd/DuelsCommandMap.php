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

namespace room17\Duels\cmd;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use room17\Duels\cmd\presets\DuelsAcceptCommand;
use room17\Duels\cmd\presets\DuelsHelpCommand;
use room17\Duels\cmd\presets\DuelsInviteCommand;
use room17\Duels\cmd\presets\DuelsQueueCommand;
use room17\Duels\cmd\presets\DuelsRefuseCommand;
use room17\Duels\Duels;
use room17\Duels\event\cmd\DuelsCommandRegisterEvent;

class DuelsCommandMap extends Command implements PluginIdentifiableCommand {

    /** @var Duels */
    private $loader;
    
    /** @var DuelsCommand[] */
    private $commands = [];

    /**
     * DuelsCommandMap constructor.
     * @param Duels $loader
     * @throws \ReflectionException
     */
    public function __construct(Duels $loader) {
        $this->loader = $loader;
        $this->registerCommand(new DuelsHelpCommand());
        $this->registerCommand(new DuelsInviteCommand($this));
        $this->registerCommand(new DuelsQueueCommand($this));
        $this->registerCommand(new DuelsAcceptCommand($this));
        $this->registerCommand(new DuelsRefuseCommand($this));
        parent::__construct("duels", "", null, ["duel"]);
        $loader->getServer()->getCommandMap()->register("duels", $this);
    }

    /**
     * @return Plugin|Duels
     */
    public function getPlugin(): Plugin {
        return $this->loader;
    }
    
    /**
     * @return Duels
     */
    public function getLoader(): Duels {
        return $this->loader;
    }
    
    /**
     * @return DuelsCommand[]
     */
    public function getCommands(): array {
        return $this->commands;
    }
    
    /**
     * @param string $alias
     * @return null|DuelsCommand
     */
    public function getCommand(string $alias): ?DuelsCommand {
        foreach($this->commands as $key => $command) {
            if(in_array(strtolower($alias), $command->getAliases()) or $alias == $command->getName()) {
                return $command;
            }
        }
        return null;
    }

    /**
     * @param DuelsCommand $command
     * @throws \ReflectionException
     */
    public function registerCommand(DuelsCommand $command): void {
        $event = new DuelsCommandRegisterEvent($command);
        $event->call();
        if($event->isCancelled()) {
            $this->loader->getLogger()->debug("{$command->getName()} couldn't be registered");
        } else {
            $this->commands[] = $command;
        }
    }
    
    /**
     * @param DuelsCommand $command
     */
    public function unregisterCommand(DuelsCommand $command): void {
        $key = array_search($command, $this->commands);
        if($key) {
            unset($this->commands[$key]);
        } else {
            $this->loader->getLogger()->error("Duels couldn't unregister {$command->getName()} because it is not registered!");
        }
    }
    
    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if($sender instanceof Player) {
            $session = $this->loader->getSessionManager()->getSession($sender);
            if(isset($args[0]) and $this->getCommand($args[0]) != null) {
                $this->getCommand(array_shift($args))->onCommand($session, $args);
            } else {
                $session->sendLocalizedMessage("TRY_USING_HELP");
            }
        } else {
            $sender->sendMessage("Please, run this command in game");
        }
    }
    
}