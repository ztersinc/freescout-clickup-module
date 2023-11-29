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
                <option value="dev" {{ $settings['clickupintegration.environment'] === 'dev' ? 'selected' : '' }}>Development</option>
                <option value="uat" {{ $settings['clickupintegration.environment'] === 'uat' ? 'selected' : '' }}>UAT</option>
                <option value="prod" {{ $settings['clickupintegration.environment'] === 'prod' ? 'selected' : '' }}>Production</option>
            </select>
            <div class="form-help">
                The selected environment will be used to prefix the "FreeScout ID" custom field in a ClickUp task. example: (dev-1, uat-1, prod-1)
            </div>
            @include('partials/field_error', ['field'=>'settings[clickupintegration.environment]'])
        </div>
    </div>

    <h3 class="subheader">{{ __('Linking Configuration') }}</h3>

    <!-- Team ID -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.team_id]') ? ' has-error' : '' }}">
        <label for="team_id" class="col-sm-2 control-label">{{ __('Team id') }}</label>

        <div class="col-sm-6">
            <input id="team_id" type="text" class="form-control input-sized" name="settings[clickupintegration.team_id]" value="{{ old('settings[clickupintegration.team_id]', $settings['clickupintegration.team_id']) }}" maxlength="60" required>
            <div class="form-help">
                Team ID in ClickUp
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.team_id]'])
        </div>
    </div>

    <!-- List ID -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.list_id]') ? ' has-error' : '' }}">
        <label for="list_id" class="col-sm-2 control-label">{{ __('List id') }}</label>

        <div class="col-sm-6">
            <input id="list_id" type="text" class="form-control input-sized" name="settings[clickupintegration.list_id]" value="{{ old('settings[clickupintegration.list_id]', $settings['clickupintegration.list_id']) }}" maxlength="60" required>
            <div class="form-help">
                List ID in ClickUp where new tasks created via FreeScout will be added
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.list_id]'])
        </div>
    </div>

    <!-- FreeScout ID - Field id -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.link_id]') ? ' has-error' : '' }}">
        <label for="link_id" class="col-sm-2 control-label">{{ __('FreeScout ID - Field id') }}</label>

        <div class="col-sm-6">
            <input id="link_id" type="text" class="form-control input-sized" name="settings[clickupintegration.link_id]" value="{{ old('settings[clickupintegration.link_id]', $settings['clickupintegration.link_id']) }}" maxlength="60" required>
            <div class="form-help">
                Internal id for field "FreeScout ID", this is required to link a FreeScout conversation with a ClickUp task
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.link_id]'])
        </div>
    </div>

    <!-- FreeScout URL - Field id -->
    <div class="form-group{{ $errors->has('settings[clickupintegration.link_url]') ? ' has-error' : '' }}">
        <label for="link_url" class="col-sm-2 control-label">{{ __('FreeScout URL - Field id') }}</label>

        <div class="col-sm-6">
            <input id="link_url" type="text" class="form-control input-sized" name="settings[clickupintegration.link_url]" value="{{ old('settings[clickupintegration.link_url]', $settings['clickupintegration.link_url']) }}" maxlength="60" required>
            <div class="form-help">
                Internal id for field "FreeScout URL", this is required to link a FreeScout conversation with a ClickUp task
            </div>

            @include('partials/field_error', ['field'=>'settings[clickupintegration.link_url]'])
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
