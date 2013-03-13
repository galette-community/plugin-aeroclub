{if $resa_ok}
    <div id="infobox">
        <h1>{_T string="RESERVATION.RESA OK"}</h1>
    </div>
{/if}
{if $resa_annule}
    <div id="warningbox">
        <h1>{_T string="RESERVATION.RESA ANNULE"}</h1>
    </div>
{/if}
{if $resa_supprime}
    <div id="errorbox">
        <h1>{_T string="RESERVATION.RESA SUPPRIME"}</h1>
    </div>
{/if}
{if $resa_dupliquer}
    <div id="infobox">
        <h1>{_T string="RESERVATION.DUPLICATION"}</h1>
    </div>
{/if}
{if $erreur_resa}
    <div id="errorbox">
        <h1>
            <ul>
            {foreach from=$msg_erreur item=msg}
                <li>{$msg}</li>
            {/foreach}
            </ul>
        </h1>
    </div>
{/if}
{if $aff_msg_warning}
    <div id="warningbox">
        <h1><span id="message">{$msg_warning}</span></h1>
    </div>
<script>
function clignotement(){ldelim}
if (document.getElementById("message").style.color=="black")
document.getElementById("message").style.color="#FFB619";
else
document.getElementById("message").style.color="black";
{rdelim}
// mise en place de l appel régulier de la fonction toutes les 0.5 secondes
setInterval("clignotement()", 500);
</script> 
{/if}
{if $aff_msg_bloque}
    <div id="errorbox">
        <h1><span id="message">{$msg_blocage}</span></h1>
    </div>
<script>
function clignotement(){ldelim}
if (document.getElementById("message").style.color=="black")
document.getElementById("message").style.color="white";
else
document.getElementById("message").style.color="black";
{rdelim}
// mise en place de l appel régulier de la fonction toutes les 0.5 secondes
setInterval("clignotement()", 500);
</script> 
{/if}
<script type="text/javascript" src="js/tooltip.js"></script>
<div id="tooltip"></div>
<form action="reservation.php" method="post">
{*
    TABLE PRESENTANT LA LISTE DES AVIONS RESERVABLES
*}
{if $dessine_avion}
<div class="bigtable">
    <table class="details">
        <caption class="ui-state-active ui-corner-top">{_T string="RESERVATION.CHOIX"}</caption>
        <tr>
{foreach from=$liste_avions item=avion}
            <td style="text-align: center !important;{if $avion_id eq $avion->avion_id} background-color:SpringGreen;{/if}">
                <a href="?jour={$jour_selectionne}&avion_id={$avion->avion_id}">
                    <img src="{if $avion->hasPicture()}picture.php?avion_id={$avion->avion_id}&thumb=1{else}picts/avion.png{/if}" 
                        border="0" title="{_T string="RESERVATION.CHOISIR"} {$avion->marque_type} {$avion->type_aeronef}">
                </a>
                <br/>{$avion->marque_type} ({$avion->immatriculation})
                <br/>{$avion->type_aeronef}
            </td>
{/foreach}
        </tr>
    </table>
</div>
<p></p>
{/if}
{*
    DEBUT TABLE DU DETAIL DE LA RESERVATION POUR UN AVION SUR TOUTE LA SEMAINE
*}
{if $dessine_semaine}
<table id="listing">
    {* ENTETE : LIENS AVANT + ARRIERE + DATES DE LA SEMAINE + BOITE SAUT RAPIDE *}
    <caption class="ui-state-active ui-corner-top">
        <table align="center">
            <tr>
                <td width="60"><a href="?jour={$semaine_avant}&avion_id={$avion_id}" title="{_T string="RESERVATION.SEM PREC"}"><img src="picts/previous_32.png" border="0"></a></td>
                <td align="center">
                    {_T string="RESERVATION.SEMAINE"} {$numero_semaine} / {$annee}
                    <br/>{$debut_semaine} - {$fin_semaine}
                    <br/>{_T string="RESERVATION.ALLER"} : <input type="text" size="8" id="aller_date" value="{$jour_selectionne_IHM}">
                </td>
                <td width="60" align="right"><a href="?jour={$semaine_apres}&avion_id={$avion_id}" title="{_T string="RESERVATION.SEM SUIV"}"><img src="picts/next_32.png" border="0"></a></td>
            </tr>
        </table>
    </caption>
    {* FIN ENTETE *}
    {* LISTE DES JOURS AFFICHES DE LA SEMAINE *}
    <thead>
        <tr>
            <th></th>
{foreach from=$jours item=jour key=jourCode}
            <th class="listing" style="text-align: center">
                {$jour}
                {* LIEN POUR RESA DE LA JOURNEE COMPLETE *}
                {*<a href="?jour={$jourCode}&avion_id={$avion_id}&resa_jour={$jourCode}&resa_heure=&resa_heure_fin=">
                    <img src="picts/resa-avion.png" border="0">
                </a>*}
                <br><small><img src="picts/jour.png"> {$ephemeride[$jourCode]->lever} &#183;
                    <img src="picts/nuit.png"> {$ephemeride[$jourCode]->coucher}</small>
            </th>
{/foreach}
        </tr>
    </thead>
    {* FIN LISTE DES JOURS AFFICHES DE LA SEMAINE *}
    {* ALGO D'AFFICHAGE DES HEURES RESERVEES, NON RESERVEES, LIBRES, ETC. *}
    <tbody>
{foreach from=$heures item=heure}
        <tr>
            <th class="listing">{$heure}</th>
{foreach from=$jours item=jour}
{if $reservations[$jour][$heure]->interdit}
    {if $reservations[$jour][$heure]->libre}
            <td style="background-color: {if $reservations[$jour][$heure]->nuit}{$couleur_interdit_nuit}{else}{$couleur_interdit}{/if}; text-align: center">
            {if $reservations[$jour][$heure]->cliquable}
                <a href="?jour={$jour_selectionne}&avion_id={$avion_id}&resa_jour={$reservations[$jour][$heure]->code_jour}&resa_heure={$heure}">
                    <img src="picts/resa-avion.png" border="0" title="{_T string="RESERVATION.RESERVER LE"} {$jour} {_T string="RESERVATION.A PARTIR"} {$heure}">
                </a>
            {/if}
            </td>
    {else}
        {if !$reservations[$jour][$heure]->masquer}
            <td style="background-color: {if $reservations[$jour][$heure]->est_resa_club}{$couleur_interdit}{else}{$couleur_reserve}{/if}; text-align: center; vertical-align: top" rowspan="{$reservations[$jour][$heure]->rowspan}">
                {if $reservations[$jour][$heure]->cliquable && $reservations[$jour][$heure]->editable}
                    <a href="?jour={$jour_selectionne}&avion_id={$avion_id}&clone_resa_id={$reservations[$jour][$heure]->resa_id}">
                        <img src="picts/copy.png" border="0" align="right" title="{_T string="RESERVATION.COPIER"}">
                    </a>
                    <a href="?jour={$jour_selectionne}&avion_id={$avion_id}&resa_id={$reservations[$jour][$heure]->resa_id}">
                        <img src="picts/edit.png" border="0" align="right" title="{_T string="RESERVATION.MODIFIER"}">
                    </a>
                {/if}
                {$reservations[$jour][$heure]->infobulle}
            </td>
        {/if}
    {/if}
{else}
    {if $reservations[$jour][$heure]->libre}
            <td style="background-color: {if $reservations[$jour][$heure]->nuit}{$couleur_libre_nuit}{elseif $reservations[$jour][$heure]->cliquable}{$couleur_libre}{else}{$couleur_libre_clair}{/if}; text-align: center">
            {if $reservations[$jour][$heure]->cliquable}
                <a href="?jour={$jour_selectionne}&avion_id={$avion_id}&resa_jour={$reservations[$jour][$heure]->code_jour}&resa_heure={$heure}">
                    <img src="picts/resa-avion.png" border="0" title="{_T string="RESERVATION.RESERVER LE"} {$jour} {_T string="RESERVATION.A PARTIR"} {$heure}">
                </a>
            {/if}
            </td>
    {else}
        {if !$reservations[$jour][$heure]->masquer}
            <td style="background-color: {if $reservations[$jour][$heure]->est_resa_club}{$couleur_interdit}{else}{$couleur_reserve}{/if}; text-align: center; vertical-align: top" rowspan="{$reservations[$jour][$heure]->rowspan}">
                {if $reservations[$jour][$heure]->cliquable && $reservations[$jour][$heure]->editable}
                    <a href="?jour={$jour_selectionne}&avion_id={$avion_id}&clone_resa_id={$reservations[$jour][$heure]->resa_id}">
                        <img src="picts/copy.png" border="0" align="right" title="{_T string="RESERVATION.COPIER"}">
                    </a>
                    <a href="?jour={$jour_selectionne}&avion_id={$avion_id}&resa_id={$reservations[$jour][$heure]->resa_id}">
                        <img src="picts/edit.png" border="0" align="right" title="{_T string="RESERVATION.MODIFIER"}">
                    </a>
                {/if}
                {$reservations[$jour][$heure]->infobulle}
            </td>
        {/if}
   {/if}
{/if}
{/foreach}
        </tr>
{/foreach}
    </tbody>
    {* FIN TABLEAU PRINCIPAL HEURES RESERVEES, ETC. *}
</table>
<p></p>
{* LEGENDE *}
<table>
    <caption class="ui-state-active ui-corner-top"><b>{_T string="RESERVATION.LEGENDE"}</b></caption>
    <tr>
        <td style="background-color: {$couleur_interdit}">&nbsp; &nbsp; &nbsp;</td>
        <td>{_T string="RESERVATION.PAS RESA"}</td>
    </tr>
    <tr>
        <td style="background-color: {$couleur_interdit_nuit}">&nbsp; &nbsp; &nbsp;</td>
        <td>{_T string="RESERVATION.PAS RESA"} {_T string="RESERVATION.NUIT AERO"}</td>
    </tr>
    <tr>
        <td style="background-color: {$couleur_libre_clair}">&nbsp; &nbsp; &nbsp;</td>
        <td>{_T string="RESERVATION.LIBRE DEPASSE"}</td>
    </tr>
    <tr>
        <td style="background-color: {$couleur_libre}">&nbsp; &nbsp; &nbsp;</td>
        <td>{_T string="RESERVATION.LIBRE"}</td>
    </tr>
    <tr>
        <td style="background-color: {$couleur_libre_nuit}">&nbsp; &nbsp; &nbsp;</td>
        <td>{_T string="RESERVATION.LIBRE"} {_T string="RESERVATION.NUIT AERO"}</td>
    </tr>
    <tr>
        <td style="background-color: {$couleur_reserve}">&nbsp; &nbsp; &nbsp;</td>
        <td>{_T string="RESERVATION.RESERVE"}</td>
    </tr>
</table>
{*
    SCRIPT POUR AFFICHER LE CALENDRIER POUR CHOISIR DATE AFFICHAGE SEMAINE
*}
<script type="text/javascript">
    $(function() {ldelim}
        _collapsibleFieldsets();
        $('#aller_date').datepicker({ldelim}
            changeMonth: true,
            changeYear: true,
            showOn: 'both',
            buttonImage: '{$template_subdir}images/calendar.png',
            buttonImageOnly: true,
            yearRange: 'c-3:c+3',
            numberOfMonths: 2,
            selectOtherMonths: true,
            showOtherMonths: false,
            showWeek: true,
            onSelect: function(dateText, inst) {ldelim}
                dateArray = dateText.split('/');
                window.location = 'reservation.php?jour=' + dateArray[2] + dateArray[1] + dateArray[0] + '&avion_id={$avion_id}';
            {rdelim}
        {rdelim});
    {rdelim});
</script>
{/if}
{*
    TABLEAU DE MODIFICATION D'UNE RESERVATION
*}
{if $dessine_reservation}
<input type="hidden" name="avion_id" value="{$resa->id_avion}">
<input type="hidden" name="resa_debut" value="{$resa->heure_debut}">
<input type="hidden" name="resa_jour" value="{$resa_jour}">
<input type="hidden" name="resa_id" value="{$resa->reservation_id}">
<input type="hidden" name="origine" value="{$resa_origine}">
<div class="bigtable">
    <table class="details">
        <caption class="ui-state-active ui-corner-top">{_T string="RESERVATION.DETAIL RESA"}</caption>
        <tr>
            <th>{_T string="RESERVATION.RESA AVION"}</th>
            <td>{$resa_avion->nom} {$resa_avion->type} ({$resa_avion->immatriculation}) {$resa_avion->cout_horaire}</td>
        </tr>
        <tr>
            <th>{_T string="RESERVATION.RESA JOUR"}</th>
            <td><input type="text" name="resa_jour_debut" id="resa_jour_debut" value="{$resa_jour_debut}" size="8"
                       title="{_T string="RESERVATION.TITLE RESA JOUR"}"
                       onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)"></td>
        </tr>
        <tr>
            <th>{_T string="RESERVATION.RESA HEURE"}</th>
            <td>
                <select name="resa_heure_debut" id="resa_heure_debut" title="{_T string="RESERVATION.TITLE HEURE DEBUT"}"
                        onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)" onchange="calculDureeVol()">
                {foreach from=$heures item=h}
                    <option value="{$h}"{if $resa_heure eq $h} selected="selected"{/if}>{$h}</option>
                {/foreach}
                </select>
            </td>
        </tr>
{if $login->isAdmin() || $is_instructeur}
        <tr>
            <th>{_T string="RESERVATION.RESA ADH"}</th>
            <td>
                <select name="resa_id_adh" id="resa_id_adh" onChange="renseigneDetailsAdherent()"
                        title="{_T string="RESERVATION.TITLE ADHERENT"}"
                        onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)">
                    <option value="$$$$$$$$$">--- Choisir adhérent ---</option>
                    {foreach from=$liste_adherents item=adherent key=k_adh}
                    <option value="{$adherent->key}"{if $k_adh eq $resa->id_adherent} selected="selected"{/if}>{$adherent->value}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
{else}
<input type="hidden" name="resa_id_adh" value="{$resa->id_adherent}">
{/if}
        <tr>
            <th>{_T string="RESERVATION.RESA INSTR"}</th>
            <td>
                <select name="instructeur_id" title="{_T string="RESERVATION.TITLE INSTRUC"}"
                        onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)">
                    <option value="null">--- Aucun / Pas d'instructeur ---</option>
                    {foreach from=$liste_instructeurs item=instructeur key=id_instruct}
                    <option value="{$id_instruct}"{if $resa->id_instructeur eq $id_instruct} selected="selected"{/if}>{$instructeur->nom} ({$instructeur->code})</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <th>{_T string="RESERVATION.RESA DUREE"}</th>
            <td>
{*                <select name="resa_fin" title="{_T string="RESERVATION.TITLE DUREE"}"
                        onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)">
{foreach from=$resa_heures_fin item=fin_IHM key=fin_key}
                    <option value="{$fin_key}"{if $fin_key eq $resa->heure_fin} selected="selected"{/if}>{$fin_IHM}</option>
{/foreach}
                </select>
*}
                <select name="resa_heure_fin" id="resa_heure_fin" title="{_T string="RESERVATION.TITLE DUREE"}"
                        onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)" onchange="calculDureeVol()">
                {foreach from=$heures item=h}
                    <option value="{$h}"{if $resa_heure_fin eq $h} selected="selected"{/if}>{$h}</option>
                {/foreach}
                </select>
                soit
                <span id="calcul_duree_resa">0 min</span>
            </td>
        </tr>
        <tr>
            <th>{_T string="RESERVATION.TOUTE LA JOURNEE"}</th>
            <td>
                <input type="checkbox" id="resa_full_day" name="resa_full_day" value="yes" onchange="onFullDayChange()">
                <label for="resa_full_day">{_T string="RESERVATION.JOURNEE COMPLETE"}</label>
            </td>
        </tr>
        <tr>
            <th>{_T string="RESERVATION.RESA NOM"}</th>
            <td><input type="text" name="resa_nom" id="resa_nom" size="70" value="{$resa->nom}" required title="{_T string="RESERVATION.TITLE NOM"}" onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)"></td>
        </tr>
        <tr>
            <th>{_T string="RESERVATION.RESA DESTI"}</th>
            <td><input type="text" name="resa_destination" size="70" value="{$resa->destination}" title="{_T string="RESERVATION.TITLE DESTI"}" onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)"></td>
        </tr>
        <tr>
            <th>{_T string="RESERVATION.RESA EMAIL"}</th>
            <td><input type="text" name="resa_email" id="resa_email" size="70" value="{$resa->email_contact}" required title="{_T string="RESERVATION.TITLE EMAIL"}" onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)"></td>
        </tr>
        <tr>
            <th>{_T string="RESERVATION.RESA PORT"}</th>
            <td><input type="text" name="resa_portable" id="resa_portable" size="70" value="{$resa->no_portable}" required title="{_T string="RESERVATION.TITLE PORTABLE"}" onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)"></td>
        </tr>
        <tr>
            <th>{_T string="RESERVATION.RESA COMMENTS"}</th>
            <td><textarea name="resa_commentaires" id="resa_commentaires" cols="70" rows="4" style="font-family: Verdana,Arial,sans-serif; font-size: 0.85em;" title="{_T string="RESERVATION.TITLE COMMENTS"}" onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)">{$resa->commentaires}</textarea></td>
        </tr>
{if $login->isStaff() || $login->isAdmin()}
        <tr>
            <th>{_T string="RESERVATION.RESA RESA"}</th>
            <td><input type="checkbox" name="est_resa_club" id="est_resa_club" value="1"{if $resa->est_reservation_club} checked="checked"{/if} onchange="onReservationClubChange()"><label for="est_resa_club" title="{_T string="RESERVATION.TITLE RESA CLUB"}" onMouseOver="tooltip.show(this)" onMouseOut="tooltip.hide(this)">{_T string="RESERVATION.TITLE RESA CLUB LABEL"}</label></td>
        </tr>
{else}
    <input type="hidden" name="est_resa_club" value="0">
{/if}
    </table>
</div>
<div class="button-container">
{if $resa->reservation_id eq 'null' || $resa->reservation_id eq ''}
    <input type="submit" id="reserver" name="reserver" value="{_T string="RESERVATION.RESERVER"}">
{else}
    <input type="submit" id="sauver" name="reserver" value="{_T string="RESERVATION.SAUVER"}">
    <input type="submit" id="cloner" name="cloner" value="{_T string="RESERVATION.CLONER"}">
    <input type="submit" id="supprimer" name="supprimer" value="{_T string="RESERVATION.SUPPRIMER"}">
{/if}
    <input type="submit" id="annuler" name="annuler" value="{_T string="RESERVATION.ANNULER"}" onclick="document.location = 'reservation.php?jour={$resa_jour}&avion_id={$resa->id_avion}'; return false;">
</div>
<script type="text/javascript">
{*
    DONNE DES INFOS D'UN ADHERENT
*}
    function renseigneDetailsAdherent(){ldelim}
        var ligne = document.getElementById('resa_id_adh').options[document.getElementById('resa_id_adh').selectedIndex].value;
        var tableau = ligne.split('$$$');
        document.getElementById('resa_nom').value = tableau[1];
        document.getElementById('resa_email').value = tableau[2];
        document.getElementById('resa_portable').value = tableau[3];
    {rdelim}
{*
    SCRIPT POUR AFFICHER LE CALENDRIER DU DEBUT DE RESERVATION
*}
    $(function() {ldelim}
        _collapsibleFieldsets();
        $('#resa_jour_debut').datepicker({ldelim}
            changeMonth: true,
            changeYear: true,
            showOn: 'both',
            buttonImage: '{$template_subdir}images/calendar.png',
            buttonImageOnly: true,
            yearRange: 'c-3:c+3',
            numberOfMonths: 2,
            selectOtherMonths: true,
            showOtherMonths: false,
            showWeek: true,
        {rdelim});
    {rdelim});
{*
    CALCUL LA DUREE D'UN VOL
*}
    function calculDureeVol(){ldelim}
        var heure_debut = document.getElementById('resa_heure_debut').options[document.getElementById('resa_heure_debut').selectedIndex].value;
        var heure_fin = document.getElementById('resa_heure_fin').options[document.getElementById('resa_heure_fin').selectedIndex].value;
        var tableau = heure_debut.split(':');
        var debut = parseInt(tableau[0], 10);
        if(tableau[1] == '30')
            debut += 0.5;
        tableau = heure_fin.split(':');
        var fin = parseInt(tableau[0], 10);
        if(tableau[1] == '30')
            fin += 0.5;
       
        var delta = fin - debut;
        var nb_h = Math.floor(Math.abs(delta));
        
        var complement = '';
        if(delta - nb_h != 0)
            complement = ' 30min';
        
        if(delta < 0)
            document.getElementById('calcul_duree_resa').innerHTML = '<b><font color="red">#Erreur# -' + nb_h +'h' + complement + '</font></b>';
        else
            document.getElementById('calcul_duree_resa').innerHTML = '<b><font color="green">' + nb_h +'h' + complement + '</font></b>';
    {rdelim}
    calculDureeVol();
{*
    SELECTIONNE LA JOURNEE COMPLETE LORSQUE LA CHECKBOX EST COCHEE
*}
    var oldDebutIndex = 0;
    var oldFinIndex = 0;
    function onFullDayChange(){ldelim}
        if(document.getElementById('resa_full_day').checked){ldelim}
            oldDebutIndex = document.getElementById('resa_heure_debut').selectedIndex;
            oldFinIndex = document.getElementById('resa_heure_fin').selectedIndex;
            document.getElementById('resa_heure_debut').selectedIndex = 0;
            document.getElementById('resa_heure_fin').selectedIndex = document.getElementById('resa_heure_fin').options.length - 1;
        {rdelim} else {ldelim}
            document.getElementById('resa_heure_debut').selectedIndex = oldDebutIndex;
            document.getElementById('resa_heure_fin').selectedIndex = oldFinIndex;
        {rdelim}
        calculDureeVol();
    {rdelim}
{*
    REND OBLIGATOIRE OU NON LE NOM DU PILOTE, SON TEL ET SON EMAIL SELON RESERVATION CLUB
*}
    function onReservationClubChange(){ldelim}
        cbResaClub = document.getElementById('est_resa_club');
        if(cbResaClub === null || cbResaClub === undefined)
            return;
        
        document.getElementById('resa_nom').required = !cbResaClub.checked;
        document.getElementById('resa_email').required = !cbResaClub.checked;
        document.getElementById('resa_portable').required = !cbResaClub.checked;
        document.getElementById('resa_commentaires').required = cbResaClub.checked;

        document.getElementById('resa_nom').disabled = cbResaClub.checked;
        document.getElementById('resa_email').disabled = cbResaClub.checked;
        document.getElementById('resa_portable').disabled = cbResaClub.checked;

        if(cbResaClub.checked){ldelim}
            document.getElementById('resa_nom').value = '';
            document.getElementById('resa_email').value = '';
            document.getElementById('resa_portable').value = '';
        {rdelim}
            
        cbxIdAdh = document.getElementById('resa_id_adh');
        if(cbxIdAdh === null || cbxIdAdh === undefined)
            return;
        if(cbResaClub.checked)
            cbxIdAdh.selectedIndex = 0;
        cbxIdAdh.disabled = cbResaClub.checked;            
    {rdelim}
    onReservationClubChange();
</script>
{/if}
</form>
