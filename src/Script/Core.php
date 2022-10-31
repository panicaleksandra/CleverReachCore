<?php

namespace CleverReachCore\Script;

use Composer\Script\Event;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Core
{
    /**
     * Executes after composer install or update commands.
     *
     * @param Event $event
     */
    public static function postUpdate(Event $event): void
    {
        self::fixAndCopyDirectory('src', 'src/Core');
        /**
         * For testing purposes, uncomment the following line.
         */
        //self::fixAndCopyDirectory('tests', 'src/Core/tests');
    }

    /**
     * Copies directory from one folder to another and fixes namespace names.
     *
     * @param string $from Source folder.
     * @param string $to Destination folder.
     */
    private static function fixAndCopyDirectory(string $from, string $to): void
    {
        self::copyDirectory(__DIR__ . '/../../vendor/cleverreach/integration-core/' . $from, __DIR__ . '/../tmp');
        self::renameNamespaces(__DIR__ . '/../tmp');
        self::copyDirectory(__DIR__ . '/../tmp', __DIR__ . '/../../' . $to);
        self::removeDirectory(__DIR__ . '/../tmp');
    }

    /**
     * Copies contents from one folder to another.
     *
     * @param string $src Source folder.
     * @param string $dst Destination folder.
     */
    private static function copyDirectory(string $src, string $dst): void
    {
        $dir = opendir($src);
        if (!mkdir($dst) && !is_dir($dst)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dst));
        }

        while (false !== ($file = readdir($dir))) {
            if (($file !== '.') && ($file !== '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::copyDirectory($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }

        closedir($dir);
    }

    /**
     * Renames namespaces within a given directory.
     *
     * @param string $directory Directory that is being fixed.
     */
    private static function renameNamespaces(string $directory): void
    {
        $iterator = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $fileToChange = file_get_contents($file->getRealPath());
                file_put_contents(
                    $file->getRealPath(),
                    str_replace("CleverReach\\", "CleverReachCore\\Core\\", $fileToChange)
                );

                $fileToChange = file_get_contents($file->getRealPath());
                file_put_contents(
                    $file->getRealPath(),
                    str_replace("Logeecom\\", "CleverReachCore\\Core\\", $fileToChange)
                );
            }
        }
    }

    /**
     * Removes a directory.
     *
     * @param string $directory Directory to be removed.
     */
    private static function removeDirectory(string $directory): void
    {
        $iterator = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($directory);
    }
}
