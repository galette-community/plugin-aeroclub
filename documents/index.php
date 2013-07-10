<?php

/* * *************************************************************************
 *
 *             Encode Explorer
 *
 *             Author / Autor : Marek Rei (marek ät siineiolekala dot net)
 *
 *             Version / Versioon : 6.3
 *
 *             Last change / Viimati muudetud: 23.09.2011
 *
 *             Homepage / Koduleht: encode-explorer.siineiolekala.net
 *
 *
 *             NB!: Comments are in english.
 *                  Comments needed for configuring are in both estonian and english.
 *                  If you change anything, save with UTF-8! Otherwise you may
 *                  encounter problems, especially when displaying images.
 *                  
 *             NB!: Kommentaarid on inglise keeles.
 *                  Seadistamiseks vajalikud kommentaarid on eesti ja inglise keeles.
 *                  Kui midagi muudate, salvestage UTF-8 formaati! Vastasel juhul
 *                  võivad probleemid tekkida, eriti piltide kuvamisega.
 *
 * ************************************************************************* */

/* * *************************************************************************
 *
 *   This is free software and it's distributed under GPL Licence.
 *
 *   Encode Explorer is written in the hopes that it can be useful to people.
 *   It has NO WARRANTY and when you use it, the author is not responsible
 *   for how it works (or doesn't).
 *   
 *   The icon images are designed by Mark James (http://www.famfamfam.com) 
 *   and distributed under the Creative Commons Attribution 3.0 License.
 *
 * ************************************************************************* */

/* * ************************************************************************ */
/*   SIIN ON SEADED                                                        */
/*                                                                         */
/*   HERE ARE THE SETTINGS FOR CONFIGURATION                               */
/* * ************************************************************************ */

//
// Algväärtustame muutujad. Ära muuda.
//
// Initialising variables. Don't change these.
//

$_CONFIG = array();
$_ERROR = "";
$_START_TIME = microtime(TRUE);

/*
 * GENERAL SETTINGS
 */

//
// Vali sobiv keel. Allpool on näha võimalikud valikud. Vaikimisi: en
//
// Choose a language. See below in the language section for options.
// Default: $_CONFIG['lang'] = "en";
//
$_CONFIG['lang'] = "fr";

//
// Kuva pildifailidele eelvaated. Vaikimisi: true
// Levinumad pildifailide tüübid on toetatud (jpeg, png, gif).
// Pdf failid on samuti toetatud kuid ImageMagick peab olema paigaldatud.
//
// Display thumbnails when hovering over image entries in the list.
// Common image types are supported (jpeg, png, gif).
// Pdf files are also supported but require ImageMagick to be installed.
// Default: $_CONFIG['thumbnails'] = true;
//
$_CONFIG['thumbnails'] = true;

//
// Eelvaadete maksimumsuurused pikslites. Vaikimisi: 200, 200
//
// Maximum sizes of the thumbnails.
// Default: $_CONFIG['thumbnails_width'] = 200;
// Default: $_CONFIG['thumbnails_height'] = 200;
//
$_CONFIG['thumbnails_width'] = 300;
$_CONFIG['thumbnails_height'] = 300;

//
// Mobillidele mõeldud kasutajaliides. true/false
// Vaikimisi: true
// 
// Mobile interface enabled. true/false
// Default: $_CONFIG['mobile_enabled'] = true;
//
$_CONFIG['mobile_enabled'] = false;

//
// Mobiilidele mõeldud kasutajaliides avaneb automaatselt. true/false
// Vaikimisi: false
//
// Mobile interface as the default setting. true/false
// Default: $_CONFIG['mobile_default'] = false;
//
$_CONFIG['mobile_default'] = false;

/*
 * USER INTERFACE
 */

//
// Kas failid avatakse uues aknas? true/false
//
// Will the files be opened in a new window? true/false 
// Default: $_CONFIG['open_in_new_window'] = false;
//
$_CONFIG['open_in_new_window'] = true;

//
// Kui sügavalt alamkataloogidest suurust näitav script faile otsib? 
// Määra see nullist suuremaks, kui soovid kogu kasutatud ruumi suurust kuvada.
// Vaikimisi: 0
//
// How deep in subfolders will the script search for files? 
// Set it larger than 0 to display the total used space.
// Default: $_CONFIG['calculate_space_level'] = 0;
//
$_CONFIG['calculate_space_level'] = 1;

//
// Kas kuvatakse lehe päis? true/false
// Vaikimisi: true;
//
// Will the page header be displayed? 0=no, 1=yes. 
// Default: $_CONFIG['show_top'] = true;
//
$_CONFIG['show_top'] = true;

//
// Veebilehe pealkiri
//
// The title for the page
// Default: $_CONFIG['main_title'] = "Encode Explorer";
//
$_CONFIG['main_title'] = "Documentation ACM";

//
// Pealkirjad, mida kuvatakse lehe päises, suvalises järjekorras.
//
// The secondary page titles, randomly selected and displayed under the main header.
// For example: $_CONFIG['secondary_titles'] = array("Secondary title", "&ldquo;Secondary title with quotes&rdquo;");
// Default: $_CONFIG['secondary_titles'] = array();
//
$_CONFIG['secondary_titles'] = array("A l'intention des pilotes");

//
// Kuva asukoht kataloogipuus. true/false
// Vaikimisi: true
//
// Display breadcrumbs (relative path of the location).
// Default: $_CONFIG['show_path'] = true;
//
$_CONFIG['show_path'] = false;

//
// Kuva lehe laadimise aega. true/false
// Vaikimisi: false
// 
// Display the time it took to load the page.
// Default: $_CONFIG['show_load_time'] = true;
//
$_CONFIG['show_load_time'] = true;

//
// Formaat faili muutmise aja kuvamiseks.
//
// The time format for the "last changed" column.
// Default: $_CONFIG['time_format'] = "d.m.y H:i:s";
//
$_CONFIG['time_format'] = "d.m.y H:i:s";

//
// Kodeering, mida lehel kasutatakse. 
// Tuleb panna sobivaks, kui täpitähtedega tekib probleeme. Vaikimisi: UTF-8
//
// Charset. Use the one that suits for you. 
// Default: $_CONFIG['charset'] = "UTF-8";
//
$_CONFIG['charset'] = "UTF-8";

/*
 * PERMISSIONS
 */

//
// Kaustade varjamine. Kaustade nimed mida lehel ei kuvata.
// Näiteks: CONFIG['hidden_dirs'] = array("ikoonid", "kaustanimi", "teinekaust");
//
// The array of folder names that will be hidden from the list.
// Default: $_CONFIG['hidden_dirs'] = array();
//
$_CONFIG['hidden_dirs'] = array();

//
// Failide varjamine. Failide nimed mida lehel ei kuvata.
// NB! Märgitud nimega failid ja kaustad varjatakse kõigis alamkaustades.
//
// Filenames that will be hidden from the list.
// Default: $_CONFIG['hidden_files'] = array(".ftpquota", "index.php", "index.php~", ".htaccess", ".htpasswd");
//
$_CONFIG['hidden_files'] = array(".ftpquota",
    "index.php",
    "index.php~",
    ".htaccess",
    ".htpasswd",
    "i18n.php",
    "css.php",
    "images.php",
    "classImageServer.php",
    "classLogger.php",
    "classGateKeeper.php",
    "classFileManager.php",
    "classDir.php",
    "classFile.php",
    "classEncodeExplorer.php",
    "classLocation.php");
//
// Määra kas lehe nägemiseks peab sisse logima.
// 'false' tähendab, et leht on avalik
// 'true' tähendab, et kasutaja peab sisestama parooli (vaata allpool).
//
// Whether authentication is required to see the contents of the page.
// If set to false, the page is public.
// If set to true, you should specify some users as well (see below).
// Important: This only prevents people from seeing the list.
// They will still be able to access the files with a direct link.
// Default: $_CONFIG['require_login'] = false;
//
$_CONFIG['require_login'] = false;

//
// Kasutajanimed ja paroolid, lehele ligipääsu piiramiseks.
// Näiteks: $_CONFIG['users'] = array(array("user1", "pass1"), array("user2", "pass2"));
// Võimalik lehte kaitsta ka ainult üldise parooliga.
// Näiteks: $_CONFIG['users'] = array(array(null, "pass"));
// Kui ühtegi kasutajat märgitud ei ole, siis parooli ei küsita.
//
// Usernames and passwords for restricting access to the page.
// The format is: array(username, password, status)
// Status can be either "user" or "admin". User can read the page, admin can upload and delete.
// For example: $_CONFIG['users'] = array(array("username1", "password1", "user"), array("username2", "password2", "admin"));
// You can also keep require_login=false and specify an admin. 
// That way everyone can see the page but username and password are needed for uploading.
// For example: $_CONFIG['users'] = array(array("username", "password", "admin"));
// Default: $_CONFIG['users'] = array();
//
// $_CONFIG['users'] = array();
$_CONFIG['users'] = array(array("ACM_document", "3f6c3e2l", "admin"));
//
// Seaded uploadimiseks, uute kaustade loomiseks ja kustutamiseks.
// Valikud kehtivad ainult andmin kontode jaoks, tavakasutajatel pole need kunagi lubatud.
//
// Permissions for uploading, creating new directories and deleting.
// They only apply to admin accounts, regular users can never perform these operations.
// Default:
// $_CONFIG['upload_enable'] = true;
// $_CONFIG['newdir_enable'] = true;
// $_CONFIG['delete_enable'] = false;
//
$_CONFIG['upload_enable'] = true;
$_CONFIG['newdir_enable'] = true;
$_CONFIG['delete_enable'] = true;

/*
 * UPLOADING
 */

//
// Nimekiri kaustadest kuhu on lubatud uploadida ja uusi kaustu luua.
// Näiteks: $_CONFIG['upload_dirs'] = array("./myuploaddir1/", "./mydir/upload2/");
// Kausta asukoht peab olema määratud põhikausta suhtes, algama "./" ja lõppema "/" märgiga.
// Kõik kaustad märgitute all on automaatselt kaasa arvatud.
// Kui nimekiri on tühi (vaikimisi), siis on kõikidesse kaustadesse failide lisamine lubatud.
//
// List of directories where users are allowed to upload. 
// For example: $_CONFIG['upload_dirs'] = array("./myuploaddir1/", "./mydir/upload2/");
// The path should be relative to the main directory, start with "./" and end with "/".
// All the directories below the marked ones are automatically included as well.
// If the list is empty (default), all directories are open for uploads, given that the password has been set.
// Default: $_CONFIG['upload_dirs'] = array();
//
$_CONFIG['upload_dirs'] = array();

//
// MIME failitüübid mis on uploadimiseks lubatud.
// Näiteks: $_CONFIG['upload_allow_type'] = array("image/png", "image/gif", "image/jpeg");
//
// MIME type that are allowed to be uploaded.
// For example, to only allow uploading of common image types, you could use:
// $_CONFIG['upload_allow_type'] = array("image/png", "image/gif", "image/jpeg");
// Default: $_CONFIG['upload_allow_type'] = array();
//
$_CONFIG['upload_allow_type'] = array();

//
// Uploadimiseks keelatud faililaiendid
//
// File extensions that are not allowed for uploading.
// For example: $_CONFIG['upload_reject_extension'] = array("php", "html", "htm");
// Default: $_CONFIG['upload_reject_extension'] = array();
//
$_CONFIG['upload_reject_extension'] = array("php");

/*
 * LOGGING
 */

//
// Failide lisamisest teatamise e-maili aadress.
// Kui määratud, siis saadetakse sellele aadressile e-mail iga kord kui keegi lisab uue faili või kausta.
//
// Upload notification e-mail.
// If set, an e-mail will be sent every time someone uploads a file or creates a new dirctory.
// Default: $_CONFIG['upload_email'] = "";
//
$_CONFIG['upload_email'] = "";

//
// Logifail. Kui määratud, siis kirjutatakse kaustade ja failide avamise kohta logi faili.
// Näiteks: $_CONFIG['log_file'] = ".log.txt";
//
// Logfile name. If set, a log line will be written there whenever a directory or file is accessed.
// For example: $_CONFIG['log_file'] = ".log.txt";
// Default: $_CONFIG['log_file'] = "";
//
$_CONFIG['log_file'] = "";

/*
 * SYSTEM
 */

//
// Algkataloogi suhteline aadress. Reeglina ei ole vaja muuta. 
// Kasutage ainult suhtelisi alamkatalooge!
// Vaikimisi: .
//
// The starting directory. Normally no need to change this.
// Use only relative subdirectories! 
// For example: $_CONFIG['starting_dir'] = "./mysubdir/";
// Default: $_CONFIG['starting_dir'] = ".";
//
$_CONFIG['starting_dir'] = ".";

//
// Asukoht serveris. Tavaliselt ei ole vaja siia midagi panna kuna script leiab ise õige asukoha. 
// Mõnes serveris tuleb piirangute tõttu see aadress ise teistsuguseks määrata.
// See fail peaks asuma serveris aadressil [AADRESS]/index.php
// Aadress võib olla näiteks "/www/data/www.minudomeen.ee/minunimi"
//
// Location in the server. Usually this does not have to be set manually.
// Default: $_CONFIG['basedir'] = "";
//
$_CONFIG['basedir'] = "";

//
// Suured failid. Kui sul on failiruumis väga suured failid (>4GB), siis on see vajalik
// faili suuruse õigeks määramiseks. Vaikimisi: false
//
// Big files. If you have some very big files (>4GB), enable this for correct
// file size calculation.
// Default: $_CONFIG['large_files'] = false;
//
$_CONFIG['large_files'] = false;

//
// Küpsise/sessiooni nimi. 
// Anna sellele originaalne väärtus kui soovid samas ruumis kasutada mitut koopiat
// ning ei taha, et toimuks andmete jagamist nende vahel.
// Väärtus tohib sisaldada ainult tähti ja numbreid. Näiteks: MYSESSION1
//
// The session name, which is used as a cookie name. 
// Change this to something original if you have multiple copies in the same space
// and wish to keep their authentication separate. 
// The value can contain only letters and numbers. For example: MYSESSION1
// More info at: http://www.php.net/manual/en/function.session-name.php
// Default: $_CONFIG['session_name'] = "";
//
$_CONFIG['session_name'] = "";

require_once 'i18n.php';
require_once 'css.php';
require_once 'images.php';

/* * ************************************************************************ */
/*   EDASIST KOODI EI OLE TARVIS MUUTA                                     */
/*                                                                         */
/*   HERE COMES THE CODE.                                                  */
/*   DON'T CHANGE UNLESS YOU KNOW WHAT YOU ARE DOING ;)                    */
/* * ************************************************************************ */

require_once 'classImageServer.php';
require_once 'classLogger.php';
require_once 'classGateKeeper.php';
require_once 'classFileManager.php';
require_once 'classDir.php';
require_once 'classFile.php';
require_once 'classLocation.php';
require_once 'classEncodeExplorer.php';

//
// This is where the system is activated. 
// We check if the user wants an image and show it. If not, we show the explorer.
//
$encodeExplorer = new EncodeExplorer();
$encodeExplorer->init();

GateKeeper::init();

if (!ImageServer::showImage() && !Logger::logQuery()) {
    $location = new Location();
    $location->init();
    if (GateKeeper::isAccessAllowed()) {
        Logger::logAccess($location->getDir(true, false, false, 0), true);
        $fileManager = new FileManager();
        $fileManager->run($location);
    }
    $encodeExplorer->run($location);
}
?>
