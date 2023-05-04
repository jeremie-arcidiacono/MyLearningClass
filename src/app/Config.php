<?php

declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This class is used to parse the configuration files and make them available to the app.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App;

/**
 * Used to parse the configuration files and make them available to the app.
 */
class Config
{
    private array $config = [];

    /**
     * Array of files that should not be loaded into the config array
     */
    private const EXCLUDED_FILES = ['constants.php'];

    /**
     * @param string $dirPath The path to the config directory
     */
    public function __construct(private readonly string $dirPath)
    {
        $this->loadConfig();
    }

    /**
     * Scan the config directory and load all config files into the config array
     * @return void
     */
    private function loadConfig(): void
    {
        $files = scandir($this->dirPath);
        foreach ($files as $file) {
            if (is_file($this->dirPath . '/' . $file)) {
                if (in_array($file, self::EXCLUDED_FILES)) {
                    continue;
                }

                $this->config[str_replace('.php', '', $file)] = require $this->dirPath . '/' . $file;
            }
        }
    }

    /**
     * Return the value of the given key in the config
     * @param string $key         The key. Dot notation can be used to access nested values (e.g. 'database.host')
     * @param mixed|null $default The default value to return if the key is not found
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        // We use dot notation to access nested config values
        $keys = explode('.', $key);
        $value = $this->config;
        foreach ($keys as $key) {
            if (isset($value[$key])) {
                $value = $value[$key];
            }
            else {
                return $default;
            }
        }
        return $value;
    }
}
