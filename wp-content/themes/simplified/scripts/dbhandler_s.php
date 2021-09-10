<?php
	
	//-----General DB functions
	
	//Get the last error from DB
	function db_get_last_error(){
		global $wpdb;
		return $wpdb->last_error;
	}
	
	//-----Table DB functions
	
	//Add entity to the specified table
	function db_add_entity_TH($tablename, $entity) {
		//Convert object to array
		if (is_object($entity)) $entity = get_object_vars($entity);
		
		global $wpdb;
		$result = $wpdb->insert($tablename, $entity);
		$id = $wpdb->insert_id;
		
		if (false === $result) throw new DataException("Ошибка добавления записи", array('function' => __FUNCTION__, 'details' => db_get_last_error(), 'table' => $tablename, 'new' => $entity));
		else g_ldv("Добавлена запись", __FUNCTION__, $tablename, $id, null, null);
		
		return $id;
	}
	
	//Update entity from the specified table by id
	function db_set_entity_TH($tablename, $entity, $idname, $id) {
		//Convert object to array
		if (is_object($entity)) $entity = get_object_vars($entity);
		
		global $wpdb;
		$old = db_get_entity($tablename, $idname, $id);
		$result = $wpdb->update($tablename, $entity, array ($idname => $id));
		
		if (false === $result) throw new DataException("Ошибка обновления записи", array('function' => __FUNCTION__, 'details' => db_get_last_error(), 'table' => $tablename, 'id' => $id, 'new' => $entity, 'old' => $old));
		else if (0 !== $result) g_ldv("Обновлена запись", __FUNCTION__, $tablename, $id, $entity, $old);
		
		return $result;
	}
	
	//Delete entity from the specified table by id
	function db_delete_entity_TH($tablename, $idname, $id) {
		global $wpdb;
		$old = db_get_entity($tablename, $idname, $id);
		$result = $wpdb->delete($tablename, array ($idname => $id));
		
		if (false === $result) throw new DataException("Ошибка удаления записи", array('function' => __FUNCTION__, 'details' => db_get_last_error(), 'table' => $tablename, 'id' => $id, 'old' => $old));
		else g_ldv("Удалена запись", __FUNCTION__, $tablename, $id, null, $old);
		
		return $result;
	}
	
	//Get all entities from the specified table
	function db_get_entities($tablename) {
		global $wpdb;
		$result =  $wpdb->get_results(
		"SELECT *
		FROM {$tablename} t");
			
		return $result;
	}
	
	//Get single entity from the specified table by id
	function db_get_entity($tablename, $idname, $id) {
		global $wpdb;						
		$result =  $wpdb->get_row(
		"SELECT *
		FROM {$tablename} t
		WHERE t.{$idname} = {$id}");
			
		return $result;
	}
	
	//-----Personal Table Functions
	
	//Save event to logs
	function db_add_log($status, $event, $location, $new, $old, $userID){
	
		$msg = array(
			'ID_User' =>  $userID,
			'DateTime' => date('Y-m-d H:i:s'),
			'Event' => $event,
			'Status' => $status,
			'Location' => json_encode($location),
			'NewValue' => json_encode($new),
			'OldValue' => json_encode($old)
		);
		
		global $wpdb;
		$result = $wpdb->insert('wp_ab_logs', $msg);
	}
	
	//Get last 20 log messages
	add_action('wp_ajax_db_list_20logs_json', 'db_list_20logs_json');
	function db_list_20logs_json() {
		global $wpdb;						
		$result =  $wpdb->get_results(
		"SELECT l.*
		FROM wp_ab_logs l
		ORDER BY DateTime DESC 
		LIMIT 20");
			
		echo g_ctj($result);
		exit();
	}
	