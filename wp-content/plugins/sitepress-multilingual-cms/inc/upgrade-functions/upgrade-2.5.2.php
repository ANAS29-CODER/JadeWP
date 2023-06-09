<?php
  global $wpdb;

  $eoFlag = wpml_get_flag_file_name('eo');
  $sql = "UPDATE {$wpdb->prefix}icl_flags SET flag = '{$eoFlag}' WHERE lang_code = 'eo' AND flag = 'nil.png' ";
  $wpdb->query( $sql );

  $quFlag = wpml_get_flag_file_name('qu');
  $sql = "UPDATE {$wpdb->prefix}icl_flags SET flag = '{$quFlag}' WHERE lang_code = 'qu' AND flag = 'nil.png' ";
  $wpdb->query( $sql );

  $zuFlag = wpml_get_flag_file_name('zu');
  $sql = "UPDATE {$wpdb->prefix}icl_flags SET flag = '{$zuFlag}' WHERE lang_code = 'zu' AND flag = 'nil.png' ";
  $wpdb->query( $sql );

  $cols = $wpdb->get_results( "SHOW COLUMNS FROM {$wpdb->prefix}icl_languages" );
if ( empty( $cols[6] ) || $cols[6]->Field != 'encode_url' ) {
	$sql = "ALTER TABLE {$wpdb->prefix}icl_languages ADD COLUMN encode_url TINYINT(1) NOT NULL DEFAULT 0";
	$wpdb->query( $sql );

	$encurls = array( 'ru', 'uk', 'zh-hans', 'zh-hant', 'ja', 'ko', 'vi', 'th', 'he', 'ar', 'el', 'fa' );
	$sql     = "UPDATE {$wpdb->prefix}icl_languages SET encode_url = 1 WHERE code IN ('" . join( "','", $encurls ) . "')";
	$wpdb->query( $sql );
}

