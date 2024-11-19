<?php

namespace App\Policy;

use App\Entity\User;

class UserResourcePolicy
{
    private function isTestUser(array $roles): bool
    {
        return in_array('ROLE_TEST_USER', $roles, true);
    }

    public function authorizeUser(User $authUser, User $user)
    {
        return (
            $this->isTestUser($authUser->getRoles()) 
            && $authUser->getId() === $user->getId()) 
            || $this->isAdminTest($authUser->getRoles()
        );
    }

    public function authorizaAdmin(array $roles): bool
    {
        return $this->isAdminTest($roles);
    }

    private function isAdminTest(array $roles): bool
    {
        return in_array('ROLE_TEST_ADMIN', $roles, true);
    }
}