<?php

namespace Modules\ClickupIntegration\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ClickupIntegration\Providers\ClickupIntegrationServiceProvider as Provider;
use Modules\ClickupIntegration\Services\ClickupService;
use Validator;

class ClickupIntegrationController extends Controller
{
    /**
     * HTML - Returns a list of linked tasks for the $conversationId
     *
     * @param string $conversationId
     * @return Response
     */
    public function linkedTasks(ClickupService $service, $conversationId)
    {
        return view(Provider::MODULE_NAME . '::conversation.partials.linked_tasks', [
            'tasks' => $service->getLinkedTasks($conversationId)
        ]);
    }

    /**
     * HTML - Returns the modal to link a new Task to the conversation
     *
     * @param string $conversationId
     * @return Response
     */
    public function linkTasks(ClickupService $service, $conversationId)
    {
        return view(Provider::MODULE_NAME . '::conversation.partials.link_tasks', compact('conversationId'));
    }

    /**
     * Removes a (linked id and url) from the specified task_id
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
}
