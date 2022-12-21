<?php

namespace Tactics\FodAttest28186\Entity\InvoiceAgency;

use InvalidArgumentException;
use Tactics\FodAttest28186\Entity\Sender\Company as SenderCompany;
use Tactics\FodAttest28186\Entity\Sender\Person as SenderPerson;
use Tactics\FodAttest28186\Entity\Sender\Sender;
use Tactics\FodAttest28186\ValueObject\CompanyNumber;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;

/**
 * Since the person/company sending the Certificates to the FOD is 99% times the same instance we have this factory
 * to easily create an InvoiceAgency
 */
final class InvoiceAgencyFactory
{
    public static function createFromSender(Sender $sender): InvoiceAgency
    {
        switch ($sender) {
            case $sender instanceof SenderCompany:
                $companyNumber = new CompanyNumber($sender->identifier());
                return new Company(
                    $sender->name(),
                    $sender->address(),
                    $companyNumber,
                );
            case $sender instanceof SenderPerson:
                $nationalRegistryNumber = new NationalRegistryNumber($sender->identifier());
                return new Person(
                    $sender->name(),
                    $sender->address(),
                    $nationalRegistryNumber
                );
            default:
                throw new InvalidArgumentException('Invalid sender passed to create InvoiceAgency from');
        }
    }
}
