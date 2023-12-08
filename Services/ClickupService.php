<?php

namespace Modules\ClickupIntegration\Services;

use Modules\ClickupIntegration\Providers\ClickupIntegrationServiceProvider as Provider;
use Modules\ClickupIntegration\Entities\Member;
use Modules\ClickupIntegration\Entities\Tag;
use Modules\ClickupIntegration\Entities\Task;
use ClickUpClient\Client;
use League\HTMLToMarkdown\HtmlConverter;
use Exception;

class ClickupService
{
    /**
     * ClickUp Client
     * @var Client
     */
    private Client $client;

    /**
     * HTML to Markdown Converter
     * @var HTMLConverter
     */
    private HtmlConverter $htmlConverter;

    /**
     * Constructs a new service to connect to ClickUp API
     */
    public function __construct()
    {
        $this->client = new Client(Provider::getApiToken());
        $this->htmlConverter = new HtmlConverter([
            'strip_tags' => true
        ]);
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

    public static function buildFreescoutId($conversationId)
    {
        $environment = Provider::getEnvironment();
        return "fs-{$environment}-{$conversationId}";
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

        try {
            $teamId = Provider::getTeamId();

            $data = $this->client->get("team/{$teamId}/task", [
                'custom_fields' => json_encode([
                    [
                        'field_id'  => Provider::getFreescoutIdFID(),
                        'operator'  => '=',
                        'value'     => self::buildFreescoutId($conversationId)
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

        $teamId = Provider::getTeamId();

        try {
            $customTaskId = basename($taskUrlId);
            $task = $this->client->get("task/{$customTaskId}", [
                'custom_task_ids' => "true",
                'team_id' => $teamId,
            ]);

            if ($task) {
                $this->_linkTask($conversationId, $task['id']);
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
     * Perform the internal task linking for custom fields
     *
     * @param string $conversationId
     * @param string $taskId
     * @param array $extra
     *
     * @return void
     */
    private function _linkTask($conversationId, $taskId, array $extra = [])
    {
        $this->client->task($taskId)->setCustomField(Provider::getFreescoutIdFID(), self::buildFreescoutId($conversationId));
        $this->client->task($taskId)->setCustomField(Provider::getFreescoutURLFID(), route('conversations.view', $conversationId));

        foreach($extra as $fieldId => $value) {
            $this->client->task($taskId)->setCustomField($fieldId, $value);
        }
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
        $task->deleteCustomField(Provider::getFreescoutIdFID());
        $task->deleteCustomField(Provider::getFreescoutURLFID());
    }

    /**
     * Return a list of assignees (Members and Groups)
     *
     * @return array
     */
    public function assignees()
    {
        # Members
        $members = $this->client->taskList(Provider::getListId())->members();
        $members = array_map([Member::class, 'hydrate'], $members['members'] ?? []);

        # Groups (Currently unavailable to save on API v2 create task)
        $groups = [];
        #$groups = $this->client->get('group', ['team_id' => Provider::getTeamId()]);
        #$groups = array_map([Group::class, 'hydrate'], $groups['groups'] ?? []);

        return compact('members', 'groups');
    }

    /**
     * Return a list of assignees (Members and Groups)
     * > Space Id is required to retrieve Tags
     *
     * @return array
     */
    public function tags()
    {
        $spaceId = Provider::getSpaceId();
        $tags = $this->client->get("space/{$spaceId}/tag");

        return array_map([Tag::class, 'hydrate'], $tags['tags'] ?? []);
    }

    /**
     * Creates a new Task and links it to the conversation
     *
     * @return array
     */
    public function createTask($conversationId, Task $task)
    {
        $response = [
            'task' => false,
            'error' => false
        ];

        try {
            $result = $this->client->taskList(Provider::getListId())->addTask([
                'name' => $task->name,
                'markdown_description' => $this->htmlConverter->convert($task->description),
                'assignees' => $task->assignees,
                'tags' => $task->tags,
            ]);

            $this->_linkTask($conversationId, $result['id'], [
                Provider::getSubmitterNameFID() => $task->submitterName,
                Provider::getSubmitterEmailFID() => $task->submitterEmail,
            ]);
            $response['task'] = Task::hydrate($result);
        } catch (Exception $e) {
            $response['error'] = $this->getPartialError($e->getMessage());
        }

        return $response;
    }
}
