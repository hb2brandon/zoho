<?php

namespace Asciisd\Zoho;

class Zoho
{
    /**
     * The Zoho library version.
     *
     * @var string
     */
    const VERSION = '1.2.10';

    /**
     * Indicates if Zoho migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * Indicates if Zoho routes will be registered.
     *
     * @var bool
     */
    public static $registersRoutes = true;

    /**
     * Get the default Zoho API options.
     *
     * @param  array  $options
     *
     * @return array
     */
    public static function zohoOptions(array $options = [], $configKey = 'zoho')
    {
        return array_merge([
            'client_id'              => config($configKey . '.client_id'),
            'client_secret'          => config($configKey . '.client_secret'),
            'redirect_uri'           => config($configKey . '.redirect_uri'),
            'currentUserEmail'       => config($configKey . '.current_user_email'),
            'applicationLogFilePath' => config($configKey . '.application_log_file_path'),
            'sandbox'                => config($configKey . '.sandbox'),
            'apiBaseUrl'             => config($configKey . '.api_base_url'),
            'apiVersion'             => config($configKey . '.api_version'),
            'access_type'            => config($configKey . '.access_type'),
            'accounts_url'           => config($configKey . '.accounts_url'),
            'persistence_handler_class' => config($configKey . '.persistence_handler_class'),
            'persistence_handler_class_name' => config($configKey . '.persistence_handler_class_name'),
            'token_persistence_path' => config($configKey . '.token_persistence_path'),
            //            'fileUploadUrl' => config($configKey . '.file_upload_url'),
        ], $options);
    }

    /**
     * Configure Zoho to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static;
    }

    /**
     * Configure Zoho to not register its routes.
     *
     * @return static
     */
    public static function ignoreRoutes()
    {
        static::$registersRoutes = false;

        return new static;
    }
}
