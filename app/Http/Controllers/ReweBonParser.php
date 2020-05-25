<?php

namespace App\Http\Controllers;

use App\ReweBonPosition;
use App\ReweProduct;
use App\ReweShop;
use App\ReweBon;
use App\User;
use App\UserEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;

class ReweBonParser extends Controller
{
    private $bonRaw;

    public function __construct(string $pdf_path)
    {
        $pdf = new Pdf(env('PDFTOTEXT_PATH', '/usr/local/bin/pdftotext'));
        $text = $pdf->setPdf($pdf_path)->setOptions(['layout'])->text();
        $this->bonRaw = $text;
    }


    /**
     * @return float|null
     */
    public function getTotal()
    {
        if (preg_match('/SUMME *EUR *([0-9]{1,},[0-9]{2})/', $this->bonRaw, $match))
            return (float)str_replace(',', '.', $match[1]);
        return NULL;
    }

    /**
     * @return int|null
     */
    public function getBonNr()
    {
        if (preg_match('/Bon-Nr.:([0-9]{1,4})/', $this->bonRaw, $match))
            return (int)$match[1];
        return NULL;
    }

    /**
     * @return int|null
     */
    public function getShopNr()
    {
        if (preg_match('/Markt:([0-9]{1,4})/', $this->bonRaw, $match))
            return (int)$match[1];
        return NULL;
    }

    /**
     * @return int|null
     */
    public function getCashierNr()
    {
        if (preg_match('/Bed.:([0-9]{4,6})/', $this->bonRaw, $match))
            return (int)$match[1];
        return NULL;
    }

    /**
     * @return int|null
     */
    public function getCashregisterNr()
    {
        if (preg_match('/Kasse:([0-9]{1,6})/', $this->bonRaw, $match))
            return (int)$match[1];
        return NULL;
    }

    /**
     * @return int
     */
    public function getEarnedPaybackPoints()
    {
        if (preg_match('/Sie erhalten ([0-9]{1,}) PAYBACK Punkt/', $this->bonRaw, $match))
            return (int)$match[1];
        return 0;
    }

    /**
     * It's possible to pay with multiple Payment methods at REWE, so this function will return an array.
     * You can for example pay with Cash, then with Coupon and with Card.
     * TODO: There is currently no support in the database layout for multiple payment methods
     * @return array
     */
    public function getPaymentMethods()
    {
        $paymentMethods = [];
        foreach (explode("\n", $this->bonRaw) as $line)
            if (preg_match('/Geg. (.*) *EUR/', $line, $match))
                $paymentMethods[] = trim($match[1]);
        return $paymentMethods;
    }

    /**
     * @return Carbon
     */
    public function getTimestamp()
    {
        $dateRaw = NULL;
        $timeRaw = NULL;

        if (preg_match('/(\d{2}\.\d{2}\.\d{4})/', $this->bonRaw, $match))
            $dateRaw = $match[1];

        if (preg_match('/(\d{2}:\d{2}:\d{2}) Uhr/', $this->bonRaw, $match)) {
            $timeRaw = $match[1];
        } elseif (preg_match('/(\d{2}:\d{2})/', $this->bonRaw, $match)) { //very unprecise...
            $timeRaw = $match[1];
        }
        return Carbon::parse($dateRaw . ' ' . $timeRaw);
    }

    private function getProductStartLine()
    {
        foreach (explode("\n", $this->bonRaw) as $line => $content)
            if (trim($content) == "EUR")
                return $line + 1;
        return -1;
    }

    private function getProductEndLine()
    {
        foreach (explode("\n", $this->bonRaw) as $line => $content)
            if (substr(trim($content), 0, 5) == "-----")
                return $line - 1;
        return -1;
    }

    public function getPositions()
    {
        $positions = [];

        $startLine = $this->getProductStartLine();
        $endLine = $this->getProductEndLine();

        $rawPos = explode("\n", $this->bonRaw);
        $lastPos = NULL;

        for ($lineNr = $startLine; $lineNr <= $endLine; $lineNr++) {
            $line = trim($rawPos[$lineNr]);

            if (strpos($line, ' Stk x') !== false && $lastPos != NULL) { //Wenn die Stückzahl des vorherigen Postens...

                if (preg_match('/(\d{1,}) Stk x *(\d{1,},\d{2})/', $line, $match)) {
                    $lastPos['amount'] = (int)$match[1];
                    $lastPos['price_single'] = (float)str_replace(',', '.', $match[2]);
                }

            } else if (strpos($line, 'kg x') !== false && $lastPos != NULL) { //Wenn die Stückzahl des vorherigen Postens...

                if (preg_match('/(\d{1,},\d{3}) kg x *(\d{1,},\d{2}) EUR/', $line, $match)) {
                    $lastPos['weight'] = (float)str_replace(',', '.', $match[1]);
                    $lastPos['price_single'] = (float)str_replace(',', '.', $match[2]);
                }

            } else {
                if ($lastPos != NULL && isset($lastPos['name']) && isset($lastPos['price_total'])) {
                    if (!isset($lastPos['price_single']) && isset($lastPos['weight']))
                        $lastPos['price_single'] = $lastPos['price_total'] / $lastPos['weight'];
                    if (!isset($lastPos['price_single']) && isset($lastPos['amount']))
                        $lastPos['price_single'] = $lastPos['price_total'] / $lastPos['amount'];
                    if (!isset($lastPos['price_single'])) {
                        $lastPos['price_single'] = $lastPos['price_total'];
                        $lastPos['amount'] = 1;
                    }

                    $positions[] = $lastPos;
                    $lastPos = NULL;
                }


                if (preg_match('/(.*)  (\d{1,},\d{2}) (.{1})/', $line, $match)) {
                    $lastPos = [
                        'name' => trim($match[1]),
                        'price_total' => (float)str_replace(',', '.', $match[2]),
                        'tax_code' => $match[3]
                    ];
                }

            }
        }

        if ($lastPos != NULL && isset($lastPos['name']) && isset($lastPos['price_total'])) {
            if (!isset($lastPos['price_single']) && isset($lastPos['weight']))
                $lastPos['price_single'] = $lastPos['price_total'] / $lastPos['weight'];
            if (!isset($lastPos['price_single']) && isset($lastPos['amount']))
                $lastPos['price_single'] = $lastPos['price_total'] / $lastPos['amount'];
            if (!isset($lastPos['price_single'])) {
                $lastPos['price_single'] = $lastPos['price_total'];
                $lastPos['amount'] = 1;
            }
            $positions[] = $lastPos;
            $lastPos = NULL;
        }

        return $positions;
    }
}