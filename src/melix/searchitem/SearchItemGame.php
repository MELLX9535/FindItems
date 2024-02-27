<?php

namespace melix\searchitem;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\world\sound\XpLevelUpSound;

class SearchItemGame
{

    /** @var Item[] */
    public array $items = [];
    /** @var Item[] */
    public array $barrier = [];

    public int $time;
    public int $click;

    public bool $permissionClick = true;

    public InvMenu $invMenu;

    public function __construct(public string $owner)
    {
        $item = SearchItems::items();
        shuffle($item);
        $this->items = $item;

        $barrier = VanillaBlocks::INVISIBLE_BEDROCK()->asItem()->setCustomName("§7Нажми чтобы открыть предмет");
        for ($i = 0; $i < count($this->items); $i++) {
            $this->barrier[$i] = $barrier;
        }

        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $this->invMenu = $menu;
        $this->game();
    }

    public function game(): void
    {
        $menu = $this->invMenu;
        $player = Server::getInstance()->getPlayerExact($this->owner);

        if ($player == null) return;

        $menu->getInventory()->setContents($this->items);
        $menu->send($player, "Найди предметы");
        $this->permissionClick = false;

        SearchItemManager::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($menu): void {

            $menu->getInventory()->setContents($this->barrier);
            $this->permissionClick = true;
            $this->time = time();

        }), 20 * 3);

        $menu->setListener(function (InvMenuTransaction $transaction): InvMenuTransactionResult {

            $player = $transaction->getPlayer();
            $slot = $transaction->getAction()->getSlot();

            if (!isset($this->barrier[$slot]) or !isset($this->items[$slot])) return $transaction->discard();

            if (!$this->permissionClick) return $transaction->discard();
            if (isset($this->click)) {
                if ($this->click == $slot) {
                    return $transaction->discard();
                }
            }

            $this->clickSlot($slot, $player);

            return $transaction->discard();
        });
    }

    public function clickSlot(int $slot, Player $player): void
    {
        if (!isset($this->click)) {

            $item = $this->items[$slot];
            $items = $this->barrier;
            $items[$slot] = $item;

            $this->invMenu->getInventory()->setContents($items);
            $this->click = $slot;

        } else {

            $itemOld = $this->items[$this->click];
            $itemNew = $this->items[$slot];

            $items = $this->barrier;

            $items[$slot] = $itemNew;
            $items[$this->click] = $itemOld;

            $this->invMenu->getInventory()->setContents($items);

            if ($itemNew->getTypeId() == $itemOld->getTypeId()) {

                if (isset($this->barrier[$this->click])) unset($this->barrier[$this->click]);
                if (isset($this->barrier[$slot])) unset($this->barrier[$slot]);

                if (isset($this->items[$this->click])) unset($this->items[$this->click]);
                if (isset($this->items[$slot])) unset($this->items[$slot]);

                $player->getWorld()->addSound($player->getPosition()->asVector3(), new XpLevelUpSound(10));

                $this->permissionClick = false;
                SearchItemManager::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (): void {

                    $this->invMenu->getInventory()->setContents($this->barrier);
                    if (isset($this->click)) unset($this->click);
                    $this->permissionClick = true;

                    if (count($this->barrier) <= 0) {
                        $player = Server::getInstance()->getPlayerExact($this->owner);

                        if ($player == null) return;

                        $player->removeCurrentWindow();
                        $result = time() - $this->time;

                        SearchItemManager::getInstance()->setPlayerGameTime(strtolower($player->getName()), $result);

                        $time = SearchItemManager::secToArray($result);
                        $player->sendMessage(SearchItemManager::PREFIX . " Вы прошли игру, время игры: §g{$time["minutes"]}мин. {$time["secs"]}сек.");
                    }
                }), 20);
                return;
            }

            $this->permissionClick = false;

            unset($this->click);

            SearchItemManager::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (): void {

                $this->invMenu->getInventory()->setContents($this->barrier);
                $this->permissionClick = true;

            }), 20);
        }
    }

}