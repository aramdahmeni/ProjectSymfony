<?php

namespace App\Controller;
use App\Repository\CommentRepository;
use App\Entity\Post;
use App\Entity\File;
use App\Entity\User;

use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;



#[Route('/post')]
class PostController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_post_index', methods: ['GET'])]
public function index(PostRepository $postRepository, CommentRepository $commentRepository): Response
{
    $posts = $postRepository->findAll();
    $comments = [];

    // Fetch comments for each post
    foreach ($posts as $post) {
        $comments[$post->getId()] = $commentRepository->findBy(['post' => $post]);
    }

    return $this->render('post/index.html.twig', [
        'posts' => $posts,
        'comments' => $comments,
    ]);
}


#[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
public function new(Request $request): Response
{
    $post = new Post();
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload
        $uploadedFiles = $request->files->get('post')['files'];

        foreach ($uploadedFiles as $uploadedFile) {
            // Create a new File entity for each uploaded file
            $file = new File();
            // Handle file upload logic and set properties of File entity
            // For example:
            $fileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move($this->getParameter('uploads_directory'), $fileName);
            $file->setFileName($fileName);
            // Add the file to the Post entity
            $post->addFile($file);
        }

        // Set other properties of the Post entity as needed
        $post->setPublished(new \DateTime());

        // Persist the Post entity
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_post_index');
    }

    return $this->render('post/new.html.twig', [
        'post' => $post,
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{postId}/comments', name: 'app_post_comments', methods: ['GET'])]
    public function comments(int $postId, CommentRepository $commentRepository): JsonResponse
    {
        // Fetch comments for the given post ID
        $comments = $commentRepository->findBy(['post' => $postId]);

        // Convert comments to array
        $commentsArray = [];
        foreach ($comments as $comment) {
            $commentsArray[] = [
                'id' => $comment->getId(),
                'content' => $comment->getContenu(),
                // Add other properties as needed
            ];
        }

        // Return JSON response with comments
        return $this->json($commentsArray);
    }
//     #[Route('/post/{id}/like', name: 'app_post_like', methods: ['POST'])]
// public function likePost(Post $post, EntityManagerInterface $entityManager, UserInterface $user): JsonResponse
// {
//     // Check if the user is authenticated
//     if (!$user) {
//         return $this->json(['error' => 'User is not authenticated'], Response::HTTP_UNAUTHORIZED);
//     }

//     // Create a new like instance
//     $like = new Like();

//     // Set the user and post for the like
//     $like->setUser($user);
//     $like->setPost($post);

//     // Persist the like
//     $entityManager->persist($like);
//     $entityManager->flush();

//     // Return a JSON response indicating success
//     return $this->json(['message' => 'Like added successfully'], Response::HTTP_OK);
// }


}