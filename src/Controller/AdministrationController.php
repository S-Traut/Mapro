<?php

namespace App\Controller;


use App\Entity\Magasin;
use Symfony\Component\Mime\Address;
use App\Repository\ArticleRepository;
use App\Repository\MagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/administration")
*/
class AdministrationController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(MagasinRepository $magasinRepository): Response
    {
        $value = $magasinRepository->findBy(['etat' => 0]);
        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
            'current_menu' => 'admin',
            'nbMagasinsAttentes' => $value
        ]);
    }

    /**
     * @Route("/waitlist")
     */
    public function magasinsEnAttentes(MagasinRepository $magasinRepository, Request $request, PaginatorInterface $paginator)
    {
        $value = $paginator->paginate($magasinRepository->findBy(['etat' => 0]), $request->query->getInt('page', 1), 10);
        return $this->render('administration/listesAttentes.html.twig', [
            'magasins' => $value
        ]);
    }

    /**
     * @Route("/articles/liste")
     */
    public function listeArticles(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request)
    {
        $articles = $paginator->paginate($articleRepository->findAll(), $request->query->getInt('page', 1), 10);
        return $this->render('administration/listesArticles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/magasins/liste")
     */
    public function listeMagasins(MagasinRepository $magasinRepository, Request $request, PaginatorInterface $paginator)
    {
        $magasins = $paginator->paginate($magasinRepository->findBy(['etat' => 1]), $request->query->getInt('page', 1), 10);
        return $this->render('administration/listesMagasins.html.twig', [
            'magasins' => $magasins
        ]);
    }

    /**
     * @Route("/statistiques")
     */
    public function listeStatistiques()
    {
        return $this->render('administration/statistiques.html.twig', [

        ]);
    }

    /**
     * @Route("/valider/{id<\d+>}")
     */
    public function validerMagasin(Magasin $shop, EntityManagerInterface $em)
    {
        $shop->setEtat(1);
        $em->flush();
        return $this->redirectToRoute('app_administration_magasinsenattentes');
    }

    /**
     * @Route("/refuser/{id<\d+>}")
     */
    public function refuserMagasin(Magasin $shop, EntityManagerInterface $em, Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('Message_de_refus', TextareaType::class)
            ->add('Envoyer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $email = $shop->getEmail();
            $template = (new TemplatedEmail())
                    ->from(new Address('vintage.mapro@gmail.com', '"Mapro compte"'))
                    ->to($email)
                    ->subject($data)
                    ->htmlTemplate('administration/refus.html.twig');
            return $this->redirectToRoute('app_administration_magasinsenattentes');
        }

        return $this->render('administration/motif.html.twig', [
            'form' => $form->createView()
        ]);
    }

}

