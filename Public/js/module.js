$(document).ready(function() {
    // Request Object
    const request = (url, data, type = 'get') => $.ajax({
        headers: {
            'X-CSRF-TOKEN': window.CSRF_TOKEN
        },
        url,
        data,
        type,
    })

    // API object
    const API = {
        linked_tasks: () => request(window.CLICKUP_ROUTES.TASK_LINKED),
        //
        link_task: payload => request(window.CLICKUP_ROUTES.TASK_LINK, payload, 'post'),
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
                    .always(() => {
                        self.isLoading(false)
                    })
            })
        }
    };

    // OnLoad actions
    refreshLinkedTasks()

    // ----------------------------------------
    // MODAL JS (Executed when modal is opened)
    // ----------------------------------------
    initializeModalJS = () => {
        const $ref = $('#clickup-link-tasks-modal')
        // Tabs
        const $tabLink = $ref.find('#clickup-tab-link')
        const $tabNew = $ref.find('#clickup-tab-new')
        // Tabs Content
        const $formContentLink = $ref.find('#tab-content-link')
        const $formContentNew = $ref.find('#tab-content-new')
        // Handlers
        // -- Link existing
        const $linkButton = $formContentLink.find('#clickup-link-task')
        const $linkNotification = $formContentLink.find('.notification')
        // -- Add new

        $tabLink.on('click', () => {
            $tabNew.removeClass('active')
            $tabLink.addClass('active')
            $formContentLink.removeClass('d-none')
            $formContentNew.addClass('d-none')
        })

        $tabNew.on('click', () => {
            $tabLink.removeClass('active')
            $tabNew.addClass('active')
            $formContentNew.removeClass('d-none')
            $formContentLink.addClass('d-none')
        })

        $linkButton.on('click', function() {
            const button = $(this)
            button.prop('disabled', true)
            button.html('Linking...')

            API.link_task($formContentLink.serialize())
                .then(response => {
                    if (response.task) {
                        $formContentLink[0].reset()
                        $linkNotification.find('.alert-success').removeClass('d-none')
                        refreshLinkedTasks()
                    } else {
                        $linkNotification.find('.alert-danger').removeClass('d-none')
                        $linkNotification.find('.link_error_message').html(response.error)
                    }
                })
                .catch(error => {
                    const message = error.responseJSON.error
                    $linkNotification.find('.alert-danger').removeClass('d-none')
                    $linkNotification.find('.link_error_message').html(message)
                })
                .always(() => {
                    button.prop('disabled', false)
                    button.html('Link')
                    setTimeout(() => {
                        $linkNotification.find('.alert-success').addClass('d-none')
                        $linkNotification.find('.alert-danger').addClass('d-none')
                    }, 5000)
                })
        })
    }
});