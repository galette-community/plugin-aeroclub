{* Titre du bloc *}
<h1 class="nojs">{_T string="MENU.TITRE SECTION"}</h1>
{if $login->isLogged()}
{* Entrées du menu *}
<ul>
   <li{if $PAGENAME eq "reservation.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}reservation.php">{_T string="MENU.RESERVATION"}</a></li>
   <li{if $PAGENAME eq "planning.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}planning.php">{_T string="MENU.PLANNING"}</a></li>
   <li{if $PAGENAME eq "compte_vol.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}compte_vol.php">{_T string="MENU.COMPTE"}</a></li>
   <li{if $PAGENAME eq "liste_vols.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}liste_vols.php">{_T string="MENU.LISTE VOLS"}</a></li>
   <li{if $PAGENAME eq "graphique.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}graphique.php">{_T string="MENU.GRAPHIQUE"}</a></li>
   <li{if $PAGENAME eq "fiche_pilote.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}fiche_pilote.php">{_T string="MENU.FICHE PILOTE"}</a></li>
   <li{if $PAGENAME eq "situation_aero.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}situation_aero.php">{_T string="MENU.SITUATION AERO"}</a></li>
   <li{if $PAGENAME eq "documents_frame.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}documents_frame.php">{_T string="MENU.DOCUMENTS 1"}</a></li>
</ul>
{/if}
{if $is_instructeur eq 1 || $login->isStaff() || $login->isAdmin()}
{* Entrées de menu visibles uniquement par les membres du staff (priorité status < 30) *}
<h1 class="nojs"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU.TITRE SECTION GESTION"}</h1>
<ul>
   <li{if $PAGENAME eq "heures_pilote.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}heures_pilote.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU STAFF.HEURES PILOTE"}</a></li>
   <li{if $PAGENAME eq "heures_avion.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}heures_avion.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU STAFF.HEURES AVION"}</a></li>
   <li{if $PAGENAME eq "soldes_pilotes.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}soldes_pilotes.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU STAFF.SOLDES PILOTES"}</a></li>
   <li{if $PAGENAME eq "types_vols_pilotes.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}types_vols_pilotes.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU STAFF.TYPES VOLS PILOTES"}</a></li>
</ul>
{/if}
{if $login->isAdmin()}
{* Entrées de menu visibles uniquement par les administrateurs *}
<h1 class="nojs"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU.TITRE SECTION ADMIN"}</h1>
<ul>
   <li{if $PAGENAME eq "liste_avions.php" || $PAGENAME eq "modifier_avion.php" || $PAGENAME eq "dispo.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}liste_avions.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU ADMIN.LISTE AVIONS"}</a></li>
   <li{if $PAGENAME eq "liste_instructeurs.php" || $PAGENAME eq "modifier_instructeur.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}liste_instructeurs.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU ADMIN.LISTE INSTRUCTEURS"}</a></li>
   <li{if $PAGENAME eq "new_operation.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}new_operation.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU ADMIN.NOUVELLE OPERATION"}</a></li>
   <li{if $PAGENAME eq "rapprochement.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}rapprochement.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU ADMIN.RAPPROCHEMENT"}</a></li>
   <li{if $PAGENAME eq "sql.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}sql.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU ADMIN.SQL"}</a></li>
   <li{if $PAGENAME eq "import.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}import.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU ADMIN.IMPORT"}</a></li>
{* <li{if $PAGENAME eq "histo_import.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}histo_import.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU ADMIN.HISTO IMPORT"}</a></li>*}
   <li{if $PAGENAME eq "parametres.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}parametres.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU ADMIN.PARAMETRES"}</a></li>
   <li{if $PAGENAME eq "version.php"} class="selected"{/if}><a href="{$galette_base_path}{$pilote_dir}version.php"><img src="{$galette_base_path}{$pilote_dir}picts/lock.png"> {_T string="MENU ADMIN.VERSION"}</a></li>
</ul>
{/if}
