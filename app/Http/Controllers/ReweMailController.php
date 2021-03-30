<?php

namespace App\Http\Controllers;

//TODO: Maybe there is a library available make it more comfortable?
use Illuminate\Support\Facades\Log;

class ReweMailController extends Controller {

    private $mailconnection;

    public function connect($host, $username, $password, int $port = 993, $folder = 'INBOX') {
        $this->mailconnection = imap_open('{' . $host . ':' . $port . '/imap/ssl}' . $folder, $username, $password);
    }

    public static function fetchMailAttachments(int $days) {
        $mailmanager = new self;
        $mailmanager->connect(config('app.rewe.mailer.host'), config('app.rewe.mailer.username'), config('app.rewe.mailer.password'), config('app.rewe.mailer.port'), config('app.rewe.mailer.inbox'));
        $mails = $mailmanager->getMails(strtotime("-" . $days . " days"));

        if(!$mails || is_null($mails) || count($mails) == 0) {
            Log::info('There is no mail to fetch.');
            return;
        }

        $files = array();

        foreach($mails as $mail) {
            //Ab hier wieder TODO cleanup!!!

            $attachments = array();
            if(isset($mail->getStructure()->parts) && count($mail->getStructure()->parts)) {
                for($i = 0; $i < count($mail->getStructure()->parts); $i++) {
                    $attachments[$i] = array(
                        'is_attachment' => false,
                        'filename'      => '',
                        'name'          => '',
                        'attachment'    => '');

                    if($mail->getStructure()->parts[$i]->ifdparameters) {
                        foreach($mail->getStructure()->parts[$i]->dparameters as $object) {
                            if(strtolower($object->attribute) == 'filename') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['filename']      = $object->value;
                            }
                        }
                    }

                    if($mail->getStructure()->parts[$i]->ifparameters) {
                        foreach($mail->getStructure()->parts[$i]->parameters as $object) {
                            if(strtolower($object->attribute) == 'name') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['name']          = $object->value;
                            }
                        }
                    }

                    if($attachments[$i]['is_attachment']) {
                        $attachments[$i]['attachment'] = $mail->getBody($i + 1);
                        if($mail->getStructure()->parts[$i]->encoding == 3) { // 3 = BASE64
                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        } elseif($mail->getStructure()->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                            $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                        }
                    }
                }
            }


            if(count($attachments) != 0) {
                foreach($attachments as $at) {
                    if($at['is_attachment'] == 1) {
                        $filename = "/tmp/" . md5($at['filename'] . time() . rand(0, 99999)) . '.pdf';
                        file_put_contents($filename, $at['attachment']);
                        $userEmail = '';
                        if(strpos($mail->getOverview()->from, 'ebon@mailing.rewe.de') !== false)
                            $userEmail = $mail->getOverview()->to;
                        elseif(strpos($mail->getOverview()->from, '<')) {
                            if(preg_match('/<(.*)>/', $mail->getOverview()->from, $match))
                                $userEmail = $match[1];
                        } else
                            $userEmail = $mail->getOverview()->from;

                        $files[] = new REWE_UserAttachment($userEmail, $filename);
                    }
                }
            }
        }

        return $files;
    }

    public function isConnected() {
        return $this->mailconnection !== null;
    }

    /**
     * Get ids from requested mails in inbox
     *
     * @param long $since - timestamp since
     *
     * @return array
     */
    public function getMails($since = null) {
        if(!$this->isConnected())
            return false;

        $emailIDs = imap_search($this->mailconnection, 'ALL' . ($since == null ? '' : ' SINCE ' . date('Y-m-d', $since)));

        if(!$emailIDs)
            return array();


        rsort($emailIDs); //sort by key (newest to the top)
        $emails = array();

        foreach($emailIDs as $email_number)
            $emails[] = new Mail($this->mailconnection, $email_number);

        return $emails;
    }

    public function closeConnection() {
        if($this->mailconnection === null)
            return;
        imap_close($this->mailconnection);
        $this->mailconnection = null;
    }

}

class Mail {

    private $mailconnection;
    private $email_number;
    //Caching down
    private $structure;
    private $overview;
    private $body = array();

    public function __construct($mailconnection, int $email_number) {
        $this->mailconnection = $mailconnection;
        $this->email_number   = $email_number;
    }

    public function getStructure() {
        if($this->structure === null)
            $this->structure = imap_fetchstructure($this->mailconnection, $this->email_number);
        return $this->structure;
    }

    public function getOverview() {
        if($this->overview === null)
            $this->overview = imap_fetch_overview($this->mailconnection, $this->email_number, 0)[0];
        return $this->overview;
    }

    public function getBody(int $option = 2) {
        if(!isset($this->body[$option]) || $this->body[$option] === null)
            $this->body[$option] = imap_fetchbody($this->mailconnection, $this->email_number, $option);
        return $this->body[$option];
    }

}

class REWE_UserAttachment {

    private $email;
    private $filename;

    public function __construct(string $email, string $filename) {
        $this->email    = $email;
        $this->filename = $filename;
    }

    public function getEMail() {
        return $this->email;
    }

    public function getFilename() {
        return $this->filename;
    }

}