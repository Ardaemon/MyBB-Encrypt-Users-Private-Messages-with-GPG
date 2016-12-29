<?php  

/* Encrypt MyBB users' private messages with GnuPG

* v. 1.0
* Coded by Ardaemon
* Website: https://github.com/Ardaemon

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.

*/

if(!defined("IN_MYBB"))
{
  die("You Cannot Access This File Directly. Please Make Sure IN_MYBB Is Defined.");
}

function gpgencrypter_info()
{
  return array(
    "name"  => "GPG Encrypter",
    "description"=> "Encrypt private messages with GnuPG",
    "website"        => "https://github.com/Ardaemon",
    "author"        => "Ardaemon",
    "authorsite"    => "https://github.com/Ardaemon",
    "version"        => "1.0",
    "guid"             => "",
    "compatibility" => "18*"
  )
}

funtion gpgencrypter_install()
{
  global $db;
  
  if(!$db->field_exists('pgp_public_key', 'users'))
    $db->write_query("ALTER TABLE `".TABLE_PREFIX."users` ADD `pgp_public_key` text;");
}

function gpgencrypter_is_installed()
{
  global $db;
  
  if($db->field_exists('pgp_public_key', 'users'))
    return true;

  else
    return false;
}

function gpgencryter_uninstall()
{
  global $db;

  if($db->field_exists('pgp_public_key', 'users'))
    $db->("ALTER TABLE `".TABLE_PREFIX."users` DROP `pgp_public_key`;");
}

/*
function get_user_pubkey($uid)
{
  global $db;

  $query = $db->query("SELECT `pgp_public_key` FROM `".TABLE_PREFIX."users WHERE uid=".$intval($uid));
  return $db->write_query($query);
}
*/

function gpgencrypter_get_fingerprint($pubkey)
{
  $gpg = new gnupg();
  
  $importedkey = $gpg->import($pubkey);
  return $importedkey['fingerprint'];
}

function gpgencryter_encrypt_message($fingerprint, $message)
{
  $gpg = new gnupg();

  $gpg->addencryptkey($fingerprint);
  $encmessage = $gpg->encrypt($message);

  return $message
}