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
        $sendService = $this->get('qr_sender_service');

        if ($tokenForm->isValid() && $tokenForm->isSubmitted()) {
            $token = $tokenForm->get('token')->getData();
            $qrCode = $generatorService->generate($token);
            $sendService->sendEmail($qrCode);

            $this->addFlash('success', 'QR code generated and sent!');
            $this->addFlash('error', 'Something just went wrong.');

            return $this->redirectToRoute('index');
        }

        return $this->render('generate.html.twig', [
            'tokenForm' => $tokenForm->createView(),
        ]);
    }
}
