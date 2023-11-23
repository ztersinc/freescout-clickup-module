<?php

namespace Modules\ClickupIntegration\Providers\Traits;

use Modules\ClickupIntegration\Providers\ClickupIntegrationServiceProvider as Provider;
use Option;

trait IntegrationFields
{
    public const FIELD_API_TOKEN = 'api_token';
    public const FIELD_ENVIRONMENT = 'environment';
    public const FIELD_LIST_ID = 'list_id';
    public const FIELD_LINK_ID = 'link_id';
    public const FIELD_LINK_URL = 'link_url';

    /**
     * Define a list of module fields, the base name prefixed by the module name.
     * Used to reference the information at the database within its own namespace
     *
     * @var array
     */
    public const MODULE_FIELDS = [
        self::FIELD_API_TOKEN   => Provider::MODULE_NAME . '.' . self::FIELD_API_TOKEN,
        self::FIELD_ENVIRONMENT => Provider::MODULE_NAME . '.' . self::FIELD_ENVIRONMENT,
        self::FIELD_LIST_ID     => Provider::MODULE_NAME . '.' . self::FIELD_LIST_ID,
        self::FIELD_LINK_ID     => Provider::MODULE_NAME . '.' . self::FIELD_LINK_ID,
        self::FIELD_LINK_URL    => Provider::MODULE_NAME . '.' . self::FIELD_LINK_URL,
    ];

    /**
     * Returns the API Token stored at Settings
     *
     * @return string
     */
    public static function getApiToken()
    {
        return Option::get(self::MODULE_FIELDS[self::FIELD_API_TOKEN]);
    }

    /**
     * Returns the Environment stored at Settings
     *
     * @return string
     */
    public static function getEnvironment()
    {
        return Option::get(self::MODULE_FIELDS[self::FIELD_ENVIRONMENT]);
    }

    /**
     * Returns the List Id stored at Settings
     *
     * @return integer
     */
    public static function getListId()
    {
        return (int) Option::get(self::MODULE_FIELDS[self::FIELD_LIST_ID]);
    }

    /**
     * Returns the Link Id stored at Settings
     *
     * @return integer
     */
    public static function getLinkId()
    {
        return Option::get(self::MODULE_FIELDS[self::FIELD_LINK_ID]);
    }

    /**
     * Returns the Link URL stored at Settings
     *
     * @return integer
     */
    public static function getLinkURL()
    {
        return Option::get(self::MODULE_FIELDS[self::FIELD_LINK_URL]);
    }
}