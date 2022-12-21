<?php

namespace Tactics\FodAttest28186\Enum;

/**
 * If the file is sent for the first time it is always an ORIGINAL. All following deliveries should be corrections.
 * This is a tough one to implement because you can not now if someone took the XML file and did or did not upload it to BOW.
 * This state actually shouldn't be managed by the implementator.
 */
final class FodSendCode extends Enum
{
    public const ORIGINAL = 0;

    public const CORRECTION = 1;
}
