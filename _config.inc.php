<?php

/**
 * Base configuration
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

if (!defined('PILOTE_PREFIX')) {
    define('PILOTE_PREFIX', 'pilote_');
}
define('PILOTE_SMARTY_PREFIX', 'plugins|pilote');

require_once 'classes/piloteAdherentComplement.class.php';
require_once 'classes/piloteOperation.class.php';
require_once 'classes/piloteParametres.class.php';
require_once 'classes/piloteSQLScripts.class.php';
require_once 'classes/piloteAvion.class.php';
require_once 'classes/piloteReservation.class.php';
require_once 'classes/piloteAvionPicture.class.php';
require_once 'classes/piloteInstructeur.class.php';
require_once 'classes/piloteAvionDispo.class.php';
require_once 'classes/pilotePDF.class.php';
?>
