<?php

// This file defines all the functions we will need.
// Since it defines things, we need to make sure it isn't loaded twice.
require_once __DIR__.'/_phoenix.php';
require_once $settings['onces'].'once.sanitise.tracker.php';

// IF STATS
if ( isset($_GET['stats']) ) {
	require_once $settings['functions'].'function.tracker.stats.php';
	tracker_stats($connection, $settings);
// END IF STATS

// IF NOT STATS
} else {
	// IF SCRAPE
	if (
		$peer['info_hash'] &&
		(
			$settings['open_tracker'] ||
			in_array($peer['info_hash'], $allowed_torrents)
		)
	) {
		// Perform a Scrape on the torrent.
		require_once $settings['functions'].'function.torrent.scrape.php';
		torrent_scrape($connection, $settings, $peer);
	// END IF SCRAPE

	// IF FULL SCRAPE
	} else if ( $settings['full_scrape'] ) {
		// Scrape the full tracker.
		require_once $settings['functions'].'function.tracker.scrape.php';
		tracker_scrape($connection, $settings);
	// END IF FULL SCRAPE

	// IF NOT ALLOWED TO SCRAPE
	} else {
		// IF ERROR TORRENT
		if ( isset($peer['info_hash']) ) {
			tracker_error('Torrent is not allowed.');
		// END IF ERROR TORRENT

		// IF ERROR TRACKER
		} else {
			tracker_error('Tracker scraping is not allowed.');
		} // END IF ERROR TRACKER

	} // END IF NOT ALLOWED TO SCRAPE

} // END IF NOT STATS
