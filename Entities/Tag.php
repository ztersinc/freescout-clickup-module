<?php

namespace Modules\ClickupIntegration\Entities;

class Tag
{
    public string $name;
    public string $fgColor;
    public string $bgColor;

    /**
     * Hydrates an entity with the required data for the integration
     *
     * @param array $data
     * @return Group
     */
    public static function hydrate(array $data)
    {
        $instance = new static;

        $instance->name = $data['name'] ?? '';
        $instance->fgColor = $data['tag_fg'] ?? '';
        $instance->bgColor = $data['tag_bg'] ?? '';

        return $instance;
    }
}