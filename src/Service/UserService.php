<?php 

namespace App\Service;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Policy\UserResourcePolicy;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private Security $security;
    private EntityManagerInterface $entityManager;
    private UserResourcePolicy $userResourcePolicy;
    private ValidatorInterface $validator;
    

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        UserResourcePolicy $userResourcePolicy,
        ValidatorInterface $validator
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->userResourcePolicy = $userResourcePolicy;
        $this->validator = $validator;
    }

    public function getUserDetails(int $id)
    {
        $authUser = $this->security->getUser();
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        if ($this->userResourcePolicy->isTestUser($authUser->getRoles())
            && $authUser->getId() !== $user->getId()
        ) {
            throw new AccessDeniedException('Access denied');
        }

        return $user;
    }
    
    public function createUser($data)
    {
        $authUser = $this->security->getUser();

        if ($this->userResourcePolicy->isTestUser($authUser->getRoles())
        ) {
            throw new AccessDeniedException('Access denied');
        }

        $user = new User();
        $user->setId($data['id']);
        $user->setLogin($data['login']);
        $user->setPass($data['pass']);
        $user->setPhone($data['phone']);
        $user->setRoles($data['roles']);

        $errors = $this->validator->validate($user);
    
        $this->checkErros($errors);

        $this->entityManager->persist($user);
        
        $this->entityManager->flush();

        return $user;
    }

    public function editUser($id, $data)
    {
        $authUser = $this->security->getUser();
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        if ($this->userResourcePolicy->isTestUser($authUser->getRoles())
            && $authUser->getId() !== $user->getId()
        ) {
            throw new AccessDeniedException('Access denied');
        }

        $user->setLogin($data['login']);
        $user->setPass($data['pass']);
        $user->setPhone($data['phone']);
        $user->setRoles($data['roles']);

        $errors = $this->validator->validate($user);

        $this->checkErros($errors);

        $this->entityManager->persist($user);
        
        $this->entityManager->flush();

        return $user;
    }

    public function deleteUser($id)
    {
        $authUser = $this->security->getUser();

        if ($this->userResourcePolicy->isTestUser($authUser->getRoles())
        ) {
            throw new AccessDeniedException('Access denied');
        }

        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $this->entityManager->remove($user);

        $this->entityManager->flush();
    }

    private function checkErros($errors)
    {
        if (count($errors)) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new \Exception(implode( '. ', $errorMessages));
        }
    }
}