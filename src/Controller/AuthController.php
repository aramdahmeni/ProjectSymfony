<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Cookie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        echo "Reached login method";
        // Check if user is already authenticated, redirect to home page
        if ($this->getUser() instanceof UserInterface) {
            // Log to console
            error_log('success', 'You are already logged in.');

            // Dump the authentication token
            $token = $this->tokenStorage->getToken();
            dump($token);

            // Dump user roles
            $user = $this->getUser();
            dump($user->getRoles());

            return $this->redirectToRoute('home');
        }

        // Get the last authentication error (if any)
        $error = $authenticationUtils->getLastAuthenticationError();

        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Create a response
        $response = $this->render('auth/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);

        // Check if authentication was successful and user exists in the database
        // Retrieve the authenticated user
        $user = $this->getUser();

        if (!$error && $user instanceof User) {
            echo "Logged in as: " . $user->getNom(); // Add this line
            dump($user->getRoles());
            $cookie = new Cookie('user_id', $user->getId());
            $response->headers->setCookie($cookie);
        }

        return $response;
    }


    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
    }
}
