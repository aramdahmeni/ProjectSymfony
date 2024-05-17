<?php

namespace App\Controller;
use App\Entity\Post;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/', name: 'app_comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository, HttpClientInterface $client,SerializerInterface $serializer): Response
    {
        try
            {$data = $commentRepository->findAll();
            $response = $serializer->serialize($data, 'json');
            return new JsonResponse($response, 200, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/new/{postId}', name: 'app_comment_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        try{
            $data = json_decode($request->getContent(), true);
            $comment = new Comment();
            $comment->setContenu($data['contenu']);
            $comment->setPost($data['post']);
            $comment->setPublished($data['published']);
            $comment->setUser($data['user_id']);
            $entityManager->persist($comment);
            $entityManager->flush();
            return new JsonResponse(['status' => 'comment created'], Response::HTTP_CREATED);
        }
        catch(\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    #[Route('/{id}', name: 'app_comment_show', methods: ['GET'])]
    public function show($id,CommentRepository $commentRepository, SerializerInterface $serializer): Response
    {
        try {
            $comment = $commentRepository->find($id);
            if ($comment === null) {
                return new JsonResponse(['error' => 'comment not found'], Response::HTTP_NOT_FOUND);
            }
            $response = $serializer->serialize($comment, 'json');
            return new JsonResponse($response, 200, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    #[Route('/show/{postId}', name: 'app_comment_show', methods: ['GET'])]
    public function showComments(int $postId, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        try {
            $post = $entityManager->getRepository(Post::class)->find($postId);

            if (!$post) {
                return new JsonResponse(['error' => 'The post does not exist'], Response::HTTP_NOT_FOUND);
            }

            $comments = $post->getComments();
            $response = $serializer->serialize($comments, 'json');
            return new JsonResponse($response, 200, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(CommentType::class, $comment);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('comment/edit.html.twig', [
    //         'comment' => $comment,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['DELETE'])]
    public function delete($id,Request $request, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response
    {
        try {
            $comment = $commentRepository->find($id);
            if ($comment === null) {
                throw new \Exception('comment not found');
            }
            $entityManager->remove($comment);
            $entityManager->flush();
            return new JsonResponse(['status' => 'comment deleted'], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}
