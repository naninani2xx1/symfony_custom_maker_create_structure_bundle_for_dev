<?php

namespace App\Controller;

use App\Backend\AMZ\StoreBundle\Entity\Store;
use App\Core\Controller\CoreController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestingController extends CoreController
{
    #[Route(path: '/index', name: 'index')]
    public function index(Request $request): Response
    {
        $store = new Store();
        $form = $this->createFormBuilder($store)
            ->add('name')
            ->add('thumbnail',FileType::class,[
                'mapped' => false,
            ])
            ->add('submit',SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $file = $request->files->get('form')['thumbnail'];
            if(is_a($file, UploadedFile::class)){
                try{
                    $result =  $this->uploadFileServices->uploadFile($file);
                }catch (FileException $fileException){
                    dd($fileException);
                }
            }

            $this->addFlash('store.success','store added');
            $this->entityManager->persist($store);
            $this->entityManager->flush();

        }

        return $this->render('@Store/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
}