<?php

/**
 * Public Class PilotePDF
 * Create PDF and provide helpfull function to layout addresses
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

class PilotePDF extends Galette\IO\Pdf {

    public function insertAeroclubAndMemberAddresses($pseudo) {
        global $preferences;

        $taille_police = 12;

        $haut_mini = 0;
        $larg = 0;
        // Si on trouve le logo custom, on l'affiche
        // en respectant sa taille
        // On sauvegarde sa taille pour plus tard
        if (file_exists(GALETTE_BASE_PATH . 'photos/custom_logo.jpg')) {
            $coefficient = 9;
            $taille = getimagesize(GALETTE_BASE_PATH . 'photos/custom_logo.jpg');
            $this->Image(GALETTE_BASE_PATH . 'photos/custom_logo.jpg', 10, 15, $taille[0] / $coefficient, $taille[1] / $coefficient);
            $haut_mini = $taille[1] / $coefficient;
            $larg = $taille[0] / $coefficient;
        }

        // 4 lignes vides, hop
        $this->Cell(10, 0, ' ');
        $this->Ln();

        // Si il y a un logo, on fait une cellule de la largeur du logo et on écrit après celle-ci
        if ($larg > 0) {
            $this->Cell($larg, 0, ' ');
        }
        $this->SetFont(Galette\IO\Pdf::FONT, 'B', $taille_police);
        // Nom association
        $this->Cell(90, 0, $preferences->pref_nom, 'B');
        $this->Ln();
        $this->SetFont(Galette\IO\Pdf::FONT, '', $taille_police);
        if ($larg > 0) {
            $this->Cell($larg, 0, ' ');
        }
        // Section association
        $this->Cell(0, 0, $preferences->pref_slogan);
        $this->Ln();
        if ($larg > 0) {
            $this->Cell($larg, 0, ' ');
        }
        // Adresse, ligne 1
        $this->Cell(0, 0, $preferences->pref_adresse);
        $this->Ln();
        if (trim($preferences->pref_address2) != '') {
            if ($larg > 0) {
                $this->Cell($larg, 0, ' ');
            }
            // Adresse, ligne 2
            $this->Cell(0, 0, $preferences->pref_adresse2);
            $this->Ln();
        }
        if ($larg > 0) {
            $this->Cell($larg, 0, ' ');
        }
        // Code postal + ville
        $this->Cell(0, 0, $preferences->pref_cp . ' ' . $preferences->pref_ville);

        // Saut entre les 2 sections pour atterrir dans la case de l'enveloppe
        $this->Ln();
        $this->Ln();
        $this->Ln();
        
        // On ajoute autant de ligne vide que nécessaire pour passer après le logo
        // Une ligne fait environ 3,9 unités.
        // On a écrit 5 lignes, soit 19.5 unités, et on le fait tant qu'on fait moins de
        // la hauteur mini définie par le logo
        for ($i = 19.5; $i < $haut_mini; $i+=3.9) {
            $this->Ln();
        }

        // Récupération de l'adhérent pour avoir son adresse
        $marge_env_C65 = 92;
        $adherent = new Galette\Entity\Adherent($pseudo);
        $this->SetFont(Galette\IO\Pdf::FONT, 'B', $taille_police);
        $this->Cell($marge_env_C65, 0, ' ');
        // Nom + prénom en gras
        $this->Cell(55, 0, $adherent->name . ' ' . $adherent->surname, 'B');
        $this->SetFont(Galette\IO\Pdf::FONT, '', $taille_police);
        $this->Ln();
        $this->Cell($marge_env_C65, 0, ' ');
        // Identifiant + login
        $this->Cell(0, 0, 'Identifiant : ' . $adherent->id . '/' . $adherent->login);
        $this->Ln();
        $this->Cell($marge_env_C65, 0, ' ');
        // Adresse
        $this->Cell(0, 0, $adherent->adress);
        $this->Ln();
        $this->Cell($marge_env_C65, 0, ' ');
        // Code postal + ville
        $this->Cell(0, 0, $adherent->zipcode . ' ' . $adherent->town);
        $this->Ln();
        $this->Ln();
        $this->Ln();
    }

}

?>
