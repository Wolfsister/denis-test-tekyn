<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController {

    /**
     * @Route(name="create-user", path="/create-user", methods={"POST"})
     */
    public function registerUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setUsername($username);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        try {
            $entityManager->flush();
        } catch(UniqueConstraintViolationException $uniqueConstraintViolationException) {
            return new JsonResponse('Try another user, this one seems to be already created.', Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('User saved', Response::HTTP_OK);
    }

}