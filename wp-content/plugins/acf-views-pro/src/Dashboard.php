<?php

declare(strict_types=1);

namespace org\wplake\acf_views;

use org\wplake\acf_views\AcfCard\AcfCards;
use org\wplake\acf_views\AcfView\AcfViews;
use WP_Screen;

defined('ABSPATH') || exit;

class Dashboard
{
    const PAGE_OVERVIEW = 'overview';
    const PAGE_DEMO_IMPORT = 'demo-import';

    private Plugin $plugin;
    private Html $html;
    private Acf $acf;
    private Options $options;
    private DemoImport $demoImport;

    public function __construct(Plugin $plugin, Html $html, Acf $acf, Options $options, DemoImport $demoImport)
    {
        $this->plugin = $plugin;
        $this->html = $html;
        $this->acf = $acf;
        $this->options = $options;
        $this->demoImport = $demoImport;
    }

    protected function getProBanner(): array
    {
        return $this->html->getProBanner(Plugin::PRO_VERSION_URL, $this->plugin->getAssetsUrl('pro.png'));
    }

    protected function getVideoReview(): string
    {
        return 'https://www.youtube.com/embed/0Vv23bmYzzo';
    }

    protected function getPages(): array
    {
        return [
            [
                'isLeftBlock' => true,
                'url' => $this->plugin->getAdminUrl(),
                'label' => 'ACF Views',
                'isActive' => false,
            ],
            [
                'isLeftBlock' => true,
                'url' => $this->plugin->getAdminUrl('', AcfCards::NAME),
                'label' => 'ACF Cards',
                'isActive' => false,
            ],
            [
                'isLeftBlock' => true,
                'url' => $this->plugin->getAdminUrl(self::PAGE_OVERVIEW),
                'label' => 'Overview',
                'isActive' => false,
            ],
            [
                'isRightBlock' => true,
                'url' => $this->plugin->getAdminUrl(self::PAGE_DEMO_IMPORT),
                'label' => 'Demo Import',
                'isActive' => false,
            ],
            [
                'isRightBlock' => true,
                'url' => Plugin::DOCS_URL,
                'isBlank' => true,
                'label' => 'Docs',
                'isActive' => false,
            ],
            [
                'isRightBlock' => true,
                'url' => Plugin::SURVEY_URL,
                'isBlank' => true,
                'label' => 'Survey',
                'isActive' => false,
            ],
        ];
    }

    protected function getCurrentAdminUrl(): string
    {
        $uri = isset($_SERVER['REQUEST_URI']) ?
            esc_url_raw(wp_unslash($_SERVER['REQUEST_URI'])) :
            '';
        $uri = preg_replace('|^.*/wp-admin/|i', '', $uri);

        if (!$uri) {
            return '';
        }

        return admin_url($uri);
    }

    protected function getPlugin(): Plugin
    {
        return $this->plugin;
    }

    public function setHooks(): void
    {
        $pluginSlug = $this->plugin->getSlug();

        add_action('admin_menu', [$this, 'addPages']);

        add_action('current_screen', function (WP_Screen $screen) {
            if (!isset($screen->post_type) ||
                !in_array($screen->post_type, [AcfViews::NAME, AcfCards::NAME,])) {
                return;
            }
            add_action('in_admin_header', [$this, 'getHeader']);
        });

        add_filter("plugin_action_links_{$pluginSlug}", [$this, 'addUpgradeToProLink']);
        // Overview should be later than the Pro link
        add_filter("plugin_action_links_{$pluginSlug}", [$this, 'addOverviewLink']);

        add_action('admin_menu', [$this, 'removeImportSubmenuLink']);
    }

    public function addPages(): void
    {
        add_submenu_page(
            sprintf('edit.php?post_type=%s', AcfViews::NAME),
            'Overview',
            'Overview',
            'edit_posts',
            self::PAGE_OVERVIEW,
            [$this, 'getOverviewPage']
        );
        add_submenu_page(
            sprintf('edit.php?post_type=%s', AcfViews::NAME),
            'Demo import',
            'Demo import',
            'edit_posts',
            self::PAGE_DEMO_IMPORT,
            [$this, 'getImportPage']
        );
    }

    public function getHeader(): void
    {
        $tabs = $this->getPages();

        $currentUrl = $this->getCurrentAdminUrl();

        foreach ($tabs as &$tab) {
            if ($currentUrl !== $tab['url']) {
                continue;
            }

            $tab['isActive'] = true;
            break;
        }

        echo $this->html->dashboardHeader($this->plugin->getName(), $tabs);
    }

    public function getOverviewPage(): void
    {
        $createAcfViewLink = $this->plugin->getAdminUrl('', AcfViews::NAME, 'post-new.php');
        $createAcfCardLink = $this->plugin->getAdminUrl('', AcfCards::NAME, 'post-new.php');

        echo $this->html->dashboardOverview(
            $createAcfViewLink,
            $createAcfCardLink,
            $this->acf->getGroupedFieldTypes(),
            [],
            [],
            $this->plugin->getVersion(),
            $this->plugin->getAdminUrl(self::PAGE_DEMO_IMPORT),
            $this->getVideoReview(),
            $this->getProBanner()
        );
    }

    public function getImportPage(): void
    {
        $isWithDeleteButton = false;

        $formMessage = '';

        if ($this->demoImport->isProcessed()) {
            if (!$this->demoImport->isHasError()) {
                $formMessage .= $this->demoImport->isImportRequest() ?
                    '<p class="av-introduction__title">Import was successful. You’re all set!</p>' :
                    '<p class="av-introduction__title">All demo objects have been deleted.</p>';
            } else {
                $formMessage .= '<p class="av-introduction__title">Request is failed.</p><br><br>' .
                    $this->demoImport->getError();
            }
        } else {
            $this->demoImport->readIDs();
        }

        if ($this->demoImport->isHasData() &&
            !$this->demoImport->isHasError()) {
            $isWithDeleteButton = true;
            $formMessage .= '<p class="av-introduction__title">Imported items</p>';

            $formMessage .= '<p><b>Display page\'s ACF fields on the same page</b></p>';
            $formMessage .= sprintf(
                '<a target="_blank" href="%s">"Samsung Galaxy A53" Page</a><br><br>',
                $this->demoImport->getSamsungLink()
            );
            $formMessage .= sprintf(
                '<a target="_blank" href="%s">"Nokia X20" Page</a><br><br>',
                $this->demoImport->getNokiaLink()
            );
            $formMessage .= sprintf(
                '<a target="_blank" href="%s">"Xiaomi 12T" Page</a><br><br>',
                $this->demoImport->getXiaomiLink()
            );
            $formMessage .= sprintf(
                '<a target="_blank" href="%s">"Phone" Field Group</a><br><br>',
                $this->demoImport->getAcfGroupLink()
            );
            $formMessage .= sprintf(
                '<a target="_blank" href="%s">"Phone" ACF View</a><br><br>',
                $this->demoImport->getPhoneAcfViewLink()
            );

            $formMessage .= '<p><b>Display a specific post, page or CPT item with its fields</b></p>';
            $formMessage .= sprintf(
                '<a target="_blank" href="%s">"Article about Samsung" page</a><br><br>',
                $this->demoImport->getSamsungArticleLink()
            );

            $formMessage .= '<p><b>Display specific posts, pages or CPT items and their fields by using filters 
<br>or by manually assigning items</b></p>';
            $formMessage .= sprintf(
                '<a target="_blank" href="%s">"Phones" ACF Card</a><br><br>',
                $this->demoImport->getPhonesAcfCardLink()
            );
            $formMessage .= sprintf(
                '<a target="_blank" href="%s">"Most popular phones in 2022" page</a><br><br>',
                $this->demoImport->getPhonesArticleLink()
            );
        }

        $formNonce = wp_create_nonce('_av-demo-import');
        echo $this->html->dashboardImport($isWithDeleteButton, $formNonce, $formMessage);
    }

    public function addOverviewLink(array $links): array
    {
        $settings_link = sprintf(
            '<a href="%s">Overview</a>',
            $this->plugin->getAdminUrl(self::PAGE_OVERVIEW)
        );

        array_unshift($links, $settings_link);

        return $links;
    }

    public function addUpgradeToProLink(array $links): array
    {
        $settings_link = sprintf(
            '<a href="%s" target="_blank">Get Pro</a>',
            Plugin::PRO_VERSION_URL
        );

        array_unshift($links, $settings_link);

        return $links;
    }

    public function removeImportSubmenuLink(): void
    {
        $url = sprintf('edit.php?post_type=%s', AcfViews::NAME);

        global $submenu;

        if (!$submenu[$url]) {
            $submenu[$url] = [];
        }

        foreach ($submenu[$url] as $itemKey => $item) {
            if (4 !== count($item) ||
                $item[2] !== self::PAGE_DEMO_IMPORT) {
                continue;
            }

            unset($submenu[$url][$itemKey]);
            break;
        }
    }
}