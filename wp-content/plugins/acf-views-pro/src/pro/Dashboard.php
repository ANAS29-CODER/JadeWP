<?php

declare(strict_types=1);

namespace org\wplake\acf_views\pro;

use org\wplake\acf_views\Acf;
use org\wplake\acf_views\DemoImport;
use org\wplake\acf_views\Options;
use org\wplake\acf_views\Plugin;
use org\wplake\acf_views\pro\AcfView\AcfViews;
use org\wplake\acf_views\Settings;

defined('ABSPATH') || exit;

class Dashboard extends \org\wplake\acf_views\Dashboard
{
    const PAGE_PRO = 'pro';

    private Html $html;
    private License $license;
    private Settings $settings;

    public function __construct(
        License    $license,
        Plugin     $plugin,
        Html       $html,
        Acf        $acf,
        Options    $options,
        DemoImport $demoImport,
        Settings   $settings
    )
    {
        parent::__construct($plugin, $html, $acf, $options, $demoImport);

        $this->html = $html;
        $this->license = $license;
        $this->settings = $settings;
    }

    protected function getProBanner(): array
    {
        return [];
    }

    protected function getPages(): array
    {
        $pages = parent::getPages();

        array_unshift($pages, [
            'isRightBlock' => true,
            'url' => $this->getPlugin()->getAdminUrl(self::PAGE_PRO),
            // uppercase here, it's against the rule, but otherwise the menu item will be too small
            'label' => 'PRO',
            'isActive' => false,
        ]);

        return $pages;
    }

    public function setHooks(): void
    {
        parent::setHooks();

        add_action('admin_menu', [$this, 'removeProSubmenuLink']);
    }

    public function addPages(): void
    {
        parent::addPages();

        add_submenu_page(
            sprintf('edit.php?post_type=%s', AcfViews::NAME),
            'Pro',
            'Pro',
            'edit_posts',
            self::PAGE_PRO,
            [$this, 'getProPage']
        );
    }

    public function getProPage(): void
    {
        $formMessage = '';

        if ($this->license->isProcessed()) {
            $formMessage = $this->license->isHasError() ?
                '<p class="av-introduction__title">Activation failed.</p>' . $this->license->getError() :
                '<p class="av-introduction__title">Success!</p>You have Activated your License Key. 
You’ll now be receiving automatic updates as soon as they’re available. Thank you for the Support!';
        }

        $formNonce = wp_create_nonce('_av-pro');
        echo $this->html->dashboardPro(
            $formNonce,
            $formMessage,
            $this->settings->getLicense(),
            $this->settings->getLicenseExpiration(),
            $this->settings->getLicenseUsedDomains()
        );
    }

    public function removeProSubmenuLink(): void
    {
        $url = sprintf('edit.php?post_type=%s', AcfViews::NAME);

        global $submenu;

        if (!$submenu[$url]) {
            $submenu[$url] = [];
        }

        foreach ($submenu[$url] as $itemKey => $item) {
            if (4 !== count($item) ||
                $item[2] !== self::PAGE_PRO) {
                continue;
            }

            unset($submenu[$url][$itemKey]);
            break;
        }
    }

    public function addUpgradeToProLink(array $links): array
    {
        // skip, as already PRO
        return $links;
    }
}
