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
$width = 700;
$height = 450;
$font_path = 'SourceSansPro-Regular.ttf';
$annees = array_key_exists('annee', $_GET) ? $_GET['annee'] : array();
$avion = array_key_exists('immat', $_GET) ? $_GET['immat'] : '#REF!';

// Nouvelle image
$image = imagecreate($width, $height);

// Couleurs
$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$blue = imagecolorallocate($image, 0, 0, 255);
$violet = imagecolorallocate($image, 128, 0, 128);
$grey50 = imagecolorallocate($image, 100, 100, 100);
$grey215 = imagecolorallocate($image, 215, 215, 215);
$orange = imagecolorallocate($image, 255, 182, 25);
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
$max_cumul_minute = 10;
// Initialisation largeur marge droite avec largeur titre immatriculation
if ($avion == 'all') {
    $size = imagettfbbox(11, 0, $font_path, 'Tous avions');
} else {
    $size = imagettfbbox(11, 0, $font_path, $avion);
}
$right_border = $size[2] + 15;

// Calcul largeur marge droite
for ($i = 0; $i < count($annees); $i++) {
    $size = imagettfbbox(10, 0, $font_path, $annees[$i]);
    if ($size[2] + 15 > $right_border) {
        $right_border = $size[2] + 15;
    }
}

// Récupération des statistiques
$stats = array();
$cumul = array();
for ($j = 0; $j < count($annees); $j++) {
    $cumul[0][$annees[$j]] = 0;
}
// Récupération sur les années sélectionnées des 12 mois de l'année
for ($m = 1; $m <= 12; $m++) {
    for ($j = 0; $j < count($annees); $j++) {
        if ($avion == 'all') {
            $stats[$m][$annees[$j]] = PiloteOperation::getDureeVolPourTousAvions($m, intval($annees[$j]));
        } else {
            $stats[$m][$annees[$j]] = PiloteOperation::getDureeVolPourAvion($avion, $m, intval($annees[$j]));
        }
        if (is_numeric($stats[$m][$annees[$j]]) && $stats[$m][$annees[$j]] > $max_minute) {
            $max_minute = $stats[$m][$annees[$j]];
        }

        $cumul[$m][$annees[$j]] = $cumul[$m - 1][$annees[$j]];
        if (is_numeric($stats[$m][$annees[$j]])) {
            $cumul[$m][$annees[$j]] += $stats[$m][$annees[$j]];
        }
        if ($cumul[$m][$annees[$j]] > $max_cumul_minute) {
            $max_cumul_minute = $cumul[$m][$annees[$j]];
        }
    }
}

// Elargissement de la zone
$max_minute *= 1.09;
$max_cumul_minute *= 1.13;

// Légende année à droite
if ($avion == 'all') {
    imagettftext($image, 11, 0, $width - $right_border + 2, 25, $orange, $font_path, 'Tous avions');
} else {
    imagettftext($image, 11, 0, $width - $right_border + 2, 25, $orange, $font_path, $avion);
}
for ($i = 0; $i < count($annees); $i++) {
    // Couleurs pour les barres de mois
    imagefilledrectangle($image, $width - $right_border + 3, 40 + $i * 25, $width - $right_border + 12, 50 + $i * 25, $couleurs_avions[$i]);
    imagettftext($image, 10, 0, $width - $right_border + 18, 50 + $i * 25, $couleurs_avions[$i], $font_path, $annees[$i]);

    // Couleurs pour les lignes de cumul
    imagefilledellipse($image, $width - $right_border + 8, 120 + $i * 25, 10, 10, $couleurs_avions[$i + 3]);
    imagettftext($image, 10, 0, $width - $right_border + 18, 125 + $i * 25, $couleurs_avions[$i + 3], $font_path, $annees[$i]);
}

// Bordure gauche, bas, droite
imageline($image, 45, 5, 45, $height - 25, $black);
imageline($image, 45, $height - 25, $width - $right_border, $height - 25, $black);
imageline($image, $width - $right_border, 5, $width - $right_border, $height - 25, $black);

// Trace du quadrillage horizontal
for ($i = 0; $i < 10; $i++) {
    // Trace valeurs Ordonnées Minutes/mois à gauche en bleu
    $min_tot = intval($max_minute / 10 * (10 - $i));
    $nb_h = floor($min_tot / 60);
    $nb_m = $min_tot - $nb_h * 60;
    imagettftext($image, 10, 0, 0, 22 + ($height - 30) / 10 * $i, $blue, $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);

    // Trace valeurs Ordonnées Minutes/mois à gauche en bleu
    $min_tot = intval($max_cumul_minute / 10 * (10 - $i));
    $nb_h = floor($min_tot / 60);
    $nb_m = $min_tot - $nb_h * 60;
    imagettftext($image, 10, 0, 0, 11 + ($height - 30) / 10 * $i, $violet, $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);

    // Ligne quadrillage horizontale en gris
    imageline($image, 46, 10 + ($height - 30) / 10 * $i, $width - $right_border - 1, 10 + ($height - 30) / 10 * $i, $grey215);
}

// Trace du quadrillage vertical
for ($i = 1; $i <= 12; $i++) {
    imageline($image, ($width - $right_border - 45) / 13 * $i + 45, 5, ($width - $right_border - 45) / 13 * $i + 45, $height - 26, $grey215);
    $size = imagettfbbox(10, 0, $font_path, date('M', strtotime('2012-' . ($i < 10 ? '0' : '') . $i . '-01')));
    imagettftext($image, 10, 0, ($width - $right_border - 45) / 13 * $i + 45 - $size[2] / 2, $height - 8, $blue, $font_path, date('M', strtotime('2012-' . ($i < 10 ? '0' : '') . $i . '-01')));
}

// Trace du graphique
$old_minute = array();
for ($j = 0; $j < count($annees); $j++) {
    $old_minute[$j] = $cumul[1][$annees[$j]];
}
for ($j = 0; $j < count($annees); $j++) {
    for ($m = 1; $m <= 12; $m++) {
        if (is_numeric($stats[$m][$annees[$j]])) {
            // Barre durée vol
            imagefilledrectangle($image, ($width - $right_border - 45) / 13 * $m + $j * 15 + 19, $height - 26, ($width - $right_border - 45) / 13 * $m + 13 + $j * 15 + 19, $height - 25 - ($height - 30) / $max_minute * $stats[$m][$annees[$j]], $couleurs_avions[$j]);
            // Texte durée vol
            $nb_h = floor($stats[$m][$annees[$j]] / 60);
            $nb_m = $stats[$m][$annees[$j]] - $nb_h * 60;
            $size = imagettfbbox(10, 270, $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);
            imagettftext($image, 10, 270, ($width - $right_border - 45) / 13 * $m + $j * 15 + 21, $height - 28 - $size[3] - ($height - 30) / $max_minute * $stats[$m][$annees[$j]], $couleurs_avions[$j], $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);
        }
        // Point de cumul
        imagefilledellipse($image, ($width - $right_border - 45) / 13 * $m + 45, $height - 25 - ($height - 30) / $max_cumul_minute * $cumul[$m][$annees[$j]], 10, 10, $couleurs_avions[$j + 3]);
        // Texte durée vol
        if ($cumul[$m][$annees[$j]] > 0) {
            $nb_h = floor($cumul[$m][$annees[$j]] / 60);
            $nb_m = $cumul[$m][$annees[$j]] - $nb_h * 60;
            $size = imagettfbbox(10, 270, $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);
            imagettftext($image, 10, 310, ($width - $right_border - 45) / 13 * $m + 50 - $size[3], $height - 18 - $size[3] - ($height - 30) / $max_cumul_minute * $cumul[$m][$annees[$j]], $couleurs_avions[$j + 3], $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);
        }

        if ($m > 1) {
            // Ligne entre les points quand mois > 1
            imageline($image, ($width - $right_border - 45) / 13 * ($m - 1) + 45, $height - 25 - ($height - 30) / $max_cumul_minute * $old_minute[$j], ($width - $right_border - 45) / 13 * $m + 45, $height - 25 - ($height - 30) / $max_cumul_minute * $cumul[$m][$annees[$j]], $couleurs_avions[$j + 3]);
            imageline($image, ($width - $right_border - 45) / 13 * ($m - 1) + 46, $height - 25 - ($height - 30) / $max_cumul_minute * $old_minute[$j], ($width - $right_border - 45) / 13 * $m + 46, $height - 25 - ($height - 30) / $max_cumul_minute * $cumul[$m][$annees[$j]], $couleurs_avions[$j + 3]);
            imageline($image, ($width - $right_border - 45) / 13 * ($m - 1) + 44, $height - 25 - ($height - 30) / $max_cumul_minute * $old_minute[$j], ($width - $right_border - 45) / 13 * $m + 44, $height - 25 - ($height - 30) / $max_cumul_minute * $cumul[$m][$annees[$j]], $couleurs_avions[$j + 3]);
            imageline($image, ($width - $right_border - 45) / 13 * ($m - 1) + 45, $height - 24 - ($height - 30) / $max_cumul_minute * $old_minute[$j], ($width - $right_border - 45) / 13 * $m + 45, $height - 24 - ($height - 30) / $max_cumul_minute * $cumul[$m][$annees[$j]], $couleurs_avions[$j + 3]);
            imageline($image, ($width - $right_border - 45) / 13 * ($m - 1) + 45, $height - 26 - ($height - 30) / $max_cumul_minute * $old_minute[$j], ($width - $right_border - 45) / 13 * $m + 45, $height - 26 - ($height - 30) / $max_cumul_minute * $cumul[$m][$annees[$j]], $couleurs_avions[$j + 3]);

            // Sauvegarde ancien point
            $old_minute[$j] = $cumul[$m][$annees[$j]];
        }
    }
}

// Modification du header pour indiquer qu'il s'agit d'une image
header("Content-type:image/png");
$ans = join("-", $annees);
header("Content-Disposition:inline ; filename=heures_avion_annees_" . $avion . "_" . $ans . ".png");

// Ecriture de l'image en PNG
imagepng($image);
?>
