<?php

namespace Bfg\Entity;

/**
 * Class ConfigFactory.
 * @package Bfg\Entity
 */
class ConfigFactory
{
    /**
     * @var string
     */
    protected string $file;

    /**
     * @var array
     */
    protected array $items = [];

    /**
     * ConfigFactory constructor.
     * @param  string  $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;

        if (is_file($file)) {
            $this->items = include $file;
        } else {
            $dir = dirname($this->file);

            if (! is_dir($dir)) {
                mkdir($dir, 0777, 1);
            }
        }
    }

    /**
     * Get variable data by dot.
     * @param  string|array  $path
     * @param  null  $default
     * @return mixed
     */
    public function get(string|array $path, $default = null): mixed
    {
        return \Arr::get($this->items, implode('.', (array) $path), $default);
    }

    /**
     * Get all data.
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Check on has data.
     * @param  string|array $path Path for implode
     * @return bool
     */
    public function has($path): bool
    {
        return \Arr::has($this->items, implode('.', (array) $path));
    }

    /**
     * Merge data in to factory.
     * @param  array  $array
     * @return $this
     */
    public function merge(array $array): static
    {
        $this->items = array_merge($this->items, \Arr::dot($array));

        return $this;
    }

    /**
     * Set data in to factory.
     * @param  string|array  $path
     * @param  null  $value
     * @return $this
     */
    public function set(string|array $path, $value = null): static
    {
        \Arr::set($this->items, implode('.', (array) $path), $value);

        return $this;
    }

    /**
     * Set data in to factory if not exists.
     * @param  string|array  $path
     * @param  null  $value
     * @return $this
     */
    public function setIfNotExists(string|array $path, $value = null): static
    {
        if (! $this->has($path)) {
            $this->set($path, $value);
        }

        return $this;
    }

    /**
     * Set data in to factory if exists.
     * @param  string|array  $path
     * @param  null  $value
     * @return $this
     */
    public function setIfExists(string|array $path, $value = null): static
    {
        if ($this->has($path)) {
            $this->set($path, $value);
        }

        return $this;
    }

    /**
     * Forget factory variables.
     * @param string|array $path
     * @return $this
     */
    public function forget($path): static
    {
        if ($this->has($path)) {
            \Arr::forget($this->items, implode('.', (array) $path));
        }

        return $this;
    }

    /**
     * Set and save data to factory.
     * @param  array  $data
     * @return bool
     */
    public function update(array $data): bool
    {
        return $this->merge($data)->save();
    }

    /**
     * Delete config file.
     * @return bool
     */
    public function delete(): bool
    {
        if (is_file($this->file)) {
            return \File::delete($this->file);
        }

        return false;
    }

    /**
     * Clear storage.
     * @return $this
     */
    public function clear(): static
    {
        $this->items = [];

        return $this;
    }

    /**
     * Save a factory data.
     * @return false
     */
    public function save(): bool
    {
        return (bool) file_put_contents(
            $this->file,
            $this
        );
    }

    /**
     * Magic method get.
     * @param  string  $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * Magic method set.
     * @param  string  $name
     * @param $value
     */
    public function __set(string $name, $value): void
    {
        $this->set($name, $value);
    }

    /**
     * Convert to string.
     * @return string
     */
    public function __toString(): string
    {
        return "<?php\n\nreturn " . array_export(
            array_dots_uncollapse($this->items)
        ).';';
    }

    /**
     * Update if call factory like function.
     * @param  array  $data
     * @return bool
     */
    public function __invoke(array $data): bool
    {
        return $this->update($data);
    }

    /**
     * Delete the variable in factory.
     * @param  string  $name
     */
    public function __unset(string $name): void
    {
        $this->forget($name);
    }

    /**
     * Check on has data.
     * @param  string  $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return $this->has($name);
    }

    /**
     * @param  string  $file
     * @return static
     */
    public static function create(string $file): static
    {
        return app(static::class, compact('file'));
    }
}
