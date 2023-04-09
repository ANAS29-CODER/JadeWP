<?php

declare(strict_types=1);

namespace org\wplake\acf_views;

defined('ABSPATH') || exit;

use org\wplake\acf_views\AcfCard\AcfCards;
use org\wplake\acf_views\AcfGroups\DemoGroup;
use org\wplake\acf_views\AcfGroups\Field;
use org\wplake\acf_views\AcfGroups\Item;
use org\wplake\acf_views\AcfView\AcfViews;
use org\wplake\acf_views\AcfView\Post;

class DemoImport
{
    private int $samsungId;
    private int $xiaomiId;
    private int $nokiaId;
    private int $phoneViewId;
    private int $phonesCardId;
    private int $samsungArticleId;
    private int $phonesArticleId;
    private int $groupId;

    private string $error;
    private bool $isProcessed;
    private AcfViews $acfViews;
    private Settings $settings;
    private bool $isImportRequest;
    private Item $item;
    private AcfCards $acfCards;
    private Cache $cache;

    public function __construct(
        AcfViews $acfViews,
        Settings $settings,
        Item $item,
        AcfCards $acfCards,
        Cache $cache
    ) {
        $this->samsungId        = 0;
        $this->xiaomiId         = 0;
        $this->nokiaId          = 0;
        $this->phoneViewId      = 0;
        $this->phonesCardId     = 0;
        $this->samsungArticleId = 0;
        $this->phonesArticleId  = 0;
        $this->groupId          = 0;

        $this->acfViews = $acfViews;
        $this->settings = $settings;

        $this->error           = '';
        $this->isProcessed     = false;
        $this->isImportRequest = false;
        $this->item            = $item->getDeepClone();
        $this->acfCards        = $acfCards;
        $this->cache           = $cache;
    }

    protected function addError(string $error): void
    {
        $this->error .= $error;
    }

    protected function createPages(): void
    {
        $samsungId        = wp_insert_post([
                                               'post_type'   => 'page',
                                               'post_status' => 'draft',
                                               'post_title'  => 'Samsung Galaxy A53 (Demo)',
                                           ]);
        $nokiaId          = wp_insert_post([
                                               'post_type'   => 'page',
                                               'post_status' => 'draft',
                                               'post_title'  => 'Nokia X20 (Demo)',
                                           ]);
        $xiaomiId         = wp_insert_post([
                                               'post_type'   => 'page',
                                               'post_status' => 'draft',
                                               'post_title'  => 'Xiaomi 12T (Demo)',
                                           ]);
        $samsungArticleId = wp_insert_post([
                                               'post_type'   => 'page',
                                               'post_status' => 'draft',
                                               'post_title'  => 'Article about Samsung (Demo)',
                                           ]);
        $phonesArticleId  = wp_insert_post([
                                               'post_type'   => 'page',
                                               'post_status' => 'draft',
                                               'post_title'  => 'Most popular phones in 2022 (Demo)',
                                           ]);

        if (is_wp_error($samsungId) ||
            is_wp_error($nokiaId) ||
            is_wp_error($xiaomiId) ||
            is_wp_error($samsungArticleId) ||
            is_wp_error($phonesArticleId)) {
            $this->addError('Fail to create pages');

            return;
        }

        $this->samsungId        = $samsungId;
        $this->nokiaId          = $nokiaId;
        $this->xiaomiId         = $xiaomiId;
        $this->samsungArticleId = $samsungArticleId;
        $this->phonesArticleId  = $phonesArticleId;
    }

    protected function createAcfView(): void
    {
        $phoneViewId = wp_insert_post([
                                          'post_type'   => AcfViews::NAME,
                                          'post_status' => 'publish',
                                          'post_title'  => '"Phone" Demo View',
                                      ]);
        if (is_wp_error($phoneViewId)) {
            $this->addError('Fail to create an ACF View');

            return;
        }

        $this->phoneViewId = $phoneViewId;
    }

    protected function createAcfCard(): void
    {
        $phonesCardId = wp_insert_post([
                                           'post_type'   => AcfCards::NAME,
                                           'post_status' => 'publish',
                                           'post_title'  => '"Phones" Demo Card',
                                       ]);
        if (is_wp_error($phonesCardId)) {
            $this->addError('Fail to create an ACF Card');

            return;
        }

        $this->phonesCardId = $phonesCardId;
    }

    protected function importAcfGroup(): array
    {
        if (! function_exists('acf_import_field_group')) {
            $this->addError('ACF plugin is not available');

            return [];
        }

        $groupJSON                            = DemoGroup::getGroupInfo();
        $groupJSON['title']                   = 'ACF Views "Phone" Demo Group';
        $groupJSON['location'][0][0]['value'] = $this->samsungId;
        $groupJSON['location'][1][0]['value'] = $this->nokiaId;
        $groupJSON['location'][2][0]['value'] = $this->xiaomiId;

        unset($groupJSON['key']);
        foreach ($groupJSON['fields'] as &$field) {
            $field['key'] = uniqid('field_');
        }

        $groupJSON = acf_import_field_group($groupJSON);

        if (! isset($groupJSON['ID'])) {
            $this->addError('Fail to import an ACF group');

            return [];
        }

        $this->groupId = (int)$groupJSON['ID'];

        return $groupJSON;
    }

    protected function fillPhoneAcfView(array $groupData): void
    {
        $view = $this->cache->getAcfViewData($this->phoneViewId);

        $view->description = 'It\'s a demo ACF View to display fields from the "Phone" demo ACF Field Group.';

        $titleLinkItem             = $this->item->getDeepClone();
        $titleLinkItem->group      = $groupData['key'];
        $titleLinkItem->field->key = Field::createKey(Acf::GROUP_POST, Post::FIELD_TITLE_LINK);
        $view->items[]             = $titleLinkItem;

        $brandItem               = $this->item->getDeepClone();
        $brandItem->group        = $groupData['key'];
        $brandItem->field->label = 'Brand:';
        $brandItem->field->key   = Field::createKey($groupData['key'], $groupData['fields'][0]['key']);
        $view->items[]           = $brandItem;

        $modelItem               = $this->item->getDeepClone();
        $modelItem->group        = $groupData['key'];
        $modelItem->field->label = 'Model:';
        $modelItem->field->key   = Field::createKey($groupData['key'], $groupData['fields'][1]['key']);
        $view->items[]           = $modelItem;

        $priceItem               = $this->item->getDeepClone();
        $priceItem->group        = $groupData['key'];
        $priceItem->field->label = 'Price:';
        $priceItem->field->key   = Field::createKey($groupData['key'], $groupData['fields'][2]['key']);
        $view->items[]           = $priceItem;

        $websiteItem                   = $this->item->getDeepClone();
        $websiteItem->group            = $groupData['key'];
        $websiteItem->field->label     = 'Website:';
        $websiteItem->field->linkLabel = 'Visit';
        $websiteItem->field->key       = Field::createKey($groupData['key'], $groupData['fields'][3]['key']);
        $view->items[]                 = $websiteItem;

        $view->cssCode = "#view {\n padding: 30px;\n color: #444444;\n}\n\n" .
                         "#view .acf-view__row {\n display:flex;\n margin:10px;\n}\n\n" .
                         "#view a {\n color:#008BB7;\n}\n\n" .
                         "#view .acf-view__label {\n width: 100px;\n font-weight: bold;\n padding-right: 10px;\n}\n\n";

        $view->saveToPostContent();

        $this->acfViews->performSaveActions($this->phoneViewId);
    }

    protected function fillPhoneAcfCard(): void
    {
        $acfCard = $this->cache->getAcfCardData($this->phonesCardId);

        $acfCard->description = 'It\'s a demo ACF Card for "Phones"';

        $acfCard->acfViewId = $this->phoneViewId;

        $acfCard->postTypes[]    = 'page';
        $acfCard->postStatuses[] = 'draft';
        $acfCard->postStatuses[] = 'publish';
        $acfCard->postIn         = [$this->samsungId, $this->xiaomiId, $this->nokiaId,];

        $acfCard->cssCode = "#card .acf-card__items {\n display:flex;\n}\n\n" .
                            "#card .acf-view {\n flex-basis:33%;\n flex-shrink:0;\n padding:10px 20px;\n}\n\n";

        $acfCard->saveToPostContent();

        $this->acfCards->performSaveActions($this->phonesCardId);
    }

    protected function fillPages(array $groupData): void
    {
        $phones = [
            $this->samsungId => [
                'samsung',
                'Galaxy A53',
                '2000',
                ['url' => 'https://www.samsung.com/us/', 'target' => '_blank',],
            ],
            $this->xiaomiId  => [
                'xiaomi',
                '12T',
                '1000',
                ['url' => 'https://www.mi.com/global', 'target' => '_blank',],
            ],
            $this->nokiaId   => [
                'nokia',
                'X20',
                '1500',
                ['url' => 'https://www.nokia.com/phones/en_us', 'target' => '_blank',],
            ],
        ];

        foreach ($phones as $pageId => $data) {
            foreach ($data as $fieldNumber => $fieldValue) {
                // 0 = brand and etc...
                update_field($groupData['fields'][$fieldNumber]['key'], $fieldValue, $pageId);
            }

            $postContent = '<!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer in urna a lorem vehicula blandit. Sed ac nisi eget nisl fermentum mattis. Donec dignissim est eu arcu faucibus tincidunt. Integer sit amet ultrices justo, at ultrices ipsum. Fusce facilisis enim sit amet augue placerat, ut mattis metus ultrices. Sed volutpat libero quam, nec convallis enim pellentesque sed. Phasellus ac magna eget lectus luctus scelerisque. Proin fringilla velit purus, vel fringilla urna pellentesque sit amet. Sed auctor aliquam placerat. Donec eleifend, orci sed gravida luctus, nisi turpis aliquam eros, ac porta justo nunc convallis ante. Aliquam erat volutpat. Cras nec velit non eros elementum posuere. Etiam lobortis lacus vel nisi pellentesque, id hendrerit est ultrices. Integer neque libero, accumsan vulputate orci sodales, convallis venenatis nibh.</p>
<!-- /wp:paragraph -->';
            $postContent .= '<!-- wp:heading --><h2>"Phone" ACF View to show fields of this page</h2><!-- /wp:heading -->';
            $postContent .= '<!-- wp:shortcode -->[acf_views view-id="' . $this->phoneViewId . '"]<!-- /wp:shortcode -->';

            wp_update_post([
                               'ID'           => $pageId,
                               'post_content' => $postContent,
                           ]);
        }

        // Samsung Article

        $postContent = '<!-- wp:paragraph -->
<p>Aliquam erat volutpat. Nunc quam augue, consequat sed tristique eget, aliquam eu lacus. Curabitur vulputate justo lorem, vel ornare ipsum fringilla et. Sed ultricies, mauris congue tristique vehicula, felis lorem maximus elit, quis aliquet purus turpis et turpis. Donec eget magna nec eros pharetra feugiat mattis sit amet purus. In ornare, lacus et lobortis rhoncus, nisl elit laoreet quam, in scelerisque turpis lacus sed neque. Duis velit dui, convallis eu quam quis, pellentesque laoreet nulla. Duis id fermentum nulla. Morbi mi metus, venenatis eu consequat id, tempor eu velit. Vivamus et rhoncus eros.</p>
<!-- /wp:paragraph -->';
        $postContent .= '<!-- wp:heading --><h2>"Phone" ACF View with the object-id argument to show Samsung Phone\'s fields</h2><!-- /wp:heading -->';
        $postContent .= '<!-- wp:shortcode -->[acf_views view-id="' . $this->phoneViewId . '" object-id="' . $this->samsungId . '"]<!-- /wp:shortcode -->';
        wp_update_post([
                           'ID'           => $this->samsungArticleId,
                           'post_content' => $postContent,
                       ]);

        // Phones Article

        $postContent = '<!-- wp:paragraph -->
<p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vestibulum vestibulum felis quis lectus ullamcorper, at egestas odio porttitor. Quisque rutrum dolor a nulla volutpat, vitae ullamcorper lacus consectetur. Maecenas ullamcorper commodo quam nec feugiat. Aenean eget arcu sit amet mauris eleifend venenatis. Donec sodales arcu non augue bibendum ullamcorper. Cras dictum odio magna, ac tincidunt leo pulvinar at. Cras vitae turpis non purus congue elementum in a massa. Ut vehicula sapien ipsum. Vivamus ac neque in enim posuere vehicula non ac risus. Cras turpis tortor, pharetra in varius vel, mattis pretium turpis. Maecenas mollis placerat nunc, mattis efficitur purus lobortis in. Duis consectetur turpis nec placerat ullamcorper.</p>
<!-- /wp:paragraph -->';
        $postContent .= '<!-- wp:heading --><h2>"Phones" ACF Card to show the phones</h2><!-- /wp:heading -->';
        $postContent .= '<!-- wp:shortcode -->[acf_cards card-id="' . $this->phonesCardId . '"]<!-- /wp:shortcode -->';
        wp_update_post([
                           'ID'           => $this->phonesArticleId,
                           'post_content' => $postContent,
                       ]);
    }

    protected function saveIDs(): void
    {
        $this->settings->setDemoImport([
                                           'samsungId'        => $this->samsungId,
                                           'xiaomiId'         => $this->xiaomiId,
                                           'nokiaId'          => $this->nokiaId,
                                           'phoneViewId'      => $this->phoneViewId,
                                           'phonesCardId'     => $this->phonesCardId,
                                           'samsungArticleId' => $this->samsungArticleId,
                                           'phonesArticleId'  => $this->phonesArticleId,
                                           'groupId'          => $this->groupId,
                                       ]);
        $this->settings->save();
    }

    public function setHooks(): void
    {
        add_action('wp_loaded', function () {
            if (! isset($_POST['_av-demo-import'])) {
                return;
            }

            check_admin_referer('_av-demo-import');

            $isImport = isset($_POST['_import']);
            $isDelete = isset($_POST['_delete']);

            ! $isImport || $this->import();
            $isImport || ! $isDelete || $this->delete();
        });
    }

    public function readIDs(): void
    {
        $ids = $this->settings->getDemoImport();

        if (! key_exists('samsungId', $ids) ||
            ! key_exists('xiaomiId', $ids) ||
            ! key_exists('nokiaId', $ids) ||
            ! key_exists('phoneViewId', $ids) ||
            ! key_exists('phonesCardId', $ids) ||
            ! key_exists('samsungArticleId', $ids) ||
            ! key_exists('phonesArticleId', $ids) ||
            ! key_exists('groupId', $ids)) {
            return;
        }

        $this->samsungId        = (int)$ids['samsungId'];
        $this->xiaomiId         = (int)$ids['xiaomiId'];
        $this->nokiaId          = (int)$ids['nokiaId'];
        $this->phoneViewId      = (int)$ids['phoneViewId'];
        $this->phonesCardId     = (int)$ids['phonesCardId'];
        $this->samsungArticleId = (int)$ids['samsungArticleId'];
        $this->phonesArticleId  = (int)$ids['phonesArticleId'];
        $this->groupId          = (int)$ids['groupId'];
    }

    public function import(): void
    {
        $this->isProcessed     = true;
        $this->isImportRequest = true;

        // pages should be created first of all
        $this->createPages();

        $groupData = $this->importAcfGroup();
        $this->isHasError() || $this->createAcfView();
        $this->isHasError() || $this->createAcfCard();

        $this->isHasError() || $this->fillPhoneAcfView($groupData);
        $this->isHasError() || $this->fillPhoneAcfCard();
        $this->isHasError() || $this->fillPages($groupData);

        $this->isHasError() || $this->saveIDs();
    }

    public function delete(): void
    {
        $this->readIDs();

        if (! $this->isHasData()) {
            return;
        }

        $this->isProcessed = true;

        // force to bypass a trash
        wp_delete_post($this->samsungId, true);
        wp_delete_post($this->xiaomiId, true);
        wp_delete_post($this->nokiaId, true);
        wp_delete_post($this->phoneViewId, true);
        wp_delete_post($this->phonesCardId, true);
        wp_delete_post($this->samsungArticleId, true);
        wp_delete_post($this->phonesArticleId, true);
        wp_delete_post($this->groupId, true);

        $this->settings->setDemoImport([]);
        $this->settings->save();

        $this->samsungId        = 0;
        $this->xiaomiId         = 0;
        $this->nokiaId          = 0;
        $this->phoneViewId      = 0;
        $this->phonesCardId     = 0;
        $this->samsungArticleId = 0;
        $this->phonesArticleId  = 0;
        $this->groupId          = 0;
    }

    public function isHasError(): bool
    {
        return ! ! $this->error;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function isProcessed(): bool
    {
        return $this->isProcessed;
    }

    public function isHasData(): bool
    {
        return ! ! $this->groupId;
    }

    public function isImportRequest(): bool
    {
        return $this->isImportRequest;
    }

    public function getAcfGroupLink(): string
    {
        return (string)get_edit_post_link($this->groupId);
    }

    public function getSamsungLink(): string
    {
        return (string)get_the_permalink($this->samsungId);
    }

    public function getXiaomiLink(): string
    {
        return (string)get_the_permalink($this->xiaomiId);
    }

    public function getNokiaLink(): string
    {
        return (string)get_the_permalink($this->nokiaId);
    }

    public function getSamsungArticleLink(): string
    {
        return (string)get_the_permalink($this->samsungArticleId);
    }

    public function getPhonesArticleLink(): string
    {
        return (string)get_the_permalink($this->phonesArticleId);
    }

    public function getPhoneAcfViewLink(): string
    {
        return (string)get_edit_post_link($this->phoneViewId);
    }

    public function getPhonesAcfCardLink(): string
    {
        return (string)get_edit_post_link($this->phonesCardId);
    }
}
