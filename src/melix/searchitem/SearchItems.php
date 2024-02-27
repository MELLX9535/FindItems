<?php

namespace melix\searchitem;

use pocketmine\block\utils\DyeColor;
use pocketmine\block\utils\MobHeadType;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

class SearchItems
{

    /** @return Item[] */
    public static function items(): array
    {
        return [
            VanillaItems::APPLE(),
            VanillaItems::APPLE(),

            VanillaItems::BOW(),
            VanillaItems::BOW(),

            VanillaItems::DIAMOND(),
            VanillaItems::DIAMOND(),

            VanillaItems::IRON_INGOT(),
            VanillaItems::IRON_INGOT(),

            VanillaItems::COMPASS(),
            VanillaItems::COMPASS(),
            //10
            VanillaItems::GOLDEN_APPLE(),
            VanillaItems::GOLDEN_APPLE(),

            VanillaItems::BLAZE_POWDER(),
            VanillaItems::BLAZE_POWDER(),

            VanillaItems::MAGMA_CREAM(),
            VanillaItems::MAGMA_CREAM(),

            VanillaBlocks::DRAGON_EGG()->asItem(),
            VanillaBlocks::DRAGON_EGG()->asItem(),

            VanillaItems::TOTEM(),
            VanillaItems::TOTEM(),
            //20
            VanillaBlocks::COBWEB()->asItem(),
            VanillaBlocks::COBWEB()->asItem(),

            VanillaBlocks::SOUL_FIRE()->asItem(),
            VanillaBlocks::SOUL_FIRE()->asItem(),

            VanillaItems::PAINTING(),
            VanillaItems::PAINTING(),

            VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::DRAGON())->asItem(),
            VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::DRAGON())->asItem(),

            VanillaItems::EMERALD(),
            VanillaItems::EMERALD(),
            //30

            VanillaBlocks::WOOL()->setColor(DyeColor::LIME())->asItem(),
            VanillaBlocks::WOOL()->setColor(DyeColor::LIME())->asItem(),

            VanillaItems::BUCKET(),
            VanillaItems::BUCKET(),

            VanillaItems::FLINT(),
            VanillaItems::FLINT(),

            VanillaItems::BONE(),
            VanillaItems::BONE(),

            VanillaBlocks::ENDER_CHEST()->asItem(),
            VanillaBlocks::ENDER_CHEST()->asItem(),
            //40
            VanillaItems::EMERALD(),
            VanillaItems::EMERALD(),

            VanillaBlocks::FIRE()->asItem(),
            VanillaBlocks::FIRE()->asItem(),
            //44
            VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PIGLIN())->asItem(),
            VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PIGLIN())->asItem(),

            VanillaItems::AMETHYST_SHARD(),
            VanillaItems::AMETHYST_SHARD(),

            VanillaItems::NETHER_STAR(),
            VanillaItems::NETHER_STAR(),

            VanillaItems::SPYGLASS(),
            VanillaItems::SPYGLASS(),

            VanillaBlocks::DANDELION()->asItem(),
            VanillaBlocks::DANDELION()->asItem(),
        ];
    }
}