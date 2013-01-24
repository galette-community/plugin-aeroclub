<?php

/**
 * Display a form to edit or add a plane (with his picture)
 *
 * PHP version 5
 *
 * Copyright © 2011 Mélissa Djebel
 *
 * This file is part of Galette (http://galette.tuxfamily.org).
 *
 * Plugin Pilote is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Plugins
 * @package   GalettePilote
 *
 * @author    Mélissa Djebel <melissa.djebel@gmx.net>
 * @copyright 2011 Mélissa Djebel
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   0.7
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7
 */
define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';
if (!$login->isLogged() || !$login->isAdmin()) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}

// Import des classes de notre plugin
require_once '_config.inc.php';

/**
 * Annulation de l'enregistrement, on revient à la liste
 */
if (array_key_exists('annuler', $_POST)) {
    header('Location: liste_avions.php?msg=pas_enregistre');
}

/**
 * Enregistrement des modifications
 */
if (array_key_exists('modifier', $_POST)) {
    $avion = new PiloteAvion(intval($_POST['avion_id']));
    $avion->nom = $_POST['nom'];
    $avion->nom_court = $_POST['nom_court'];
    $avion->marque_type = $_POST['marque_type'];
    $avion->type_aeronef = $_POST['type_aeronef'];
    $avion->immatriculation = $_POST['immatriculation'];
    $avion->couleur = $_POST['couleur'];
    $avion->nb_places = intval($_POST['nb_places']);
    $avion->cout_horaire = doubleval(str_replace(',', '.', $_POST['cout_horaire']));
    $avion->remarques = $_POST['remarques'];
    $avion->DC_autorisee = $_POST['DC_autorisee'] == '1';
    $avion->est_remorqueur = $_POST['est_remorqueur'] == '1';
    $avion->est_reservable = $_POST['est_reservable'] == '1';
    $avion->store();

    // picture upload
    if (isset($_FILES['photo'])) {
        if ($_FILES['photo']['tmp_name'] != '') {
            if (is_uploaded_file($_FILES['photo']['tmp_name'])) {
                $res = $avion->picture->store($_FILES['photo']);
                if ($res < 0) {
                    switch ($res) {
                        case PiloteAvionPicture::INVALID_FILE:
                            $patterns = array('|%s|', '|%t|');
                            $replacements = array(
                                $avion->picture->getAllowedExts(),
                                htmlentities($avion->picture->getBadChars())
                            );
                            $error_detected[] = preg_replace(
                                    $patterns, $replacements, _T("- Filename or extension is incorrect. Only %s files are allowed. File name should not contains any of: %t")
                            );
                            break;
                        case PiloteAvionPicture::FILE_TOO_BIG:
                            $error_detected[] = preg_replace(
                                    '|%d|', AutoPicture::MAX_FILE_SIZE, _T("File is too big. Maximum allowed size is %d")
                            );
                            break;
                        case PiloteAvionPicture::MIME_NOT_ALLOWED:
                            /** FIXME: should be more descriptive */
                            $error_detected[] = _T("Mime-Type not allowed");
                            break;
                        case PiloteAvionPicture::SQL_ERROR:
                        case PiloteAvionPicture::SQL_BLOB_ERROR:
                            $error_detected[] = _T("An SQL error has occured.");
                            break;
                    }
                }
            }
        }
    }

    //delete photo
    if (isset($_POST['del_photo'])) {
        if (!$avion->picture->delete()) {
            $error_detected[]
                    = _T("An error occured while trying to delete car's photo");
        }
    }

    header('Location: liste_avions.php?msg=enregistre');
}

/**
 * Lecture des infos de l'avion
 */
if (array_key_exists('avion_id', $_GET)) {
    $avion = new PiloteAvion(intval($_GET['avion_id']));
} else {
    $avion = new PiloteAvion();
}

$detail_picture = '';
if ($avion->hasPicture()) {
    $kb = filesize($avion->picture->getPath()) / 1024;
    $detail_picture = $avion->picture->getWidth() . '×' . $avion->picture->getHeight() . ' pixels / ' . round($kb, 1) . ' Ko';
}

$tpl->assign('page_title', _T("MODIFIER AVION.PAGE TITLE"));
//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('avion', $avion);
$tpl->assign('detail_picture', $detail_picture);

$content = $tpl->fetch('modifier_avion.tpl', PILOTE_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PILOTE_SMARTY_PREFIX);
?>
