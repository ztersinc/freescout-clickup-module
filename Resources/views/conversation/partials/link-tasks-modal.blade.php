<div id="clickup-link-tasks-modal">
    <ul class="nav nav-tabs">
        <li role="presentation" class="active" id="clickup-tab-new">
            <a href="#">Add New Task</a>
        </li>
        <li role="presentation" id="clickup-tab-link">
            <a href="#">Link Existing Task</a>
        </li>
    </ul>
    <div class="clickup-modal-content">
        {{-- Add new task HTML --}}
        <form id="tab-content-new" class="form-horizontal">
            <input type="hidden" name="conversation_id" value="{{ $conversation->id }}" />
            {{-- Submitter Name --}}
            <div class="form-group">
                <label for="submitter_name" class="col-sm-3 control-label">Submitter Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="submitter_name" name="submitter_name" value="{{ $conversation->customer->getFullName(true) }}">
                </div>
            </div>
            <div class="form-group">
                <label for="submitter_email" class="col-sm-3 control-label">Submitter Email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="submitter_email" name="submitter_email" value="{{ $conversation->customer_email }}">
                </div>
            </div>
            {{-- Task Name --}}
            <div class="form-group">
                <label for="name" class="col-sm-3 control-label">Task Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name" value="{{ $conversation->subject }}">
                </div>
            </div>
            {{-- Description --}}
            <div class="form-group">
                <label for="description" class="col-sm-3 control-label">Description</label>
                <div class="col-sm-9">
                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        rows=5
                    >{{ $conversation->getFirstThread()->body ?? '' }}</textarea>
                </div>
            </div>
            {{-- Assignee --}}
            <div class="form-group">
                <label for="description" class="col-sm-3 control-label">Assignees</label>
                <div class="col-sm-9">
                    <select class="form-control select2 select2-assignees" name="assignees[]" placeholder="Select Assignee" multiple></select>
                </div>
            </div>
            {{-- Tags --}}
            <div class="form-group">
                <label for="description" class="col-sm-3 control-label">Tags</label>
                <div class="col-sm-9">
                    <select class="form-control select2 select2-tags" name="tags[]" placeholder="Select Tags" multiple></select>
                </div>
            </div>
            {{-- Actions --}}
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="button" class="btn btn-primary" id="clickup-new-task">Create</button>
                </div>
            </div>
            <hr style="margin: 10px;"/>
            {{-- Notification --}}
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
        {{-- Link existing task HTML --}}
        <form id="tab-content-link" class="d-none">
            <input type="hidden" name="conversation_id" value="{{ $conversation->id }}" />
            <div class="row">
                <div class="col-xs-12">
                    {{-- Link --}}
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
                    {{-- Notification --}}
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
    </div>
</div>