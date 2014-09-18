<?php
define("DBO_TECHNOLOGY","mysql");                                                 # Database technology (only mysql is supported at this time)
define("DBO_HOST","localhost");                                                   # Database hostname
define("DBO_USER","username");                                                    # Database user
define("DBO_PASSWORD","password");                                                # Database user password
define("DBO_DB","database");                                                      # Database name
define("DBO_TBL_PREFIX","wmn");                                                   # Set a prefix for tables ( '_' will be appended before the actual table names

define("DEFAULT_LANGUAGE","en-uk");                                               # Set language for app
define("DEFAULT_LOCALE","en-uk");                                                 # Set locale for app
define("DEFAULT_TIMEZONE","Europe/Brussels");                                     # Set timezone

define("SSL_FORCE",false);                                                        # force https (or not)

define("LOG_LEVEL","debug");                                                      # Set log level (debig, info, warning, error)

define("FS_ROOT","/path/to/website/root");                                        # filesystem path to the root of the app
define("WW_ROOT","http" .(isset($_SERVER['HTTPS'])?"s":""). "://domain.tld/");    # Change this to match the URL you want for the app


/* uncomment to go into maintenance mode */
//define("MAINTENANCE",true);
//define("MAINTENANCE_MSG","The application will be down for maintenance until 2014/03/12 @ 14:00");
?>
