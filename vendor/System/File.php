<?php

declare(strict_types=1);

namespace System;

class File
{
    /**
     * @var root
     */
    public $root;

    /**
     * File constructor.
     * @param $root
     */
    public function __construct($root)
    {
        $this->root = $root;
    }

    /**
     * @param string $file
     * return file path
     * @return string
     */
    public function path(string $file): string
    {
        return $this->root.DIRECTORY_SEPARATOR.str_replace(['/','\\'],DIRECTORY_SEPARATOR,$file);
    }

    /**
     * @param $file
     * check if file exist in path
     * @return bool
     */
    public function exists($file): bool
    {
        return file_exists($this->path($file));
    }

    /**
     * @param $file
     * @return mixed
     */
    public function call($file)
    {
        return require $this->path($file);
    }
}
