<?php

namespace App;

use PHPUnit\Framework\TestCase;

require_once "../vendor/autoload.php";

class EmailProcessorTest extends TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {

    }

    public function testEmailWork()
    {
        $file = '../log.dat';
        $id = mt_rand(1, 99);

        $mail = new EmailProcessor();
        $this->assertTrue($mail->mxRecordValidate() !== false);
        $this->assertTrue($mail->sendEmail($id) !== false);
        $result = $mail->checkEmail($id);
        if ($result === false) {
            for ($i = 0; $i < 5; $i++) {
                sleep(5 * $i);
                if ($mail->checkEmail($id) !== false) {
                    $result = true;
                    break;
                }
            }
        }

        if ($result === false) {
            $this->assertTrue($mail->checkEmail($id) !== false);
        } else {
            file_put_contents($file, $result, FILE_APPEND);
        }

    }
}