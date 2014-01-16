ScrobbleScrobble
================

Small, poorly coded, PHP script that automatically retrieves the latest Scrobbles from Last.fm and stores each track in a MySQL database.

# Note

I quickly wrote this little project for personal use, hence it not being a polished piece of code. I've just committed it to GitHub because others might find it useful, too.

# Configuration

Set either one or both of the "localhost" and "hosted server" database settings in *_config.php*. Also request and retrieve a Last.fm API key and API secret, and enter these in the *_config.php* file, too.

# Running

I have set up a CRON-job to ping this script once every 4 hours, which, seeing that Last.fm's REST api is able to return 50 tracks (default, with a maximum of 200), is long enough to capture everything you potentially listen to. Since the script is required to make several requests (1 for each individual track!), setting the CRON-job to a less frequent interval is not recommended.
