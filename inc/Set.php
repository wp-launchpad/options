<?php

namespace LaunchpadOptions;

use LaunchpadOptions\Interfaces\SetInterface;

class Set implements SetInterface
{

    protected $values = [];

    protected $slug = '';

    /**
     * @param array $values
     * @param string $slug
     */
    public function __construct(array $values, string $slug)
    {
        $this->values = $values;
        $this->slug = $slug;
    }


    public function delete(string $name)
    {
        unset($this->values[$name]);
    }

    public function get(string $name, $default = null)
    {
        /**
         * Pre-filter any setting before read
         *
         * @param mixed $default The default value.
         */
        $value = apply_filters( "pre_get_{$this->slug}" . $name, null, $default ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

        if ( null !== $value ) {
            return $value;
        }

        if( ! $this->has($name)) {
            return $default;
        }

        /**
         * Filter any setting after read
         *
         * @param mixed $default The default value.
         */
        return apply_filters( "get_{$this->slug}" . $name, $this->values[$name], $default ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
    }

    public function has(string $name): bool
    {
        return key_exists($name, $this->values);
    }

    public function set(string $name, $value)
    {
        $this->values[$name] = $value;
    }

    public function set_values(array $values)
    {
        foreach ($values as $name => $value) {
            $this->values[$name] = $value;
        }
    }

    public function get_values(): array
    {
        $output = [];

        foreach ($this->values as $name => $value) {
            $output[$name] = $this->get($name);
        }

        return $output;
    }

    public function get_options(): array
    {
        return $this->get_values();
    }
}