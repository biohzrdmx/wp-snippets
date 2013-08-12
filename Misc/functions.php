<?php
	/* ========================================================================================== */

	/**
	 * Filter to search posts by title only
	 * @param  string $search   Search string
	 * @param  object $wp_query WP Query object
	 * @return object           The modified WP Query object
	 */
	function __search_by_title_only( $search, &$wp_query )
	{
		global $wpdb;
		if ( empty( $search ) )
			return $search; // skip processing - no search term in query
		$q = $wp_query->query_vars;
		$n = ! empty( $q['exact'] ) ? '' : '%';
		$search =
		$searchand = '';
		foreach ( (array) $q['search_terms'] as $term ) {
			$term = esc_sql( like_escape( $term ) );
			$search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
			$searchand = ' AND ';
		}
		if ( ! empty( $search ) ) {
			$search = " AND ({$search}) ";
			if ( ! is_user_logged_in() )
				$search .= " AND ($wpdb->posts.post_password = '') ";
		}
		return $search;
	}
	add_filter( 'posts_search', '__search_by_title_only', 500, 2 );

	/* ========================================================================================== */

	/**
	 * Highlight the specified words in the given string
	 * @param  string $text  The words to find
	 * @param  string $words The string to search in
	 * @return string        The string with the highlighted words
	 */
	function highlight_words($text, $words) {
		$split_words = explode(' ', $words);
		foreach($split_words as $word)
			$text = preg_replace("|($word)|Ui", '<b>$1</b>', $text);
		return $text;
	}

	/* ========================================================================================== */
?>