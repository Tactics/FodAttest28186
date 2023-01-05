<?php

namespace Tactics\FodAttest28186\Enum;

/**
 * For every Sheet (Tax Certificate) we must tell the FOD if it pertains
 * normal sheet (first delivery), change, addition or annulation
 *
 * Again this should not be up to us but in line with how the FOD has set up the XML
 */
final class FodSheetType extends Enum
{
    public const NORMAL = 0;

    public const CHANGE = 1;

    public const ADDITION = 2;

    public const ANNULATION = 3;
}
