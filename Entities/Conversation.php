<?php

namespace Modules\ClickupIntegration\Entities;

use App\Thread;
use App\Conversation as AppConversation;
use Modules\ClickupIntegration\Providers\ClickupIntegrationServiceProvider as Provider;

class Conversation
{
    protected static $bodyTemplates = [
        Provider::ACTION_TYPE_TASK_LINKED => 'ClickUp Task linked: <a href="%URL%" target="_blank">%URL%</a>',
    ];

    /**
     * Hydrates an entity with the required data for the integration
     *
     * @param array $data
     * @return Task
     */
    public static function addNote($conversationId, Task $task, $actionType = Provider::ACTION_TYPE_TASK_LINKED)
    {
        $conversation = AppConversation::find($conversationId);
        $thread = new Thread;
        $thread->conversation_id = $conversation->id;
        $thread->user_id = $conversation->user_id;
        $thread->body = self::parseBody($actionType, $task);
        $thread->type = Thread::TYPE_LINEITEM;
        $thread->state = Thread::STATE_PUBLISHED;
        $thread->status = Thread::STATUS_NOCHANGE;
        $thread->action_type = $actionType;
        $thread->source_via = Thread::PERSON_USER;
        $thread->source_type = Thread::SOURCE_TYPE_WEB;
        $thread->customer_id = $conversation->customer_id;
        $thread->created_by_user_id = $conversation->user_id;
        $thread->save();
    }

    protected static function parseBody($actionType, Task $task)
    {
        $body = '(Conversation -> Body Template) not defined';

        switch ($actionType) {
            case Provider::ACTION_TYPE_TASK_LINKED:
                $body = str_replace('%URL%', $task->getCustomUrl(), self::$bodyTemplates[$actionType]);
                break;
            default:
        }

        return $body;
    }
}