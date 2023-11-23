<?php

namespace Modules\ClickupIntegration\Entities;

use Modules\ClickupIntegration\Providers\ClickupIntegrationServiceProvider as Provider;

class Task implements HydratableInterface
{
    public string $id;
    public string $custom_id;
    public string $team_id;
    public string $name;
    public string $url;
    public string $status;

    /**
     * Hydrates an entity with the required data for the integration
     *
     * @param array $data
     * @return Task
     */
    public static function hydrate(array $data)
    {
        $instance = new static;

        $instance->id = $data['id'] ?? null;
        $instance->custom_id = $data['custom_id'] ?? null;
        $instance->team_id = $data['team_id'] ?? null;
        $instance->name = $data['name'] ?? null;
        $instance->url = $data['url'] ?? null;
        $instance->status = $data['status']['status'] ?? '-';

        return $instance;
    }

    /**
     * Returns the custom URL for this task
     *
     * @return string
     */
    public function getCustomUrl()
    {
        return join("/", [
            Provider::URL,
            't',
            $this->team_id,
            $this->custom_id
        ]);
    }
}