<?php

namespace App\Controller;

use App\Entity\Music;
use App\Form\UploadType;
use App\Repository\MusicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploaderController extends AbstractController
{
    #[Route('/', name: 'app_uploader')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $music = new Music();
        $form = $this->createForm(UploadType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('link')->getData();
            $image = $form->get('cover')->getData();
            $musicName = uniqid('music-');

            if ($file) {
                try {
                    $fileName = $musicName . '.' . $file->guessExtension();
                    $file->move($this->getParameter('uploads_directory'), $fileName);

                    $music->setLink($fileName);
                } catch (\Exception $e) {
                    throw new \Exception('An error occured while uploading the music: ' . $e->getMessage());
                }
            }

            if ($image) {
                try {
                    $fileName = $musicName . '.' . $image->guessExtension();
                    $image->move($this->getParameter('uploads_directory'), $fileName);

                    $music->setCover($fileName);
                } catch (\Exception $e) {
                    throw new \Exception('An error occured while uploading the music: ' . $e->getMessage());
                }
            }

            $em->persist($music);
            $em->flush();

            $this->addFlash('success', 'Your track has been uploaded!');
            return $this->redirectToRoute('app_uploader_ok', ['id' => $music->getId()]);
        }

        return $this->render('uploader/index.html.twig', [
            'uploadForm' => $form
        ]);
    }

    #[Route('/ok/{id}', name: 'app_uploader_ok')]
    public function ok(Music $music): Response
    {
        return $this->render('uploader/ok.html.twig', [
            'music' => $music
        ]);
    }
}
