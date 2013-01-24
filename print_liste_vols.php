<?php

/**
 * Print as PDF all known flights for a pilot.
 * An admin can see flights for an other pilote.
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
 * Liste des vols
 */
$liste_operations = PiloteOperation::getVolsForLogin($pseudo, 'date_operation', 'asc', 1, 9999, intval($annee_selectionnee));


$pdf = new PilotePDF('P', 'mm', 'A4', true, 'UTF-8');

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

$pdf->SetFont(Galette\IO\Pdf::FONT, 'B', 9);
$pdf->Cell(22, 0, _T("LISTE VOLS.DATE"), 'B');
$pdf->Cell(18, 0, _T("LISTE VOLS.IMMAT"), 'B');
$pdf->Cell(18, 0, _T("LISTE VOLS.TYPE VOL"), 'B');
$pdf->Cell(29, 0, _T("LISTE VOLS.AEROPORTS"), 'B');
$pdf->Cell(19, 0, _T("LISTE VOLS.PASSAGERS"), 'B');
$pdf->Cell(21, 0, _T("LISTE VOLS.INSTRUCTEUR"), 'B');
$pdf->Cell(23, 0, _T("LISTE VOLS.NB ATTERRISSAGES"), 'B');
$pdf->Cell(18, 0, _T("LISTE VOLS.DUREE"), 'B', 0, 'R');
$pdf->Cell(22, 0, _T("LISTE VOLS.MONTANT"), 'B', 0, 'R');

$pdf->SetFont(Galette\IO\Pdf::FONT, '', 9);
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

    $pdf->Cell(22, 0, $operation->date_operation_court, 'B');
    $pdf->Cell(18, 0, $operation->immatriculation, 'B');
    $pdf->Cell(18, 0, $operation->type_vol, 'B');
    $pdf->Cell(29, 0, $operation->aeroport_depart . ' / ' . $operation->aeroport_arrivee, 'B');
    $pdf->Cell(19, 0, $operation->nb_passagers, 'B', 0, 'C');
    $pdf->Cell(21, 0, $operation->instructeur, 'B');
    $pdf->Cell(23, 0, $operation->nb_atterrissages, 'B', 0, 'C');
    $pdf->Cell(18, 0, $operation->duree, 'B', 0, 'R');
    if ($operation->montant < 0) {
        $pdf->SetTextColor(255, 0, 0);
    } else {
        $pdf->SetTextColor(0, 128, 0);
    }
    $pdf->Cell(22, 0, $operation->montant, 'B', 0, 'R');
    $pdf->Ln();
}


$pdf->Output('liste_vols.pdf', 'D');
?>
