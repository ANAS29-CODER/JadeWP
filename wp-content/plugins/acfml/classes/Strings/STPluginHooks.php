<?php

namespace ACFML\Strings;

use ACFML\Options;
use WPML\FP\Fns;
use WPML\LIB\WP\Hooks;

class STPluginHooks implements \IWPML_Backend_Action {

	const PLUGIN_STATUS_KEY = 'string-translation-status';

	const PLUGIN_STATUS_ACTIVATED   = 'activated';
	const PLUGIN_STATUS_DEACTIVATED = 'deactivated';

	/**
	 * @var Translator
	 */
	private $translator;

	/**
	 * @param Translator $translator
	 */
	public function __construct( Translator $translator ) {
		$this->translator = $translator;
	}

	/**
	 * @return void
	 */
	public function add_hooks() {
		if ( wp_doing_ajax() ) {
			return;
		}

		if ( $this->needsRegistration() ) {
			Hooks::onAction( 'wp_loaded' )
				->then( [ $this, 'maybeRegisterFieldGroupsStrings' ] );
		} else {
			$this->setPluginStatus( self::PLUGIN_STATUS_DEACTIVATED );
		}
	}

	/**
	 * @return bool
	 */
	private function needsRegistration() {
		return defined( 'WPML_ST_VERSION' ) &&
			self::PLUGIN_STATUS_DEACTIVATED === self::getPluginStatus();
	}

	/**
	 * @param array $fieldGroup
	 *
	 * @return bool
	 */
	public function hasPackage( $fieldGroup ) {
		return Package::STATUS_NOT_REGISTERED !== Package::create( $fieldGroup['ID'] )->getStatus();
	}

	/**
	 * @return void
	 */
	public function maybeRegisterFieldGroupsStrings() {
		wpml_collect( acf_get_field_groups() )
			->reject( [ $this, 'hasPackage' ] )
			->map( [ $this->translator, 'registerGroupAndFieldsAndLayouts' ] );

		$this->setPluginStatus( self::PLUGIN_STATUS_ACTIVATED );
	}

	/**
	 * @return string|null
	 */
	private function getPluginStatus() {
		return Options::get( self::PLUGIN_STATUS_KEY );
	}

	/**
	 * @param string $status
	 *
	 * @return void
	 */
	private function setPluginStatus( $status ) {
		if ( self::getPluginStatus() !== $status ) {
			Options::set( self::PLUGIN_STATUS_KEY, $status );
		}
	}
}
