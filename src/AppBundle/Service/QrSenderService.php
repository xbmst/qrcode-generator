<?php

namespace AppBundle\Service;

class QrSenderService
{
    public function __construct(string $text, \Swift_Mailer $mailer)
    {
        $this->text = $text;
        $this->mailer = $mailer;
    }

    public function sendEmail($text)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Generated QR code')
            ->setFrom([
                'qrgen2@gmail.com' => 'QR code generator'
            ])
            ->setTo('qrgen2@gmail.com')
            ->setContentType('text/html')
        ;
        $cid = $message->embed(\Swift_Image::fromPath('images/'.preg_replace('/\s+/', '', $text).'.png'));
        $message->setBody(
            '<html>' .
            '<head></head>' .
            '   <body>' .
            '       <img src="'.$cid.'" alt="Image" />' .
            '   </body>' .
            '</html>',
            'text/html'
        );
        $this->mailer->send($message);
    }
}
