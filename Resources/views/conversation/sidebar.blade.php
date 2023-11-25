<div class="conv-sidebar-block" id="clickup-sidebar">
    <div class="panel-group accordion accordion-empty">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href=".collapse-conv-clickup">ClickUp
                        <b class="caret"></b>
                    </a>
                </h4>
            </div>
            <div class="collapse-conv-clickup panel-collapse collapse in">
                <div id="clickup-task-list-container" class="panel-body">
                    <div class="loader">
                        <div class="spinner-border" role="status">
                            <i class="glyphicon glyphicon-hourglass rotating"></i>
                        </div>
                    </div>
                    <div class="results">
                        <!-- HTML dynamically generated - API.linked_tasks -->
                    </div>
                    <hr style="margin: 10px;" />
                    <div class="clickup-task-list-items-container">
                        <a
                            href="{{ route('clickup.tasks.link-modal', $conversation->id) }}"
                            class="btn btn-trans"
                            data-trigger="modal"
                            data-modal-title="{{ __("Link ClickUp Tasks") }}"
                            data-modal-no-footer="true"
                            data-modal-on-show="initializeModalJS"
                            role="button"
                        >
                            <i class="glyphicon glyphicon-link"></i>
                            <span>Link Tasks</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('stylesheets')
    @parent
    <link href="{{ asset(\Module::getPublicPath('clickupintegration').'/css/module.css') }}" rel="stylesheet">
@endsection

@section('javascripts')
    @parent
    <script type="text/javascript" {!! \Helper::cspNonceAttr() !!}>
        window.CLICKUP_ROUTES = {
            TASK_LINKED: '{{ route('clickup.tasks.linked', $conversation->id) }}',
            TASK_UNLINK: '{{ route('clickup.tasks.unlink') }}'
        }
    </script>
    <script src="{{ asset(\Module::getPublicPath('clickupintegration').'/js/module.js') }}"></script>
@endsection