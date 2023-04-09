<?php

namespace ACFML\Post;

class EditorHooksFactory implements \IWPML_Backend_Action_Loader {

	/**
	 * @return \IWPML_Action[]|null
	 */
	public function create() {
		global $pagenow;

		$isPostEditScreen = in_array( $pagenow, [ 'post.php', 'post-new.php' ], true );

		if ( $isPostEditScreen ) {
			return [
				new NativeEditorTranslationHooks(),
				new MixedFieldGroupModesHooks(),
			];
		}

		return null;
	}
}
