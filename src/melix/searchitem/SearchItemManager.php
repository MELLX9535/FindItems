<?php

namespace melix\searchitem;

use Frago9876543210\EasyForms\elements\Button;
use Frago9876543210\EasyForms\forms\MenuForm;
use JsonException;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;


class SearchItemManager extends PluginBase
{

    public static SearchItemManager $instance;

    public Config $config;

    const PREFIX = "§c§l§oFindItem§7×§r§f";
    const TITLE = "§c§l§oFindItem";

    public function onEnable(): void
    {
        self::$instance = $this;

        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->getServer()->getCommandMap()->register("searchitem", new SearchItemCommand("searchitem", "§bИгра: найди предметы"));
    }

    /**
     * @throws JsonException
     */
    public function onDisable(): void
    {
        $this->config->save();
    }

    public static function getInstance(): SearchItemManager
    {
        return self::$instance;
    }

    public function setPlayerGameTime(string $name, int $secs): void
    {
        $old = ($this->config->exists($name) ? $this->config->get($name) : 999999);
        if ($secs < $old) {
            $this->config->set($name, $secs);
        }
    }

    public static function secToArray($secs): array
    {
        $res = [];
        $res['days'] = floor($secs / 86400);
        $secs = $secs % 86400;

        $res['hours'] = floor($secs / 3600);
        $secs = $secs % 3600;

        $res['minutes'] = floor($secs / 60);
        $res['secs'] = $secs % 60;

        return $res;
    }

}

class SearchItemCommand extends Command
{

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        $this->setPermission(DefaultPermissions::ROOT_USER);
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        $player = $sender;
        if ($player instanceof Player) {

            $info =
                "Игра найди пару §7(Найди предметы)\n\n" .
                "§fВаша задача §7-§f найти §b§lодинаковые предметы§r\n" .
                "§fПеред вами находятся §aячейки§f с разными предметам,\n" .
                "которые §cскрыты§f от вас.\n" .
                "Одновременно вы можете открыть §b§lтолько две ячейки.§r§f\n\n" .
                "Запоминайте предметы, ищите им пары как можно быстрее!";

            $player->sendForm(new MenuForm(SearchItemManager::TITLE, $info,
                [
                    new Button("§lНачать игру"),
                    new Button("§lТоп игроков по времени")
                ],
                function (Player $player, Button $button): void {
                    $value = $button->getValue();
                    $name = strtolower($player->getName());

                    switch ($value) {

                        case 0:

                            new SearchItemGame($name);

                            break;

                        case 1:
                            $data = SearchItemManager::getInstance()->config->getAll();
                            asort($data);
                            $text = "\u{e1BA} §fТоп по времени прохождения \u{e1BA}\n\n";
                            $i = 1;
                            foreach ($data as $name => $secs) {
                                if ($i > 5) break;
                                $time = SearchItemManager::secToArray($secs);
                                $text .= "§e#1 §f$name §7- §g{$time["minutes"]}мин. {$time["secs"]}сек.\n";
                                $i++;
                            }

                            $player->sendForm(new MenuForm(SearchItemManager::TITLE, $text));
                            break;
                    }
                }
            ));
        }
    }
}