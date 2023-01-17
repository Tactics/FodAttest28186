<?php

namespace Tactics\FodAttest28186;

use Tactics\FodAttest28186\Enum\FodCountryCode;
use Tactics\FodAttest28186\Enum\FodFileType;
use Tactics\FodAttest28186\Enum\FodLanguageCode;
use Tactics\FodAttest28186\Enum\FodSendCode;
use Tactics\FodAttest28186\Model\Certifier\Certifier;
use Tactics\FodAttest28186\Model\Child\Child;
use Tactics\FodAttest28186\Model\Child\ChildWithNationalRegistry;
use Tactics\FodAttest28186\Model\Child\ChildWithoutNationalRegistry;
use Tactics\FodAttest28186\Model\Debtor\Debtor;
use Tactics\FodAttest28186\Model\InvoiceAgency\InvoiceAgency;
use Tactics\FodAttest28186\Modal\Sender\Company;
use Tactics\FodAttest28186\Modal\Sender\Person;
use Tactics\FodAttest28186\Modal\Sender\Sender;
use Tactics\FodAttest28186\Modal\Tariff\Tariff;
use Tactics\FodAttest28186\Modal\Tariff\TariffCollection;
use Tactics\FodAttest28186\Modal\Tariff\TariffXmlMapper;
use Tactics\FodAttest28186\Modal\TaxSheet\TaxSheet;
use Tactics\FodAttest28186\Modal\TaxSheet\TaxSheetMap;

final class XmlGenerator
{
    private const DATE_FORMAT = 'd-m-Y';

    private const NAME_MAX_LENGTH = 41;

    private const FIRSTNAME_MAX_LENGTH = 30;

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
        $xml .= $this->info();
        $xml .= $this->sender();
        $xml .= '<Aangiften><Aangifte>';
        $xml .= $this->invoiceAgency();
        $xml .= '<Opgaven><Opgave32586>';
        $xml .= $this->sheets();
        $xml .= '</Opgave32586></Opgaven>';
        $xml .= $this->controlNumbers();
        $xml .= '</Aangifte></Aangiften>';
        $xml .= $this->controlNumbersTotal();
        $xml .= '</Verzending></Verzendingen>';

        return $xml;
    }

    private function info(): string
    {
        $date = date(self::DATE_FORMAT);

        return <<<EOT
    <v002_inkomstenjaar>$this->year</v002_inkomstenjaar>
    <v0010_bestandtype>{$this->fileType->value()}</v0010_bestandtype>
    <v0011_aanmaakdatum>$date</v0011_aanmaakdatum>
    <v0025_typeenvoi>{$this->sendCode->value()}</v0025_typeenvoi>
EOT;
    }

    private function sender(): string
    {
        $senderName = $this->escapeInvalidXmlChars($this->sender->name());
        $senderAddress = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $this->sender->address()->addressLine(),
                $this->addressMaxLength()
            )
        );

        $xml = <<<EOT
    <v0014_naam>$senderName</v0014_naam>
    <v0015_adres>$senderAddress</v0015_adres>
    <v0016_postcode>{$this->sender->address()->postal()}</v0016_postcode>
    <v0017_gemeente>{$this->sender->address()->city()}</v0017_gemeente>
    <v0018_telefoonnummer>{$this->sender->phoneNumber()}</v0018_telefoonnummer>
    <v0023_emailadres>{$this->sender->email()}</v0023_emailadres>
    <v0021_contactpersoon>{$this->sender->contactName()}</v0021_contactpersoon>
    <v0022_taalcode>{$this->sender->contactLanguageCode()->value()}</v0022_taalcode>
    <v0028_landwoonplaats>{$this->sender->address()->countryCode()->value()}</v0028_landwoonplaats>
EOT;

        if ($this->sender instanceof Company) {
            $xml .= sprintf('<v0024_nationaalnr>%s</v0024_nationaalnr>', $this->sender->identifier());
        }

        if ($this->sender instanceof Person) {
            $xml .= sprintf('<v0030_nationaalnummer>%s</v0030_nationaalnummer>', $this->sender->identifier());
        }

        return $xml;
    }

    private function invoiceAgency(): string
    {
        $name = $this->escapeInvalidXmlChars($this->invoiceAgency->name());
        $addressLine = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $this->invoiceAgency->address()->addressLine(),
                $this->addressMaxLength()
            )
        );
        $postal = $this->invoiceAgency->address()->postal();
        $city = $this->invoiceAgency->address()->city();
        $countryCode = $this->invoiceAgency->address()->countryCode()->value();

        $languageCodeDutch = FodLanguageCode::DUTCH;
        $languageCodeFrench = FodLanguageCode::FRENCH;
        $languageCodeGerman = FodLanguageCode::GERMAN;

        $xml = <<<EOT
    <a1002_inkomstenjaar>$this->year</a1002_inkomstenjaar>
    <a1011_naamnl1>$name</a1011_naamnl1>
    <a1013_adresnl>$addressLine</a1013_adresnl>
    <a1014_postcodebelgisch>$postal</a1014_postcodebelgisch>
    <a1015_gemeente>$city</a1015_gemeente>
    <a1016_landwoonplaats>$countryCode</a1016_landwoonplaats>
    <a1020_taalcode>$languageCodeDutch</a1020_taalcode>
    <a1027_naamfr1>$name</a1027_naamfr1>
    <a1029_adresfr>$addressLine</a1029_adresfr>
    <a1030_gemeentefr>$city</a1030_gemeentefr>
    <a1031_taalfr>$languageCodeFrench</a1031_taalfr>
    <a1032_naamde1>$name</a1032_naamde1>
    <a1034_adresde>$addressLine</a1034_adresde>
    <a1035_gemeentede>$city</a1035_gemeentede>
    <a1036_taalde>$languageCodeGerman</a1036_taalde>
EOT;

        if ($this->invoiceAgency instanceof Company) {
            $xml .= sprintf('<a1005_registratienummer>%s</a1005_registratienummer>', $this->invoiceAgency->identifier());
        }

        if ($this->invoiceAgency instanceof Person) {
            $xml .= sprintf('<a1037_nationaalnr>%s</a1037_nationaalnr>', $this->invoiceAgency->identifier());
        }

        return $xml;
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

        $xml = '<Fiche28186>';
        $xml .= $this->sheetInfo($sheet);
        $xml .= $this->debtor($sheet->debtor());

        $certifier = $this->invoiceAgency->certifier();
        if ($certifier instanceof Certifier) {
            $xml .= $this->certifier($certifier);
        }

        $xml .= $this->child($sheet->child());

        foreach ($tariffCollection->getIterator() as $index => $tariff) {
            $xml .= $this->tariff($index + 1, $tariff);
        }

        $totalAmount = $tariffCollection->sum();

        $totalControlAmount = $totalAmount * 2;
        $this->totalAmount += $totalControlAmount;

        $xml .= "<f86_2064_totalamount>$totalAmount</f86_2064_totalamount>";
        $xml .= "<f86_2059_totaalcontrole>$totalControlAmount</f86_2059_totaalcontrole>";
        $xml .= '</Fiche28186>';

        return $xml;
    }

    private function sheetInfo(TaxSheet $sheet): string
    {
        return <<<EOT
    <f2002_inkomstenjaar>$this->year</f2002_inkomstenjaar>
    <f2008_typefiche>28186</f2008_typefiche>
    <f2009_volgnummer>$this->sheetCounter</f2009_volgnummer>
    <f2010_referentie>{$sheet->uid()->toString()}</f2010_referentie>
    <f2028_typetraitement>{$sheet->type()->value()}</f2028_typetraitement>
    <f2029_enkelopgave325>0</f2029_enkelopgave325>
EOT;
    }

    /**
     * Company number is a required field but is not a required prop on Debtor (just because it does not make sense to require it)
     * That's why we check if company number is set and otherwise print a '0' (default value for this field in the XML example of the government)
     *
     * @param Debtor $debtor
     * @return string
     */
    private function debtor(Debtor $debtor): string
    {
        $debtorCompanyNumber = $debtor->companyNumber() ?? '0';
        $rrn = $debtor->nationalRegistryNumber()->value();

        $xml = <<<EOT
    <f2005_registratienummer>$debtorCompanyNumber</f2005_registratienummer>
    <f2011_nationaalnr>$rrn</f2011_nationaalnr>
EOT;

        // Due to a bug/inconsistency in the tool we always need to provide the postal code.
        // We don't want to make this required on the debtor since this is a mistake.
        // So we extract it first here or fallback to a default.
        $country = $debtor->details() ?
            $debtor->details()->address()->countryCode() :
            FodCountryCode::from(FodCountryCode::BELGIUM);

        $xml .= <<<EOT
        <f2018_landwoonplaats>{$country->value()}</f2018_landwoonplaats>
EOT;

        $debtorDetails = $debtor->details();
        if ($debtorDetails) {
            $name = $this->escapeInvalidXmlChars($this->formatMaxLength($debtorDetails->familyName(), self::NAME_MAX_LENGTH));
            $firstName = $this->escapeInvalidXmlChars($this->formatMaxLength($debtorDetails->givenName(), self::FIRSTNAME_MAX_LENGTH));
            $address = $debtorDetails->address();
            $addressLine = $this->escapeInvalidXmlChars(
                $this->formatMaxLength(
                    $address->addressLine(),
                    $this->addressMaxLength()
                )
            );

            $xml .= <<<EOT
    <f2013_naam>$name</f2013_naam>
    <f2015_adres>$addressLine</f2015_adres>
    <f2017_gemeente>{$address->city()}</f2017_gemeente>
    <f2114_voornamen>$firstName</f2114_voornamen>
EOT;
        }

        $postal = $debtor->details() ?
            $debtor->details()->address()->postal() :
            null;

        if ($postal) {
            if ($country->value() === FodCountryCode::BELGIUM) {
                $xml .= "<f2016_postcodebelgisch>$postal</f2016_postcodebelgisch>";
            } else {
                $xml .= "<f2112_buitenlandspostnummer>$postal</f2112_buitenlandspostnummer>";
            }
        }

        return $xml;
    }

    private function certifier(Certifier $certifier): string
    {
        $name = $this->escapeInvalidXmlChars($this->formatMaxLength($certifier->name(), self::NAME_MAX_LENGTH));
        $address = $certifier->address();
        $addressLine = $this->escapeInvalidXmlChars(
            $this->formatMaxLength(
                $address->addressLine(),
                $this->addressMaxLength()
            )
        );
        $postal = $address->postal();

        return <<<EOT
    <f86_2031_certificationautorisation>{$certifier->certificationCode()->value()}</f86_2031_certificationautorisation>
    <f86_2100_certifierpostnr>$postal</f86_2100_certifierpostnr>
    <f86_2109_certifiercbenumber>{$certifier->companyNumber()->value()}</f86_2109_certifiercbenumber>
    <f86_2154_certifiermunicipality>{$address->city()}</f86_2154_certifiermunicipality>
    <f86_2155_certifiername>$name</f86_2155_certifiername>
    <f86_2156_certifieradres>$addressLine</f86_2156_certifieradres>
EOT;
    }

    private function child(Child $child): string
    {
        $xml = '';

        if ($child instanceof ChildWithoutNationalRegistry) {
            $childDetails = $child->details();

            $name = $this->escapeInvalidXmlChars($this->formatMaxLength($childDetails->familyName(), self::NAME_MAX_LENGTH));
            $firstName = $this->escapeInvalidXmlChars($this->formatMaxLength($childDetails->givenName(), self::FIRSTNAME_MAX_LENGTH));
            $address = $childDetails->address();
            $addressLine = $this->escapeInvalidXmlChars(
                $this->formatMaxLength(
                    $address->addressLine(),
                    $this->addressMaxLength()
                )
            );
            $formattedDayOfBirth = $childDetails->dayOfBirth()->format(self::DATE_FORMAT);

            $xml = <<<EOT
    <f86_2101_childcountry>{$address->countryCode()->value()}</f86_2101_childcountry>
    <f86_2102_childaddress>$addressLine</f86_2102_childaddress>
    <f86_2106_childname>$name</f86_2106_childname>
    <f86_2107_childfirstname>$firstName</f86_2107_childfirstname>
    <f86_2139_childpostnr>{$address->postal()}</f86_2139_childpostnr>
    <f86_2140_childmunicipality>{$address->city()}</f86_2140_childmunicipality>
    <f86_2163_childbirthdate>$formattedDayOfBirth</f86_2163_childbirthdate>
EOT;
        }

        if ($child instanceof ChildWithNationalRegistry) {
            $xml = <<<EOT
    <f86_2153_nnchild>{$child->nationalRegistryNumber()->value()}</f86_2153_nnchild>
EOT;
        }

        return $xml;
    }

    private function tariff(int $period, Tariff $tariff): string
    {
        $xmlMap = TariffXmlMapper::xmlMap($period);

        $xml = "<" . $xmlMap[TariffXmlMapper::NUMBER_OF_DAYS] . ">" . $tariff->days() . "</" . $xmlMap[TariffXmlMapper::NUMBER_OF_DAYS] . ">";
        $xml .= "<" . $xmlMap[TariffXmlMapper::TARIFF] . ">" . $tariff->tariff() . "</" . $xmlMap[TariffXmlMapper::TARIFF] . ">";
        $xml .= "<" . $xmlMap[TariffXmlMapper::AMOUNT] . ">" . ($tariff->tariff() * $tariff->days()) . "</" . $xmlMap[TariffXmlMapper::AMOUNT] . ">";
        $xml .= "<" . $xmlMap[TariffXmlMapper::STARTDATE] . ">" . $tariff->period()->begin()->format(self::DATE_FORMAT) . "</" . $xmlMap[TariffXmlMapper::STARTDATE] . ">";
        $xml .= "<" . $xmlMap[TariffXmlMapper::ENDDATE] . ">" . $tariff->period()->end()->format(self::DATE_FORMAT) . "</" . $xmlMap[TariffXmlMapper::ENDDATE] . ">";

        return $xml;
    }

    private function controlNumbers(): string
    {
        $totalRecords = $this->sheetCounter + 2;
        $triangularNumberRecords = ($this->sheetCounter * ($this->sheetCounter + 1)) / 2;

        $xml = <<<EOT
    <r8002_inkomstenjaar>$this->year</r8002_inkomstenjaar>
    <r8010_aantalrecords>$totalRecords</r8010_aantalrecords>
    <r8011_controletotaal>$triangularNumberRecords</r8011_controletotaal>
    <r8012_controletotaal>$this->totalAmount</r8012_controletotaal>
EOT;

        if ($this->invoiceAgency instanceof Company) {
            $xml .= sprintf('<r8005_registratienummer>%s</r8005_registratienummer>', $this->invoiceAgency->identifier());
        }

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
     * Keep backwards compatibility
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
}
