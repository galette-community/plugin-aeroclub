<?php

/**
 * List all known operations for a pilot.
 * An admin can see operations for an other pilote.
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

// Import des classes de notre plugin
require_once '_config.inc.php';

if (!$login->isLogged() || !(PiloteInstructeur::isPiloteInstructeur($login->login) || $login->isAdmin() || $login->isStaff())) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}

// Récupération du tri
$tri = array_key_exists('tri', $_GET) ? $_GET['tri'] : 'nom';
$direction = array_key_exists('direction', $_GET) ? $_GET['direction'] : 'asc';
switch ($tri) {
    case 'nom':
        $tri_sql = 'nom_adh';
        break;
    case 'prenom':
        $tri_sql = 'prenom_adh';
        break;
    case 'pseudo':
        $tri_sql = 'login_adh';
        break;
    case 'an-1':
        $tri_sql = 'somme2011';
        break;
    case 'an':
        $tri_sql = 'somme2012';
        break;
    case 'glissant':
        $tri_sql = 'sommeglissant';
        break;
}

// Liste des pilotes
$stats = PiloteOperation::getStatistiquesPilotes('all', $tri_sql, $direction);

// Définition taille image + police utilisée
$width = 750;
$height = 30 * count($stats) + 30;
$font_path = 'SourceSansPro-Regular.ttf';

// Nouvelle image
$image = imagecreate($width, $height);

// Couleurs
$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$grey50 = imagecolorallocate($image, 100, 100, 100);
$grey215 = imagecolorallocate($image, 215, 215, 215);
$blue = imagecolorallocate($image, 0, 0, 255);
$purple = imagecolorallocate($image, 131, 0, 146);

// Fond en blanc
imagefill($image, 0, 0, $white);

$max_minute = 10;
$left_border = 30;
// Trace du nom des adhérents actifs
for ($i = 0; $i < count($stats); $i++) {
    if (is_numeric($stats[$i]->somme2011) && $max_minute < $stats[$i]->somme2011) {
        $max_minute = $stats[$i]->somme2011;
    }
    if (is_numeric($stats[$i]->somme2012) && $max_minute < $stats[$i]->somme2012) {
        $max_minute = $stats[$i]->somme2012;
    }
    imagettftext($image, 10, 0, 0, ($height - 30) / count($stats) * $i + 17, $grey50, $font_path, $stats[$i]->login_adh);
    imagettftext($image, 10, 0, 0, ($height - 30) / count($stats) * $i + 27, $grey50, $font_path, $stats[$i]->nom_adh);
    $size = imagettfbbox(10, 0, $font_path, $stats[$i]->nom_adh);
    if ($left_border < $size [2] + 4) {
        $left_border = $size[2] + 4;
    }
}

// Bordure gauche, bas, droite
imageline($image, $left_border, 5, $left_border, $height - 25, $black);
imageline($image, $left_border, $height - 25, $width - 30, $height - 25, $black);
imageline($image, $width - 30, 5, $width - 30, $height - 25, $black);

// Agrandissement de qq % du graphique
$max_minute *= 1.07;

// Trace des ordonnées
for ($i = 1; $i < 10; $i++) {
    imageline($image, ($width - $left_border - 30) / 10 * $i + $left_border, 5, ($width - $left_border - 30) / 10 * $i + $left_border, $height - 26, $grey215);
    $nb_h = floor($max_minute / 10 * $i / 60);
    $nb_m = floor($max_minute / 10 * $i - $nb_h * 60);
    $txt = $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m;
    $size = imagettfbbox(10, 0, $font_path, $txt);
    imagettftext($image, 10, 0, ($width - $left_border - 30) / 10 * $i + $left_border - $size[2] / 2, $height - 12, $grey50, $font_path, $txt);
}
// Max minute
$nb_h = floor($max_minute / 60);
$nb_m = floor($max_minute - $nb_h * 60);
$txt = $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m;
$size = imagettfbbox(10, 0, $font_path, $txt);
imagettftext($image, 10, 0, $width - 30 - $size[2] / 2, $height - 12, $grey50, $font_path, $txt);

// Trace des longueurs adhérents
for ($i = 0; $i < count($stats); $i++) {
    if (is_numeric($stats[$i]->somme2011)) {
        imagefilledrectangle($image, $left_border + 1, ($height - 30) / count($stats) * $i + 7, ($width - $left_border - 30) / $max_minute * $stats[$i]->somme2011 + $left_border, ($height - 30) / count($stats) * $i + 16, $blue);
        $nb_h = floor($stats[$i]->somme2011 / 60);
        $nb_m = $stats[$i]->somme2011 - $nb_h * 60;
        $txt = ($nb_h > 0 ? $nb_h . 'h' : '') . ($nb_m < 10 ? '0' : '') . $nb_m . 'min';
        imagettftext($image, 10, 0, ($width - $left_border - 30) / $max_minute * $stats[$i]->somme2011 + $left_border + 2, ($height - 30) / count($stats) * $i + 17, $blue, $font_path, $txt);
    }
    if (is_numeric($stats[$i]->somme2012)) {
        imagefilledrectangle($image, $left_border + 1, ($height - 30) / count($stats) * $i + 17, ($width - $left_border - 30) / $max_minute * $stats[$i]->somme2012 + $left_border, ($height - 30) / count($stats) * $i + 26, $purple);
        $nb_h = floor($stats[$i]->somme2012 / 60);
        $nb_m = $stats[$i]->somme2012 - $nb_h * 60;
        $txt = ($nb_h > 0 ? $nb_h . 'h' : '') . ($nb_m < 10 ? '0' : '') . $nb_m . 'min';
        imagettftext($image, 10, 0, ($width - $left_border - 30) / $max_minute * $stats[$i]->somme2012 + $left_border + 2, ($height - 30) / count($stats) * $i + 27, $purple, $font_path, $txt);
    }
}

// Modification du header pour indiquer qu'il s'agit d'une image
header("Content-type:image/png");
header("Content-Disposition:inline ; filename=heures_pilote.png");

// Ecriture de l'image en PNG
imagepng($image);
?>
