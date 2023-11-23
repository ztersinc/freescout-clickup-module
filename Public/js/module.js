$(document).ready(function() {
    // Request Object
    const request = (url, data, type = 'get') => $.ajax({ url, data, type })

    // API object
    const API = {
        linked_tasks: () => request(window.CLICKUP_ROUTES.TASK_LINKED),
        //
        link_task: () => {},
        unlink_task: task_id => request(window.CLICKUP_ROUTES.TASK_UNLINK, {task_id}, 'delete')
    }

    // Events
    const refreshLinkedTasks = () => {
        $taskListContainer.isLoading(true)

        API.linked_tasks()
            .then(html => $taskListContainer.setHTML(html))
            .catch(console.error)
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
                    .catch(console.error)
            })
        }
    };

    // OnLoad actions
    refreshLinkedTasks();
});