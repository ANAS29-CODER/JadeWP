<?php

declare(strict_types=1);

namespace org\wplake\acf_views\AcfGroups;

use org\wplake\acf_views\AcfGroup;

defined('ABSPATH') || exit;

class MetaField extends AcfGroup
{
    // to fix the group name in case class name changes
    const CUSTOM_GROUP_NAME = self::GROUP_NAME_PREFIX . 'meta-field';
    const FIELD_GROUP = 'group';
    const FIELD_FIELD_KEY = 'fieldKey';

    /**
     * @a-type select
     * @return_format value
     * @required 1
     * @ui 1
     * @label Group
     * @instructions Select a target group
     */
    public string $group;
    /**
     * @a-type select
     * @return_format value
     * @required 1
     * @label Field
     * @instructions Select a target field. Note : only fields with <a target="_blank" href="https://docs.acfviews.com/getting-started/supported-field-types">supported field types</a> are listed here
     */
    public string $fieldKey;
    /**
     * @a-type select
     * @ui 1
     * @required 1
     * @label Comparison
     * @instructions Controls how field value will be compared
     * @choices {"=":"Equal","!=": "Not Equal",">":"Bigger",">=":"Bigger or Equal","<":"Less","<=":"Less or Equal","LIKE":"Contains","NOT LIKE":"Not Contains","EXISTS":"Exists","NOT EXISTS":"Not Exists"}
     * @default_value =
     */
    public string $comparison;
    // not required, as it's user should be able to select != ''
    /**
     * @label Value
     * @instructions Value that will be compared. Use <strong>$post$</strong> to pick up the actual ID dynamically. Can be empty, in case you want to compare with empty string
     * @conditional_logic [[{"field": "local_acf_views_meta-field__comparison","operator": "!=","value": "EXISTS"},{"field": "local_acf_views_meta-field__comparison","operator": "!=","value": "NOT EXISTS"}]]
     */
    public string $value;

    public function getAcfFieldId(): string
    {
        return Field::getAcfFieldIdByKey($this->fieldKey);
    }
}
