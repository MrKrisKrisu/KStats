<?php

namespace Tests\Unit;

use App\Http\Controllers\ReweBonParser;
use PHPUnit\Framework\TestCase;
use Spatie\PdfToText\Exceptions\PdfNotFound;
use Spatie\PdfToText\Pdf;

class ReweReceiptParsingTest extends TestCase
{

    /**
     * @return void
     */
    public function testNegativeTotalAmount()
    {
        $parser = new ReweBonParser(dirname(__FILE__) . '/ReweReceiptParsingTestFiles/negative_amount.pdf');
        $this->assertEquals(-0.25, $parser->getTotal());
    }

    /**
     * @return void
     */
    public function testBonParsingWeight()
    {
        $parser = new ReweBonParser(dirname(__FILE__) . '/ReweReceiptParsingTestFiles/weight_eccash.pdf');

        $this->assertEquals(11.0, $parser->getTotal());
        $this->assertEquals(1234, $parser->getBonNr());
        $this->assertEquals(1234, $parser->getShopNr());
        $this->assertEquals(252525, $parser->getCashierNr());
        $this->assertEquals(2, $parser->getCashregisterNr());
        $this->assertEquals(5, $parser->getEarnedPaybackPoints());
        $this->assertContains("EC-Cash", $parser->getPaymentMethods());
        $this->assertEquals(1577880000, $parser->getTimestamp()->getTimestamp());

        $positions = [];
        foreach ($parser->getPositions() as $position)
            $positions[$position['name']] = $position;

        $this->assertEquals(1, $positions['BROT']['price_single']);
        $this->assertEquals(0.5, $positions['AUFSCHNITT']['price_single']);
        $this->assertEquals(0.5, $positions['NATUR-JOGHURT']['price_single']);
        $this->assertEquals(0.01, $positions['ESSEN']['price_single']);
        $this->assertEquals(1.99, $positions['BANANE']['price_single']);
        $this->assertEquals(2.99, $positions['BANANE']['price_total']);
        $this->assertEquals(1.5, $positions['BANANE']['weight']);
        $this->assertEquals(1, $positions['EIER']['price_single']);
        $this->assertEquals(1, $positions['WEIZENMEHL']['price_single']);
        $this->assertEquals(1, $positions['WASSER']['price_single']);
        $this->assertEquals(1, $positions['SOFTDRINK']['price_single']);
        $this->assertEquals(1, $positions['MILCH']['price_single']);
        $this->assertEquals(1, $positions['EIS']['price_single']);

    }

    /**
     * @return void
     */
    public function testBonParsingPaymentMethods()
    {
        $parser = new ReweBonParser(dirname(__FILE__) . '/ReweReceiptParsingTestFiles/multipleProducts_multiplePaymentMethods_paybackCoupon.pdf');

        $this->assertEquals(8.62, $parser->getTotal());
        $this->assertEquals(9999, $parser->getBonNr());
        $this->assertEquals(51, $parser->getShopNr());
        $this->assertEquals(123414, $parser->getCashierNr());
        $this->assertEquals(14, $parser->getCashregisterNr());
        $this->assertEquals(22, $parser->getEarnedPaybackPoints());
        $this->assertContains("BAR", $parser->getPaymentMethods());
        $this->assertContains("VISA", $parser->getPaymentMethods());
        $this->assertEquals(1577880000, $parser->getTimestamp()->getTimestamp());

        $positions = [];
        foreach ($parser->getPositions() as $position)
            $positions[$position['name']] = $position;

        $this->assertEquals(0.25, $positions['LEERGUT']['price_single']);
        $this->assertEquals(2.99, $positions['KARTOFFELN']['price_single']);
        $this->assertEquals(1.49, $positions['NUDELN']['price_single']);
        $this->assertEquals(0.49, $positions['QUARK']['price_single']);
        $this->assertEquals(1.99, $positions['SÜßIGKEITEN']['price_single']);
        $this->assertEquals(0.69, $positions['SCHOKOLADE']['price_single']);
        $this->assertEquals(1.38, $positions['SCHOKOLADE']['price_total']);
        $this->assertEquals(0.53, $positions['SCHMAND 24%']['price_single']);
    }
}
