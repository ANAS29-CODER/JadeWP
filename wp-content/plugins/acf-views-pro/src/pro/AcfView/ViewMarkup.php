<?php

declare(strict_types=1);

namespace org\wplake\acf_views\pro\AcfView;

use org\wplake\acf_views\AcfGroups\AcfViewData;
use org\wplake\acf_views\AcfGroups\Field;
use org\wplake\acf_views\AcfGroups\Item;
use org\wplake\acf_views\AcfView\FieldMeta;
use org\wplake\acf_views\Html;

defined('ABSPATH') || exit;

class ViewMarkup extends \org\wplake\acf_views\AcfView\ViewMarkup
{
    protected array $masonryGalleries;
    protected array $lightboxGalleries;

    public function __construct(Html $html)
    {
        parent::__construct($html);

        $this->masonryGalleries = [];
        $this->lightboxGalleries = [];
    }

    protected function getRepeaterMarkup(Item $item): string
    {
        $repeaterMarkup = '';
        $repeaterId = '$' . $item->field->id . '$';

        foreach ($item->repeaterFields as $repeaterField) {
            $repeaterFieldId = $repeaterId . ':$' . $repeaterField->id . '$';
            $repeaterMarkup .= sprintf("\r\n\t\t\t<!--%s-->\r\n", $repeaterFieldId) .
                $this->html->viewRow(
                    'row',
                    "\t\t\t",
                    'acf-view__' . $repeaterField->id,
                    $repeaterField->label,
                    $repeaterFieldId,
                    false
                ) .
                sprintf("\t\t\t<!--%s-->\r\n", $repeaterFieldId);
        }

        return "\r\n\t\t<!--{$repeaterId}-->\r\n" .
            $this->html->viewRow(
                'repeater-item',
                "\t\t",
                'acf-view__' . $item->field->id . '-item',
                '',
                $repeaterMarkup,
                true
            ) .
            "\t\t<!--{$repeaterId}-->\r\n";
    }

    protected function getRowMarkup(FieldMeta $fieldMeta, Item $item): string
    {
        if ('repeater' !== $fieldMeta->getType()) {
            return parent::getRowMarkup($fieldMeta, $item);
        }

        $repeaterMarkup = self::getRepeaterMarkup($item);

        return "\r\n" . $this->html->viewRow(
                'repeater',
                "\t",
                'acf-view__' . $item->field->id,
                $item->field->label,
                $repeaterMarkup,
                true
            );
    }

    /**
     * Extending: adding Repeater fields
     * @param string $type
     * @param AcfViewData $view
     *
     * @return Field[]
     */
    protected function getFieldsByType(string $type, AcfViewData $view): array
    {
        $fitFields = parent::getFieldsByType($type, $view);
        $fitRepeaterFields = [];

        foreach ($view->items as $item) {

            if (!$item->repeaterFields) {
                continue;
            }

            $repeaterFieldsMeta = $item->getRepeaterFieldsMeta();
            if (!$repeaterFieldsMeta) {
                $item->setRepeaterFieldsMeta();
                $repeaterFieldsMeta = $item->getRepeaterFieldsMeta();
            }

            foreach ($item->repeaterFields as $repeaterField) {
                $isFit = $type === $repeaterFieldsMeta[$repeaterField->getAcfFieldId()]->getType();
                if (!$isFit) {
                    continue;
                }

                $fitRepeaterFields[] = $repeaterField;
            }
        }

        return array_merge($fitFields, $fitRepeaterFields);
    }

    /**
     * @param Field[] $galleryFields
     * @param int|string $viewId
     * @return void
     */
    protected function addMasonryGallery(array $galleryFields, $viewId): void
    {
        foreach ($galleryFields as $galleryField) {
            if ('masonry' !== $galleryField->galleryType) {
                continue;
            }

            $this->masonryGalleries[] = [
                'selector' => sprintf('.acf-view--id--%s .acf-view__%s .acf-view__field',
                    $viewId, $galleryField->id),
                'rowMinHeight' => $galleryField->masonryRowMinHeight,
                'gutter' => $galleryField->masonryGutter,
                'mobileGutter' => $galleryField->masonryMobileGutter,
            ];
        }
    }

    /**
     * @param Field[] $galleryFields
     * @param int|string $viewId
     *
     * @return void
     */
    protected function addLightBoxGallery(array $galleryFields, $viewId): void
    {
        foreach ($galleryFields as $galleryField) {
            if (!$galleryField->galleryWithLightBox) {
                continue;
            }

            $this->lightboxGalleries[] = sprintf('.acf-view--id--%s .acf-view__%s .acf-view__field',
                $viewId, $galleryField->id);
        }
    }

    protected function markViewAsRendered(AcfViewData $view): void
    {
        parent::markViewAsRendered($view);

        $galleryFields = $this->getFieldsByType('gallery', $view);

        $this->addMasonryGallery($galleryFields, $view->getSource());
        $this->addLightBoxGallery($galleryFields, $view->getSource());
    }

    public function getMarkup(
        AcfViewData $view,
        int         $pageId,
        string      $viewMarkup = '',
        bool        $isSkipCache = false,
        bool        $isIgnoreCustomMarkup = false
    ): string
    {
        $viewMarkup = $viewMarkup || $isIgnoreCustomMarkup ?
            $viewMarkup :
            trim($view->customMarkup);

        // always call parent to add CSS code to the markup
        $viewMarkup = parent::getMarkup($view, $pageId, $viewMarkup, $isSkipCache);

        if (!$pageId) {
            return $viewMarkup;
        }

        // add shortcode supporting
        // 1. Gutenberg by default runs do_shortcode, but our plugin's shortcode could be called from other places

        return do_shortcode($viewMarkup);
    }

    public function getMasonryGalleries(): array
    {
        return $this->masonryGalleries;
    }

    public function getLightBoxGalleries(): array
    {
        return $this->lightboxGalleries;
    }
}
