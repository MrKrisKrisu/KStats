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

    public function __construct(string $bonRaw)
    {
        $this->bonRaw = $bonRaw;
    }


    /**
     * @return float|null
     */
    public function getTotal()
    {
        if (preg_match('/SUMME\nEUR\n([0-9]{1,4},[0-9]{2})/', $this->bonRaw, $match))
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
            if (preg_match('/Geg. (.*)/', $line, $match))
                $paymentMethods[] = $match[1];
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
                if (preg_match('/(.*) Stk x/', $line, $match)) {
                    $lastPos['amount'] = (int)$match[1];
                }

                $lineNr++; //Einzelpreis befindet sich in der nächsten Zeile
                $line = trim($rawPos[$lineNr]);
                $p = (float)str_replace(',', '.', $line);
                if ($p < 0)
                    $p *= -1;
                $lastPos['price_single'] = $p;

            } else if (strpos($line, 'kg x') !== false && $lastPos != NULL) { //Wenn die Stückzahl des vorherigen Postens...

                if (preg_match('/(.*) kg x/', $line, $match))
                    $lastPos['weight'] = (float)str_replace(',', '.', $match[1]);

                $lineNr++; //Einzelpreis befindet sich in der nächsten Zeile
                $line = trim($rawPos[$lineNr]);
                $p = (float)str_replace(',', '.', $line);
                if ($p < 0)
                    $p *= -1;
                $lastPos['price_single'] = $p;

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

                $lastPos = [
                    'name' => $line
                ];

                $lineNr++;
                $line = trim($rawPos[$lineNr]);
                $p = (float)str_replace(',', '.', substr($line, 0, -2));
                $lastPos['price_total'] = $p;
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