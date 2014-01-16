<?php
/*
 * Based on the following classes:
 * @Author Rory Standley <rorystandley@gmail.com>
 * @Version 1.3
 * @Package Database
 * @https://github.com/rorystandley/MySQL-CRUD-PHP-OOP
 */
class Database {

	private $connect  = false;

	/*
	 * Connect
	 * Open database connection
	 */
	public function connect() {
		if(!$this->connect) {

			if( ! $this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) ) {
				return "Unable to connect to database: (" . $this->db->errno . ") " . $this->db->error;
			} else {
				$this->connect = true;
	  	}
		}
		// We are connected: check for tables
		$this->tables_exist();
	}

	/*
	 * Tables_exist
	 * Check if tables are set; otherwise, create them
	 */
	private function tables_exist() {
		
		// Try to connect if not connected yet
		if(!$this->connect) { $this->connect(); }

		if(!$this->db->query("SHOW TABLES LIKE `lastfm_last_scrobble`") ||
	  	 !$this->db->query("SHOW TABLES LIKE `lastfm_track_stats`")) {

			// Try to create tables if they do not exist yet
			$track_stats = $this->db->prepare("CREATE TABLE IF NOT EXISTS `lastfm_track_stats` (
				`id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
				`track` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`artist` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`album` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
				`duration` INT unsigned NOT NULL,
				`URL` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
				`UTS` INT unsigned NOT NULL,
				PRIMARY KEY(id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8"
			);
			$last_scrobble = $this->db->prepare("CREATE TABLE IF NOT EXISTS `lastfm_last_scrobble` (
				`id` INT unsigned NOT NULL,
				`UTS` INT unsigned NOT NULL,
				PRIMARY KEY(id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8"
			);
			if(!$track_stats->execute() || !$last_scrobble->execute()) {
	    	echo "Unable to find or create required tables: (" . $this->db->errno . ") " . $this->db->error;
			} else {
				$last_scrobble = $this->db->query("INSERT INTO `lastfm_last_scrobble` (id, UTS) VALUES (1, 1)");	
			}
	  }
		return true;
	}

	/*
	 * Updated
	 * Get the Unix timestamp from the last scrobble update
	 */
	public function updated() {

		// Try to connect if not connected yet
		if(!$this->connect) { $this->connect(); }

		$result = $this->db->query("SELECT * FROM `lastfm_last_scrobble` WHERE `id` = 1");
		$return = $result->fetch_assoc()['UTS'];
		$result->close();
		return $return;
	}

	/*
	 * Insert
	 * Insert the new items
	 */
	public function insert( $ITEM ) {

		$stmt = $this->db->prepare("INSERT INTO `lastfm_track_stats`
			(track, artist, album, duration, URL, UTS) VALUES(?, ?, ?, ?, ?, ?)");
		$stmt->bind_param('sssisi', $ITEM['track'], $ITEM['artist'], $ITEM['album'], $ITEM['duration'], $ITEM['URL'], $ITEM['UTS']);

		if(!$stmt->execute()) {
	    	echo "Unable to add Scrobbles to the database: (" . $this->db->errno . ") " . $this->db->error;
		}
	}
	
	public function modified( $UTS ) {
		$stmt = $this->db->prepare("UPDATE `lastfm_last_scrobble` SET `UTS` = ? WHERE `id` = 1");
		$stmt->bind_param('i', $UTS);

		if(!$stmt->execute()) {
	    	echo "Unable to update last scrobble timestamp: (" . $this->db->errno . ") " . $this->db->error;
		}		
	}
} 