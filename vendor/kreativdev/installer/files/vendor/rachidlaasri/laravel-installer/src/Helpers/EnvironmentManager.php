<?php

namespace RachidLaasri\LaravelInstaller\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnvironmentManager
{
    /**
     * @var string
     */
    private $envPath;

    /**
     * @var string
     */
    private $envExamplePath;

    /**
     * Set the .env and .env.example paths.
     */
    public function __construct()
    {
        $this->envPath = base_path('.env');
        $this->envExamplePath = base_path('.env.example');
    }

    /**
     * Get the content of the .env file.
     *
     * @return string
     */
    public function getEnvContent()
    {
        if (! file_exists($this->envPath)) {
            if (file_exists($this->envExamplePath)) {
                copy($this->envExamplePath, $this->envPath);
            } else {
                touch($this->envPath);
            }
        }

        return file_get_contents($this->envPath);
    }

    /**
     * Get the the .env file path.
     *
     * @return string
     */
    public function getEnvPath()
    {
        return $this->envPath;
    }

    /**
     * Get the the .env.example file path.
     *
     * @return string
     */
    public function getEnvExamplePath()
    {
        return $this->envExamplePath;
    }

    /**
     * Save the edited content to the .env file.
     *
     * @param Request $input
     * @return string
     */
    public function saveFileClassic(Request $input)
    {
        $message = trans('installer_messages.environment.success');

        try {
            file_put_contents($this->envPath, $input->get('envConfig'));
        } catch (Exception $e) {
            $message = trans('installer_messages.environment.errors');
        }

        return $message;
    }

    /**
     * Save the form content to the .env file.
     *
     * @param Request $request
     * @return string
     */
    public function saveFileWizard(Request $request)
    {
        $results = trans('installer_messages.environment.success');

        $envFileData =
        'APP_NAME=\''.$request->app_name."'\n".
        'APP_ENV='.'local'."\n".
        'APP_KEY='.'base64:'.base64_encode(Str::random(32))."\n".
        'APP_DEBUG='.$request->app_debug."\n".
        'APP_URL='.$request->app_url."\n\n".
        'LOG_CHANNEL='.'stack'."\n\n".
        'DB_CONNECTION='.'mysql'."\n".
        'DB_HOST='.$request->database_hostname."\n".
        'DB_PORT='.'3306'."\n".
        'DB_DATABASE='.$request->database_name."\n".
        'DB_USERNAME='.$request->database_username."\n".
        'DB_PASSWORD="'.$request->database_password. '"' . "\n\n".
        'BROADCAST_DRIVER='.'log'."\n".
        'CACHE_DRIVER='.'file'."\n".
        'QUEUE_CONNECTION='.'sync'."\n".
        'SESSION_DRIVER='.'file'."\n".
        'SESSION_LIFETIME='.'120'."\n\n".
        'REDIS_HOST='.'127.0.0.1'."\n".
        'REDIS_PASSWORD='.'null'."\n".
        'REDIS_PORT='.'6379'."\n\n".
        'MAIL_DRIVER='.'smtp'."\n".
        'MAIL_HOST='.'smtp.mailtrap.io'."\n".
        'MAIL_PORT='.'2525'."\n".
        'MAIL_USERNAME='.'null'."\n".
        'MAIL_PASSWORD='.'null'."\n".
        'MAIL_ENCRYPTION='.'null'."\n\n".
        'PUSHER_APP_ID='.''."\n".
        'PUSHER_APP_KEY='.''."\n".
        'PUSHER_APP_SECRET='.''."\n\n".
        'MOLLIE_KEY='.'test_5HcWVs9qc5pzy36H9Tu9mwAyats33J'."\n\n".
        'TIMEZONE='.'America/Maceio'."\n\n".
        'VAPID_PUBLIC_KEY='.'BFvK_Vl5zh6g-8glfwClhH-1P37uZqEL3rgiWHMxlzTxtnd5y2DY06AaTpayCuGvG2Di7Kw6vqX6tf7F46pwqpw'."\n".
        'VAPID_PRIVATE_KEY='.'QHX2L0MHBmPUPHiq76zVgUiGhyOlZ42vssTh-YpDEbk'."\n\n".
        'ANET_API_LOGIN_ID='.'3Ca5hYQ6h'."\n".
        'ANET_TRANSACTION_KEY='.'8bt8Kr5gPZ3ZE23C'."\n".
        'ANET_PUBLIC_CLIENT_KEY='.'7m38JBnNjStNFq58BA6Wrr852ahtT533cGKavWwu6Fge28RDc5wC7wTL8Vsb35B3';

        try {
            file_put_contents($this->envPath, $envFileData);
        } catch (Exception $e) {
            $results = trans('installer_messages.environment.errors');
        }

        return $results;
    }
}
