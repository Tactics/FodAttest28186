<?php

namespace Tests\Unit\Model\TaxSheet;

use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;
use Tactics\FodAttest28186\Model\TaxSheet\TaxSheetUid;

final class TaxSheetUidTest extends TestCase
{
    /**
     * @test
     * @dataProvider validTaxSheetUidProvider
     * @param string $uid
     * @return void
     * @throws AssertionFailedException
     */
    public function valid_tax_sheet_uid(string $uid): void
    {
        $taxSheetUid = TaxSheetUid::fromString($uid);
        self::assertEquals($uid, $taxSheetUid->toString());
    }

    public function validTaxSheetUidProvider(): iterable
    {
        yield 'A random string of 4 characters will successfully create a TaxSheetUid' => [
            'uid' => '12Ef',
        ];
    }

    /**
     * @test
     * @dataProvider invalidTaxSheetUidProvider
     * @param string $uid
     * @return void
     */
    public function invalid_tax_sheet_uid(string $uid): void
    {
        $this->expectException(AssertionFailedException::class);
        TaxSheetUid::fromString($uid);
    }

    public function invalidTaxSheetUidProvider(): iterable
    {
        yield 'A TaxSheetUid can not be created without a string of more than 20 characters' => [
            'uid' => '12Ef4ze5ZEflzef57-zkejnfz454-kjzefb1',
        ];
        yield 'A TaxSheetUid can not be created from an empty string' => [
            'uid' => ''
        ];
    }
}
