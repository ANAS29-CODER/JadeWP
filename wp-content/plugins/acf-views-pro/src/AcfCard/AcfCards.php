<?php

declare(strict_types=1);

namespace org\wplake\acf_views\AcfCard;

use org\wplake\acf_views\AcfGroups\AcfCardData;
use org\wplake\acf_views\AcfView\AcfViews;
use org\wplake\acf_views\Cache;
use org\wplake\acf_views\Cpt;
use org\wplake\acf_views\Html;
use org\wplake\acf_views\Plugin;
use WP_Query;

defined('ABSPATH') || exit;

/**
 * ACF Card = list of WP_Query settings.
 * So extra loading comparing with the code way only in the following items :
 * 1. getting ACF Card fields from DB (json from post_meta is used here, so low)
 * 2. using ACF View to display (ACF View extra loading here, low)
 */
class AcfCards extends Cpt
{
    const NAME = 'acf_cards';
    const COLUMN_DESCRIPTION = self::NAME . '_description';
    const COLUMN_SHORTCODE = self::NAME . '_shortcode';
    const COLUMN_AUTHOR = self::NAME . '_author';
    const COLUMN_CREATED = self::NAME . '_created';
    const COLUMN_LAST_MODIFIED = self::NAME . '_lastModified';

    protected QueryBuilder $queryBuilder;
    protected CardMarkup $cardMarkup;

    public function __construct(
        Html         $html,
        Plugin       $plugin,
        QueryBuilder $queryBuilder,
        CardMarkup   $cardMarkup,
        Cache        $cache
    )
    {
        parent::__construct($html, $plugin, $cache);

        $this->queryBuilder = $queryBuilder;
        $this->cardMarkup = $cardMarkup;
    }

    protected function updateQueryPreview(AcfCardData $acfCardData): void
    {
        $acfCardData->queryPreview = print_r($this->queryBuilder->getQueryArgs($acfCardData, 1), true);
    }

    protected function updateMarkup(AcfCardData $acfCardData): void
    {
        $acfCardData->markup = $this->cardMarkup->getMarkup($acfCardData, false, true);
    }

    protected function addLayoutCSS(AcfCardData $acfCardData): void
    {
        $layoutCSS = $this->cardMarkup->getLayoutCSS($acfCardData);

        if (!$layoutCSS) {
            return;
        }

        $acfCardData->cssCode = false === strpos($acfCardData->cssCode, '/*BEGIN LAYOUT_RULES*/') ?
            ($acfCardData->cssCode . "\n" . $layoutCSS . "\n") :
            preg_replace(
                '|\/\*BEGIN LAYOUT_RULES\*\/(.*\s)+\/\*END LAYOUT_RULES\*\/|',
                $layoutCSS,
                $acfCardData->cssCode
            );
    }

    public function addCPT(): void
    {
        $labels = [
            'name' => 'ACF Cards',
            'singular_name' => 'ACF Card',
            'menu_name' => 'ACF Cards',
            'parent_item_colon' => 'Parent ACF Card',
            'all_items' => 'ACF Cards',
            'view_item' => 'Browse ACF Card',
            'add_new_item' => 'Add New ACF Card',
            'add_new' => 'Add New',
            'edit_item' => 'Edit ACF Card',
            'update_item' => 'Update ACF Card',
            'search_items' => 'Search ACF Card',
            'not_found' => 'Not Found',
            'not_found_in_trash' => 'Not Found In Trash',
        ];

        $args = [
            'label' => 'ACF Cards',
            'description' => 'Create ACF Card item to choose a set of posts (or CPT items) and paste the shortcode in a target place to display the posts with their ACF fields.' .
                '<br>(which fields are printed depending on a selected ACF View in the Card settings)',
            'labels' => $labels,
            'public' => true,
            // e.g. Yoast doesn't reflect in Sitemap then
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_rest' => false,
            'has_archive' => false,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'delete_with_user' => false,
            'exclude_from_search' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'can_export' => false,
            'rewrite' => false,
            'query_var' => false,
            'menu_icon' => 'dashicons-format-gallery',
            'supports' => ['title',],
            'show_in_graphql' => false,
        ];

        register_post_type(self::NAME, $args);
    }

    public function setHooks(): void
    {
        parent::setHooks();

        add_action('init', [$this, 'addCPT']);
        add_action('admin_menu', [$this, 'addPage']);
        add_action(
            'manage_' . self::NAME . '_posts_custom_column',
            [
                $this,
                'printColumn',
            ],
            10,
            2
        );
        add_action('pre_get_posts', [$this, 'addSortableColumnsToRequest',]);
        // priority is important here, should be 1) after the acf code (20)
        // 2) after the CPT save hook, which replaces fields with json (30)
        add_action('acf/save_post', [$this, 'performSaveActions'], 30);
        add_filter('manage_' . self::NAME . '_posts_columns', [$this, 'getColumns',]);
        add_filter('manage_edit-' . self::NAME . '_sortable_columns', [$this, 'getSortableColumns',]);
        add_filter('enter_title_here', [$this, 'getTitlePlaceholder',]);
    }

    public function replacePostUpdatedMessage(array $messages): array
    {
        global $post;

        $messages[self::NAME] = [
            0 => '', // Unused. Messages start at index 1.
            1 => 'ACF Card updated.',
            2 => 'Custom field updated.',
            3 => 'Custom field deleted.',
            4 => 'ACF Card updated.',
            5 => isset($_GET['revision']) ? sprintf(
                'ACF Card restored to revision from %s',
                wp_post_revision_title((int)$_GET['revision'], false)
            ) : false,
            6 => 'ACF Card published.',
            7 => 'ACF Card saved.',
            8 => 'ACF Card submitted.',
            9 => sprintf(
                'ACF Card scheduled for: <strong>%1$s</strong>.',
                date_i18n('M j, Y @ G:i', strtotime($post->post_date))
            ),
            10 => 'ACF Card draft updated.',
        ];

        return $messages;
    }

    /**
     * @param int|string $postId
     *
     * @return void
     */
    public function performSaveActions($postId): void
    {
        if (!$this->isMyPost($postId)) {
            return;
        }

        $acfCardData = $this->cache->getAcfCardData($postId);

        $this->updateQueryPreview($acfCardData);
        $this->updateMarkup($acfCardData);
        $this->addLayoutCSS($acfCardData);

        $acfCardData->saveToPostContent();
    }

    public function addPage(): void
    {
        $url = sprintf('edit.php?post_type=%s', AcfViews::NAME);

        global $submenu;

        if (!$submenu[$url]) {
            $submenu[$url] = [];
        }

        // 'ACF Views' has 5, so 6 is right after
        $submenu[$url][6] = [
            'ACF Cards',
            'manage_options',
            sprintf(
                'edit.php?post_type=%s',
                self::NAME
            )
        ];

        ksort($submenu[$url]);
    }

    public function getTitlePlaceholder(string $title): string
    {
        $screen = get_current_screen()->post_type ?? '';
        if (self::NAME !== $screen) {
            return $title;
        }

        return 'Name your card';
    }

    public function getColumns(array $columns): array
    {
        unset($columns['date']);

        return array_merge($columns, [
            self::COLUMN_DESCRIPTION => 'Description',
            self::COLUMN_SHORTCODE => 'Shortcode',
            self::COLUMN_AUTHOR => 'Author',
            self::COLUMN_LAST_MODIFIED => 'Last modified',
            self::COLUMN_CREATED => 'Created',
        ]);
    }

    public function getSortableColumns(array $columns): array
    {
        return array_merge($columns, [
            self::COLUMN_AUTHOR => self::COLUMN_AUTHOR,
            self::COLUMN_LAST_MODIFIED => self::COLUMN_LAST_MODIFIED,
            self::COLUMN_CREATED => self::COLUMN_CREATED,
        ]);
    }

    public function addSortableColumnsToRequest(WP_Query $query): void
    {
        if (!is_admin()) {
            return;
        }

        $orderBy = $query->get('orderby');

        switch ($orderBy) {
            case self::COLUMN_AUTHOR:
                $query->set('orderby', 'post_author');
                break;
            case self::COLUMN_LAST_MODIFIED:
                $query->set('orderby', 'post_modified');
                break;
            case self::COLUMN_CREATED:
                $query->set('orderby', 'post_date');
                break;
        }
    }

    public function printColumn(string $column, int $postId): void
    {
        switch ($column) {
            case self::COLUMN_DESCRIPTION:
                $view = $this->cache->getAcfCardData($postId);

                echo esc_html($view->description);
                break;
            case self::COLUMN_SHORTCODE:
                echo $this->html->postboxShortcodes(
                    $postId,
                    true,
                    Plugin::SHORTCODE_CARDS,
                    get_the_title($postId),
                    true
                );
                break;
            case self::COLUMN_AUTHOR:
                echo esc_html(get_user_by('id', get_post($postId)->post_author)->display_name ?? '');
                break;
            case self::COLUMN_LAST_MODIFIED:
                echo esc_html(explode(' ', get_post($postId)->post_modified)[0]);
                break;
            case self::COLUMN_CREATED:
                echo esc_html(explode(' ', get_post($postId)->post_date)[0]);
                break;
        }
    }

    public function addMetaboxes(): void
    {
        parent::addMetaboxes();

        add_meta_box(
            'acf-views_shortcode_cpt',
            'Shortcode',
            function ($post, $meta) {
                if (!$post ||
                    'publish' !== $post->post_status) {
                    echo 'Press the publish button to see the shortcode.';

                    return;
                }

                echo $this->html->postboxShortcodes(
                    $post->ID,
                    false,
                    Plugin::SHORTCODE_CARDS,
                    get_the_title($post),
                    true
                );
            },
            [
                self::NAME,
            ],
            'side',
            // right after the publish button
            'core'
        );
    }
}
