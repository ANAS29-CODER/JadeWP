<?php

declare(strict_types=1);

namespace org\wplake\acf_views\pro;

use org\wplake\acf_views\Settings;
use org\wplake\acf_views\vendors\Puc_v4p13_Plugin_UpdateChecker;

defined('ABSPATH') || exit;

class License
{
    private string $error;
    private bool $isProcessed;
    private Settings $settings;
    /**
     * @var Puc_v4p13_Plugin_UpdateChecker
     */
    private $updateChecker;

    /**
     * @param Settings $settings
     * @param Puc_v4p13_Plugin_UpdateChecker $updateChecker
     */
    public function __construct(Settings $settings, $updateChecker)
    {
        $this->settings      = $settings;
        $this->error         = '';
        $this->isProcessed   = false;
        $this->updateChecker = $updateChecker;
    }

    protected function addError(string $error): void
    {
        $this->error .= $error;
    }

    protected function deactivateLicense(): void
    {
        $this->settings->setLicense('');
        $this->settings->setLicenseExpiration('');
        $this->settings->setLicenseUsedDomains('');

        $this->settings->save();
    }

    /**
     * automatically refresh the update info message at the plugins page (if there is)
     * so if the license just added, but an update was already available,
     * then the 'update now' link will appear without pressing check for updates. And visa versa
     */
    protected function refreshTheUpdateInfo(): void
    {
        $this->updateChecker->checkForUpdates();
    }

    protected function setLicense(string $license): void
    {
        $this->isProcessed = true;

        $this->settings->setLicense($license);
        $info = $this->updateChecker->requestInfo();

        $expiration  = $info->_expiration ?? '';
        $usedDomains = $info->_usedDomains ?? '';

        if (! $expiration) {
            $this->deactivateLicense();

            $this->addError(
                'Your license key appears to be invalid or you\'ve reached the maximum number of websites for this key.'
            );

            return;
        }

        $this->settings->setLicenseExpiration($expiration);
        $this->settings->setLicenseUsedDomains($usedDomains);

        $this->settings->save();
    }

    public function setHooks(): void
    {
        add_action('wp_loaded', function () {
            if (! isset($_POST['_av-pro'])) {
                return;
            }

            check_admin_referer('_av-pro');

            if ($this->settings->getLicense() &&
                isset($_POST['_deactivate'])) {
                $this->deactivateLicense();
            } else {
                $license = sanitize_text_field($_POST['_license'] ?? '');
                $this->setLicense($license);
            }

            $this->refreshTheUpdateInfo();
        });

        // show updates info even if there is no license
        $this->updateChecker->addQueryArgFilter([$this, 'addLicenseKeyToQueryArgs']);
    }

    public function addLicenseKeyToQueryArgs(array $queryArgs): array
    {
        $queryArgs['_license-key'] = $this->settings->getLicense();
        $queryArgs['_version']     = $this->settings->getVersion();

        return $queryArgs;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function isProcessed(): bool
    {
        return $this->isProcessed;
    }

    public function isHasError(): bool
    {
        return ! ! $this->error;
    }
}
