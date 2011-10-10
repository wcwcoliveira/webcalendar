<?php // $Id$
/**
 * This page handles logins for nonuser calendars.
 */

foreach( array(
    'access',
    'config',
    'dbi4php',
    'formvars',
    'functions',
    'translate',
  ) as $i ) {
  include_once 'includes/' . $i . '.php';
}
require_once 'includes/classes/WebCalendar.class';

$WebCalendar = new WebCalendar( __FILE__ );
$WebCalendar->initializeFirstPhase();

include 'includes/' . $user_inc;
include 'includes/gradient.php';

$WebCalendar->initializeSecondPhase();

load_global_settings();

$WebCalendar->setLanguage();

if ( $single_user == 'Y'/* No login for single-user mode.*/ ||
    $use_http_auth )/* No web login for HTTP-based authentication.*/
  die_miserable_death ( print_not_auth() );

$login = getValue ( 'login' );
if ( empty ( $login ) )
  die_miserable_death( translate( 'must specify login' ) );

$date = getValue ( 'date' );
$return_path = getValue ( 'return_path' );
// Was a return path set?
$url = ( ! empty ( $return_path )
  ? clean_whitespace ( $return_path
     . ( ! empty ( $date ) ? '?date=' . $date : '' ) )
  : 'index.php' );

if ( $login == '__public__' )
  do_redirect ( $url );

if ( ! nonuser_load_variables ( $login, 'temp_' ) )
  die_miserable_death ( translate ( 'No such nonuser calendar' )
     . ": $login" );

if ( empty ( $temp_is_public ) || $temp_is_public != 'Y' )
  die_miserable_death ( print_not_auth() );
// calculate path for cookie
if ( empty ( $PHP_SELF ) )
  $PHP_SELF = $_SERVER['PHP_SELF'];

$cookie_path = str_replace ( 'nulogin.php', '', $PHP_SELF );
// echo "Cookie path: $cookie_path\n";
if ( get_magic_quotes_gpc() )
  $login = stripslashes ( $login );

$login = trim ( $login );
$badLoginStr = translate ( 'Illegal chars in login XXX' );

if ( $login != addslashes ( $login ) )
  die_miserable_death (
    str_replace ( 'XXX', htmlentities ( $login ), $badLoginStr ) );

// Allow proper login using NUC name
$encoded_login = encode_string ( $login . '|nonuser' );

// set login to expire in 365 days
setcookie( 'webcalendar_session', $encoded_login,
  ( ! empty ( $remember ) && $remember == 'yes' ?
  31536000 + time() : 0 ), $cookie_path );

do_redirect ( $url );

?>
