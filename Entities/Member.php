<?php

namespace Modules\ClickupIntegration\Entities;

class Member
{
    public string $id;
    public string $username;
    public string $email;
    public string $profilePicture;

    /**
     * Hydrates an entity with the required data for the integration
     *
     * @param array $data
     * @return Member
     */
    public static function hydrate(array $data)
    {
        $instance = new static;

        $instance->id = $data['id'] ?? '';
        $instance->username = $data['username'] ?? '';
        $instance->email = $data['email'] ?? '';
        $instance->profilePicture = $data['profilePicture'] ?? '';

        return $instance;
    }
}