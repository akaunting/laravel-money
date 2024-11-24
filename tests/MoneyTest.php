<?php

namespace Akaunting\Money\Tests;

use Akaunting\Money\Casts\MoneyCast;
use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use InvalidArgumentException;
use NumberFormatter;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class MoneyTest extends TestCase
{
    public function testCastUsing()
    {
        $this->assertSame(MoneyCast::class, Money::castUsing([]));
    }

    public function testFactoryMethods()
    {
        $this->assertEquals(Money::USD(25), Money::USD(10)->add(Money::USD(15)));
        $this->assertEquals(Money::TRY(25), Money::TRY(10)->add(Money::TRY(15)));
    }

    public function testParsingAmountFromMoney()
    {
        $money1 = Money::USD(1000);
        $money2 = Money::USD($money1);

        $this->assertEquals($money1, $money2);
    }

    public function testBigValue()
    {
        $this->assertEquals((string) new Money(123456789.321, new Currency('USD'), true), '$123,456,789.32');
    }

    public function testValueString()
    {
        $this->assertEquals(new Money('1', new Currency('USD')), new Money(1, new Currency('USD')));
        $this->assertEquals(new Money('1.1', new Currency('USD')), new Money(1.1, new Currency('USD')));
    }

    public function testValueFunction()
    {
        $this->assertEquals(new Money(function () {
            return 1;
        }, new Currency('USD')), new Money(1, new Currency('USD')));
    }

    public function testStringThrowsException()
    {
        $this->expectException(UnexpectedValueException::class);

        new Money('foo', new Currency('USD'));
    }

    public function testLocale()
    {
        Money::setLocale(null);
        $this->assertEquals('en_GB', Money::getLocale());
        Money::setLocale('en_US');
        $this->assertEquals('en_US', Money::getLocale());
    }

    public function testConvertUnit()
    {
        $m1 = new Money(100, new Currency('USD'), true);
        $m2 = new Money(100, new Currency('USD'));

        $this->assertEquals(10000, $m1->getAmount());
        $this->assertNotEquals($m1, $m2);
    }

    public function testGetters()
    {
        $m = new Money(100, new Currency('USD'));
        $this->assertEquals(100, $m->getAmount());
        $this->assertEquals(1, $m->getValue());
        $this->assertEquals(new Currency('USD'), $m->getCurrency());
        $this->assertNotEmpty($m->toArray());
        $this->assertJson($m->toJson());
        $this->assertNotEmpty($m->jsonSerialize());
    }

    public function testGettingRoundedAmount()
    {
        $money = Money::USD(1000.213);

        $this->assertSame(1000.21, $money->getRoundedAmount());
    }

    public function testRoundingWithInvalidMode()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Rounding mode should be 1 | 2 | 3 | 4');

        $money = Money::USD(1000.213);

        $money->round(2, 5);
    }

    public function testSameCurrency()
    {
        $m = new Money(100, new Currency('USD'));
        $this->assertTrue($m->isSameCurrency(new Money(100, new Currency('USD'))));
        $this->assertFalse($m->isSameCurrency(new Money(100, new Currency('TRY'))));
    }

    public function testComparison()
    {
        $m1 = new Money(50, new Currency('USD'));
        $m2 = new Money(100, new Currency('USD'));
        $m3 = new Money(200, new Currency('USD'));

        $this->assertEquals(-1, $m2->compare($m3));
        $this->assertEquals(1, $m2->compare($m1));
        $this->assertEquals(0, $m2->compare($m2));

        $this->assertTrue($m2->equals($m2));
        $this->assertFalse($m3->equals($m2));

        $this->assertTrue($m3->greaterThan($m2));
        $this->assertFalse($m2->greaterThan($m3));

        $this->assertTrue($m2->greaterThanOrEqual($m2));
        $this->assertFalse($m2->greaterThanOrEqual($m3));

        $this->assertTrue($m2->lessThan($m3));
        $this->assertFalse($m3->lessThan($m2));

        $this->assertTrue($m2->lessThanOrEqual($m2));
        $this->assertFalse($m3->lessThanOrEqual($m2));
    }

    public function testDifferentCurrenciesCannotBeCompared()
    {
        $this->expectException(InvalidArgumentException::class);

        $m1 = new Money(100, new Currency('USD'));
        $m2 = new Money(100, new Currency('TRY'));

        $m1->compare($m2);
    }

    public function testConversion()
    {
        $m1 = new Money(100, new Currency('USD'));
        $m2 = new Money(350, new Currency('TRY'));

        $this->assertEquals($m1->convert(new Currency('TRY'), 3.5), $m2);
    }

    public function testAddition()
    {
        $m1 = new Money(1100.101, new Currency('USD'));
        $m2 = new Money(1100.021, new Currency('USD'));
        $sum = $m1->add($m2);

        $this->assertEquals(new Money(2200.122, new Currency('USD')), $sum);
        $this->assertNotEquals($sum, $m1);
        $this->assertNotEquals($sum, $m2);
    }

    public function testMutableAddition()
    {
        $money1 = Money::USD(1100.101)->mutable();
        $money2 = Money::USD(1100.021);

        $money1->add($money2);

        $this->assertEquals(Money::USD(2200.122)->mutable(), $money1);
        $this->assertNotEquals(Money::USD(2200.122)->mutable(), $money2);
    }

    public function testDifferentCurrenciesCannotBeAdded()
    {
        $this->expectException(InvalidArgumentException::class);

        $m1 = new Money(100, new Currency('USD'));
        $m2 = new Money(100, new Currency('TRY'));

        $m1->add($m2);
    }

    public function testSubtraction()
    {
        $m1 = new Money(100.10, new Currency('USD'));
        $m2 = new Money(100.02, new Currency('USD'));
        $diff = $m1->subtract($m2);

        $this->assertEquals(new Money(0.08, new Currency('USD')), $diff);
        $this->assertNotSame($diff, $m1);
        $this->assertNotSame($diff, $m2);
    }

    public function testMutableSubtraction()
    {
        $money1 = Money::USD(100.10)->mutable();
        $money2 = Money::USD(100.02);

        $money1->subtract($money2);

        $this->assertEquals(Money::USD(0.08)->mutable(), $money1);
        $this->assertNotEquals(Money::USD(0.08)->mutable(), $money2);
    }

    public function testDifferentCurrenciesCannotBeSubtracted()
    {
        $this->expectException(InvalidArgumentException::class);

        $m1 = new Money(100, new Currency('USD'));
        $m2 = new Money(100, new Currency('TRY'));

        $m1->subtract($m2);
    }

    public function testMultiplication()
    {
        $m1 = new Money(15, new Currency('USD'));
        $m2 = new Money(1, new Currency('USD'));

        $this->assertEquals($m1, $m2->multiply(15));
        $this->assertNotEquals($m1, $m2->multiply(10));
    }

    public function testMutableMultiplication()
    {
        $money1 = Money::USD(15);
        $money2 = Money::USD(1)->mutable();

        $money2->multiply(15);

        $this->assertEquals(Money::USD(15)->mutable(), $money2);
        $this->assertTrue($money2->equals($money1));
    }

    public function testDivision()
    {
        $m1 = new Money(2, new Currency('USD'));
        $m2 = new Money(10, new Currency('USD'));

        $this->assertEquals($m1, $m2->divide(5));
        $this->assertNotEquals($m1, $m2->divide(2));
    }

    public function testMutableDivision()
    {
        $money1 = Money::USD(2);
        $money2 = Money::USD(10)->mutable();

        $money2->divide(5);

        $this->assertEquals(Money::USD(2)->mutable(), $money2);
        $this->assertTrue($money2->equals($money1));
    }

    public function testInvalidDivisor()
    {
        $this->expectException(InvalidArgumentException::class);

        $m = new Money(100, new Currency('USD'));
        $m->divide(0);
    }

    public function testAllocation()
    {
        $m1 = new Money(100, new Currency('USD'));

        list($part1, $part2, $part3) = $m1->allocate([1, 1, 1]);
        $this->assertEquals(new Money(34, new Currency('USD')), $part1);
        $this->assertEquals(new Money(33, new Currency('USD')), $part2);
        $this->assertEquals(new Money(33, new Currency('USD')), $part3);

        $m2 = new Money(101, new Currency('USD'));

        list($part1, $part2, $part3) = $m2->allocate([1, 1, 1]);
        $this->assertEquals(new Money(34, new Currency('USD')), $part1);
        $this->assertEquals(new Money(34, new Currency('USD')), $part2);
        $this->assertEquals(new Money(33, new Currency('USD')), $part3);
    }

    public function testAllocationOrderIsImportant()
    {
        $m = new Money(5, new Currency('USD'));

        list($part1, $part2) = $m->allocate([3, 7]);
        $this->assertEquals(new Money(2, new Currency('USD')), $part1);
        $this->assertEquals(new Money(3, new Currency('USD')), $part2);

        list($part1, $part2) = $m->allocate([7, 3]);
        $this->assertEquals(new Money(4, new Currency('USD')), $part1);
        $this->assertEquals(new Money(1, new Currency('USD')), $part2);
    }

    public function testComparators()
    {
        $m1 = new Money(0, new Currency('USD'));
        $m2 = new Money(-1, new Currency('USD'));
        $m3 = new Money(1, new Currency('USD'));
        $m4 = new Money(1, new Currency('USD'));
        $m5 = new Money(1, new Currency('USD'));
        $m6 = new Money(-1, new Currency('USD'));

        $this->assertTrue($m1->isZero());
        $this->assertTrue($m2->isNegative());
        $this->assertTrue($m3->isPositive());
        $this->assertFalse($m4->isZero());
        $this->assertFalse($m5->isNegative());
        $this->assertFalse($m6->isPositive());
    }

    /**
     * @dataProvider providesFormat
     */
    public function testFormat($expected, $cur, $amount, $message)
    {
        $this->assertEquals($expected, (string) Money::$cur($amount), $message);
    }

    public static function providesFormat()
    {
        return [
            ['₺1.548,48', 'TRY', 154848.25895, 'Example: ' . __LINE__],
            ['$1,548.48', 'USD', 154848.25895, 'Example: ' . __LINE__],
        ];
    }

    public function testFormatSimple()
    {
        $m1 = new Money(1, new Currency('USD'));
        $m2 = new Money(10, new Currency('USD'));
        $m3 = new Money(100, new Currency('USD'));
        $m4 = new Money(1000, new Currency('USD'));
        $m5 = new Money(10000, new Currency('USD'));
        $m6 = new Money(100000, new Currency('USD'));

        $this->assertEquals('0.01', $m1->formatSimple());
        $this->assertEquals('0.10', $m2->formatSimple());
        $this->assertEquals('1.00', $m3->formatSimple());
        $this->assertEquals('10.00', $m4->formatSimple());
        $this->assertEquals('100.00', $m5->formatSimple());
        $this->assertEquals('1,000.00', $m6->formatSimple());
    }

    public function testFormatWithoutZeroes()
    {
        $m1 = new Money(100, new Currency('USD'), true);
        $m2 = new Money(100.50, new Currency('USD'), true);

        $this->assertEquals('$100.00', $m1->format());
        $this->assertEquals('$100', $m1->formatWithoutZeroes());

        $this->assertEquals('$100.50', $m2->format());
        $this->assertEquals('$100.50', $m2->formatWithoutZeroes());
    }

    /**
     * @dataProvider providesFormatForHumans
     */
    public function testFormatForHumans($expected, $cur, $amount, $locale, $message)
    {
        $this->assertEquals($expected, (string) Money::$cur($amount)->formatForHumans($locale), $message);
    }

    public static function providesFormatForHumans()
    {
        return [
            ['€1,55K', 'EUR', 154848.25895, 'nl_NL', 'Example: ' . __LINE__],
            ['$1.55K', 'USD', 154848.25895, 'en_US', 'Example: ' . __LINE__],
        ];
    }

    /**
     * @dataProvider providesFormatLocale
     */
    public function testFormatLocale($expected, $cur, $amount, $locale, $message)
    {
        $this->assertEquals($expected, Money::$cur($amount)->formatLocale($locale), $message);
    }

    public static function providesFormatLocale()
    {
        return [
            ['₺1.548,48', 'TRY', 154848.25895, 'tr_TR', 'Example: ' . __LINE__],
            ['$1,548.48', 'USD', 154848.25895, 'en_US', 'Example: ' . __LINE__],
        ];
    }

    public function testCallbackFormatLocale()
    {
        $m = new Money(1, new Currency('USD'));

        $actual = $m->formatLocale(null, function (NumberFormatter $formatter) {
            $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
        });

        $formatter = new NumberFormatter($m::getLocale(), NumberFormatter::CURRENCY);
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
        $expected = $formatter->formatCurrency('0.01', 'USD');

        $this->assertEquals($expected, $actual);
    }

    public function testMakingMutable()
    {
        $money = Money::USD(1000)->immutable();

        $this->assertTrue($money->isImmutable());
        $this->assertFalse($money->mutable()->isImmutable());
    }

    public function testStaticMacro()
    {
        Money::macro('testMacro', fn () => Money::USD(1099));

        $this->assertEquals(Money::USD(1099), Money::testMacro());
    }
}
