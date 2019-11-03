<?php

namespace AlexBrin\Scoreboard;

use pocketmine\plugin\PluginBase;

/**
 * Class Scoreboard
 * @package AlexBrin\Scoreboard
 *
 * @author Alexander Gorenkov <g.a.androidjc2@ya.ru>
 */
class Main extends PluginBase
{

    /**
     * {@inheritDoc}
     */
    public function onEnable()
    {
        $this->getLogger()->notice("Example for group lesson: https://vk.com/php_pmapi");
    }

    /**
     * @return API
     */
    public static function getAPI(): API
    {
        return API::getInstance();
    }
}

