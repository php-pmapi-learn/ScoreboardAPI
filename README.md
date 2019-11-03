# English
**This plugin is an example for a [lesson](https://vk.com/@php_pmapi-scoreboard-pakety)**. Please, use [translate.google.com](https://translate.google.com)

The plugin is ready for use in production.

### Available method
#### API
Method name | Return type | Description
-----------|------------------|---------
create(Player $player, string $title, array $lines = []) | [\AlexBrin\Scoreboard\Models\Scoreboard](./src/AlexBrin/Scoreboard/Models/Scoreboard.php) | Creates a scoreboard or returns an existing one
get(Player $player) | [\AlexBrin\Scoreboard\Models\Scoreboard](./src/AlexBrin/Scoreboard/Models/Scoreboard.php) or null | Returns an existing scoreboard if it exists
delete(Player $player) | bool | Deletes the player's scoreboard. Will return false if scoreboard does not exist
isExists(Player $player) | bool | Checks if the player already has a scoreboard
#### Scoreboard
Some methods of this object have the parameter `bool $forceUpdate`. If it is `true', the update will be sent to the player immediately.

Method name | Return type | Description
-----------|------------------|---------
getPlayer() | Player | Returns the player to whom this scoreboard belongs
isVisible() | bool | is the scoreboard Shown to the player
show(string $title) | Scoreboard | Show scoreboard to player
hide() | Scoreboard | Hides the scoreboard to the player
setLine(int $line, string $content, bool $forceUpdate = true) | Scoreboard | Sets the specified string
removeLine(int $line) | Scoreboard | Removes a line from the scoreboard
setLines(string[] $lines, bool $forceUpdate = true) | Scoreboard | Updates multiple lines at once
update() | Scoreboard | Sends changes to the player

# Русский
**Этот плагин - пример для [урока](https://vk.com/@php_pmapi-scoreboard-pakety)**.

Плагин готов к использования на продакшене.

### Доступные методы
#### API
Имя метода | Возвращаемый тип | Описание
-----------|------------------|---------
create(Player $player, string $title, array $lines = []) | [\AlexBrin\Scoreboard\Models\Scoreboard](./src/AlexBrin/Scoreboard/Models/Scoreboard.php) | Создает скорборд или возвращает существующий
get(Player $player) | [\AlexBrin\Scoreboard\Models\Scoreboard](./src/AlexBrin/Scoreboard/Models/Scoreboard.php) или null | Возвращает существующий скорборд, если он существует
delete(Player $player) | bool | Удаляет скорборд игрока. Вернет false, если скорборд не существует
isExists(Player $player) | bool | Проверяет, существует ли уже скорборд у игрока
#### Scoreboard
У некоторых методов этого объекта присутствует параметр `bool $forceUpdate`. Если он равен `true`, то обновление отправится игроку незамедлительно.

Имя метода | Возвращаемый тип | Описание
-----------|------------------|---------
getPlayer() | Player | Возвращает игрока, которому приналлежит этот скорборд
isVisible() | bool | Показан ли скорборд игроку
show(string $title) | Scoreboard | Показывает игроку скорборд
hide() | Scoreboard | Скрывает игроку скорборд
setLine(int $line, string $content, bool $forceUpdate = true) | Scoreboard | Устанавливает указанную строку
removeLine(int $line) | Scoreboard | Удаляет строку из скорборда
setLines(string[] $lines, bool $forceUpdate = true) | Scoreboard | Обновляет сразу несколько строк
update() | Scoreboard | Отправляет изменения игроку