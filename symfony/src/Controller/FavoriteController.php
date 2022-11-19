<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\FavoriteProductService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{

    /**
     * @Route(name="save-favorite", path="/save/{code}", methods={"POST"})
     */
    public function registerUser(EntityManagerInterface $entityManager, FavoriteProductService $favoriteProductService, string $code): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new \Exception("User not found. ");
        }

        $favoriteProductService->addFavoriteProduct($currentUser, $code);
        $entityManager->flush();

        return new JsonResponse('Product successfully saved as favorite.', Response::HTTP_OK);
    }
}