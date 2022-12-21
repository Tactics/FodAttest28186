<?php

namespace Tactics\FodAttest28186\Enum;

/**
 * When sending your XML to the BOW-application it needs to be a FINAL document.
 * This really does not do much, but we try to keep it in line with how the FOD constructed their code.
 */
final class FodFileType extends Enum
{
    public const FINAL = 'BELCOTAX';

    public const TEST = 'BELCOTST';
}
