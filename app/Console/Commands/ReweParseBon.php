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
use App\Http\Controllers\Backend\Receipt\Grocy\ReceiptController;
use App\Exceptions\NotConnectedException;
use Illuminate\Support\Facades\Log;

class ReweParseBon extends Command {

    protected $signature = "rewe:parse {days=2}";

    public function handle(): int {
        $this->info('* Fetch mails and attachments...');

        $files = ReweMailController::fetchMailAttachments($this->argument("days"));

        $this->info(strtr('* :count mails fetched!', [
            ':count' => count($files),
        ]));

        foreach($files as $bonAttachment) {
            try {
                $userEmail = UserEmail::firstOrCreate(["email" => $bonAttachment->getEMail()]);
                $user      = $userEmail->verifiedUser;

                $this->info(strtr('* Parse receipt from <:email>...', [':email' => $userEmail->email,]));

                $filename     = $bonAttachment->getFilename();
                $uploadedFile = new UploadedFile($filename, md5(time() . rand()) . '.pdf');

                if($user !== null) {
                    $receipt = ImportController::parseReweReceipt($user, $uploadedFile);
                    $this->info(strtr('** Receipt successfully parsed. ID=:id', [':id' => $receipt->id]));
                    if(isset($user->socialProfile->grocy_host) && $receipt->wasRecentlyCreated == 1) {
                        ReceiptController::addReceiptToStock($receipt);
                    }
                } else {
                    $this->info(strtr('** There is no verified user for <:email>...', [':email' => $userEmail->email,]));
                }
            } catch(ReceiptParseException|PdfNotFound $exception) {
                $this->error('** Receipt could not be parsed!');
                Log::error('** Receipt could not be parsed! ' . $bonAttachment->getEMail());
                report($exception);
            } catch(NotConnectedException $exception) {
                $this->error('** Could not connect to Grocy!');
                Log::error('** Could not connect to Grocy! ' . $bonAttachment->getEMail());
                report($exception);
            }
        }

        return 0;
    }

}
