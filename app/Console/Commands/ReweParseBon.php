<?php

namespace App\Console\Commands;

use App\Http\Controllers\ReweBonParser;
use App\Http\Controllers\ReweMailController;
use App\Models\UserEmail;
use Illuminate\Console\Command;
use REWEParser\Exception\ReceiptParseException;
use Spatie\PdfToText\Exceptions\PdfNotFound;
use App\Http\Controllers\Backend\Receipt\ImportController;
use Illuminate\Http\UploadedFile;

class ReweParseBon extends Command {

    protected $signature = "rewe:parse {days=2}";

    public function handle(): int {

        echo '* Fetch mails and attachments...' . PHP_EOL;

        $files = ReweMailController::fetchMailAttachments($this->argument("days"));

        echo strtr('* :count mails fetched!', [
                ':count' => count($files),
            ]) . PHP_EOL;

        foreach($files as $bonAttachment) {
            try {
                $userEmail = UserEmail::firstOrCreate(["email" => $bonAttachment->getEMail()]);

                echo strtr('* Parse receipt from <:email>...', [':email' => $userEmail->email,]) . PHP_EOL;

                $filename     = $bonAttachment->getFilename();
                $uploadedFile = new UploadedFile($filename, md5(time() . rand()) . '.pdf');

                if($userEmail->verifiedUser != null) {
                    $receipt = ImportController::parseReweReceipt($userEmail->verifiedUser, $uploadedFile);
                    echo strtr('** Receipt successfully parsed. ID=:id', [':id' => $receipt->id]) . PHP_EOL;
                } else {
                    echo strtr('** There is no verified user for <:email>...', [':email' => $userEmail->email,]) . PHP_EOL;
                }
            } catch(ReceiptParseException $e) {
                report($e);
                dump("Error while parsing receipt. Is the format compatible?");
            } catch(PdfNotFound $e) {
                report($e);
            }
        }

        return 0;
    }

}
