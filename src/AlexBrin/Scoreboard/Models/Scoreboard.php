<?php

namespace AlexBrin\Scoreboard\Models;

use InvalidArgumentException;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\Player;

/**
 * Class Scoreboard
 * @package AlexBrin\Scoreboard\Models
 *
 * @author Alexander Gorenkov <g.a.androidjc2@ya.ru>
 */
final class Scoreboard
{
    private const objectiveName = "objective";

    /**
     * @var Player
     */
    private $player;

    /**
     * @var bool
     */
    private $isVisible = false;

    /**
     * @var string[]
     */
    private $lines = [];

    private $dirt = [];

    /**
     * Scoreboard constructor.
     * @param Player $player
     */
    public function __construct(Player &$player)
    {
        $this->player = $player;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    /**
     * Создает скорборд и показывает его игроку
     *
     * @param string $title
     * @return Scoreboard
     */
    public function show(string $title): Scoreboard
    {
        if ($this->isVisible()) {
            return $this;
        }

        $pk = new SetDisplayObjectivePacket;
        $pk->displaySlot = "sidebar";
        $pk->objectiveName = self::objectiveName;
        $pk->displayName = $title;
        $pk->criteriaName = "dummy";
        $pk->sortOrder = 0;

        $this->player->sendDataPacket($pk);
        $this->isVisible = true;

        return $this;
    }

    /**
     * @return Scoreboard
     */
    public function hide(): Scoreboard
    {
        if (!$this->isVisible()) {
            return $this;
        }

        $pk = new RemoveObjectivePacket;
        $pk->objectiveName = self::objectiveName;

        $this->player->sendDataPacket($pk);
        $this->isVisible = true;

        return $this;
    }


    /**
     * @param int $line
     * @param string $content
     * @param bool $forceUpdate Если true, то обновление отправится сразу
     * @return Scoreboard
     */
    public function setLine(int $line, string $content, bool $forceUpdate = true): Scoreboard
    {
        if ($line < 0 || $line > 14) {
            throw new InvalidArgumentException(sprintf("A maximum of 15 lines is allowed. Given line number: %s", count($this->lines) - 1));
        }

        $this->lines[$line] = $content;
        if (!in_array($line, $this->dirt)) {
            $this->dirt[] = $line;
        }

        if ($forceUpdate) {
            $this->update();
        }

        return $this;
    }

    /**
     * Удаляет одну строку
     *
     * @param int $line
     * @return Scoreboard
     */
    public function removeLine(int $line): Scoreboard
    {
        if ($line > 15) {
            throw new InvalidArgumentException(sprintf("A maximum of 15 lines is allowed. Given line number: %s", $line));
        }

        if (!isset($this->lines[$line])) {
            throw new InvalidArgumentException(sprintf("This line does not exist. Allowed values: %s", implode(', ', array_keys($this->lines))));
        }

        unset($this->lines[$line]);
        $this->player->dataPacket(self::prepareRemoveLinesPacket([$line]));
        return $this;
    }

    /**
     * Подготавливает пакет для удаления существующих линий
     *
     * @param int[] $lines
     * @return SetScorePacket
     */
    private static function prepareRemoveLinesPacket(array $lines): SetScorePacket
    {
        $pk = new SetScorePacket;
        $pk->type = SetScorePacket::TYPE_REMOVE;

        foreach ($lines as $line) {
            $entry = new ScorePacketEntry;
            $entry->objectiveName = self::objectiveName;
            $entry->score = $line;
            $entry->scoreboardId = $line;
            $pk->entries[] = $entry;
        }

        return $pk;
    }

    /**
     * Устанавливает строки
     *
     * @param string[] $lines
     * @param bool $forceUpdate Если true, то обновление отправится сразу
     * @return Scoreboard
     */
    public function setLines(array $lines, bool $forceUpdate = true): Scoreboard
    {
        if (count($lines) > 15) {
            throw new InvalidArgumentException(sprintf("A maximum of 15 lines is allowed. Given line number: %s", count($this->lines) - 1));
        }

        foreach ($lines as $score => $line) {
            if (isset($this->lines[$score]) && $this->lines[$score] != $line) {
                $this->dirt[] = $score;
            }
        }

        $this->lines = $lines;

        if ($forceUpdate) {
            $this->update();
        }

        return $this;
    }

    /**
     * Обновляет строки скорборда у клиента
     *
     * @return Scoreboard
     */
    public function update(): Scoreboard
    {
        if (!$this->isVisible()) {
            throw new InvalidArgumentException("You cannot change the contents of a hidden scoreboard");
        }

        $batch = new BatchPacket;
        $batch->addPacket(self::prepareRemoveLinesPacket($this->dirt));

        /** @var ScorePacketEntry $entries */
        $entries = [];

        foreach ($this->lines as $score => $line) {
            $pk = new ScorePacketEntry;
            $pk->customName = $line;
            $pk->objectiveName = self::objectiveName;
            $pk->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
            $pk->score = $score;
            $pk->scoreboardId = $score;
            $entries[] = $pk;
        }

        $pk = new SetScorePacket;
        $pk->type = SetScorePacket::TYPE_CHANGE;
        $pk->entries = $entries;

        $batch->addPacket($pk);

        $this->player->sendDataPacket($batch);

        return $this;
    }
}