<?php

namespace App\Controller;

use App\Entity\File;
use App\Form\FileType;
use App\Repository\FileRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/file')]
class FileController extends AbstractController
{
    #[Route('/', name: 'app_file_index', methods: ['GET'])]
    public function index(FileRepository $fileRepository): JsonResponse
    {
        try {
            $files = $fileRepository->findAll();
            return $this->json($files, Response::HTTP_OK, [], ['groups' => 'file:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/new', name: 'app_file_new', methods: ['POST'])]
    public function new(Request $request, HttpClientInterface $client,PostRepository $postRepository,EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $file = new File();
            $file->setFileName($data['fileName']);
            $post= $postRepository->find($data['post']);
            $file->setPost($post);
            $entityManager->persist($file);
            $entityManager->flush();

            return $this->json(['status' => 'File created'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'app_file_show', methods: ['GET'])]
    public function show($id,FileRepository $fileRepository, SerializerInterface $serializer): JsonResponse
    {
        try {
            $file = $fileRepository->find($id);
            if ($file === null) {
                return new JsonResponse(['error' => 'file not found'], Response::HTTP_NOT_FOUND);
            }
            $response = $serializer->serialize($file, 'json');
            return new JsonResponse($response, 200, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // #[Route('/{id}/edit', name: 'app_file_edit', methods: ['PUT'])]
    // public function edit(Request $request, File $file, EntityManagerInterface $entityManager): JsonResponse
    // {
    //     try {
    //         $data = json_decode($request->getContent(), true);
    //         // Update $file with data from $data
    //         $entityManager->flush();
    //         return $this->json(['status' => 'File updated'], Response::HTTP_OK);
    //     } catch (\Exception $e) {
    //         return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    #[Route('/{id}', name: 'app_file_delete', methods: ['DELETE'])]
    public function delete($id,Request $request, FileRepository $fileRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $file = $fileRepository->find($id);
            if ($file === null) {
                throw new \Exception('like not found');
            }
            $entityManager->remove($file);
            $entityManager->flush();
            return new JsonResponse(['status' => 'file deleted'], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
