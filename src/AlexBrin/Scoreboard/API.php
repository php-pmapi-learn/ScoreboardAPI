<?php

namespace AlexBrin\Scoreboard;

use AlexBrin\Scoreboard\Models\Scoreboard;
use AlexBrin\Scoreboard\Models\ScoreboardManager;
use pocketmine\player\Player;

/**
 * Class API
 * @package AlexBrin\Scoreboard
 *
 * @author Alexander Gorenkov <g.a.androidjc2@ya.ru>
 */
class API
{
    /**
     * @var ScoreboardManager
     */
    private $manager;

    /**
     * @var API
     */
    private static $instance;

    public function __construct()
    {
        $this->manager = new ScoreboardManager;
    }

    /**
     * @param Player $player
     * @param string $title
     * @param array $lines
     * @return Scoreboard
     */
    public function create(Player $player, string $title, array $lines = []): Scoreboard {
        $sb = $this->manager->create($player);
        if(!$sb) {
            return null;
        }

        $sb->show($title);
        $sb->setLines($lines);

        return $sb;
    }

    /**
     * @param Player $player
     * @return Scoreboard|null
     */
    public function get(Player $player): ?Scoreboard {
        return $this->manager->get($player);
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isExists(Player $player): bool {
        return $this->manager->isExists($player);
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function delete(Player $player): bool {
        return $this->manager->delete($player);
    }

    /**
     * @return API
     */
    public static function getInstance(): API
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}