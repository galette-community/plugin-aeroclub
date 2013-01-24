<?php

/**
 * Public Class PiloteAvionPicture
 * Store the picture of a plane
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

/**
 * Class to store a picture of a plane
 *
 * @category  Classes
 * @name      PiloteAvionPicture
 * @package   Pilote
 * @author    Mélissa Djebel <melissa.djebel@gmx.net>
 * @copyright 2011 Mélissa Djebel
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or later
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7
 */
class PiloteAvionPicture extends Galette\Core\Picture {

    protected $tbl_prefix = PILOTE_PREFIX;

    const PK = PiloteAvion::PK;

    //path is relative to Picture class, not to PilotePicture
    protected $store_path = '../plugins/Pilote/avions_photos/';
    protected $max_width = 350;
    protected $max_height = 350;

    /**
     * Construit une nouvelle image d'un avion soit vierge, soit à partir de son ID
     * 
     * @param int $args ID de l'avion dont on cherche l'image
     */
    public function __construct($args = null) {
        $this->store_path = GALETTE_ROOT . 'plugins/Pilote/avions_photos/';
        $immat = '';
        if (is_numeric($args)) {
            $immat = trim(PiloteAvion::getImmatriculation(intval($args)));
        }

        parent::__construct($immat);
    }

    /**
     * Gets the default picture to show, anyways
     *
     * @see Logo::getDefaultPicture()
     *
     * @return void
     */
    protected function getDefaultPicture() {
        $this->file_path = GALETTE_ROOT . 'plugins/Pilote/picts/avion.png';
        $this->format = 'png';
        $this->mime = 'image/png';
        $this->has_picture = false;
    }

    /**
     * Affiche la miniature d'une photo d'un avion et la créé si nécessaire
     */
    public function displayThumb() {
        $nom_fichier = substr($this->file_path, 0, strlen($this->file_path) - 4);
        
        // Ano 61 - Quand le fichier fait 0Ko on affiche l'image par défaut
        // Ou que l'image est trop petite (<40x40 pixels)
        $size = getimagesize($this->file_path);
        if (!$size || $size[0] < 40 || $size[1] < 40) {
            $this->getDefaultPicture();
            $this->display();
            return;
        }

        // On récupère la miniature
        $nom_thumb = $nom_fichier . '_th' . '.png';

        // Si la miniature n'existe pas, on la créé
        if (!is_file($nom_thumb)) {
            //$this->createthumb($this->file_path, $nom_thumb, 128, 128);
            $w = round($this->getOptimalWidth() * 128 / $this->max_width);
            $h = round($this->getOptimalHeight() * 128 / $this->max_height);
            $this->_createRoundThumb($this->file_path, $nom_thumb, $w, $h, 5, 10);
        }
        header('Content-type: ' . $this->mime);
        readfile($nom_thumb);
    }

    /**
     * Créer une miniature d'une image donnée en arrondissant les bords (transparent)
     * 
     * @param string $img_src Nom de l'image source
     * @param string $img_dest Nom de l'image de destination
     * @param int $w_thumb Largeur en pixel de la miniature
     * @param int $h_thumb Hauteur en pixel de la miniature
     * @param int $border Taille de la bordure
     * @param int $radial Rayon de la bordure
     */
    private function _createRoundThumb($img_src, $img_dest, $w_thumb, $h_thumb, $border = 10, $radial = 24) {

        $pic['destNormal']['name'] = $img_src; // nom du fichier normal
        // Récupération des infos de l'image source
        list($pic['src']['info']['width'], $pic['src']['info']['height'], $pic['src']['info']['type'], $pic['src']['info']['attr']) = getimagesize($img_src);

        //On vérifie si le parametre de la hauteur est plus grand que 0
        if ($h_thumb == 0) {
            // si egal a zaro on affecte la hauteur proportionnellement 
            $h_thumb = floor($pic['src']['info']['height'] * $w_thumb / $pic['src']['info']['width']);
        }
        switch ($pic['src']['info']['type']) {
            case"1":
                // Création de l'image pour une source gif
                $pic['src']['ress'] = imagecreatefromgif($img_src);
                break;
            case"2":
                // Création de l'image pour une source jpg
                $pic['src']['ress'] = imagecreatefromjpeg($img_src);
                break;
            case"3":
                // Création de l'image pour une source png
                $pic['src']['ress'] = imagecreatefrompng($img_src);
                break;
        }

        // On crée la miniature vide pour l'image Etat Normal
        $pic['destNormal']['ress'] = imagecreatetruecolor($w_thumb, $h_thumb);
        // On crée la miniature Normal
        imagecopyresampled($pic['destNormal']['ress'], $pic['src']['ress'], 0, 0, 0, 0, $w_thumb, $h_thumb, $pic['src']['info']['width'], $pic['src']['info']['height']);

        // On commence à créer le masque pour le contour coin rond
        // On crée le mask vide
        $pic['maskBorder']['ress'] = imagecreate($w_thumb, $h_thumb);
        // affectation de la couleur verte
        $pic['maskBorder']['green'] = imagecolorallocate($pic['maskBorder']['ress'], 0, 255, 0);
        // affectation de la couleur rose
        $pic['maskBorder']['pink'] = imagecolorallocate($pic['maskBorder']['ress'], 255, 0, 255);
        // Ici on trace la zone à mettre en transparence avant le merge entre les 2 images
        // PRINCIPE : 4 cercle situé dans chauque coin avec un rayon de 2 fois la bordure
        // PRINCIPE : 1 forme polygonale de 8 cotés pour peindre de rose la zone restante
        imagefilledellipse($pic['maskBorder']['ress'], $radial, $radial, $radial * 2, $radial * 2, $pic['maskBorder']['pink']); // cercle gauche supérieur
        imagefilledellipse($pic['maskBorder']['ress'], $w_thumb - $radial, $radial, $radial * 2, $radial * 2, $pic['maskBorder']['pink']); // cercle droite supérieur
        imagefilledellipse($pic['maskBorder']['ress'], $radial, $h_thumb - $radial, $radial * 2, $radial * 2, $pic['maskBorder']['pink']); // cercle gauche inférieur
        imagefilledellipse($pic['maskBorder']['ress'], $w_thumb - $radial, $h_thumb - $radial, $radial * 2, $radial * 2, $pic['maskBorder']['pink']); // cercle droit inférieur
        imagefilledpolygon($pic['maskBorder']['ress'], array($radial, 0, 0, $radial, 0, $h_thumb - $radial, $radial, $h_thumb, $w_thumb - $radial, $h_thumb, $w_thumb, $h_thumb - $radial, $w_thumb, $radial, $w_thumb - $radial, 0), 8, $pic['maskBorder']['pink']); // forme géométrique à 8 coter
        imagecolortransparent($pic['maskBorder']['ress'], $pic['maskBorder']['pink']); // Applique la transparence à la couleur rose
        // TRAITEMENT SUR L'IMAGE NORMAL
        // copie du masque au dessus de la miniature avec une transparence (0%)
        imagecopymerge($pic['destNormal']['ress'], $pic['maskBorder']['ress'], 0, 0, 0, 0, $w_thumb, $h_thumb, 100);
        // il faut enlever le vert pour que le fond soit transparent
        if ($radial > 0) {
            // si le radial est de 0 alors ne pas appliquer la transparence parce que le pixel 0,0 
            // n'est pas vert ce qui entraine une transparence sur les zones qui on la meme couleur 
            // que le pixel 0,0
            // conversion en palette 256 couleur 
            //imagetruecolortopalette($pic['destNormal']['ress'], FALSE, 256); 
            // affectation de la couleur verte (récupérer au pixel 0,0)
            $pic['destNormal']['green'] = imagecolorat($pic['destNormal']['ress'], 0, 0);
            // Applique la transparence à la couleur verte
            imagecolortransparent($pic['destNormal']['ress'], $pic['destNormal']['green']);
        }
        // On enregistre la miniature avec bordure coin rond
        imagepng($pic['destNormal']['ress'], $img_dest);
        imagedestroy($pic['destNormal']['ress']);
    }

    /**
     * Deletes a picture, from both database and filesystem
     *
     * @return boolean true if image was successfully deleted, false otherwise
     */
    public function delete($transaction = true) {
        $nom_fichier = substr($this->file_path, 0, strlen($this->file_path) - 4);

        $nom_thumb = $nom_fichier . '_th.png';

        if (is_file($nom_thumb)) {
            unlink($nom_thumb);
        }

        return parent::delete();
    }

    /**
     * Stores an image on the disk and in the database
     *
     * @param object $file the uploaded file
     * @param bool $ajax not used
     *
     * @return true|false result of the storage process
     */
    public function store($file, $ajax = false) {
        $nom_fichier = substr($this->file_path, 0, strlen($this->file_path) - 4);

        $nom_thumb = $nom_fichier . '_th.png';

        if (is_file($nom_thumb)) {
            unlink($nom_thumb);
        }

        return parent::store($file);
    }

}

?>
