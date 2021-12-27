<?php

namespace OptiGov\IO;

use OptiGov\Exceptions\IOException;

class FileReader
{
    public function __construct()
    {
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function exists(string $filename): bool
    {
        return file_exists($filename);
    }

    /**
     * @param string $filename
     * @return string
     * @throws IOException
     */
    public function get(string $filename): string
    {
        if (!$this->exists($filename))
            throw new IOException("File [$filename] was not found.");

        return file_get_contents($filename);
    }
}