<?php
//global variables
//system
define( "SYSTEM_URL", "domain.com/" ); // (domain.com/ or domain.com/backup/)
define( "SYSTEM_NAME", "Backup Utility" );
define( "SSL", "http://" ); // (http:// or https://)
define( "FULL_URL", SSL . SYSTEM_URL );
define( "CRON_ENABLED", true ); // ( true or false )
define( "REQUIRE_AUTHENTICATION", true ); // ( true or false )
define( "DB_FILENAME", "data.sqlite" );
//email
define( "EMAIL_FROM", "email@domain.com" );
define( "EMAIL_HOST", "mail.domain.com" );
define( "EMAIL_PORT", 25 );
define( "EMAIL_USERNAME", "email@domain.com" );
define( "EMAIL_PASSWORD", "password" );
//server settings
date_default_timezone_set('America/Indiana/Indianapolis');
ini_set("log_errors" , "1");
ini_set("display_errors" , "0");
?>