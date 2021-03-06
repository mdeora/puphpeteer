<?php

namespace ExtractrIo\Puphpeteer\Tests;

use ExtractrIo\Rialto\Data\JsFunction;

class ResourceInstanciator
{
    protected $resources = [];

    public function __construct(array $browserOptions, string $url) {
        $this->browserOptions = $browserOptions;
        $this->url = $url;

        $this->resources = [
            'Browser' => function ($puppeteer) {
                return $puppeteer->launch($this->browserOptions);
            },
            'BrowserFetcher' => function ($puppeteer) {
                return $puppeteer->createBrowserFetcher();
            },
            'CDPSession' => function ($puppeteer) {
                return $this->Target($puppeteer)->createCDPSession();
            },
            'Coverage' => function ($puppeteer) {
                return $this->Page($puppeteer)->coverage;
            },
            'ElementHandle' => function ($puppeteer) {
                return $this->Page($puppeteer)->querySelector('body');
            },
            'ExecutionContext' => function ($puppeteer) {
                return $this->Frame($puppeteer)->executionContext();
            },
            'Frame' => function ($puppeteer) {
                return $this->Page($puppeteer)->mainFrame();
            },
            'JSHandle' => function ($puppeteer) {
                return $this->Page($puppeteer)->evaluateHandle(JsFunction::create('window'));
            },
            'Keyboard' => function ($puppeteer) {
                return $this->Page($puppeteer)->keyboard;
            },
            'Mouse' => function ($puppeteer) {
                return $this->Page($puppeteer)->mouse;
            },
            'Page' => function ($puppeteer) {
                return $this->Browser($puppeteer)->newPage();
            },
            'Request' => function ($puppeteer) {
                return $this->Response($puppeteer)->request();
            },
            'Response' => function ($puppeteer) {
                return $this->Page($puppeteer)->goto($this->url);
            },
            'SecurityDetails' => function ($puppeteer) {
                return $this->Page($puppeteer)->goto('https://example.com')->securityDetails();
            },
            'Target' => function ($puppeteer) {
                return $this->Page($puppeteer)->target();
            },
            'Touchscreen' => function ($puppeteer) {
                return $this->Page($puppeteer)->touchscreen;
            },
            'Tracing' => function ($puppeteer) {
                return $this->Page($puppeteer)->tracing;
            },
        ];
    }

    public function getResourceNames(): array
    {
        return array_keys($this->resources);
    }

    public function __call(string $name, array $arguments)
    {
        if (!isset($this->resources[$name])) {
            throw new \InvalidArgumentException("The $name resource is not supported.");
        }

        return $this->resources[$name](...$arguments);
    }
}
