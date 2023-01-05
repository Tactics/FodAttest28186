<?php

namespace Tactics\FodAttest28186\Modal\Tariff;

use InvalidArgumentException;

final class TariffXmlMapper
{
    public const STARTDATE = 'startdate';
    public const ENDDATE = 'enddate';
    public const TARIFF = 'tariff';
    public const NUMBER_OF_DAYS = 'number_of_days';
    public const AMOUNT = 'amount';

    private const STARTDATE_1 = 'f86_2055_begindate1';
    private const ENDDATE_1 = 'f86_2056_enddate1';
    private const TARIFF_1 = 'f86_2111_dailytariff1';
    private const NUMBER_OF_DAYS_1 = 'f86_2110_numberofday1';
    private const AMOUNT_1 = 'f86_2060_amount1';

    private const STARTDATE_2 = 'f86_2093_begindate2';
    private const ENDDATE_2 = 'f86_2144_enddate2';
    private const TARIFF_2 = 'f86_2115_dailytariff2';
    private const NUMBER_OF_DAYS_2 = 'f86_2113_numberofday2';
    private const AMOUNT_2 = 'f86_2061_amount2';

    private const STARTDATE_3 = 'f86_2157_begindate3';
    private const ENDDATE_3 = 'f86_2158_enddate3';
    private const TARIFF_3 = 'f86_2117_dailytariff3';
    private const NUMBER_OF_DAYS_3 = 'f86_2116_numberofday3';
    private const AMOUNT_3 = 'f86_2062_amount3';

    private const STARTDATE_4 = 'f86_2161_begindate4';
    private const ENDDATE_4 = 'f86_2162_enddate4';
    private const TARIFF_4 = 'f86_2120_dailytariff4';
    private const NUMBER_OF_DAYS_4 = 'f86_2119_numberofday4';
    private const AMOUNT_4 = 'f86_2063_amount4';


    private const PERIOD_1 = [
        self::STARTDATE => self::STARTDATE_1,
        self::ENDDATE => self::ENDDATE_1,
        self::TARIFF => self::TARIFF_1,
        self::NUMBER_OF_DAYS => self::NUMBER_OF_DAYS_1,
        self::AMOUNT => self::AMOUNT_1,
    ];

    private const PERIOD_2 = [
        self::STARTDATE => self::STARTDATE_2,
        self::ENDDATE => self::ENDDATE_2,
        self::TARIFF => self::TARIFF_2,
        self::NUMBER_OF_DAYS => self::NUMBER_OF_DAYS_2,
        self::AMOUNT => self::AMOUNT_2,
    ];

    private const PERIOD_3 = [
        self::STARTDATE => self::STARTDATE_3,
        self::ENDDATE => self::ENDDATE_3,
        self::TARIFF => self::TARIFF_3,
        self::NUMBER_OF_DAYS => self::NUMBER_OF_DAYS_3,
        self::AMOUNT => self::AMOUNT_3,
    ];

    private const PERIOD_4 = [
        self::STARTDATE => self::STARTDATE_4,
        self::ENDDATE => self::ENDDATE_4,
        self::TARIFF => self::TARIFF_4,
        self::NUMBER_OF_DAYS => self::NUMBER_OF_DAYS_4,
        self::AMOUNT => self::AMOUNT_4,
    ];

    public static function xmlMap(int $period): array
    {
        switch ($period) {
            case 1:
                return self::PERIOD_1;
            case 2:
                return self::PERIOD_2;
            case 3:
                return self::PERIOD_3;
            case 4:
                return self::PERIOD_4;
        }

        throw new InvalidArgumentException(sprintf('Invalid period %d passed to TariffMapper', $period));
    }
}
