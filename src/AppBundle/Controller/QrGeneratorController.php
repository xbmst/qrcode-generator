<?php

namespace AppBundle\Controller;

use AppBundle\Form\GetTokenType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QrGeneratorController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $tokenForm = $this->createForm(GetTokenType::class);
        $tokenForm->handleRequest($request);
        $generatorService = $this->get('qr_generator_service');

        if ($tokenForm->isValid() && $tokenForm->isSubmitted()) {
            $token = $tokenForm->get('token')->getData();

            $qrCode = $generatorService->generate($token);
            $this->sendEmail($qrCode);

            $this->addFlash('success', 'QR code generated and sent!');
            $this->addFlash('error', 'Something just went wrong.');

            return $this->redirectToRoute('index');
        }

        return $this->render('generate.html.twig', [
            'tokenForm' => $tokenForm->createView(),
        ]);
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
        $cid = $message->embed(\Swift_Image::fromPath('images/'.(preg_replace('/\s+/', '', $text)).'.png'));
        $message->setBody(
            '<html>' .
            '<head></head>' .
            '   <body>' .
            '       <img src="'.$cid.'" alt="Image" />' .
            '   </body>' .
            '</html>',
            'text/html'
        );
        $this->get('mailer')->send($message);
    }
}
