<?php

namespace Modules\ClickupIntegration\Entities;

class Group
{
    public string $id;
    public string $name;
    public string $avatarUrl;

    /**
     * Hydrates an entity with the required data for the integration
     *
     * @param array $data
     * @return Group
     */
    public static function hydrate(array $data)
    {
        $instance = new static;

        $instance->id = $data['id'] ?? '';
        $instance->name = $data['name'] ?? '';
        $instance->avatarUrl = $data['avatar']['attachment']['thumbnail_small'] ?? '';

        return $instance;
    }
}