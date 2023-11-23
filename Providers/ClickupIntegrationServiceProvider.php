<?php

namespace Modules\ClickupIntegration\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\ClickupIntegration\Providers\Traits\IntegrationFields;
use Eventy;
use Option;
use View;

class ClickupIntegrationServiceProvider extends ServiceProvider
{
    use IntegrationFields;

    public const URL = 'https://app.clickup.com';
    public const MODULE_NAME = 'clickupintegration';
    public const SECTION_NAME = 'clickup';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->hooks();
    }

    /**
     * Module hooks.
     */
    public function hooks()
    {
        /**
         * Filter to allow a new sidebar section (ClickUp) to be added inside Manage -> Settings page.
         */
        Eventy::addFilter('settings.sections', function($sections) {
            $sections[self::SECTION_NAME] = ['title' => __('ClickUp'), 'icon' => 'chevron-up', 'order' => 400];
            return $sections;
        }, 10);

        /**
         * Filter that returns settings used within the ClickUp page,
         * All options or configurations to interact with our settings page
         */
        Eventy::addFilter('settings.section_settings', function($settings, $section) {
            if ($section === self::SECTION_NAME) {
                $settings = Option::getOptions([
                    self::MODULE_FIELDS[self::FIELD_API_TOKEN],
                    self::MODULE_FIELDS[self::FIELD_ENVIRONMENT],
                    self::MODULE_FIELDS[self::FIELD_LIST_ID],
                    self::MODULE_FIELDS[self::FIELD_LINK_ID],
                    self::MODULE_FIELDS[self::FIELD_LINK_URL],
                ]);
            }
            return $settings;
        }, 10, 2);

        /**
         * Filter that returns the view path for the page that is being visualized
         */
        Eventy::addFilter('settings.view', function($viewPath, $section) {
            if ($section === self::SECTION_NAME) {
                $viewPath = self::MODULE_NAME . '::' . $viewPath;
            }
            return $viewPath;
        }, 10, 2);

        /**
         * Action that is executed once a conversation is open (Not draft) to show the Linking integration
         */
        Eventy::addAction('conversation.after_prev_convs', function($customer, $conversation, $mailbox) {
            // Skip if no customer (e.g. a draft email)
            if (empty($customer)) {
                return;
            }

            echo View::make(self::MODULE_NAME . '::conversation/sidebar', [
                'routes' => [
                    'linked_tasks' => route('clickup.linked_tasks', $conversation->id),
                    'link_tasks' => route('clickup.link_tasks', $conversation->id),
                    'unlink_task' => route('clickup.unlink_task'),
                ],
            ])->render();
        }, -1, 3);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTranslations();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('clickupintegration.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'clickupintegration'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/clickupintegration');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/clickupintegration';
        }, \Config::get('view.paths')), [$sourcePath]), 'clickupintegration');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $this->loadJsonTranslationsFrom(__DIR__ .'/../Resources/lang');
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
