<?php

namespace Tests\Unit;

use App\Http\Controllers\ReweBonParser;
use PHPUnit\Framework\TestCase;
use Spatie\PdfToText\Pdf;

class ReweReceiptParsingTest extends TestCase
{
    /**
     * @return void
     */
    public function testBonParsing()
    {
        $this->assertTrue(true);

        $pdf = new Pdf(env('PDFTOTEXT_PATH', '/usr/bin/pdftotext'));
        $text = $pdf->setPdf(dirname(__FILE__) . '/ReweReceiptParsingTestFiles/weight_eccash.pdf')->text();

        $parser = new ReweBonParser($text);

        $this->assertEquals(11.0, $parser->getTotal());
        $this->assertEquals(1234, $parser->getBonNr());
        $this->assertEquals(1234, $parser->getShopNr());
        $this->assertEquals(252525, $parser->getCashierNr());
        $this->assertEquals(2, $parser->getCashregisterNr());
        $this->assertEquals(5, $parser->getEarnedPaybackPoints());
        $this->assertContains("EC-Cash", $parser->getPaymentMethods());
        $this->assertEquals(1577880000, $parser->getTimestamp()->getTimestamp());
        //TODO: Test für Positionen


        $pdf = new Pdf(env('PDFTOTEXT_PATH', '/usr/bin/pdftotext'));
        $text = $pdf->setPdf(dirname(__FILE__) . '/ReweReceiptParsingTestFiles/multipleProducts_multiplePaymentMethods_paybackCoupon.pdf')->text();

        $parser = new ReweBonParser($text);

        $this->assertEquals(8.62, $parser->getTotal());
        $this->assertEquals(9999, $parser->getBonNr());
        $this->assertEquals(51, $parser->getShopNr());
        $this->assertEquals(123414, $parser->getCashierNr());
        $this->assertEquals(14, $parser->getCashregisterNr());
        $this->assertEquals(22, $parser->getEarnedPaybackPoints());
        $this->assertContains("BAR", $parser->getPaymentMethods());
        $this->assertContains("VISA", $parser->getPaymentMethods());
        $this->assertEquals(1577880000, $parser->getTimestamp()->getTimestamp());
        //TODO: Test für Positionen
    }
}
