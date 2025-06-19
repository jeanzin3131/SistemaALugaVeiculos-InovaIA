<?php

/**
 * Simplified proxy autoloader to load Composer dependencies.
 *
 * The original Composer generated file expected the vendor directory to be in
 * the same location. Because this project keeps a copy of that file inside the
 * `config` folder, the relative path was incorrect and autoloading failed.
 *
 * Instead of duplicating Composer's generated file (which can become outdated
 * when dependencies change), we simply require the real autoload script from
 * the vendor directory.
 */

return require_once __DIR__ . '/../vendor/autoload.php';
