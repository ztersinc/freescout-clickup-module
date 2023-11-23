<div class="conv-sidebar-block" id="swh-content">
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
                <div id="clickup-task-list-container" class="panel-body" style="position: relative;">
                    <div class="loader">
                        <div class="spinner-border" role="status" style="margin: 10px; font-size: 25px;">
                            <i class="glyphicon glyphicon-hourglass rotating"></i>
                        </div>
                    </div>
                    <div class="results">
                        <!-- HTML dynamically generated - API.linked_tasks -->
                    </div>
                    <hr style="margin: 10px;" />
                    <div style="display: flex; justify-content: center;">
                        <a
                            href="{{ $routes['link_tasks'] }}"
                            class="btn btn-trans"
                            data-trigger="modal"
                            data-modal-title="{{ __("Link ClickUp Tasks") }}"
                            data-modal-no-footer="true"
                            data-modal-on-show=""
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
    <style>
        .loader {
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            background-color: rgba(0,0,0,0.2);
            color: black;
            width: 260px;
            left: 0px;
            height: 100%;
            z-index: 1;
        }
        .loader.d-none {
            display: none;
        }
        @-webkit-keyframes rotating {
            from {
                -webkit-transform: rotate(0deg);
            }
            to {
                -webkit-transform: rotate(360deg);
            }
        }

        .rotating {
            -webkit-animation: rotating 2s linear infinite;
        }
    </style>
@endsection

@section('javascript')
    @parent
    $(document).ready(function() {
        let $taskListContainer = {};

        // Request Object
        const request = (url, payload, type = 'get') => {
            return $.ajax({
                type,
                url,
                xhrFields: {
                    withCredentials: true
                },
                data: Object.assign({}, payload, {
                    _token: '{!! csrf_token() !!}'
                })
            })
        }

        // Shared API object
        const API = {
            linked_tasks: () => request('{!! $routes['linked_tasks'] !!}'),
            //
            link_task: () => {},
            unlink_task: (task_id) => request('{!! $routes['unlink_task'] !!}', {task_id}, 'delete')
        }

        // Events
        const refreshLinkedTasks = () => {
            $taskListContainer.isLoading(true)

            API.linked_tasks()
                .then(html => $taskListContainer.setHTML(html))
                .catch(err => console.error)
                .always(() => {
                    $taskListContainer.isLoading(false)
                    $taskListContainer.refreshEvents()
                })
        }

        // Containers
        $taskListContainer = {
            ref: $('#clickup-task-list-container'),
            setHTML: function(html) {
                this.ref.find('.results').html(html)
            },
            isLoading: function(loading) {
                if (loading) this.ref.find('.loader').removeClass('d-none')
                else this.ref.find('.loader').addClass('d-none')
            },
            refreshEvents: function() {
                const self = this;
                $('.clickup-unlink-task').on('click', function() {
                    self.isLoading(true)
                    API.unlink_task($(this).data('task-id'))
                        .then(refreshLinkedTasks)
                        .catch(err => console.error)
                })
            }
        };

        // OnLoad actions
        refreshLinkedTasks();
    });
@endsection