<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use DateTimeImmutable;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/article/creer', name: 'app_article_create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $article = new Article();
        $article->setTitre('Moyen sûr du titre')
            ->setTexte('Texte')
            ->setPublie(1)
            ->setDate(new DateTimeImmutable());


        $form = $this->createFormBuilder($article)
            ->add('titre', TextType::class)
            ->add('texte', TextType::class)
            ->add('publie', CheckboxType::class)
            ->add('date', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Créer un article'])
            ->getForm();


        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            $entityManager->persist($article);

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre article N° ' . $article->getId() . ' est ajouté !'
            );
        }


        return $this->render('article/creer.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/article/liste', name: 'app_article_list')]
    public function read(EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->findAll();


        return $this->render('article/liste.html.twig', [
            'controller_name' => 'ArticleController',
            'liste_article' => $article
        ]);
    }

    #[Route('/article/modifier/{id}', name: 'app_article_modifier')]
    public function update(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);
        if (!$article) {
            throw $this->createNotFoundException(
            'No article found for id ' . $id
            );
        }



        $form = $this->createFormBuilder($article)
        ->add('titre', TextType::class)
        ->add('texte', TextType::class)
        ->add('publie', CheckboxType::class)
        ->add('date', DateType::class)
        ->add('save', SubmitType::class, ['label' => 'Créer un article'])
        ->getForm();


        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            // ... perform some action, such as saving the task to the database

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre article N° ' . $article->getId() . ' a été modifé !'
            );
        }


        return $this->render('article/modifier.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/article/supprimer/{id}', name: 'app_article_supprimer')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {

        $article = $entityManager->getRepository(Article::class)->find($id);
        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id ' . $id
            );
        }

        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Votre article a été supprimé !'
        );


        return $this->redirectToRoute('app_article_list');
    }
}
