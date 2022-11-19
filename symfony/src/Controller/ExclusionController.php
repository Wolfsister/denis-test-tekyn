<?php

namespace App\Controller;

use App\Service\ExcludedProductManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ExclusionController extends AbstractController
{
    /**
     * @Route(name="add-exclusion", path="/exclude/{code}", methods={"POST"})
     */
    public function addExclusion(EntityManagerInterface $entityManager, ExcludedProductManager $excludedProductManager, string $code): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new \Exception("User not found. ");
        }

        $excludedProductManager->addExcludedProduct($currentUser, $code);
        $entityManager->flush();

        return new JsonResponse('Product successfully saved as excluded.', Response::HTTP_OK);
    }

}