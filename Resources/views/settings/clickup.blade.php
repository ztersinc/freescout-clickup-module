<form class="form-horizontal margin-top margin-bottom" method="POST" action="">
    {{ csrf_field() }}

    <h3 class="subheader">{{ __('General') }}</h3>

    <!-- Integration Status  -->
    <div class="form-group">
        <label for="integration_status" class="col-sm-2 control-label">{{ __('Integration Status') }}</label>

        <div class="col-sm-6">
            @if ($settings['integration_status'])
                <div class="text-success">
                    <i class="glyphicon glyphicon-ok"></i> <strong>Active</strong>
                </div>
            @else
                <div class="text-danger">
                    <i class="glyphicon glyphicon-remove"></i> <strong>Inactive</strong>
                </div>
            @endif
        </div>
    </div>

    {{-- Enabled --}}
    <div class="form-group{{ $errors->has('settings[clickupintegration.enabled]') ? ' has-error' : '' }}">
        <label for="clickupintegration.enabled" class="col-sm-2 control-label">{{ __('Integration Enabled?') }}</label>

        <div class="col-sm-6">
            <div class="controls">
                <div class="onoffswitch-wrap">
                    <div class="onoffswitch">
                        <input type="checkbox" name="settings[clickupintegration.enabled]" value="1" id="clickupintegration.enabled" class="onoffswitch-checkbox" @if (old('settings[clickupintegration.enabled]', $settings['clickupintegration.enabled']))checked="checked"@endif >
                        <label class="onoffswitch-label" for="clickupintegration.enabled"></label>
                    </div>
                </div>
            </div>
            <div class="form-help">
                Hide/Show the Integration, useful to configure initial settings before enabling
            </div>
            @include('partials/field_error', ['field'=>'settings.clickupintegration.enabled'])
        </div>
    </div>

    <!-- API Token -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.api_token]') ? ' has-error' : '' }}">
        <label for="api_token" class="col-sm-2 control-label">{{ __('ClickUp API Token') }}</label>

        <div class="col-sm-6">
            <input id="api_token" type="text" class="form-control input-sized" name="settings[clickupintegration.api_token]" value="{{ old('settings[clickupintegration.api_token]', $settings['clickupintegration.api_token']) }}" maxlength="60" required autofocus>
            <div class="form-help">
                API Token can be generated in ClickUp under "Settings -> Apps"
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.api_token]'])
        </div>
    </div>

    <!-- Environment -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.environment]') ? ' has-error' : '' }}">
        <label for="locale" class="col-sm-2 control-label">{{ __('Environment') }}</label>

        <div class="col-sm-6">
            <select id="locale" class="form-control input-sized" name="settings[clickupintegration.environment]" required>
                @foreach ($settings['environments'] as $environment => $label)
                    <option
                        value="{{ $environment }}"
                        {{ $settings['clickupintegration.environment'] === $environment ? 'selected' : '' }}
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <div class="form-help">
                The environment will be used to prefix the "FreeScout ID" custom field in a ClickUp task. <br/>
                Example: (fs-dev-1, fs-uat-1, fs-qa-1, fs-prod-1)
            </div>
            @include('partials/field_error', ['field'=>'settings[clickupintegration.environment]'])
        </div>
    </div>

    <h3 class="subheader">{{ __('Linking Configuration') }}</h3>

    <!-- Team Id -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.team_id]') ? ' has-error' : '' }}">
        <label for="team_id" class="col-sm-2 control-label">{{ __('Team Id') }}</label>

        <div class="col-sm-6">
            <input id="team_id" type="text" class="form-control input-sized" name="settings[clickupintegration.team_id]" value="{{ old('settings[clickupintegration.team_id]', $settings['clickupintegration.team_id']) }}" maxlength="60" required>
            <div class="form-help">
                Team Id in ClickUp
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.team_id]'])
        </div>
    </div>

    <!-- Space Id -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.space_id]') ? ' has-error' : '' }}">
        <label for="space_id" class="col-sm-2 control-label">{{ __('Space Id') }}</label>

        <div class="col-sm-6">
            <input id="space_id" type="text" class="form-control input-sized" name="settings[clickupintegration.space_id]" value="{{ old('settings[clickupintegration.space_id]', $settings['clickupintegration.space_id']) }}" maxlength="60" required>
            <div class="form-help">
                Space Id in ClickUp, used to retrieve Tags
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.space_id]'])
        </div>
    </div>

    <!-- List Id -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.list_id]') ? ' has-error' : '' }}">
        <label for="list_id" class="col-sm-2 control-label">{{ __('List Id') }}</label>

        <div class="col-sm-6">
            <input id="list_id" type="text" class="form-control input-sized" name="settings[clickupintegration.list_id]" value="{{ old('settings[clickupintegration.list_id]', $settings['clickupintegration.list_id']) }}" maxlength="60" required>
            <div class="form-help">
                List Id in ClickUp where new tasks created via FreeScout will be added
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.list_id]'])
        </div>
    </div>

    <h3 class="subheader">{{ __('Custom Fields Configuration') }}</h3>

    <!-- Submitter Name - Field id -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.submitter_name]') ? ' has-error' : '' }}">
        <label for="submitter_name" class="col-sm-2 control-label">{{ __('Submitter - Field Id') }}</label>

        <div class="col-sm-6">
            <input id="submitter_name" type="text" class="form-control input-sized" name="settings[clickupintegration.submitter_name]" value="{{ old('settings[clickupintegration.submitter_name]', $settings['clickupintegration.submitter_name']) }}" maxlength="60" required>
            <div class="form-help">
                Internal id for field "Submitter"
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.submitter_name]'])
        </div>
    </div>

    <!-- Submitter Email - Field id -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.submitter_email]') ? ' has-error' : '' }}">
        <label for="submitter_email" class="col-sm-2 control-label">{{ __('Submitter Email - Field Id') }}</label>

        <div class="col-sm-6">
            <input id="submitter_email" type="text" class="form-control input-sized" name="settings[clickupintegration.submitter_email]" value="{{ old('settings[clickupintegration.submitter_email]', $settings['clickupintegration.submitter_email']) }}" maxlength="60" required>
            <div class="form-help">
                Internal id for field "Submitter Email"
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.submitter_email]'])
        </div>
    </div>

    <!-- FreeScout ID - Field id -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.freescout_id]') ? ' has-error' : '' }}">
        <label for="freescout_id" class="col-sm-2 control-label">{{ __('FreeScout Id - Field Id') }}</label>

        <div class="col-sm-6">
            <input id="freescout_id" type="text" class="form-control input-sized" name="settings[clickupintegration.freescout_id]" value="{{ old('settings[clickupintegration.freescout_id]', $settings['clickupintegration.freescout_id']) }}" maxlength="60" required>
            <div class="form-help">
                Internal id for field "FreeScout ID"
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.freescout_id]'])
        </div>
    </div>

    <!-- FreeScout URL - Field id -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.freescout_url]') ? ' has-error' : '' }}">
        <label for="freescout_url" class="col-sm-2 control-label">{{ __('FreeScout URL - Field Id') }}</label>

        <div class="col-sm-6">
            <input id="freescout_url" type="text" class="form-control input-sized" name="settings[clickupintegration.freescout_url]" value="{{ old('settings[clickupintegration.freescout_url]', $settings['clickupintegration.freescout_url']) }}" maxlength="60" required>
            <div class="form-help">
                Internal id for field "FreeScout URL"
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.freescout_url]'])
        </div>
    </div>

    <div class="form-group margin-top">
        <div class="col-sm-6 col-sm-offset-2">
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>
        </div>
    </div>
</form>
