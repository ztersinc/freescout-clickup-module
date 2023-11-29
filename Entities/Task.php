<?php

namespace Modules\ClickupIntegration\Entities;

use Modules\ClickupIntegration\Providers\ClickupIntegrationServiceProvider as Provider;

class Task
{
    public string $id;
    public string $custom_id;
    public string $team_id;
    public string $name;
    public string $description;
    public string $url;
    public string $status;
    public array $assignees;

    /**
     * Hydrates an entity with the required data for the integration
     *
     * @param array $data
     * @return Task
     */
    public static function hydrate(array $data)
    {
        $instance = new static;

        $instance->id = $data['id'] ?? '';
        $instance->custom_id = $data['custom_id'] ?? '';
        $instance->team_id = $data['team_id'] ?? '';
        $instance->name = $data['name'] ?? '';
        $instance->description = $data['description'] ?? '';
        $instance->url = $data['url'] ?? '';
        $instance->status = $data['status']['status'] ?? '-';
        $instance->assignees = $data['assignees'] ?? [];

        return $instance;
    }

    /**
     * Returns the custom URL for this task if its available
     *
     * @return string
     */
    public function getCustomUrl()
    {
        return $this->custom_id ? join("/", [
            Provider::URL,
            't',
            $this->team_id,
            $this->custom_id
        ]) : $this->url;
    }
}