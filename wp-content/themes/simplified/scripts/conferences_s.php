<?php
	
	//Get number of conference participations
	function conferences_count() {
		global $wpdb;						
		$result =  $wpdb->get_var(
		"SELECT COUNT(*)
		FROM wp_ab_conferences c
			INNER JOIN wp_ab_articles a on a.ID_Conference = c.ID_Conference");
		return $result;
	}
	
	//Get full list of conference years
	function conferences_list_years() {
		global $wpdb;						
		$result =  $wpdb->get_results(
		"SELECT 
			YEAR(c.DateFrom) as CYear
		FROM
			wp_ab_conferences c
		GROUP BY
			YEAR(c.DateFrom)
		ORDER BY
			YEAR(c.DateFrom) DESC", 'ARRAY_A');
			
		return $result;
	}