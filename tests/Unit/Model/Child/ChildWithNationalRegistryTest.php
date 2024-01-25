<?php

namespace Tests\Unit\Model\Child;

use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\Exception\NonMatchingBirthdayException;
use Tactics\FodAttest28186\Model\Child\ChildWithNationalRegistry;
use Tactics\FodAttest28186\ValueObject\NationalRegistryNumber;
use Tests\Unit\Factory\DayOfBirthFactory;

final class ChildWithNationalRegistryTest extends TestCase
{
    /**
     * @var DayOfBirthFactory
     */
    private $dayOfBirthFactory;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->dayOfBirthFactory = new DayOfBirthFactory();
    }

    public function testAChildWithNationalRegistryNumberMustMatchWithGivenDayOfBirth(): void
    {
        $rrn = NationalRegistryNumber::fromString('90.11.13.409-04');
        $dayOfBirth = $this->dayOfBirthFactory->create('1990-11-13');
        $child = ChildWithNationalRegistry::create($rrn, $dayOfBirth);

        $this->assertEquals($rrn->value(), $child->nationalRegistryNumber()->value());
        $this->assertEquals($dayOfBirth->format(), $child->dayOfBirth()->format());
    }

    public function testAChildWithNationalRegistryNumberMustThrowExceptionWhenNotMatchingWithGivenDayOfBirth(): void
    {
        $this->expectException(NonMatchingBirthdayException::class);
        $rrn = NationalRegistryNumber::fromString('90.11.13.409-04');
        $dayOfBirth = $this->dayOfBirthFactory->create('1990-11-14');
        ChildWithNationalRegistry::create($rrn, $dayOfBirth);
    }

    public function testAChildWithBisNumberBypassesBirthDayCheck(): void
    {
        $rrn = NationalRegistryNumber::fromString('15.00.00.061-89');
        $dayOfBirth = $this->dayOfBirthFactory->create('1990-11-13');
        $child = ChildWithNationalRegistry::create($rrn, $dayOfBirth);

        $this->assertEquals($rrn->value(), $child->nationalRegistryNumber()->value());
        $this->assertEquals($dayOfBirth->format(), $child->dayOfBirth()->format());
    }
}
