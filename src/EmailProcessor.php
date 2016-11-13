<?php
namespace App;

use PHPMailer;

class EmailProcessor
{
    /**
     * @return bool
     */
    public function mxRecordValidate()
    {
        $domain = substr(strrchr(Config::getUser('email'), '@'), 1);
        $mxArr = dns_get_record($domain, DNS_MX);

        if (!empty($mxArr[0]['target'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param integer $id
     * @return bool
     */
    public function sendEmail($id)
    {
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = Config::smtp('host');
        $mail->SMTPAuth = true;
        $mail->Username = Config::getUser('email');
        $mail->Password = Config::getUser('pass');
        $mail->SMTPSecure = Config::smtp('smtpSecure');
        $mail->CharSet = Config::smtp('charset');
        $mail->Port = Config::smtp('port');

        $mail->setFrom(Config::getUser('email'), Config::getUser('name'));
        $mail->addAddress(Config::getUser('email'));

        $mail->Subject = "Mail testing subject id = $id";
        $mail->isHTML(true);
        $mail->Body = 'Check email for working';

        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param integer $id
     * @return string|boolean
     */
    public function checkEmail($id)
    {
        $user = Config::getUser('email');
        $pass = Config::getUser('pass');

        // New connections
        $sopen = fsockopen(Config::pop3('host'), Config::pop3('port'), $errno, $errstr, 10)
        or die('Connection with pop3 server aborted');
        $infoMessages[] = fgets($sopen, 1024);

        // Authorize
        fputs($sopen, "USER $user\r\n");
        $infoMessages[] = fgets($sopen, 1024);

        fputs($sopen, "PASS $pass\r\n");
        $infoMessages[] = fgets($sopen, 1024);

        // List messages
        fputs($sopen, "LIST\r\n");
        $infoMessages[] = fgets($sopen, 1024);

        // Generate array list messages
        $list = [];
        while (!feof($sopen)) {
            $str = rtrim(fgets($sopen, 1024));
            $list[] = $str;
            if (trim($str) === '.') {
                break;
            }
        }
        // Unset from array, value with '.' added from server
        unset($list[count($list) - 1]);

        // Get message number issued from server
        foreach ($list as &$messageTitle) {
            $part = explode(' ', $messageTitle);
            $messageTitle = $part[0];
        }

        // Sort in reverse order, to start search with the last message
        krsort($list);

        // Search own message with ID
        $messageID = '';
        foreach ($list as $message) {
            fputs($sopen, "RETR $message\r\n");
            while (!feof($sopen)) {
                $str = fgets($sopen, 1024);
                if (strpos($str, "Mail testing subject id = $id") != false) {
                    $messageID = $message;
                }
                if (trim($str) == '.') {
                    break;
                }
            }
            if ($messageID != '') {
                break;
            }
        }

        // Get all data from message with own ID
        $messageData = '';

        if ($messageID != '') {
            fputs($sopen, "RETR $messageID\r\n");

            while (!feof($sopen)) {
                $str = trim(fgets($sopen, 1024));
                $messageData .= $str;
                if (trim($str) == '.') {
                    break;
                }
            }
        }

        // Delete own message
        if ($messageID != '') {
            fputs($sopen, "DELE $messageID\r\n");
            $infoMessages[] = fgets($sopen, 1024);
        }

        // Close session
        fputs($sopen, "QUIT\r\n");
        $infoMessages[] = fgets($sopen, 1024);
        fclose($sopen);

        // Return needed Data from message
        $headerStart = strpos($messageData, 'Received:');
        $headerEnd = strpos($messageData, 'Date:');

        if ($messageID == '') {
            return false;
        } else {
            return substr($messageData, $headerStart, $headerEnd - $headerStart);
        }
    }
}
