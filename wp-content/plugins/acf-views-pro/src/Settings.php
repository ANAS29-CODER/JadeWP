<?php

declare(strict_types=1);

namespace org\wplake\acf_views;

defined('ABSPATH') || exit;

class Settings
{
    private Options $options;

    private string $version;
    private string $license;
    private string $licenseExpiration;
    private array $demoImport;
    private string $licenseUsedDomains;

    public function __construct(Options $options)
    {
        $this->options = $options;

        $this->version            = '';
        $this->license            = '';
        $this->licenseExpiration  = '';
        $this->licenseUsedDomains = '';
        $this->demoImport         = [];
    }

    public function load(): void
    {
        $settings = (array)($this->options->getOption(Options::OPTION_SETTINGS) ?: []);

        if (isset($settings['version'])) {
            $this->version = (string)$settings['version'];
        }

        if (isset($settings['license'])) {
            $this->license = (string)$settings['license'];
        }

        if (isset($settings['licenseExpiration'])) {
            $this->licenseExpiration = (string)$settings['licenseExpiration'];
        }

        if (isset($settings['licenseUsedDomains'])) {
            $this->licenseUsedDomains = (string)$settings['licenseUsedDomains'];
        }

        if (isset($settings['demoImport'])) {
            $this->demoImport = (array)$settings['demoImport'];
        }
    }

    public function save(): void
    {
        $settings = [
            'version'            => $this->version,
            'license'            => $this->license,
            'licenseExpiration'  => $this->licenseExpiration,
            'licenseUsedDomains' => $this->licenseUsedDomains,
            'demoImport'         => $this->demoImport,
        ];

        $this->options->updateOption(Options::OPTION_SETTINGS, $settings);
    }

    //// setters / getters

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setLicense(string $license): void
    {
        $this->license = $license;
    }

    public function getLicense(): string
    {
        return $this->license;
    }

    public function setLicenseExpiration(string $licenseExpiration): void
    {
        $this->licenseExpiration = $licenseExpiration;
    }

    public function getLicenseExpiration(): string
    {
        return $this->licenseExpiration;
    }

    public function setLicenseUsedDomains(string $licenseUsedDomains): void
    {
        $this->licenseUsedDomains = $licenseUsedDomains;
    }

    public function getLicenseUsedDomains(): string
    {
        return $this->licenseUsedDomains;
    }

    public function setDemoImport(array $demoImport): void
    {
        $this->demoImport = $demoImport;
    }


    public function getDemoImport(): array
    {
        return $this->demoImport;
    }
}
