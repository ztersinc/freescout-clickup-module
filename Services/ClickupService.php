<?php

namespace Modules\ClickupIntegration\Services;
require_once __DIR__.'/../vendor/autoload.php';

use Modules\ClickupIntegration\Providers\ClickupIntegrationServiceProvider as Provider;
use Modules\ClickupIntegration\Entities\Task;
use ClickUpClient\Client;
use Exception;

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

    public function isAuthorized()
    {
        $isAuthorized = true;

        try {
            $this->client->get("user");
        } catch (Exception) {
            $isAuthorized = false;
        }

        return $isAuthorized;
    }

    /**
     * Returns a partial error to be displayed in UI
     *
     * @param string $error
     * @return string
     */
    private function getPartialError($error)
    {
        return substr($error, strpos($error, 'response'));
    }

    /**
     * Returns an array of linked tasks for the environment-conversationId
     *
     * @param int $conversationId
     * @return array ['tasks' => [Task::class], 'error' => string]
     */
    public function getLinkedTasks($conversationId)
    {
        $response = [
            'tasks' => [],
            'error' => false
        ];

        $environment = Provider::getEnvironment();
        $listId = Provider::getListId();
        $linkId = Provider::getLinkId();

        try {
            $data = $this->client->get("list/{$listId}/task", [
                'custom_fields' => json_encode([
                    [
                        'field_id'  => $linkId,
                        'operator'  => '=',
                        'value'     => "{$environment}-{$conversationId}"
                    ]
                ])
            ]);

            $tasks = $data['tasks'] ?? [];
            $response['tasks'] = array_map([Task::class, 'hydrate'], $tasks);
        } catch (Exception $e) {
            $response['error'] = $this->getPartialError($e->getMessage());
        }

        return $response;
    }

    /**
     * Updates the custom field ($linkId and $linkUrl) from the Task
     *
     * @param int $conversationId
     * @param string $taskUrlId
     * @return ['task' => Task::class, 'error' => string]
     */
    public function linkTask($conversationId, $taskUrlId)
    {
        $response = [
            'task' => false,
            'error' => false
        ];

        try {
            $customTaskId = basename($taskUrlId);
            $task = $this->client->get("task/{$customTaskId}", [
                'custom_task_ids' => "true",
                'team_id' => Provider::getTeamId(),
            ]);

            if ($task) {
                $environment = Provider::getEnvironment();
                $taskId = $task['id'];
                $this->client->task($taskId)->setCustomField(Provider::getLinkId(), "{$environment}-{$conversationId}");
                $this->client->task($taskId)->setCustomField(Provider::getLinkURL(), route('conversations.view', $conversationId));

                $response['task'] = Task::hydrate($task);
            } else {
                $response['error'] = 'Task not found';
            }
        } catch (Exception $e) {
            $response['error'] = $this->getPartialError($e->getMessage());
        }

        return $response;
    }

    /**
     * Updates the custom field ($linkId and $linkUrl) from the Task
     *
     * @param string $taskId
     * @return void
     */
    public function unlinkTask($taskId)
    {
        $task = $this->client->task($taskId);
        $task->deleteCustomField(Provider::getLinkId());
        $task->deleteCustomField(Provider::getLinkURL());
    }
}
