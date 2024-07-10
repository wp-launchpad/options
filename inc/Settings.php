<?php

namespace LaunchpadOptions;

class Settings implements Interfaces\SettingsInterface
{

    /**
     * WordPress Option facade.
     *
     * @var Options
     */
    protected $options;

    /**
     * Settings option key.
     *
     * @var string
     */
    protected $settings_key;

    /**
     * Settings values.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Instantiate settings.
     *
     * @param Options $options WordPress Option facade.
     * @param string $settings_key Settings option key.
     */
    public function __construct(Options $options, string $settings_key = 'settings')
    {
        $this->options = $options;
        $this->settings_key = $settings_key;
        $this->settings = new Set($this->options->get($settings_key, []), $this->settings_key);
    }

    /**
     * @inheritDoc
     */
    public function get(string $name, $default = null)
    {
        return $this->settings->get($name, $default);
    }

    /**
     * @inheritDoc
     */
    public function set(string $name, $value)
    {
        $this->settings->set($name, $value);

        $this->persist();
    }

    /**
     * @inheritDoc
     */
    public function delete(string $name)
    {
        $this->settings->delete($name);

        $this->persist();
    }

    /**
     * @inheritDoc
     */
    public function has(string $name): bool
    {
        return $this->settings->has($name);
    }

    /**
     * Persist the settings into the database.
     * @return void
     */
    protected function persist()
    {
        do_action("pre_persist_{$this->settings_key}", $this->settings);

        $this->options->set($this->settings_key, $this->settings);

        do_action("persist_{$this->settings_key}", $this->settings);
    }

    /**
     * Import multiple values at once.
     *
     * @param array<string,mixed> $values Values to import.
     *
     * @return void
     */
    public function import(array $values)
    {
        $this->settings->set_values($values);

        $this->persist();
    }

    /**
     * Export settings values.
     *
     * @return array<string,mixed>
     */
    public function dumps(): array
    {
        return $this->settings->get_values();
    }
}