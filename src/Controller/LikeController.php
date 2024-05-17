<?php

namespace App\Controller;

use App\Entity\Like;
use App\Form\LikeType;
use App\Repository\LikeRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/like')]
class LikeController extends AbstractController
{
    #[Route('/', name: 'app_like_index', methods: ['GET'])]
    public function index(LikeRepository $likeRepository, HttpClientInterface $client,SerializerInterface $serializer ): Response
    {
        try
            {$data = $likeRepository->findAll();
            $response = $serializer->serialize($data, 'json');
            return new JsonResponse($response, 200, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/new', name: 'app_like_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserRepository $userRepository,PostRepository $postRepository): Response
    {
        try{
            
            $data = json_decode($request->getContent(), true);
            $like = new Like();
            $post=$postRepository->find($data['post']);
            $like->setPost($post);
            $user = $userRepository->find($data['user']);

            $like->setUser($user);

            $entityManager->persist($like);
            $entityManager->flush();
            return new JsonResponse(['status' => 'Like created'], Response::HTTP_CREATED);
        }
        catch(\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    #[Route('/{id}', name: 'app_like_show', methods: ['GET'])]
    public function show($id,LikeRepository $likeRepository, SerializerInterface $serializer): Response
    {
        try {
            $like = $likeRepository->find($id);
            if ($like === null) {
                return new JsonResponse(['error' => 'like not found'], Response::HTTP_NOT_FOUND);
            }
            $response = $serializer->serialize($like, 'json');
            return new JsonResponse($response, 200, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // #[Route('/{id}/edit', name: 'app_like_edit', methods: ['PUT'])]
    // public function edit(Request $request, Like $like, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(LikeType::class, $like);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_like_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('like/edit.html.twig', [
    //         'like' => $like,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_like_delete', methods: ['DELETE'])]
    public function delete($id,Request $request, LikeRepository $likeRepository, EntityManagerInterface $entityManager): Response
    {
        try {
            $like = $likeRepository->find($id);
            if ($like === null) {
                throw new \Exception('like not found');
            }
            $entityManager->remove($like);
            $entityManager->flush();
            return new JsonResponse(['status' => 'like deleted'], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}