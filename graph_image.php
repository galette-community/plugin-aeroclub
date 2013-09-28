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
if (!$login->isLogged()) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}

// Import des classes de notre plugin
require_once '_config.inc.php';


$pseudo = $login->login;
if ($login->isAdmin() && array_key_exists('pseudo', $_GET)) {
    $pseudo = $_GET['pseudo'];
}

$liste = array();
$max_minute = 10;
$max_solde = 10;
$min_solde = -10;
$total_minute = 0;
$total_solde = 0;
$max_sum_solde = 10;
$min_sum_solde = -10;
// Pour tous les mois à partir du prochain jusqu'à + 12
for ($i = intval(date('m') + 1); $i < intval(date('m')) + 13; $i++) {
    // Récupération du montant + durée pour le mois n
    // La routine nous donne automatiquement le mois de l'année précédente pour
    // Mois > aujourd'hui et année en cours pour Mois <= aujourd'hui
    $r = PiloteOperation::getMontantOperationForMonth($pseudo, $i > 12 ? $i - 12 : $i);
    $liste[] = $r;
    // Calcul total durée de vol + max du mois durée de vol
    if (is_numeric($r->duree)) {
        $total_minute += $r->duree;
        if ($r->duree > $max_minute) {
            $max_minute = $r->duree;
        }
    }

    // Calcul solde mini, solde maxi
    if (is_numeric($r->montant)) {
        $total_solde += $r->montant;
        if ($total_solde > $max_sum_solde) {
            $max_sum_solde = $total_solde;
        }
        if ($total_solde < $min_sum_solde) {
            $min_sum_solde = $total_solde;
        }
        if ($r->montant > $max_solde) {
            $max_solde = $r->montant;
        }
        if ($r->montant < $min_solde) {
            $min_solde = $r->montant;
        }
    }
}

// Agrandissement de qq % du graphique
$max_minute *= 1.07;
$max_solde *= 1.07;
$min_solde *= 1.07;
$total_minute *= 1.07;
$max_sum_solde *= 1.07;
$min_sum_solde *= 1.07;

// Définition taille image + police utilisée
$width = 750;
$height = 550;
$font_path = 'SourceSansPro-Regular.ttf';

// Nouvelle image
$image = imagecreate($width, $height);

// Couleurs
$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$grey = imagecolorallocate($image, 215, 215, 215);
$blue = imagecolorallocate($image, 0, 0, 255);
//$red = imagecolorallocate($image, 255, 0, 0);
$purple = imagecolorallocate($image, 131, 0, 146);
$orange = imagecolorallocate($image, 255, 202, 89);

// Fond en blanc
imagefill($image, 0, 0, $white);

// Bordure gauche, bas, droite
imageline($image, 30, 5, 30, $height - 25, $black);
imageline($image, 30, $height - 25, $width - 30, $height - 25, $black);
imageline($image, $width - 30, 5, $width - 30, $height - 25, $black);

// Trace du quadrillage horizontal
for ($i = 0; $i < 10; $i++) {
    // Trace valeurs Ordonnées Minutes/mois à gauche en bleu
    $min_tot = intval($max_minute / 10 * (10 - $i));
    $nb_h = floor($min_tot / 60);
    $nb_m = $min_tot - $nb_h * 60;
    imagettftext($image, 10, 0, 0, 10 + ($height - 30) / 10 * $i, $blue, $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);

    // Trace valeurs Ordonnées Somme minutes sur l'année à gauche sous la valeur précédente en violet
    $min_tot = intval($total_minute / 10 * (10 - $i));
    $nb_h = floor($min_tot / 60);
    $nb_m = $min_tot - $nb_h * 60;
    imagettftext($image, 10, 0, 0, 21 + ($height - 30) / 10 * $i, $purple, $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);

    // Trace valeur Ordonnées solde/mois à droite en rouge
    //imagettftext($image, 10, 0, $width - 28, 10 + ($height - 30) / 10 * $i, $red, $font_path, intval(($max_solde - $min_solde) / 10 * (10 - $i) + $min_solde) . '€');
    // Trace valeur Ordonnées somme solde sur l'année à droite en orange sous la valeur précédente
    imagettftext($image, 10, 0, $width - 28, 15 + ($height - 30) / 10 * $i, $orange, $font_path, intval(($max_sum_solde - $min_sum_solde) / 10 * (10 - $i) + $min_sum_solde) . '€');

    // Ligne quadrillage horizontale en gris
    imageline($image, 31, 10 + ($height - 30) / 10 * $i, $width - 31, 10 + ($height - 30) / 10 * $i, $grey);
}
// Calcul de la ligne de zéro solde/mois
//$zero = ($height - 30) / ($max_solde - $min_solde) * $max_solde + 5;
// Ecriture ordonnée 0€ solde/mois
//imagettftext($image, 10, 0, $width - 28, $zero + 5, $red, $font_path, '0€');
// Calcul de la ligne de zéro somme solde année
$zero_sum = ($height - 30) / ($max_sum_solde - $min_sum_solde) * $max_sum_solde + 5;
// Ecriture ordonnée 0€ somme solde année
imagettftext($image, 10, 0, $width - 28, $zero_sum + 5, $orange, $font_path, '0€');
// Ecriture valeurs mini tout en bas graphique
imagettftext($image, 10, 0, 0, $height - 20, $blue, $font_path, '0h');
//imagettftext($image, 10, 0, $width - 28, $height - 25, $red, $font_path, intval($min_solde) . '€');
imagettftext($image, 10, 0, $width - 28, $height - 20, $orange, $font_path, intval($min_sum_solde) . '€');

$minute = 0;
$old_minute = 0;
$montant = 0;
$old_montant = 0;
// Parcours de la liste des valeurs
for ($i = 0; $i < count($liste); $i++) {
    // Quadrillage vertical gris
    if ($i > 0) {
        imageline($image, ($width - 60) / 12 * $i + 30, 5, ($width - 60) / 12 * $i + 30, $height - 26, $grey);
    }

    // 'max' dans la requête est dernière date opération du mois saisi
    // on récupère cette date pour valeurs abscisse
    if (is_string($liste[$i]->max)) {
        // Taille de la chaîne "Mois" sur 3 lettres
        $size = imagettfbbox(10, 0, $font_path, date('M', strtotime($liste[$i]->max)));
        // Dessin de la chaîne "Mois" sur 3 lettres centré
        imagettftext($image, 10, 0, ($width - 60) / 12 * $i + ($width - 60) / 12 - $size[2] / 2, $height - 12, $black, $font_path, date('M', strtotime($liste[$i]->max)));
        // Taille de la chaine "Année" sur 4 chiffres
        $size = imagettfbbox(10, 0, $font_path, date('Y', strtotime($liste[$i]->max)));
        // Dessin de la chaine "Année" sur 4 chiffres centré
        imagettftext($image, 10, 0, ($width - 60) / 12 * $i + ($width - 60) / 12 - $size[2] / 2, $height, $black, $font_path, date('Y', strtotime($liste[$i]->max)));
    }

    $old_minute = $minute;
    // Si la durée est présente
    if (is_numeric($liste[$i]->duree)) {
        $minute += $liste[$i]->duree;
        // Dessin d'un rectangle de la hauteur de la durée
        imagefilledrectangle($image, ($width - 60) / 12 * $i + 33, $height - 26, ($width - 60) / 12 * $i + ($width - 60) / 12, $height - 20 - ($height - 30) / $max_minute * $liste[$i]->duree, $blue);
        // Texte de durée
        $nb_h = floor($liste[$i]->duree / 60);
        $nb_m = $liste[$i]->duree - $nb_h * 60;
        imagettftext($image, 10, -45, ($width - 60) / 12 * $i + 32, $height - 40 - ($height - 30) / $max_minute * $liste[$i]->duree, $blue, $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);
    }

    $old_montant = $montant;
    // Si le montant est présent
    if (is_numeric($liste[$i]->montant)) {
        $montant += $liste[$i]->montant;
        // Dessin d'un rectangle de la hauteur du solde mois par rapport à 0€
        //imagefilledrectangle($image, ($width - 60) / 12 * $i + ($width - 60) / 12 + 3, $zero, ($width - 60) / 12 * ($i + 1.5), $zero - ($height - 30) / ($max_solde - $min_solde) * $liste[$i]->montant, $red);
    }

    // Somme durée
    // On trace un point en 1er
    imagefilledellipse($image, ($width - 60) / 12 * $i + ($width - 60) / 12 + 2, $height - 25 - ($height - 30) / $total_minute * $minute, 8, 8, $purple);
    // Texte somme durée
    $nb_h = floor($minute / 60);
    $nb_m = $minute - $nb_h * 60;
    imagettftext($image, 10, -45, ($width - 60) / 12 * $i + ($width - 60) / 12 + 2, $height - 10 - ($height - 30) / $total_minute * $minute, $purple, $font_path, $nb_h . 'h' . ($nb_m < 10 ? '0' : '') . $nb_m);
    if ($i > 0) {
        // A partir du 2ème mois, on trace une ligne vers le mois précédent
        // Pour faire une ligne épaisse, on trace 5 lignes ainsi 
        // x1    , y1     -> x2    , y2
        // x1 + 1, y1     -> x2 + 1, y2
        // x1 - 1, y1     -> x2 - 1, y2
        // x1    , y1 + 1 -> x2    , y2 + 1
        // x1    , y1 - 1 -> x2    , y2 - 1
        imageline($image, ($width - 60) / 12 * ($i - 1) + ($width - 60) / 12 + 2, $height - 25 - ($height - 30) / $total_minute * $old_minute, ($width - 60) / 12 * $i + ($width - 60) / 12 + 2, $height - 25 - ($height - 30) / $total_minute * $minute, $purple);
        imageline($image, ($width - 60) / 12 * ($i - 1) + ($width - 60) / 12 + 3, $height - 25 - ($height - 30) / $total_minute * $old_minute, ($width - 60) / 12 * $i + ($width - 60) / 12 + 3, $height - 25 - ($height - 30) / $total_minute * $minute, $purple);
        imageline($image, ($width - 60) / 12 * ($i - 1) + ($width - 60) / 12 + 1, $height - 25 - ($height - 30) / $total_minute * $old_minute, ($width - 60) / 12 * $i + ($width - 60) / 12 + 1, $height - 25 - ($height - 30) / $total_minute * $minute, $purple);
        imageline($image, ($width - 60) / 12 * ($i - 1) + ($width - 60) / 12 + 2, $height - 26 - ($height - 30) / $total_minute * $old_minute, ($width - 60) / 12 * $i + ($width - 60) / 12 + 2, $height - 26 - ($height - 30) / $total_minute * $minute, $purple);
        imageline($image, ($width - 60) / 12 * ($i - 1) + ($width - 60) / 12 + 2, $height - 24 - ($height - 30) / $total_minute * $old_minute, ($width - 60) / 12 * $i + ($width - 60) / 12 + 2, $height - 24 - ($height - 30) / $total_minute * $minute, $purple);
    }

    // Somme montant
    // On trace un point en 1er
    imagefilledellipse($image, ($width - 60) / 12 * $i + ($width - 60) / 12 + 2, $zero_sum - ($height - 30) / ($max_sum_solde - $min_sum_solde) * $montant, 8, 8, $orange);
    // Texte somme €
    imagettftext($image, 10, -45, ($width - 60) / 12 * $i + ($width - 60) / 12 - 10, $zero_sum - 20 - ($height - 30) / ($max_sum_solde - $min_sum_solde) * $montant, $orange, $font_path, round($montant) . '€');
    if ($i > 0) {
        // A partir du 2ème mois, on trace une ligne vers le mois précédent
        // Pour faire une ligne épaisse, on trace 5 lignes ainsi 
        // x1    , y1     -> x2    , y2
        // x1 + 1, y1     -> x2 + 1, y2
        // x1 - 1, y1     -> x2 - 1, y2
        // x1    , y1 + 1 -> x2    , y2 + 1
        // x1    , y1 - 1 -> x2    , y2 - 1
        imageline($image, ($width - 60) / 12 * ($i - 1) + ($width - 60) / 12 + 2, $zero_sum - ($height - 30) / ($max_sum_solde - $min_sum_solde) * $old_montant, ($width - 60) / 12 * $i + ($width - 60) / 12 + 2, $zero_sum - ($height - 30) / ($max_sum_solde - $min_sum_solde) * $montant, $orange);
        imageline($image, ($width - 60) / 12 * ($i - 1) + ($width - 60) / 12 + 3, $zero_sum - ($height - 30) / ($max_sum_solde - $min_sum_solde) * $old_montant, ($width - 60) / 12 * $i + ($width - 60) / 12 + 3, $zero_sum - ($height - 30) / ($max_sum_solde - $min_sum_solde) * $montant, $orange);
        imageline($image, ($width - 60) / 12 * ($i - 1) + ($width - 60) / 12 + 1, $zero_sum - ($height - 30) / ($max_sum_solde - $min_sum_solde) * $old_montant, ($width - 60) / 12 * $i + ($width - 60) / 12 + 1, $zero_sum - ($height - 30) / ($max_sum_solde - $min_sum_solde) * $montant, $orange);
        imageline($image, ($width - 60) / 12 * ($i - 1) + ($width - 60) / 12 + 2, $zero_sum - ($height - 29) / ($max_sum_solde - $min_sum_solde) * $old_montant, ($width - 60) / 12 * $i + ($width - 60) / 12 + 2, $zero_sum - ($height - 29) / ($max_sum_solde - $min_sum_solde) * $montant, $orange);
        imageline($image, ($width - 60) / 12 * ($i - 1) + ($width - 60) / 12 + 2, $zero_sum - ($height - 31) / ($max_sum_solde - $min_sum_solde) * $old_montant, ($width - 60) / 12 * $i + ($width - 60) / 12 + 2, $zero_sum - ($height - 31) / ($max_sum_solde - $min_sum_solde) * $montant, $orange);
    }
}

// Par dessous tout ce qu'on a dessiné, on trace les 2 lignes de montant 0€
//imageline($image, 31, $zero, $width - 31, $zero, $red);
imageline($image, 31, $zero_sum, $width - 31, $zero_sum, $orange);

// Modification du header pour indiquer qu'il s'agit d'une image
header("Content-type:image/png");
header("Content-Disposition:inline ; filename=graph_image.png");

// Ecriture de l'image en PNG
imagepng($image);
?>
