<?php

namespace App\Policy;

class UserResourcePolicy
{
    public function isTestUser(array $roles): bool
    {
        return in_array('ROLE_TEST_USER', $roles, true);
    }
}