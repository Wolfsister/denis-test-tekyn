<?php

namespace App\Controller;

use App\Entity\FavoriteProduct;
use App\Entity\User;
use App\Service\FavoriteProductManager;
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
    public function saveFavorite(EntityManagerInterface $entityManager, FavoriteProductManager $favoriteProductManager, string $code): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new \Exception("User not found. ");
        }

        $favoriteProductManager->addFavoriteProduct($currentUser, $code);
        $entityManager->flush();

        return new JsonResponse('Product successfully saved as favorite.', Response::HTTP_OK);
    }

    /**
     * @Route(name="delete-favorite", path="/delete/{code}", methods={"DELETE"})
     */
    public function deleteFavorite(EntityManagerInterface $entityManager, FavoriteProductManager $favoriteProductManager, string $code): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new \Exception("User not found. ");
        }

        $favoriteProductManager->removeFavoriteProduct($currentUser, $code);
        $entityManager->flush();

        return new JsonResponse('Product successfully removed of favorites.', Response::HTTP_OK);
    }
}