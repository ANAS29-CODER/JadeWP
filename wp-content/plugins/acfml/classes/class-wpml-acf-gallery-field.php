<?php

class WPML_ACF_Gallery_Field extends WPML_ACF_Post_Object_Field {

	/**
	 * @return array
	 */
	public function convert_ids() {
		$ids = parent::convert_ids();

		return is_array( $ids ) ? $ids : [ $ids ];
	}
}
