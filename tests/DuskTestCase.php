<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        // if (! static::runningInSail()) {
        //     static::startChromeDriver();       // Start the ChromeDriver server (If you are using other WebDriver, you can run your webdriver first)
        // }
    }

    /**
     * Determine if Dusk should run using a headless browser. You may modified your argument here
     */
    protected function getBrowserArguments(): array
    {
        return collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                // '--disable-gpu',
                // '--headless=new',
            ]);
        })->all();
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        // $driverUrl = $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515';
        // $browser = $_ENV['DUSK_BROWSER'] ?? 'chrome';

        // $driverUrl = 'http://localhost:4444';
        $driverUrl = 'http://localhost:9515';
        $browser = 'edge';

        switch ($browser) {
            case 'firefox':
                $capabilities = DesiredCapabilities::firefox();
                // $options = (new FirefoxOptions())->addArguments($this->getBrowserArguments());
                // $capabilities->setCapability(FirefoxOptions::CAPABILITY, $options);
                break;

            case 'edge':
                $capabilities = DesiredCapabilities::microsoftEdge();
                break;

            case 'chrome':
            default:
                $capabilities = DesiredCapabilities::chrome();
                // $options = (new ChromeOptions())->addArguments($this->getBrowserArguments());
                // $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
                break;
        }

        return RemoteWebDriver::create($driverUrl, $capabilities);
    }
}
