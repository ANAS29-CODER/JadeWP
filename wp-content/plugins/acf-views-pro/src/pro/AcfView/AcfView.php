<?php

declare(strict_types=1);

namespace org\wplake\acf_views\pro\AcfView;

use Error;
use org\wplake\acf_views\AcfGroups\Field;
use org\wplake\acf_views\AcfGroups\Item;
use org\wplake\acf_views\AcfView\FieldMeta;

defined('ABSPATH') || exit;

class AcfView extends \org\wplake\acf_views\AcfView\AcfView
{
    /**
     * @param string $phpCode
     * @param int $viewId
     * @param int|string $objectId Can be 'options' or 'user_x'
     * @param array $fields
     *
     * @return array
     */
    protected static function executePHPCode(string $phpCode, int $viewId, $objectId, array $fields): array
    {
        // declared variables, must be available in the executing code
        $_viewId = $viewId;
        $_objectId = $objectId;
        $_fields = $fields;

        try {
            // convert possible FALSE and NULL to array
            $customVariables = @eval($phpCode) ?: [];
        } catch (Error $ex) {
            // return an empty array in case the code contains syntax errors
            return [];
        }

        return is_array($customVariables) ?
            $customVariables :
            [];
    }

    protected function getFieldMarkup(FieldMeta $fieldMeta, Field $field, $fieldValue): FieldMarkup
    {
        return new FieldMarkup($this->acf, $fieldMeta, $field, $fieldValue);
    }

    protected function insertRepeaterField(
        FieldMeta $fieldMeta,
        Item      $item,
                  $fieldValue
    ): void
    {
        $repeaterId = $item->field->id;
        $regExp = "/<!--\\\${$repeaterId}\\\$-->([\s\S]*)<!--\\\${$repeaterId}\\\$-->/mi";
        preg_match_all($regExp, $this->html, $originRepeaterItemMatch, PREG_SET_ORDER);
        $originRepeaterItemMatch = $originRepeaterItemMatch ?
            $originRepeaterItemMatch[0] :
            [];

        // just return, because existing not required (as empty rows are skipped in markup)
        if (2 !== count($originRepeaterItemMatch)) {
            return;
        }

        $repeaterItemTemplate = $originRepeaterItemMatch[1];
        $repeaterHtml = '';
        $rows = $fieldValue ?
            (array)$fieldValue :
            [];

        $repeaterFieldsMeta = $item->getRepeaterFieldsMeta();
        // e.g. already filled for cache/tests
        if (!$repeaterFieldsMeta) {
            $item->setRepeaterFieldsMeta();
            $repeaterFieldsMeta = $item->getRepeaterFieldsMeta();
        }

        foreach ($rows as $row) {
            $repeaterItemMarkup = $repeaterItemTemplate;

            foreach ($item->repeaterFields as $repeaterField) {
                $subFieldMeta = $repeaterFieldsMeta[$repeaterField->getAcfFieldId()];
                $subFieldId = "\${$repeaterId}\$:\${$repeaterField->id}\$";
                $subFieldMarkup = '';

                $subFieldValue = $row[$subFieldMeta->getName()] ?? '';
                $subFieldValue = $subFieldValue ?: $repeaterField->defaultValue;
                $isRemoveWhenEmpty = true;

                if (!!$subFieldValue ||
                    $repeaterField->isVisibleWhenEmpty ||
                    'true_false' === $subFieldMeta->getType()) {
                    $subFieldMarkup = $this->getFieldMarkup($subFieldMeta, $repeaterField, $subFieldValue);
                    $subFieldMarkup = $subFieldMarkup->getMarkup($this->view->getSource());
                    $isRemoveWhenEmpty = false;
                }

                $repeaterItemMarkup = $this->injectFieldInMarkup(
                    $subFieldId,
                    $subFieldMarkup,
                    $repeaterItemMarkup,
                    $isRemoveWhenEmpty
                );
            }

            $repeaterHtml .= $repeaterItemMarkup;
        }

        $this->html = str_replace($originRepeaterItemMatch[0], $repeaterHtml, $this->html);
    }

    protected function insertField(
        FieldMeta $fieldMeta,
        Item      $item,
                  $fieldValue
    ): void
    {
        if ('repeater' !== $fieldMeta->getType()) {
            parent::insertField($fieldMeta, $item, $fieldValue);
        }

        $this->insertRepeaterField($fieldMeta, $item, $fieldValue);
    }

    public function insertFields(bool $isMinifyMarkup = true): array
    {
        $fieldValues = parent::insertFields($isMinifyMarkup);

        $viewId = $this->view->getSource();
        $objectId = $this->dataPost->getId();

        $phpCode = str_replace('<?php', '', $this->view->phpVariables);
        // the static function is used to avoid any chance of changing the context (this)
        $phpVariables = self::executePHPCode($phpCode, $viewId, $objectId, $fieldValues);

        $customVariables = (array)apply_filters(
            'acf_views/view/custom_variables',
            $phpVariables,
            $viewId,
            $objectId,
            $fieldValues
        );
        $customVariables = (array)apply_filters(
            'acf_views/view/custom_variables/view_id=' . $viewId,
            $customVariables,
            $viewId,
            $objectId,
            $fieldValues
        );

        foreach ($customVariables as $name => $value) {
            $this->html = str_replace('{' . $name . '}', $value, $this->html);
        }

        return $fieldValues;
    }
}