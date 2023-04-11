<?php
/*
Plugin Name: Loft Optimizer
Plugin URI: http://optimizerLoft.com
Description: Наш первый плагин по оптимизации изображений на wordpress.
Version: 1.0
Author: Basilteam
Author URL: http://Basilteam.com
 */

defined('ABSPATH') or die();

class Optimizer
{

    private $imagePaths;

    private $apiUri = 'https://api.jetpic.net/shrink-formdata';

    private $apiToken = '3W7dxrbKxQd';

    public function __construct()
    {
        $this->run();
    }

    private function getImagePaths()
    {
        return $this->imagePaths;
    }

    private function setImagePaths($paths)
    {
        $this->imagePaths = $paths;
    }

    public function run()
    {
        $this->loadImagesPath();

        if (!@$this->getImagePaths()) {
            $this->printMessage('Warning: no pictures found. Only images with the [jpg,jpeg,png] extensions are suitable for optimization.');
            exit();
        }

        echo PHP_EOL . 'Notice: Found ' . count($this->getImagePaths()) . ' images for optimization.' . PHP_EOL;

        $paths = $this->getImagePaths();
        $this->printMessage('Notice: Optimization of the image is running.');

        foreach ($paths as $path) {

            if (file_exists($path . '_bak')) {
                continue;
            }

            $filename = basename($path);
            $this->makeImageOptimizingByPath($path);
            $this->makeImageBackup($filename);
            $this->renameOptimizedImage($filename);
        }

        $this->printMessage('Notice: Images have been successfully optimized.');
    }

    private function makeImageOptimizingByPath($path)
    {
        $fp = fopen(__DIR__ . "/optimized.tmp", 'w+');
        $ch = curl_init($this->apiUri);

        curl_setopt_array($ch, array(
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'file' => new CURLFile ($path)
                ),
                CURLOPT_USERPWD => 'api:' . $this->apiToken,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_FILE => $fp
            )
        );
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    private function makeImageBackup($filename)
    {
        rename($filename, $filename . "_bak");
    }

    private function renameOptimizedImage($filename)
    {
        rename('optimized.tmp', $filename);
    }

    private function loadImagesPath()
    {
        $this->setImagePaths(glob(__DIR__ . '/*.{jpg,png,jpeg}', GLOB_BRACE));
    }

    private function printMessage($message)
    {
        echo PHP_EOL . $message . PHP_EOL;
    }
}

$optimize = new Optimizer();