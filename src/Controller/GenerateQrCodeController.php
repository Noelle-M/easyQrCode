<?php

namespace App\Controller;

use App\Services\QrcodeService;
use App\Form\GenerateQrCodeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenerateQrCodeController extends AbstractController
{
    /**
     * @Route("/", name="app_generate_qr_code")
     */
    public function index(Request $request, QrcodeService $qrcodeService): Response
    {
        $qrCode = null;

        $numUnique = null;
        $form = $this->createForm(GenerateQrCodeType::class, null);
        $form->handleRequest($request);
        $oneMore = false;


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $qrCode = $qrcodeService->qrcode($data['name']);
            $numUnique = $data['name'];
            $oneMore = true;
        }

        //si nombreQR.txt n'existe pas on le crée.
        if (!file_exists('nombreQR.txt')) {
            file_put_contents('nombreQR.txt', "0");
        }
        //si on a généré compteur + 1
        if ($oneMore == true) {
            $fo = fopen('nombreQR.txt', 'rb');
            $firstLine = intval(fgets($fo));
            file_put_contents('nombreQR.txt', $firstLine + 1);
        }

        return $this->render('generate_qr_code/index.html.twig', [
            'form' => $form->createView(),
            'qrCode' => $qrCode,
            'numUnique' => $numUnique,
        ]);
    }
}
