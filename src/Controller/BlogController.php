<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Post;
use App\Form\ContactType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/categories/{slug}', name: 'show_category', requirements: ['slug' => '[a-zA-Z0-9_-]+'])]
    public function showCategory(Category $category): Response
    {
        return $this->render('blog/categories.html.twig', ['category' => $category]);
    }

    #[Route('/articles/{slug}', name: 'show_post', requirements: ['slug' => '[a-zA-Z0-9_-]+'])]
    public function showPost(Post $post): Response
    {
        return $this->render('blog/single-post.html.twig', ['post' => $post]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/contactez-nous', name: 'contact')]
    public function showContact(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $contact = new Contact();

        $form = $this
            ->createForm(ContactType::class, $contact)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            $email = (new Email())
                ->from($contact->getEmail())
                ->to('contact@symfoblog.net')
                ->subject($contact->getSubject())
                ->text($contact->getMessage())
                ->html($contact->getMessage());

            $mailer->send($email);

            $request->getSession()->getFlashBag()->add('success', 'Message envoyé avec succès');

            return $this->redirectToRoute('app_blog');
        }

        return $this->render('blog/contact.html.twig', ['form' => $form->createView()]);
    }

    public function menuCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('_categories.html.twig', [
            'categories' => $categories,
        ]);
    }

}
