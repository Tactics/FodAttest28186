<?php

namespace Tactics\FodAttest28186\Enum;

final class FodCertificationCode extends Enum
{
    /**
     * Code to be used when there is no Certifier.
     * Using this code also means the other certification fields must not be filled in.
     */
    public const NOT_APPLICABLE = 0;

    /**
     * Code to be used when daycare is certified by Opgroeien Regie.
     * That they are allowed, recognized, subsidized, controlled or supervised, or have received
     * a quality label from "l'Office de la Naissance et de l'Enfance" or "Kind & Gezin" / "Opgroeien regie"
     * or from the German-speaking Community
     */
    public const OPGROEIEN_REGIE = 1;

    /**
     * that they are allowed, recognized, subsidized, or controlled by the local, community,
     * or regional government authorities
     */
    public const REGIONAL_GOVERNMENT = 2;

    /**
     * that they are allowed, recognized, subsidized, or controlled by foreign government institutions located
     * in another member state of the European Economic Area
     */
    public const EUROPEAN_GOVERNMENT = 3;

    /**
     * that they have a connection with a school located in a member state of the European Economic Area
     * or with the governing body of a school located in a member state of the European Economic Area,
     * in accordance with 145/35, paragraph 2, 3°, of the Income Tax Code.
     */
    public const SCHOOL = 4;
}
