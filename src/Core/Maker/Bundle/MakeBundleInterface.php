<?php

namespace App\Core\Maker\Bundle;

interface MakeBundleInterface
{
    /**
     * Automatic register bundle on folder config.
     * @param string|null $path
     * @param string $nameBundle
     * @return void
     */
    public function registerBundle(?string $path, string $nameBundle): void;

    public function generateContentRepository();
    public function generateContentBundle();
    public function generateContentEntity();
    public function generateContentController();
}