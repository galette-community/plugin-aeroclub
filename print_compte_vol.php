<?php

/**
 * Print as PDF all known operations for a pilot.
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

/**
 * Variables
 */
$annee_selectionnee = array_key_exists('compte_annee', $_GET) ? $_GET['compte_annee'] : date('Y');
$pseudo = $login->login;
if ($login->isAdmin() && array_key_exists('pseudo', $_GET)) {
    $pseudo = $_GET['pseudo'];
}

/**
 * Récupération de la liste des opérations
 */
if ($annee_selectionnee != _T('COMPTE VOL.TOUTES')) {
    $liste_operations = PiloteOperation::getOperationsForLogin($pseudo, 'date_operation', 'asc', 1, 9999, intval($annee_selectionnee));
    $nb_operations = PiloteOperation::getNombreOperationsForLogin($pseudo, intval($annee_selectionnee));
} else {
    $liste_operations = PiloteOperation::getOperationsForLogin($pseudo, 'date_operation', 'asc', 1, 9999);
    $nb_operations = PiloteOperation::getNombreOperationsForLogin($pseudo);
}

/**
 * Totaux
 */
if ($annee_selectionnee != _T('COMPTE VOL.TOUTES')) {
    $toutes_operations = PiloteOperation::getOperationsForLogin($pseudo, '1', 'asc', 1, 99999, intval($annee_selectionnee));
} else {
    $toutes_operations = PiloteOperation::getOperationsForLogin($pseudo, '1', 'asc', 1, 99999);
}

$solde_trouve = false;
foreach ($toutes_operations as $operation) {
    if ($operation->type_operation != '****' || ($operation->type_operation == '****' && !$solde_trouve)) {
        $solde += $operation->montant_operation;
        if ($operation->type_operation == '****') {
            $solde_trouve = true;
        }
    }
    $total_vols += $operation->duree_minute;
}


$pdf = new PilotePDF();

// Set document information
$pdf->SetTitle('Compte vol');
$pdf->SetSubject('');
$pdf->SetKeywords('');

$pdf->AddPage();

$pdf->insertAeroclubAndMemberAddresses($pseudo);

$pdf->SetFont(Galette\IO\Pdf::FONT, '', 9);
$pdf->Cell(0, 0, 'Impression le ' . date('d/m/Y'));
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont(Galette\IO\Pdf::FONT, 'B');
$pdf->Cell(25, 0, _T("COMPTE VOL.DATE"), 'B');
$pdf->Cell(25, 0, _T("COMPTE VOL.TYPE"), 'B');
$pdf->Cell(80, 0, _T("COMPTE VOL.LIBELLE"), 'B');
$pdf->Cell(25, 0, _T("COMPTE VOL.DUREE"), 'B', 0, 'R');
$pdf->Cell(25, 0, _T("COMPTE VOL.MONTANT"), 'B', 0, 'R');
$pdf->Ln();

$pdf->SetFont(Galette\IO\Pdf::FONT);
$vieille_date = '';
foreach ($liste_operations as $operation) {
    $pdf->SetTextColor(0);

    $nvlle_date = _T('COMPTE VOL.MOIS.' . date('m', strtotime($operation->date_operation))) . ' ' . date('Y', strtotime($operation->date_operation));
    if ($nvlle_date != $vieille_date) {
        $pdf->SetFont(Galette\IO\Pdf::FONT, 'B');
        $pdf->Ln();
        $pdf->Cell(60, 0, $nvlle_date);
        $pdf->SetFont(Galette\IO\Pdf::FONT);
        $pdf->Ln();
        $vieille_date = $nvlle_date;
    }

    $pdf->Cell(25, 0, $operation->date_operation_court, 'B');
    $pdf->Cell(25, 0, $operation->type_operation, 'B');
    $pdf->Cell(80, 0, $operation->libelle_operation, 'B');
    $pdf->Cell(25, 0, $operation->duree, 'B', 0, 'R');
    if ($operation->montant < 0) {
        $pdf->SetTextColor(255, 0, 0);
    } else {
        $pdf->SetTextColor(0, 128, 0);
    }
    $pdf->Cell(25, 0, $operation->montant, 'B', 0, 'R');
    $pdf->Ln();
}
$pdf->SetTextColor(0);

$pdf->Ln();
$pdf->Ln();

// Total vols
$heure_vols = floor($total_vols / 60);
$minute_vols = $total_vols - $heure_vols * 60;
if ($heure_vols > 0) {
    $total_vols = $heure_vols . ' h ' . $minute_vols . ' min';
} else {
    $total_vols = $minute_vols . ' min';
}
$pdf->Cell(45, 0, _T("COMPTE VOL.TOTAL VOL") . ' :');
$pdf->Cell(50, 0, $total_vols);
$pdf->Ln();
$pdf->Cell(45, 0, _T("COMPTE VOL.SOLDE") . date('d/m/Y'));
if ($solde < 0) {
    $pdf->SetTextColor(255, 0, 0);
} else {
    $pdf->SetTextColor(0, 128, 0);
}
$pdf->Cell(50, 0, number_format($solde, 2, ',', ' ') . ' EUR');
$pdf->Ln();

$pdf->Output('compte_vol.pdf', 'D');
?>
