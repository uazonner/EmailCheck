<?php

namespace App;

use PHPUnit\Framework\TestCase;

require_once "../vendor/autoload.php";

class EmailProcessorTest extends TestCase
{
    protected $result;

    public function setUp()
    {

    }

    public function tearDown()
    {

    }

    public function testEmailWork()
    {

        $id = mt_rand(1, 99);

        $mail = new EmailProcessor();
        $this->assertTrue($mail->mxRecordValidate());
        $this->assertTrue($mail->sendEmail($id));
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
            $this->assertTrue($mail->checkEmail($id));
        } else {
            $file = '../log.dat';
            $time = date('d-m-Y H:i:s');
            $result = '[' . $time . '] ' . $result . "\r\n";
            $put = file_put_contents($file, $result, FILE_APPEND);
            $this->assertNotEmpty($put);
        }
    }
}