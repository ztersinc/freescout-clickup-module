<?php

namespace Modules\ClickupIntegration\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ClickupIntegration\Entities\Conversation;
use Modules\ClickupIntegration\Entities\Task;
use Modules\ClickupIntegration\Providers\ClickupIntegrationServiceProvider as Provider;
use Modules\ClickupIntegration\Services\ClickupService;
use Validator;

class ClickupIntegrationController extends Controller
{
    /**
     * GET - Returns a list of linked tasks for the $conversationId
     *
     * @param string $conversationId
     * @return Response
     */
    public function linkedTasks(ClickupService $service, $conversationId)
    {
        return view(
            Provider::MODULE_NAME . '::conversation.partials.linked-tasks-list',
            $service->getLinkedTasks($conversationId)
        );
    }

    /**
     * POST - Links an existing task to a conversation
     *
     * @return Response
     */
    public function linkTask(Request $request, ClickupService $service)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'task_url_id' => 'required',
            'conversation_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'A Task URL or ID is required'
            ], 400);
        }

        $conversationId = $request->conversation_id;
        $response = $service->linkTask($conversationId, $request->task_url_id);

        if ($response['task']) {
            Conversation::addNote($conversationId, $response['task']);
        }

        return $response;
    }

    /**
     * DELETE - Removes a (linked id and url) from the specified task_id
     *
     * @return Response
     */
    public function unlinkTask(Request $request, ClickupService $service)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'task_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Task ID is required'
            ], 400);
        } else {
            $service->unlinkTask($request->task_id);
        }
    }

    /**
     * GET - Returns the modal to link a new Task to the conversation
     *
     * @param string $conversationId
     * @return Response
     */
    public function linkTasks(ClickupService $service, $conversationId)
    {
        return view(Provider::MODULE_NAME . '::conversation.partials.link-tasks-modal', compact('conversationId'));
    }

    /**
     * GET - Return a list of assignee (users) to be added for a new Task
     *
     * @param ClickupService $service
     * @return void
     */
    public function assignee(ClickupService $service)
    {
        return $service->assignee();
    }

    /**
     * POST - Creates and link a new Task to the conversation
     *
     * @param Request $request
     * @param ClickupService $service
     * @return void
     */
    public function create(Request $request, ClickupService $service)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'assignees' => 'required|array|min:1',
            'conversation_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 400);
        }

        $conversationId = $request->conversation_id;
        $task = Task::hydrate($request->all());
        $response = $service->createTask($conversationId, $task);

        if ($response['task']) {
            Conversation::addNote($conversationId, $response['task']);
        }

        return $response;
    }
}
