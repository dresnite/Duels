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

namespace room17\Duels;


class DuelsSettings {
    
    const MESSAGE_FILE = "messages.json";
    const SETTINGS_FILE = "settings.json";
    
    /** @var Duels */
    private $loader;
    
    /** @var string[] */
    private $messages;
    
    /**
     * DuelsSettings constructor.
     * @param Duels $loader
     */
    public function __construct(Duels $loader) {
        $this->loader = $loader;
        $this->refreshData();
    }
    
    /**
     * @return Duels
     */
    public function getLoader(): Duels {
        return $this->loader;
    }
    /**
     * @return string[]
     */
    public function getMessages(): array {
        return $this->messages;
    }
    
    /**
     * @param string $identifier
     * @param array $args
     * @return string
     */
    public function getMessage(string $identifier, array $args = []): string {
        $message = $this->messages[$identifier] ?? "Message ($identifier) not found";
        foreach($args as $arg => $value) {
            $message = str_replace("{" . $arg . "}", $value, $message);
        }
        return Duels::translateColors($message);
    }
    
    public function refreshData(): void {
        $this->messages = json_decode(file_get_contents($this->loader->getDataFolder() . self::MESSAGE_FILE), true);
    }
    
}