<?php

namespace App\Enum;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;

enum Colors implements HasColor
{
    // Purple & Pink hues
    case Violet;
    case Lavender;
    case Plum;
    case Mulberry;
    case Magenta;
    case Rose;
    case Ruby;
    case SunsetRose;
    case CoralReef;
    case Coral;
    case Garnet;
    case Blush;
    case Fuchsia;
    case Orchid;

    // Red & Scarlet hues
    case Crimson;
    case Carmine;
    case Burgundy;
    case Scarlet;
    case Merlot;
    case Brick;

    // Blue & Aqua tones
    case Indigo;
    case Sky;
    case Azure;
    case Cobalt;
    case Sapphire;
    case Cerulean;
    case Ocean;
    case Glacier;
    case Lagoon;
    case Arctic;
    case Navy;
    case Denim;
    case Twilight;
    case Midnight;
    case Aqua;
    case Periwinkle;

    // Greens & Naturals
    case Emerald;
    case Moss;
    case Forest;
    case Mint;
    case Teal;
    case Jade;
    case Meadow;
    case Sage;
    case Pine;
    case Lime;
    case Chartreuse;

    // Warm & Sunny spectrum
    case Amber;
    case Gold;
    case Copper;
    case Sunrise;
    case Peach;
    case Papaya;
    case Citrus;
    case Marigold;
    case Tangerine;
    case Honey;

    // Earthy & Rustic neutrals
    case Rust;
    case Mulch;
    case Sand;
    case Clay;
    case Terracotta;

    // Stone & Monochrome
    case Slate;
    case Steel;
    case Charcoal;
    case Graphite;
    case Obsidian;
    case Onyx;
    case Flint;
    case Quartz;
    case Pearl;
    case Ivory;
    case Smoke;
    case Ash;

    // Pastel & Muted accents
    case Powder;
    case Seafoam;
    case Butter;
    case Mauve;
    case Mist;

    public function getColor(): string|array|null
    {
        return match ($this) {
            // Purple & Pink hues
            self::Violet => Color::hex('#7C3AED'),
            self::Lavender => Color::hex('#C084FC'),
            self::Plum => Color::hex('#7E22CE'),
            self::Mulberry => Color::hex('#9D174D'),
            self::Magenta => Color::hex('#BE185D'),
            self::Rose => Color::hex('#E11D48'),
            self::Ruby => Color::hex('#A4133C'),
            self::SunsetRose => Color::hex('#F472B6'),
            self::CoralReef => Color::hex('#FB7185'),
            self::Coral => Color::hex('#F87171'),
            self::Garnet => Color::hex('#991B1B'),
            self::Blush => Color::hex('#F9A8D4'),
            self::Fuchsia => Color::hex('#FF00FF'),
            self::Orchid => Color::hex('#DA70D6'),

            // Red & Scarlet hues
            self::Crimson => Color::hex('#DC143C'),
            self::Carmine => Color::hex('#960018'),
            self::Burgundy => Color::hex('#800020'),
            self::Scarlet => Color::hex('#FF2400'),
            self::Merlot => Color::hex('#733043'),
            self::Brick => Color::hex('#B22222'),

            // Blue & Aqua tones
            self::Indigo => Color::hex('#4338CA'),
            self::Sky => Color::hex('#0EA5E9'),
            self::Azure => Color::hex('#2563EB'),
            self::Cobalt => Color::hex('#1D4ED8'),
            self::Sapphire => Color::hex('#0F52BA'),
            self::Cerulean => Color::hex('#0891B2'),
            self::Ocean => Color::hex('#0369A1'),
            self::Glacier => Color::hex('#38BDF8'),
            self::Lagoon => Color::hex('#0EA5A0'),
            self::Arctic => Color::hex('#E0F2FE'),
            self::Navy => Color::hex('#1E3A8A'),
            self::Denim => Color::hex('#1E40AF'),
            self::Twilight => Color::hex('#312E81'),
            self::Midnight => Color::hex('#0F172A'),
            self::Aqua => Color::hex('#00FFFF'),
            self::Periwinkle => Color::hex('#A5B4FC'),

            // Greens & Naturals
            self::Emerald => Color::hex('#10B981'),
            self::Moss => Color::hex('#4D7C0F'),
            self::Forest => Color::hex('#065F46'),
            self::Mint => Color::hex('#34D399'),
            self::Teal => Color::hex('#0D9488'),
            self::Jade => Color::hex('#0F766E'),
            self::Meadow => Color::hex('#16A34A'),
            self::Sage => Color::hex('#A3B18A'),
            self::Pine => Color::hex('#14532D'),
            self::Lime => Color::hex('#84CC16'),
            self::Chartreuse => Color::hex('#D9F99D'),

            // Warm & Sunny spectrum
            self::Amber => Color::hex('#F59E0B'),
            self::Gold => Color::hex('#D97706'),
            self::Copper => Color::hex('#B45309'),
            self::Sunrise => Color::hex('#F97316'),
            self::Peach => Color::hex('#FDBA74'),
            self::Papaya => Color::hex('#FDBA5C'),
            self::Citrus => Color::hex('#FACC15'),
            self::Marigold => Color::hex('#F4B400'),
            self::Tangerine => Color::hex('#FF8C00'),
            self::Honey => Color::hex('#FBBF24'),

            // Earthy & Rustic neutrals
            self::Rust => Color::hex('#B7410E'),
            self::Mulch => Color::hex('#78350F'),
            self::Sand => Color::hex('#F5DEB3'),
            self::Clay => Color::hex('#B5651D'),
            self::Terracotta => Color::hex('#E07A5F'),

            // Stone & Monochrome
            self::Slate => Color::hex('#475569'),
            self::Steel => Color::hex('#64748B'),
            self::Charcoal => Color::hex('#1E293B'),
            self::Graphite => Color::hex('#111827'),
            self::Obsidian => Color::hex('#0B1120'),
            self::Onyx => Color::hex('#131313'),
            self::Flint => Color::hex('#4B5563'),
            self::Quartz => Color::hex('#D1D5DB'),
            self::Pearl => Color::hex('#E2E8F0'),
            self::Ivory => Color::hex('#FFF8E1'),
            self::Smoke => Color::hex('#6B7280'),
            self::Ash => Color::hex('#C7C7C7'),

            // Pastel & Muted accents
            self::Powder => Color::hex('#E0E7FF'),
            self::Seafoam => Color::hex('#CCFBF1'),
            self::Butter => Color::hex('#FEF3C7'),
            self::Mauve => Color::hex('#E8D1DC'),
            self::Mist => Color::hex('#E2E8F0'),
        };
    }
}
