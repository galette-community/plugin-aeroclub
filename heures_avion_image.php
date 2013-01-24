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

// Définition taille image + police utilisée
$width = 650;
$height = 450;
$font_path = 'SourceSansPro-Regular.ttf';

// Nouvelle image
$image = imagecreate($width, $height);

// Couleurs
$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$blue = imagecolorallocate($image, 0, 0, 255);
$grey50 = imagecolorallocate($image, 100, 100, 100);
$grey215 = imagecolorallocate($image, 215, 215, 215);
$couleurs_avions = array();
$couleurs_avions[] = imagecolorallocate($image, 31, 73, 125);
$couleurs_avions[] = imagecolorallocate($image, 79, 129, 189);
$couleurs_avions[] = imagecolorallocate($image, 192, 80, 77);
$couleurs_avions[] = imagecolorallocate($image, 155, 187, 89);
$couleurs_avions[] = imagecolorallocate($image, 128, 100, 162);
$couleurs_avions[] = imagecolorallocate($image, 75, 172, 198);
$couleurs_avions[] = imagecolorallocate($image, 247, 150, 70);
$couleurs_avions[] = imagecolorallocate($image, 0, 176, 80);

// Fond en blanc
imagefill($image, 0, 0, $white);

$max_minute = 10;
$right_border = 30;

// Récupération des statistiques
$avion = PiloteOperation::getAvionsActifs();
$stats = array();
for ($i = 0; $i < count($avion); $i++) {
    // Largeur 
    $size = imagettfbbox(10, 0, $font_path, $avion[$i]);
    if ($size[2] + 20 > $right_border) {
        $right_border = $size[2] + 20;
    }
    // Récupération sur les 11 derniers mois
    for ($m = -11; $m <= 0; $m++) {
        $stats[$avion[$i]][$m] = PiloteOperation::getDureeVolPourAvion($avion[$i], intval(date('m', strtotime($m . ' months'))), intval(date('Y', strtotime($m . ' months'))));
        if (is_numeric($stats[$avion[$i]][$m]) && $stats[$avion[$i]][$m] > $max_minute) {
            $max_minute = $stats[$avion[$i]][$m];
        }
    }
}

// Elargissement de la zone
$max_minute *= 1.08;

for ($i = 0; $i < count($avion); $i++) {
    imagefilledellipse($image, $width - $right_border + 8, 45 + $i * 25, 10, 10, $couleurs_avions[$i]);
    imagettftext($image, 10, 0, $width - $right_border + 15, 50 + $i * 25, $couleurs_avions[$i], $font_path, $avion[$i]);
}

// Bordure gauche, bas, droite
imageline($image, 38, 5, 38, $height - 25, $black);
imageline($image, 38, $height - 25, $width - $right_border, $height - 25, $black);
imageline($image, $width - $right_border, 5, $width - $right_border, $height - 25, $black);

// Trace du quadrillage horizontal
for ($i = 0; $i < 10; $i++) {
    // Trace valeurs Ordonnées Minutes/mois à gauche en bleu
    $min_tot = intval($max_minute / 10 * (10 - $i));
    $nb_h = floor($min_tot / 60);
    $nb_m = $min_tot - $nb_h * 60;
    imagettftext($image, 10, 0, 0, 15 + ($height - 30) / 10 * $i, $blue, $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);

    // Ligne quadrillage horizontale en gris
    imageline($image, 39, 10 + ($height - 30) / 10 * $i, $width - $right_border - 1, 10 + ($height - 30) / 10 * $i, $grey215);
}

// Trace du quadrillage vertical
for ($i = 1; $i <= 12; $i++) {
    imageline($image, ($width - $right_border - 38) / 13 * $i + 38, 5, ($width - $right_border - 38) / 13 * $i + 38, $height - 26, $grey215);
    $size = imagettfbbox(10, 0, $font_path, date('M', strtotime(($i - 12) . ' months')));
    imagettftext($image, 10, 0, ($width - $right_border - 38) / 13 * $i + 38 - $size[2] / 2, $height - 12, $blue, $font_path, date('M', strtotime(($i - 12) . ' months')));
    $size = imagettfbbox(10, 0, $font_path, date('Y', strtotime(($i - 12) . ' months')));
    imagettftext($image, 10, 0, ($width - $right_border - 38) / 13 * $i + 38 - $size[2] / 2, $height - 1, $blue, $font_path, date('Y', strtotime(($i - 12) . ' months')));
}

$old_minute = array();
for ($i = 0; $i < count($avion); $i++) {
    for ($m = -11; $m <= 0; $m++) {
        if ($m > -11 && is_numeric($old_minute[$i]) && is_numeric($stats[$avion[$i]][$m])) {
            imageline($image, ($width - $right_border - 38) / 13 * ($m + 11) + 38, $height - 25 - ($height - 30) / $max_minute * $old_minute[$i], ($width - $right_border - 38) / 13 * ($m + 12) + 38, $height - 25 - ($height - 30) / $max_minute * $stats[$avion[$i]][$m], $couleurs_avions[$i]);
            imageline($image, ($width - $right_border - 38) / 13 * ($m + 11) + 39, $height - 25 - ($height - 30) / $max_minute * $old_minute[$i], ($width - $right_border - 38) / 13 * ($m + 12) + 39, $height - 25 - ($height - 30) / $max_minute * $stats[$avion[$i]][$m], $couleurs_avions[$i]);
            imageline($image, ($width - $right_border - 38) / 13 * ($m + 11) + 37, $height - 25 - ($height - 30) / $max_minute * $old_minute[$i], ($width - $right_border - 38) / 13 * ($m + 12) + 37, $height - 25 - ($height - 30) / $max_minute * $stats[$avion[$i]][$m], $couleurs_avions[$i]);
            imageline($image, ($width - $right_border - 38) / 13 * ($m + 11) + 38, $height - 24 - ($height - 30) / $max_minute * $old_minute[$i], ($width - $right_border - 38) / 13 * ($m + 12) + 38, $height - 24 - ($height - 30) / $max_minute * $stats[$avion[$i]][$m], $couleurs_avions[$i]);
            imageline($image, ($width - $right_border - 38) / 13 * ($m + 11) + 38, $height - 26 - ($height - 30) / $max_minute * $old_minute[$i], ($width - $right_border - 38) / 13 * ($m + 12) + 38, $height - 26 - ($height - 30) / $max_minute * $stats[$avion[$i]][$m], $couleurs_avions[$i]);
        }
        if (is_numeric($stats[$avion[$i]][$m])) {
            // Point temps vol
            imagefilledellipse($image, ($width - $right_border - 38) / 13 * ($m + 12) + 38, $height - 25 - ($height - 30) / $max_minute * $stats[$avion[$i]][$m], 8, 8, $couleurs_avions[$i]);
            $old_minute[$i] = $stats[$avion[$i]][$m];
            // Texte durée vol
            $nb_h = floor($old_minute[$i] / 60);
            $nb_m = $old_minute[$i] - $nb_h * 60;
            imagettftext($image, 10, -45, ($width - $right_border - 38) / 13 * ($m + 12) + 15, $height - 55 - ($height - 30) / $max_minute * $old_minute[$i], $couleurs_avions[$i], $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);
        }
    }
}

// Modification du header pour indiquer qu'il s'agit d'une image
header("Content-type:image/png");
header("Content-Disposition:inline ; filename=heures_avion.png");

// Ecriture de l'image en PNG
imagepng($image);
?>
