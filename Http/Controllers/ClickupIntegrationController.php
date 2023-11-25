<?php

namespace Modules\ClickupIntegration\Http\Controllers;

use App\Thread;
use App\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ClickupIntegration\Providers\ClickupIntegrationServiceProvider as Provider;
use Modules\ClickupIntegration\Services\ClickupService;
use Validator;

class ClickupIntegrationController extends Controller
{
    /**
     * Returns a list of linked tasks for the $conversationId
     *
     * @param string $conversationId
     * @return Response
     */
    public function linkedTasks(ClickupService $service, $conversationId)
    {
        // $conversation = Conversation::find($conversationId);
        // $thread = new Thread;
        // $thread->conversation_id = $conversation->id;
        // $thread->user_id = $conversation->user_id;
        // $thread->body = 'ClickUp Task linked: <a href="https://app.clickup.com/t/14312548/DIGDEV-10144" target="_blank">https://app.clickup.com/t/14312548/DIGDEV-10144</a>';
        // $thread->type = Thread::TYPE_LINEITEM;
        // $thread->state = Thread::STATE_PUBLISHED;
        // $thread->status = Thread::STATUS_NOCHANGE;
        // $thread->action_type = Provider::ACTION_TYPE_TASK_LINKED;
        // $thread->source_via = Thread::PERSON_USER;
        // $thread->source_type = Thread::SOURCE_TYPE_WEB;
        // $thread->customer_id = $conversation->customer_id;
        // $thread->created_by_user_id = $conversation->user_id;
        // $thread->save();

        return view(
            Provider::MODULE_NAME . '::conversation.partials.linked-tasks-list',
            $service->getLinkedTasks($conversationId)
        );
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

    /**
     * Returns the modal to link a new Task to the conversation
     *
     * @param string $conversationId
     * @return Response
     */
    public function linkTasks(ClickupService $service, $conversationId)
    {
        return view(Provider::MODULE_NAME . '::conversation.partials.link-tasks-modal', compact('conversationId'));
    }
}
