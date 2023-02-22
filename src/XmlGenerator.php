<?php

namespace Tactics\FodAttest28186;

use Tactics\FodAttest28186\Enum\FodCountryCode;
use Tactics\FodAttest28186\Enum\FodFileType;
use Tactics\FodAttest28186\Enum\FodLanguageCode;
use Tactics\FodAttest28186\Enum\FodSendCode;
use Tactics\FodAttest28186\Model\Certifier\Certifier;
use Tactics\FodAttest28186\Model\Child\ChildDetails;
use Tactics\FodAttest28186\Model\Child\ChildWithNationalRegistry;
use Tactics\FodAttest28186\Model\Debtor\Debtor;
use Tactics\FodAttest28186\Model\Debtor\DebtorDetails;
use Tactics\FodAttest28186\Model\InvoiceAgency\Company as InvoiceAgencyCompany;
use Tactics\FodAttest28186\Model\InvoiceAgency\InvoiceAgency;
use Tactics\FodAttest28186\Model\InvoiceAgency\Person as InvoiceAgencyPerson;
use Tactics\FodAttest28186\Model\Sender\Company;
use Tactics\FodAttest28186\Model\Sender\Person;
use Tactics\FodAttest28186\Model\Sender\Sender;
use Tactics\FodAttest28186\Model\Tariff\Tariff;
use Tactics\FodAttest28186\Model\Tariff\TariffCollection;
use Tactics\FodAttest28186\Model\TaxSheet\TaxSheet;
use Tactics\FodAttest28186\Model\TaxSheet\TaxSheetMap;

final class XmlGenerator
{
    private const DATE_FORMAT = 'd-m-Y';

    private int $year;

    private FodFileType $fileType;

    private Sender $sender;

    private FodSendCode $sendCode;

    private InvoiceAgency $invoiceAgency;

    private TaxSheetMap $sheetCollection;

    private int $sheetCounter = 0;

    private int $totalAmount = 0;

    /**
     * @param int $year
     * @param FodFileType $fileType
     * @param Sender $sender
     * @param FodSendCode $sendCode
     * @param InvoiceAgency $invoiceAgency
     * @param TaxSheetMap $sheetCollection
     */
    public function __construct(
        int $year,
        FodFileType $fileType,
        Sender $sender,
        FodSendCode $sendCode,
        InvoiceAgency $invoiceAgency,
        TaxSheetMap $sheetCollection
    ) {
        $this->year = $year;
        $this->fileType = $fileType;
        $this->sender = $sender;
        $this->sendCode = $sendCode;
        $this->invoiceAgency = $invoiceAgency;
        $this->sheetCollection = $sheetCollection;
    }

    public function generate(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<Verzendingen><Verzending>';
        $xml .= $this->senderInfo();
        $xml .= '<Aangiften><Aangifte>';
        $xml .= $this->declarationInfo();
        $xml .= '<Opgaven><Opgave32586>';
        $xml .= $this->sheets();
        $xml .= '</Opgave32586></Opgaven>';
        $xml .= $this->controlNumbers();
        $xml .= '</Aangifte></Aangiften>';
        $xml .= $this->controlNumbersTotal();
        $xml .= '</Verzending></Verzendingen>';

        return $xml;
    }

    private function senderInfo(): string
    {
        [$date, $senderName, $senderAddress, $senderEmail] = $this->prepareSenderInfo();

        $xml = <<<EOT
    <v0002_inkomstenjaar>$this->year</v0002_inkomstenjaar>
    <v0010_bestandtype>{$this->fileType->value()}</v0010_bestandtype>
    <v0011_aanmaakdatum>$date</v0011_aanmaakdatum>
    <v0014_naam>$senderName</v0014_naam>
    <v0015_adres>$senderAddress</v0015_adres>
    <v0016_postcode>{$this->sender->address()->postal()}</v0016_postcode>
    <v0017_gemeente>{$this->sender->address()->city()}</v0017_gemeente>
    <v0018_telefoonnummer>{$this->sender->phoneNumber()->value()}</v0018_telefoonnummer>
    <v0021_contactpersoon>{$this->sender->contactName()}</v0021_contactpersoon>
    <v0022_taalcode>{$this->sender->contactLanguageCode()->value()}</v0022_taalcode>
    <v0023_emailadres>$senderEmail</v0023_emailadres>
EOT;

        if ($this->sender instanceof Company) {
            $xml .= sprintf('<v0024_nationaalnr>%s</v0024_nationaalnr>', $this->sender->identifier());
        }

        $xml .= <<<EOT
    <v0025_typeenvoi>{$this->sendCode->value()}</v0025_typeenvoi>
    <v0028_landwoonplaats>{$this->sender->address()->countryCode()->value()}</v0028_landwoonplaats>
EOT;

        if ($this->sender instanceof Person) {
            $xml .= sprintf('<v0030_nationaalnummer>%s</v0030_nationaalnummer>', $this->sender->identifier());
        }

        return $xml;
    }

    private function prepareSenderInfo(): array
    {
        $date = date(self::DATE_FORMAT);
        $senderName = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $this->sender->name(),
                $this->nameMaxLength()
            )
        );

        $senderAddress = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $this->sender->address()->addressLine(),
                $this->addressMaxLength()
            )
        );

        $senderEmail = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $this->sender->email(),
                $this->emailMaxLength()
            )
        );

        return [$date, $senderName, $senderAddress, $senderEmail];
    }

    private function declarationInfo(): string
    {
        [$splitName, $addressLine, $postal, $city, $countryCode, $languageCodeDutch] = $this->prepareDeclarationInfo();

        $xml = "<a1002_inkomstenjaar>$this->year</a1002_inkomstenjaar>";

        if ($this->invoiceAgency instanceof InvoiceAgencyCompany) {
            $xml .= sprintf('<a1005_registratienummer>%s</a1005_registratienummer>', $this->invoiceAgency->identifier());
        }

        if ($this->invoiceAgency instanceof InvoiceAgencyCompany && $this->invoiceAgency->division()) {
            $xml .= sprintf('<a1007_division>%s</a1007_division>', $this->invoiceAgency->division()->value());
        }

        $splitNameNl = $splitName;
        $xml .= sprintf('<a1011_naamnl1>%s</a1011_naamnl1>', array_shift($splitNameNl));
        if (!empty($splitNameNl)) {
            $xml .= sprintf('<a1012_naamnl2>%s</a1012_naamnl2>', implode('', $splitNameNl));
        }


        $xml .= <<<EOT
    <a1013_adresnl>$addressLine</a1013_adresnl>
    <a1014_postcodebelgisch>$postal</a1014_postcodebelgisch>
    <a1015_gemeente>$city</a1015_gemeente>
    <a1016_landwoonplaats>$countryCode</a1016_landwoonplaats>
    <a1020_taalcode>$languageCodeDutch</a1020_taalcode>
EOT;

        if ($this->invoiceAgency instanceof InvoiceAgencyPerson) {
            $xml .= sprintf('<a1037_nationaalnr>%s</a1037_nationaalnr>', $this->invoiceAgency->identifier());
        }

        return $xml;
    }

    private function prepareDeclarationInfo(): array
    {
        $formattedName = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $this->invoiceAgency->name(),
                $this->declarationNameMaxLength()
            )
        );
        $splitName = $this->splitName($formattedName);
        $addressLine = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $this->invoiceAgency->address()->addressLine(),
                $this->addressMaxLength()
            )
        );
        $postal = $this->invoiceAgency->address()->postal();
        $city = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $this->invoiceAgency->address()->city(),
                $this->cityMaxLength()
            )
        );

        $countryCode = $this->invoiceAgency->address()->countryCode()->value();

        $languageCodeDutch = FodLanguageCode::DUTCH;

        return [$splitName, $addressLine, $postal, $city, $countryCode, $languageCodeDutch];
    }

    private function sheets(): string
    {
        $xml = '';

        foreach ($this->sheetCollection->getIterator() as $sheet) {
            foreach ($sheet->tariffGroups() as $collection) {
                $xml .= $this->sheet($sheet, $collection);
            }
        }

        return $xml;
    }

    private function sheet(TaxSheet $sheet, TariffCollection $tariffCollection): string
    {
        $this->sheetCounter++;

        [$rrn, $country, $debtorDetails] = $this->prepareDebtorInfo($sheet->debtor());
        if ($debtorDetails) {
            [$debtorName, $debtorFirstName, $debtorAddressCity, $debtorAddressLine, $debtorPostal] = $this->prepareDebtorDetails($debtorDetails);
        }

        $child = $sheet->child();
        $childDetails = $child->details();
        if ($childDetails) {
            [$childName, $childFirstName, $childAddressCity, $childAddress, $childAddressLine, $formattedChildDayOfBirth] = $this->prepareChildDetails($childDetails);
        }

        $certifier = $this->invoiceAgency->certifier();
        if ($certifier instanceof Certifier) {
            [$certifierName, $certifierAddressCity, $certifierAddressLine, $certifierPostal] = $this->prepareCertifierDetails($certifier);
        }

        $xml = <<<EOT
    <Fiche28186>
    <f2002_inkomstenjaar>$this->year</f2002_inkomstenjaar>
    <f2005_registratienummer>{$this->sender->identifier()}</f2005_registratienummer>
    EOT;

        if ($this->sender instanceof InvoiceAgencyCompany && $this->sender->division()) {
            $xml .= sprintf('<f2007_division>%s</f2007_division>', $this->sender->division()->value());
        }

        $xml .= <<<EOT
    <f2008_typefiche>28186</f2008_typefiche>
    <f2009_volgnummer>$this->sheetCounter</f2009_volgnummer>
    <f2010_referentie>{$sheet->uid()->toString()}</f2010_referentie>
    <f2011_nationaalnr>$rrn</f2011_nationaalnr>
    <f2012_geboortedatum/>
EOT;
        if ($debtorDetails) {
            $xml .= <<< EOT
                <f2013_naam>$debtorName</f2013_naam>
                <f2014_naampartner/>
                <f2015_adres>$debtorAddressLine</f2015_adres>
EOT;
            if ($country->value() === FodCountryCode::BELGIUM) {
                $xml .= "<f2016_postcodebelgisch>$debtorPostal</f2016_postcodebelgisch>";
            }

            $xml .= <<< EOT
                <f2017_gemeente>$debtorAddressCity</f2017_gemeente>
 EOT;
        } else {
            $xml .= <<< EOT
                <f2013_naam/>
                <f2014_naampartner/>
                <f2015_adres/>
                <f2016_postcodebelgisch/>
                <f2017_gemeente/>
EOT;
        }

        $xml .= <<< EOT
    <f2018_landwoonplaats>{$country->value()}</f2018_landwoonplaats>
    <f2028_typetraitement>{$sheet->type()->value()}</f2028_typetraitement>
    <f2029_enkelopgave325>0</f2029_enkelopgave325>
EOT;

        if ($debtorDetails) {
            if ($country->value() !== FodCountryCode::BELGIUM) {
                $xml .= "<f2112_buitenlandspostnummer>$debtorPostal</f2112_buitenlandspostnummer>";
            }
            $xml .= <<<EOT
                <f2114_voornamen>$debtorFirstName</f2114_voornamen>
EOT;
        }

        if ($certifier instanceof Certifier) {
            $xml .= <<<EOT
    <f86_2031_certificationautorisation>{$certifier->certificationCode()->value()}</f86_2031_certificationautorisation>
EOT;
        } else {
            $xml .= <<<EOT
    <f86_2031_certificationautorisation />
EOT;
        }

        $totalAmount = $tariffCollection->sum();
        $totalControlAmount = $totalAmount * 2;
        $this->totalAmount += $totalControlAmount;

        [$tariff1BeginDate, $tariff1EndDate, $tariff1Tariff, $tariff1Days, $tariff1Total] = $this->prepareTariff($tariffCollection, 1);
        [$tariff2BeginDate, $tariff2EndDate, $tariff2Tariff, $tariff2Days, $tariff2Total] = $this->prepareTariff($tariffCollection, 2);
        [$tariff3BeginDate, $tariff3EndDate, $tariff3Tariff, $tariff3Days, $tariff3Total] = $this->prepareTariff($tariffCollection, 3);
        [$tariff4BeginDate, $tariff4EndDate, $tariff4Tariff, $tariff4Days, $tariff4Total] = $this->prepareTariff($tariffCollection, 4);

        $xml .= <<<EOT
                <f86_2055_begindate1>$tariff1BeginDate</f86_2055_begindate1>
                <f86_2056_enddate1>$tariff1EndDate</f86_2056_enddate1>
                <f86_2059_totaalcontrole>$totalControlAmount</f86_2059_totaalcontrole>
                <f86_2060_amount1>$tariff1Total</f86_2060_amount1>
                <f86_2061_amount2>$tariff2Total</f86_2061_amount2>
                <f86_2062_amount3>$tariff3Total</f86_2062_amount3>
                <f86_2063_amount4>$tariff4Total</f86_2063_amount4>
                <f86_2064_totalamount>$totalAmount</f86_2064_totalamount>
                <f86_2093_begindate2>$tariff2BeginDate</f86_2093_begindate2>
EOT;

        if ($certifier instanceof Certifier) {
            $xml .= <<<EOT
    <f86_2100_certifierpostnr>$certifierPostal</f86_2100_certifierpostnr>
EOT;
        } else {
            $xml .= <<<EOT
    <f86_2100_certifierpostnr />
EOT;
        }

        if ($childDetails) {
            $xml .= <<<EOT
    <f86_2101_childcountry>{$childAddress->countryCode()->value()}</f86_2101_childcountry>
    <f86_2102_childaddress>$childAddressLine</f86_2102_childaddress>
    <f86_2106_childname>$childName</f86_2106_childname>
    <f86_2107_childfirstname>$childFirstName</f86_2107_childfirstname>
EOT;
        } else {
            $xml .= <<<EOT
    <f86_2101_childcountry />
    <f86_2102_childaddress />
    <f86_2106_childname />
    <f86_2107_childfirstname />
EOT;
        }

        if ($certifier instanceof Certifier) {
            $xml .= <<<EOT
    <f86_2109_certifiercbenumber>{$certifier->companyNumber()->value()}</f86_2109_certifiercbenumber>
EOT;
        } else {
            $xml .= <<<EOT
    <f86_2109_certifiercbenumber />
EOT;
        }

        $xml .= <<<EOT
    <f86_2110_numberofday1>$tariff1Days</f86_2110_numberofday1>
    <f86_2111_dailytariff1>$tariff1Tariff</f86_2111_dailytariff1>
    <f86_2113_numberofday2>$tariff2Days</f86_2113_numberofday2>
    <f86_2115_dailytariff2>$tariff2Tariff</f86_2115_dailytariff2>
    <f86_2116_numberofday3>$tariff3Days</f86_2116_numberofday3>
    <f86_2117_dailytariff3>$tariff3Tariff</f86_2117_dailytariff3>
    <f86_2119_numberofday4>$tariff4Days</f86_2119_numberofday4>
    <f86_2120_dailytariff4>$tariff4Tariff</f86_2120_dailytariff4>
EOT;

        if ($childDetails) {
            $xml .= <<<EOT
    <f86_2139_childpostnr>{$childAddress->postal()}</f86_2139_childpostnr>
    <f86_2140_childmunicipality>{$childAddressCity}</f86_2140_childmunicipality>
EOT;
        }

        $xml .= <<<EOT
    <f86_2144_enddate2>$tariff2EndDate</f86_2144_enddate2>
EOT;

        if ($child instanceof ChildWithNationalRegistry) {
            $xml .= <<<EOT
    <f86_2153_nnchild>{$child->nationalRegistryNumber()->value()}</f86_2153_nnchild>
EOT;
        }

        if ($certifier instanceof Certifier) {
            $xml .= <<<EOT
    <f86_2154_certifiermunicipality>$certifierAddressCity</f86_2154_certifiermunicipality>
    <f86_2155_certifiername>$certifierName</f86_2155_certifiername>
    <f86_2156_certifieradres>$certifierAddressLine</f86_2156_certifieradres>
EOT;
        }

        $xml .= <<<EOT
    <f86_2157_begindate3>$tariff3BeginDate</f86_2157_begindate3>
    <f86_2158_enddate3>$tariff3EndDate</f86_2158_enddate3>
    <f86_2161_begindate4>$tariff4BeginDate</f86_2161_begindate4>
    <f86_2162_enddate4>$tariff4EndDate</f86_2162_enddate4>
EOT;

        if ($childDetails) {
            $xml .= <<<EOT
    <f86_2163_childbirthdate>$formattedChildDayOfBirth</f86_2163_childbirthdate>
EOT;
        }

        $xml .= '</Fiche28186>';

        return $xml;
    }

    private function prepareTariff(TariffCollection $tariffCollection, int $tariffNumber): array
    {
        $tariff = $tariffCollection->getTariff($tariffNumber);
        $tariffBeginDate = $tariff ? $tariff->period()->begin()->format(self::DATE_FORMAT) : null;
        $tariffEndDate = $tariff ? $tariff->period()->end()->format(self::DATE_FORMAT) : null;
        $tariffTariff = $tariff ? $tariff->tariff() : null;
        $tariffDays = $tariff ? $tariff->days() : null;
        $tariffTotal = $tariff ? $tariffDays * $tariffTariff : null;

        return [$tariffBeginDate, $tariffEndDate, $tariffTariff, $tariffDays, $tariffTotal];
    }

    private function prepareDebtorInfo(Debtor $debtor): array
    {
        $rrn = $debtor->nationalRegistryNumber()->value();

        // Due to a bug/inconsistency in the tool we always need to provide the country code.
        // We don't want to make this required on the debtor since this is a mistake.
        // So we extract it first here or fallback to a default.
        $country = $debtor->details() ?
            $debtor->details()->address()->countryCode() :
            FodCountryCode::from(FodCountryCode::BELGIUM);

        $debtorDetails = $debtor->details();

        return [$rrn, $country, $debtorDetails];
    }

    private function prepareDebtorDetails(DebtorDetails $debtorDetails): array
    {
        $debtorName = $this->escapeInvalidXmlChars($this->formatMaxLength($debtorDetails->familyName(), $this->nameMaxLength()));
        $debtorFirstName = $this->escapeInvalidXmlChars($this->formatMaxLength($debtorDetails->givenName(), $this->firstnameDebtorMaxLength()));
        $debtorAddress = $debtorDetails->address();
        $debtorAddressCity = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $debtorAddress->city(),
                $this->cityMaxLength()
            )
        );
        $debtorAddressLine = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $debtorAddress->addressLine(),
                $this->addressMaxLength()
            )
        );
        $debtorPostal = $debtorDetails->address()->postal();

        return [$debtorName, $debtorFirstName, $debtorAddressCity, $debtorAddressLine, $debtorPostal];
    }

    //TODO: DebtorDetails and ChildDetails should be an interface so these two functions can be abstracted
    private function prepareChildDetails(ChildDetails $childDetails): array
    {
        $childName = $this->escapeInvalidXmlChars($this->formatMaxLength($childDetails->familyName(), $this->nameMaxLength()));
        $childFirstName = $this->escapeInvalidXmlChars($this->formatMaxLength($childDetails->givenName(), $this->firstnameMaxLength()));
        $childAddress = $childDetails->address();
        $childAddressCity = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $childAddress->city(),
                $this->cityMaxLength()
            )
        );
        $childAddressLine = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $childAddress->addressLine(),
                $this->childAddressMaxLength()
            )
        );
        $formattedChildDayOfBirth = $childDetails->dayOfBirth()->format();

        return [$childName, $childFirstName, $childAddressCity, $childAddress, $childAddressLine, $formattedChildDayOfBirth];
    }

    private function prepareCertifierDetails(Certifier $certifier): array
    {
        $certifierName = $this->escapeInvalidXmlChars($this->formatMaxLength($certifier->name(), $this->nameMaxLength()));
        $certifierAddress = $certifier->address();
        $certifierAddressCity = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $certifierAddress->city(),
                $this->cityMaxLength()
            )
        );
        $certifierAddressLine = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $certifierAddress->addressLine(),
                $this->addressMaxLength()
            )
        );
        $certifierPostal = $certifierAddress->postal();

        return [$certifierName, $certifierAddressCity, $certifierAddressLine, $certifierPostal];
    }

    private function controlNumbers(): string
    {
        $totalRecords = $this->sheetCounter + 2;
        $triangularNumberRecords = ($this->sheetCounter * ($this->sheetCounter + 1)) / 2;

        $xml = <<<EOT
    <r8002_inkomstenjaar>$this->year</r8002_inkomstenjaar>
    <r8005_registratienummer>{$this->sender->identifier()}</r8005_registratienummer>
    EOT;

        if ($this->sender instanceof InvoiceAgencyCompany && $this->sender->division()) {
            $xml .= sprintf('<r8007_division>%s</r8007_division>', $this->sender->division()->value());
        }

        $xml .= <<<EOT
    <r8010_aantalrecords>$totalRecords</r8010_aantalrecords>
    <r8011_controletotaal>$triangularNumberRecords</r8011_controletotaal>
    <r8012_controletotaal>$this->totalAmount</r8012_controletotaal>
EOT;

        return $xml;
    }

    private function controlNumbersTotal(): string
    {
        $totalRecords = $this->sheetCounter + 4;
        $triangularNumberRecords = ($this->sheetCounter * ($this->sheetCounter + 1)) / 2;

        return <<<EOT
    <r9002_inkomstenjaar>$this->year</r9002_inkomstenjaar>
    <r9010_aantallogbestanden>3</r9010_aantallogbestanden>
    <r9011_totaalaantalrecords>$totalRecords</r9011_totaalaantalrecords>
    <r9012_controletotaal>$triangularNumberRecords</r9012_controletotaal>
    <r9013_controletotaal>$this->totalAmount</r9013_controletotaal>
EOT;
    }

    private function escapeInvalidXmlChars(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }

    private function formatMaxLength(string $value, int $length): string
    {
        return substr($value, 0, $length);
    }

    /**
     * Return the max length an address can be. Specs changed for fiscal year 2022.
     * Keep backwards compatibility (for 7 years) because specs can change over time
     *
     * @return int
     */
    private function addressMaxLength(): int
    {
        if ($this->year < 2022) {
            return 32;
        }

        return 200;
    }

    /**
     * Return the max length an address can be. Specs changed for fiscal year 2022.
     * Keep backwards compatibility (for 7 years) because specs can change over time
     *
     * @return int
     */
    private function childAddressMaxLength(): int
    {
        return 41;
    }

    /**
     * Return the max length a name can be
     * Keep backwards compatibility (for 7 years) because specs can change over time
     *
     * @return int
     */
    private function cityMaxLength(): int
    {
        return 27;
    }


    /**
     * Return the max length a name can be
     * Keep backwards compatibility (for 7 years) because specs can change over time
     *
     * @return int
     */
    private function nameMaxLength(): int
    {
        return 41;
    }

    /**
     * Return the max length a name can be
     * Keep backwards compatibility (for 7 years) because specs can change over time
     *
     * @return int
     */
    private function firstnameMaxLength(): int
    {
        return 30;
    }

    /**
     * Return the max length a name can be
     * Keep backwards compatibility (for 7 years) because specs can change over time
     *
     * @return int
     */
    private function firstnameDebtorMaxLength(): int
    {
        return 15;
    }

    /**
     * Return the max length a name can be
     * Keep backwards compatibility (for 7 years) because specs can change over time
     *
     * @return int
     */
    private function declarationNameMaxLength(): int
    {
        /* NOTE: there are two name fields for agencies. The first one is capped at 28 characters. The second one is
         * capped at 31 characters. The max length of a name is therefore 28 + 31 characters.
         */
        return 28 + 31;
    }

    /**
     * TODO: contacted FOD to check this spec. Email generally has a max length of 254 characters.
     *
     * Return the max length a name can be
     * Keep backwards compatibility (for 7 years) because specs can change over time
     *
     * @return int
     */
    private function emailMaxLength(): int
    {
        return 44;
    }

    /**
     * there are two name fields for agencies.
     * The first one is capped at 28 characters.
     * The second one is capped at 31 characters.
     *
     * We split the string on 28 characters which could return us an array of 1, 2 or 3 elements (since the formattedName max length is 28 + 31)
     *
     * @param string $formattedName
     * @return array
     */
    private function splitName(string $formattedName): array
    {
        /* NOTE: we use str_split and not mb_str_split since we are uncertain if the FOD module counts bytes or chars.
         * str_split will return fewer characters if there are more bytes.
         * mb_str_split will return 28 characters but if the FOD module counts bytes this would cause a problem.
         */
        return str_split($formattedName, 28);
    }
}
