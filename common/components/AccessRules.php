<?php

namespace common\components;

use yii\filters\AccessRule;

class AccessRules extends AccessRule
{

    /**
     * @inheritdoc
     */
    protected function matchRole($user)
    {
        if ($user->identity) {
            $userRoles = $user->identity->rolesIdentity;
        }

        if (empty($this->roles)) {
            return true;
        }

        foreach ($this->roles as $role) {
            if ($user->isGuest && $role == '?') {
                return true;
            } elseif (!$user->isGuest && is_array($userRoles) && in_array($role, $userRoles)) {

                return true;
            } elseif (!$user->isGuest && $role == '@') {
                return true;
            }
        }
        return false;
    }
}