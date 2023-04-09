<?php

namespace ACFML\Repeater\Sync;

use ACFML\FieldGroup\Mode;
use ACFML\Helper\Fields;
use ACFML\Repeater\Shuffle\Strategy;
use WPML\API\Sanitize;
use WPML\LIB\WP\Hooks;

class PostHooks implements \IWPML_Backend_Action {

	/**
	 * @var Strategy
	 */
	private $shuffled;

	public function __construct( Strategy $shuffled ) {
		$this->shuffled = $shuffled;
	}
	/**
	 * @return int
	 */
	private function getId() {
		return (int) Sanitize::stringProp( 'post', $_GET );
	}

	/**
	 * @return void
	 */
	public function add_hooks() {
		$id = $this->getId();
		if ( ! $id ) {
			return;
		}
		$fields = get_field_objects( $id );

		if ( $fields
			&& ( Fields::containsType( $fields, 'repeater' ) || Fields::containsType( $fields, 'flexible_content' ) )
			&& in_array( Mode::getForFieldableEntity( 'post' ), [ Mode::ADVANCED, Mode::MIXED ], true )
			&& $this->shuffled->isOriginal( $id )
			&& $this->shuffled->hasTranslations( $id )
		) {
			Hooks::onAction( 'add_meta_boxes' )
				->then( [ $this, 'displayCheckbox' ] );
		}
	}

	public function displayCheckbox() {
		$id = $this->getId();
		CheckboxUI::addMetaBox(
			$this->shuffled->getTrid( $id ),
			get_post_type( $id )
		);
	}
}
