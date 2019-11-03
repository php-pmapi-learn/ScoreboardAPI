<?php

namespace AlexBrin\Scoreboard\Models;

use pocketmine\Player;

/**
 * Class ScoreboardManager
 * @package AlexBrin\Scoreboard\Models
 *
 * @author Alexander Gorenkov <g.a.androidjc2@ya.ru>
 */
final class ScoreboardManager
{
    /**
     * @var Scoreboard[]
     */
    protected $scoreboards = [];

    /**
     * @param Player $player
     * @return Scoreboard|null
     */
    public function get(Player $player): ?Scoreboard
    {
        if (!$this->isExists($player)) {
            return null;
        }

        return $this->scoreboards[$player->getName()];
    }

    /**
     * @param Scoreboard $scoreboard
     */
    public function set(Scoreboard &$scoreboard) {
        if($this->isExists($scoreboard->getPlayer())) {
            $this->delete($scoreboard->getPlayer());
        }

        $this->scoreboards[$scoreboard->getPlayer()->getName()] = $scoreboard;
    }

    /**
     * Проверка существования скорборда у игрока
     * @param Player $player
     * @return bool
     */
    public function isExists(Player $player): bool
    {
        return isset($this->scoreboards[$player->getName()]);
    }

    /**
     * Создание скорборда
     * Если скорборд уже существует, то вернется уже существующий
     *
     * @param Player $player
     * @return Scoreboard|null
     */
    public function create(Player $player): ?Scoreboard
    {
        if ($this->isExists($player)) {
            return $this->get($player);
        }

        // Создаем в отдельной переменной, чтобы передать ссылки на этот объект
        // Ссылки позволят реализовать эдакий синглтон для наших скорбордов. Что это - расскажет википедия :)
        $sb = new Scoreboard($player);
        $this->set($sb);
        return $this->get($player);
    }

    /**
     * Удаление скорборда у игрока
     *
     * @param Player $player
     * @return bool
     */
    public function delete(Player $player): bool {
        if($this->isExists($player)) {
            return false;
        }

        $this->scoreboards[$player->getName()]->hide();
        unset($this->scoreboards[$player->getName()]);
        return true;
    }
}