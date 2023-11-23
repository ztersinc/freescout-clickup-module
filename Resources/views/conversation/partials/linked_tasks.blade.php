@if(count($tasks))
    <ul class="sidebar-block-list">
        @foreach ($tasks as $task)
            <li>
                <div style="display: flex;">
                    <div style="width: 95%; overflow-wrap: anywhere;">
                        <a href="{{ $task->getCustomUrl() }}" target="_blank">
                            <i class="glyphicon glyphicon-chevron-right"></i>
                            {{ $task->name }}
                            <span class="form-help" style="display: inline-block">
                                - ({{ $task->status }})
                            </span>
                        </a>
                    </div>
                    <span style="padding: 0 5px">
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