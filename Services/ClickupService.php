<?php

namespace Modules\ClickupIntegration\Services;
require_once __DIR__.'/../vendor/autoload.php';

use Modules\ClickupIntegration\Providers\ClickupIntegrationServiceProvider as Provider;
use ClickUpClient\Client;
use Modules\ClickupIntegration\Entities\Task;

class ClickupService
{
    /**
     * ClickUp Client
     * @var Client
     */
    private Client $client;

    /**
     * Constructs a new service to connect to ClickUp API
     */
    public function __construct()
    {
        $this->client = new Client(Provider::getApiToken());

        // $this->client->taskList(901002892846)->addTask([
        //     'name' => 'Test task created via API',
        //     'description' => 'Description of task'
        // ]);
    }

    /**
     * Returns a list of linked tasks for the environment-conversationId combination
     *
     * @param int $conversationId
     *
     * @return
     */
    public function getLinkedTasks($conversationId)
    {
        $environment = Provider::getEnvironment();
        $listId = Provider::getListId();
        $linkId = Provider::getLinkId();

        $response = $this->client->get("list/{$listId}/task", [
            'custom_fields' => json_encode([
                [
                    'field_id'  => $linkId,
                    'operator'  => '=',
                    'value'     => "{$environment}-{$conversationId}"
                ]
            ])
        ]);

        $tasks = $response['tasks'] ?? [];
        return array_map([Task::class, 'hydrate'], $tasks);
    }

    /**
     * Updates the custom field ($linkId and $linkUrl) from the Task
     *
     * @param string $taskId
     * @return void
     */
    public function unlinkTask($taskId)
    {
        $linkId = Provider::getLinkId();
        $linkUrl = Provider::getLinkURL();

        $task = $this->client->task($taskId);
        $task->deleteCustomField($linkId);
        $task->deleteCustomField($linkUrl);
    }
}
