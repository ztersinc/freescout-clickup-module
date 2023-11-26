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
            <div class="row">
                <div class="col-xs-12">
                    <div class="input-group">
                        <input type="hidden" name="conversation_id" value="{{ $conversationId }}" />
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
                            <strong>Error!</strong> - <span class="link_error_message"></span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div id="tab-content-new" class="d-none">
            {{-- Add new task HTML --}}
            NEW
        </div>
    </div>
</div>