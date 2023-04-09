<?php

declare(strict_types=1);

namespace org\wplake\acf_views\pro\AcfCard;

use org\wplake\acf_views\AcfGroups\AcfCardData;
use org\wplake\acf_views\AcfView\FieldMeta;

defined('ABSPATH') || exit;

class QueryBuilder extends \org\wplake\acf_views\AcfCard\QueryBuilder
{
    protected function getMetaArgsForSerializedField(array $subMetaField): array
    {
        // relationship & post_object (with multiple checkbox) can be found only in this manner
        // https://www.advancedcustomfields.com/resources/querying-relationship-fields/

        // equal and not equals won't work here. Only LIKE & NOT LIKE
        $subMetaField['compare'] = '=' === $subMetaField['compare'] ?
            'LIKE' :
            $subMetaField['compare'];
        $subMetaField['compare'] = '!=' === $subMetaField['compare'] ?
            'NOT LIKE' :
            $subMetaField['compare'];

        // add quotes to the value. To matches exactly "123", not just 123. This prevents a match for "1234" in 'a:1:{i:0;s:4:"1234";}'.
        $subMetaField['value'] = sprintf('"%s"', $subMetaField['value']);

        return $subMetaField;
    }

    protected function getMetaQueryArgs(AcfCardData $acfCardData): array
    {
        $metaQuery = [
            // can be empty in case hidden
            'relation' => $acfCardData->metaFilter->relation ?: 'AND',
        ];

        global $post;
        // a) object-id in the current loop
        // b) the current page id
        $currentId = $post->ID ?? 0;
        $currentId = $currentId ?: get_queried_object_id();

        foreach ($acfCardData->metaFilter->rules as $metaRule) {
            $subMeta = [
                // can be empty in case hidden
                'relation' => $metaRule->relation ?: 'AND',
            ];
            foreach ($metaRule->fields as $field) {
                $fieldMeta = new FieldMeta($field->getAcfFieldId());

                if (!$fieldMeta->isFieldExist()) {
                    continue;
                }

                $subMetaField = [
                    'key' => $fieldMeta->getName(),
                    'compare' => $field->comparison,
                ];

                if (!in_array($field->comparison, ['EXISTS', 'NOT EXISTS',], true)) {
                    $fieldValue = $field->value;
                    // magic inserting
                    $fieldValue = str_replace('$post$', (string)$currentId, $fieldValue);

                    $subMetaField['value'] = $fieldValue;

                    if ('relationship' === $fieldMeta->getType() ||
                        ('post_object' === $fieldMeta->getType() && $fieldMeta->isMultiple())) {
                        $subMetaField = $this->getMetaArgsForSerializedField($subMetaField);
                    }
                }

                $subMeta[] = $subMetaField;
            }

            $metaQuery[] = $subMeta;
        }

        return $metaQuery;
    }

    protected function getTaxQueryArgs(AcfCardData $acfCardData): array
    {
        $taxQuery = [
            // can be empty in case hidden
            'relation' => $acfCardData->taxFilter->relation ?: 'AND',
        ];

        foreach ($acfCardData->taxFilter->rules as $taxRule) {
            $subTax = [
                // can be empty in case hidden
                'relation' => $taxRule->relation ?: 'AND',
            ];
            foreach ($taxRule->taxonomies as $taxonomy) {
                $subTerm = [
                    'taxonomy' => $taxonomy->taxonomy,
                    // it's necessary to use term_id, wp has a bug when 'field' => 'slug'
                    'field' => 'term_id',
                    'operator' => $taxonomy->comparison,
                ];

                if (!in_array($taxonomy->comparison, ['EXISTS', 'NOT EXISTS',], true)) {
                    $subTerm['terms'] = [$taxonomy->getTermId(),];
                }

                $subTax[] = $subTerm;
            }

            $taxQuery[] = $subTax;
        }

        return $taxQuery;
    }

    public function getQueryArgs(AcfCardData $acfCardData, int $pageNumber): array
    {
        $args = parent::getQueryArgs($acfCardData, $pageNumber);

        if ($acfCardData->metaFilter->rules) {
            $args['meta_query'] = $this->getMetaQueryArgs($acfCardData);
        }

        if ($acfCardData->taxFilter->rules) {
            $args['tax_query'] = $this->getTaxQueryArgs($acfCardData);
        }

        if ($acfCardData->isWithPagination) {
            $args = array_merge($args, [
                'posts_per_page' => $acfCardData->paginationPerPage,
                'offset' => ($pageNumber - 1) * $acfCardData->paginationPerPage,
            ]);

            if (-1 !== $acfCardData->limit) {
                $overAmount = ($acfCardData->paginationPerPage * $pageNumber) - $acfCardData->limit;

                if ($overAmount > 0) {
                    $postsPerPage = $acfCardData->paginationPerPage - $overAmount;
                    $postsPerPage = max($postsPerPage, 0);
                    $args['posts_per_page'] = $postsPerPage;
                }
            }
        }

        $cardId = $acfCardData->getSource();

        $args = (array)apply_filters(
            'acf_views/card/query_args',
            $args,
            $cardId,
            $pageNumber
        );
        $args = (array)apply_filters(
            'acf_views/card/query_args/id=' . $cardId,
            $args,
            $cardId,
            $pageNumber
        );

        return $args;
    }
}