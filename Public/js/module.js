$(document).ready(function() {
    const imgPlaceholder = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAVZJREFUSEvtls8rRFEUxz93MiUrmRqpKb9SkjTNwoLI+JmFLG3E1tqCncyS8gcoOyllI1OkptGIkg01ZTU2ikxPWSgyTfM0c7e4P149C+9s7uKeez7nfM89tyvcPVz+wEQA9kv1QGq/lEZf6sF9aJ1TJ5bugreC0k8f3L4ATYnvA4YboWMRSq9w1ClXhemDfwwkYPgQYjOQm4XHtIpZ2/cO7lmB+AbcbcLtqhbUOzg6BGNZeLmCTBLcsg/g+ihM30AoDMdx+HjShtpXLEKQPIXmUTibgueMEdQe3JeC3jXIr0M+ZQy1A7dMwsgJFLOyWrfiA7ghJvtaKcm+fjpWULOKRR1M5CDSL2+wc2ENNQMntqB7Wc5qdWY9mt4DUp3X8XMov8P9Dvz2W3Iu4eFAmZYeuG0eBnaVwWoOhW24XlL66oGVYcwdArC5ZpYnAqkthTM/9v+k/gIrRZQ5bKD+HAAAAABJRU5ErkJggg=="

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
        create_task: payload => request(window.CLICKUP_ROUTES.TASK_CREATE, payload, 'post'),
        link_task: payload => request(window.CLICKUP_ROUTES.TASK_LINK, payload, 'post'),
        linked_tasks: () => request(window.CLICKUP_ROUTES.TASK_LINKED),
        unlink_task: task_id => request(window.CLICKUP_ROUTES.TASK_UNLINK, {task_id}, 'delete'),
        assignees: () => request(window.CLICKUP_ROUTES.TASK_ASSIGNEES),
        tags: () => request(window.CLICKUP_ROUTES.TASK_TAGS),
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
        const $newButton = $formContentNew.find('#clickup-new-task')
        const $newNotification = $formContentNew.find('.notification')
        const $assigneesHandler = $formContentNew.find('.select2.select2-assignees')
        const $tagsHandler = $formContentNew.find('.select2.select2-tags')

        /**
         * Select2 Assignees
         */
        {
            const renderAssignees = data => {
                const assigneeRenderer = state => {
                    if (!state.id) return state.text
                    return $(`<span>
                        <img class="assignee-img" src="${state.imgSrc}" /> ${state.text}
                    </span>`);
                }

                $assigneesHandler.select2({
                    placeholder: 'Select People',
                    data,
                    templateResult: assigneeRenderer,
                    templateSelection: assigneeRenderer
                });
            }

            if (window.CLICKUP_LOADED_ASSIGNES) {
                renderAssignees(window.CLICKUP_LOADED_ASSIGNES)
            } else {
                // Loading and caching assignees
                API.assignees().then(results => {
                    const members = results.members.map(obj => {
                        obj.text = obj.username
                        obj.imgSrc = obj.profilePicture || imgPlaceholder
                        return obj
                    })

                    const groups = results.groups.map(obj => {
                        /**
                         * Groups are not currently supported
                         * https://clickup.canny.io/public-api/p/api-call-for-assigning-teams
                         */
                        obj.text = false
                        return obj
                        // Remove previous lines once supported
                        obj.text = obj.name
                        obj.imgSrc = obj.avatarUrl || imgPlaceholder
                        return obj
                    })

                    const assignes = [
                        { text: 'PEOPLE', children: members.filter(obj => obj.text) },
                        { text: 'TEAMS (not currently supported via API)', children: groups.filter(obj => obj.text) },
                    ]

                    renderAssignees(assignes)
                    window.CLICKUP_LOADED_ASSIGNES = assignes
                })
            }
        }

        /**
         * Select2 Tags
         */
        {
            const renderTags = data => {
                const tagRenderer = state => {
                    if (!state.id) return state.text
                    return $(`<span
                        class="label"
                        style="color: white; background: ${state.bgColor}; border-radius: 15px; font-weight: 600;"
                    >${state.name}</span>`);
                }

                $tagsHandler.select2({
                    placeholder: 'Select Tags',
                    data,
                    templateResult: tagRenderer,
                    templateSelection: tagRenderer
                });
            }

            if (window.CLICKUP_LOADED_TAGS) {
                renderTags(window.CLICKUP_LOADED_TAGS)
            } else {
                // Loading and caching tags
                API.tags().then(results => {
                    const tags = results.map(obj => {
                        obj.id = obj.name
                        obj.text = obj.name
                        return obj
                    })

                    renderTags(tags)
                    window.CLICKUP_LOADED_TAGS = tags
                })
            }
        }

        /**
         * TAB - Add New Task
         */
        {
            $tabNew.on('click', () => {
                $tabLink.removeClass('active')
                $tabNew.addClass('active')
                $formContentNew.removeClass('d-none')
                $formContentLink.addClass('d-none')
            })

            $newButton.on('click', function() {
                const button = $(this)
                button.prop('disabled', true)
                button.html('Creating...')

                $newNotification.find('.alert-success').addClass('d-none')
                $newNotification.find('.alert-danger').addClass('d-none')

                API.create_task($formContentNew.serialize())
                    .then(response => {
                        if (response.task) {
                            const task = response.task
                            $assigneesHandler.val(null).trigger('change')
                            $tagsHandler.val(null).trigger('change')
                            $newNotification.find('.alert-success').removeClass('d-none')
                            $newNotification.find('.new-task-url').attr("href", task.url)
                            $newNotification.find('.new-task-url').html(task.url)
                            refreshLinkedTasks()
                        } else {
                            $newNotification.find('.alert-danger').removeClass('d-none')
                            $newNotification.find('.new-error-message').html(response.error || '')
                        }
                    })
                    .catch(error => {
                        const message = error.responseJSON.error
                        $newNotification.find('.alert-danger').removeClass('d-none')
                        $newNotification.find('.new-error-message').html(message)
                    })
                    .always(() => {
                        button.prop('disabled', false)
                        button.html('Create')
                    })
            })
        }

        /**
         * TAB - Link Existing Task
         */
        {
            $tabLink.on('click', () => {
                $tabNew.removeClass('active')
                $tabLink.addClass('active')
                $formContentLink.removeClass('d-none')
                $formContentNew.addClass('d-none')
            })

            $linkButton.on('click', function() {
                const button = $(this)
                button.prop('disabled', true)
                button.html('Linking...')

                $linkNotification.find('.alert-success').addClass('d-none')
                $linkNotification.find('.alert-danger').addClass('d-none')

                API.link_task($formContentLink.serialize())
                    .then(response => {
                        if (response.task) {
                            $formContentLink[0].reset()
                            $linkNotification.find('.alert-success').removeClass('d-none')
                            refreshLinkedTasks()
                        } else {
                            $linkNotification.find('.alert-danger').removeClass('d-none')
                            $linkNotification.find('.link-error-message').html(response.error || '')
                        }
                    })
                    .catch(error => {
                        const message = error.responseJSON.error
                        $linkNotification.find('.alert-danger').removeClass('d-none')
                        $linkNotification.find('.link-error-message').html(message)
                    })
                    .always(() => {
                        button.prop('disabled', false)
                        button.html('Link')
                    })
            })
        }
    }
});