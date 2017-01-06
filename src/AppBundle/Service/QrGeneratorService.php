<?php

namespace AppBundle\Service;

use Endroid\QrCode\QrCode;

class QrGeneratorService
{
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function generate($token)
    {
        $qrCode = new QrCode();

        $qrCode
            ->setText($token)
            ->setSize(280)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
            ->setLabelFontSize(16)
            ->setImageType(QrCode::IMAGE_TYPE_PNG)
        ;

        $qrCode->save('images/'.preg_replace('/\s+/', '', $token).'.png');

        return $qrCode->getText();
    }
}
