<?php

class DotEnv {
    private $path;

    public function __construct(string $path) {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("O arquivo .env não foi encontrado.");
        }
        $this->path = $path;
    }

    public function load(): void {
        if (!is_readable($this->path)) {
            throw new \RuntimeException("O arquivo .env não é legível.");
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;  // Ignora comentários
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_ENV) && !array_key_exists($name, $_SERVER)) {
                putenv("$name=$value");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
