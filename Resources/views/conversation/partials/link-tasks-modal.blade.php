<div id="clickup-link-tasks-modal">
    <ul class="nav nav-tabs">
        <li role="presentation" class="active" id="clickup-tab-link">
            <a href="#">Link existing Task</a>
        </li>
        <li role="presentation" id="clickup-tab-new">
            <a href="#">Add new Task</a>
        </li>
    </ul>
    <div class="clickup-modal-content">
        <form id="tab-content-link">
            {{-- Link existing task HTML --}}
            <input type="hidden" name="conversation_id" value="{{ $conversationId }}" />
            <div class="row">
                <div class="col-xs-12">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="ClickUp Task URL or ID" name="task_url_id">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" id="clickup-link-task">Link</button>
                        </span>
                    </div>
                    <span class="form-help">
                        Example of Task URL: <strong>https://app.clickup.com/t/14312548/DIGDEV-12345</strong> <br />
                        Example of ID: <strong>DIGDEV-12345</strong> (Last string in the url after the slash symbol "/")
                    </span>
                    <hr style="margin: 10px;"/>
                    <div class="notification">
                        <div class="alert alert-success d-none" role="alert">
                            <strong>Success!</strong> - Your task has been linked!
                        </div>
                        <div class="alert alert-danger d-none" role="alert">
                            <strong>Error!</strong> - <span class="link-error-message"></span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form id="tab-content-new" class="form-horizontal d-none">
            {{-- Add new task HTML --}}
            <input type="hidden" name="conversation_id" value="{{ $conversationId }}" />
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Task Name">
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="description" name="description" placeholder="Task Description"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-sm-2 control-label">Assignee</label>
                <div class="col-sm-10">
                    <select class="form-control select2 select2-assignee" name="assignees[]" placeholder="Select Assignee" multiple></select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" class="btn btn-primary" id="clickup-new-task">Create</button>
                </div>
            </div>
            <hr style="margin: 10px;"/>
            <div class="notification">
                <div class="alert alert-success d-none" role="alert">
                    <strong>Success!</strong> - Your task has been created and linked! <br />
                    <strong>Task:</strong> <a href="#" class="new-task-url" target="_blank"></a>
                </div>
                <div class="alert alert-danger d-none" role="alert">
                    <strong>Error!</strong> - <span class="new-error-message"></span>
                </div>
            </div>
        </form>
    </div>
</div>