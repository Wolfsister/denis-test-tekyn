<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\FavoriteProductManager;
use App\Service\SubstitutionManager;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException;
use MongoDB\Driver\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubstituteController extends AbstractController
{

    /**
     * @Route(name="save-substitution", path="/save/substitution/{eanCodeToReplace}", methods={"POST"})
     */
    public function saveSubstitution(Request $request, EntityManagerInterface $entityManager, SubstitutionManager $substitutionManager, string $eanCodeToReplace): JsonResponse
    {
        $eanCodeOfSubstitute = $request->get('eanCodeOfSubstitute');
        if (!$eanCodeOfSubstitute) {
            throw new InvalidArgumentException("Please indicate an ean code of a substitute with the param name 'eanCodeOfSubstitute'.");
        }

        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new \Exception("User not found.");
        }

        $similarProduct = $substitutionManager->searchSimilarProduct($eanCodeToReplace);
        $substitutionManager->addSubstitution($currentUser, $eanCodeToReplace, $similarProduct);
        $entityManager->flush();

        return new JsonResponse('Substitution successfully saved.', Response::HTTP_OK);
    }
}