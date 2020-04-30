<?php

namespace App\Controller\Admin;

use App\Entity\Option ;
use App\Form\OptionType;
use App\Repository\OptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AdminOptionController extends AbstractController
{
   /**
     * @Route("/admin/option", name="admin.option.index", methods={"GET"})
     */
    public function index(OptionRepository $optionRepository): Response
    {
        return $this->render('admin/option/index.html.twig', [
            'options' => $optionRepository->findAll(),
        ]);
    }
     /**
     * @Route("/admin/option/new", name="admin.option.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $option = new Option();
        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($option);
            $entityManager->flush();

            return $this->redirectToRoute('admin.option.index');
        }

        return $this->render('admin/option/new.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/admin/option/{id}/edit", name="admin.option.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Option $option): Response
    {
        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.option.index', ['id' =>$option->getId()]);
        }

        return $this->render('admin/option/edit.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
        ]);
    }

   /**
    * @Route("/admin/option/{id}", name="admin.option.delete", methods="DELETE")
    */
    public function delete(Option $option, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $option->getId(), $request->get('_token'))) {
            $this->entityManager->remove($option);
            $this->entityManager->flush();
            $this->addFlash("success", "Votre option a été supprimé avec succès");
        }
         
        return $this->redirectToRoute('admin.option.index');
    }
}
