@if(count($tasks))
    <ul class="sidebar-block-list">
        @foreach ($tasks as $task)
            <li class="clickup-task-list-item">
                <div>
                    <div class="clickup-task-list-item-name">
                        <a href="{{ $task->getCustomUrl() }}" target="_blank">
                            <i class="glyphicon glyphicon-chevron-right"></i>
                            {{ $task->name }}
                            <span class="form-help" style="display: inline-block">
                                - ({{ $task->status }})
                            </span>
                        </a>
                    </div>
                    <span class="clickup-task-list-item-unlink">
                        <a href="#" class="clickup-unlink-task" data-task-id="{{ $task->id }}">
                            <strong>X</strong>
                        </a>
                    </span>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <span class="form-help">
        No tasks have been linked
    </span>
@endif