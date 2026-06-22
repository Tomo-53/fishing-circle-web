<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Dusk テスト実行前の準備。
     * - Sail 環境: Sail が ChromeDriver を管理するためスキップ。
     * - CI 環境: e2e.yml で ChromeDriver を明示的にバックグラウンド起動するためスキップ。
     * - ローカル: DuskTestCase が ChromeDriver を自動起動する。
     */
    public static function prepare(): void
    {
        if (! static::runningInSail() && ! env('CI')) {
            static::startChromeDriver(['--port=9515']);
        }
    }

    /**
     * RemoteWebDriver インスタンスを生成する。CI ではヘッドレス Chrome を使用する。
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }
}
