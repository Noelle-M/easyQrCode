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
        $form = $this->createForm(GenerateQrCodeType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $qrCode = $qrcodeService->qrcode($data['name']);
        }

        return $this->render('generate_qr_code/index.html.twig', [
            'form' => $form->createView(),
            'qrCode' => $qrCode
        ]);
    }
}
