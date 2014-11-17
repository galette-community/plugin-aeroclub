<div id="pilote_content">
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
            setInterval("clignotement()", 500);
        </script> 

    {/if}
    {if $aff_msg_bloque}
        <div id="errorbox">
            <h1><span id="message">{$msg_blocage}</span></h1>
        </div>

        <script>
            setInterval("clignotement()", 500);
        </script> 
    {/if}
    {if $ajax}
        <script>
            ajax_messages_box_styles();
        </script>
    {/if}

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
                                <img src="{if $avion->hasPicture()}picture.php?avion_id={$avion->immatriculation}&thumb=1&quick=1{else}picts/avion.png{/if}"  class="tooltip_pilote" title="{$avion->tooltip}"/>
                                <br/>{$avion->marque_type} ({$avion->immatriculation})
                                <br/>{$avion->type_aeronef}
                            </a>
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
        <form action="reservation.php" method="post" id="form_tableau_semaine">
            {* ENTETE : LIENS AVANT + ARRIERE + DATES DE LA SEMAINE + BOITE SAUT RAPIDE *}
            <table align="center">
                <tr>
                    <td width="60">
                        <a href="?jour={$semaine_avant}&avion_id={$avion_id}" class="tooltip_pilote" title="{_T string="RESERVATION.SEM PREC"}">
                            <img src="picts/previous_32.png"/>
                        </a>
                    </td>
                    <td align="center">
                        {_T string="RESERVATION.SEMAINE"} {$numero_semaine} / {$annee}
                        <br/>{$debut_semaine} - {$fin_semaine}
                        <br/>{_T string="RESERVATION.ALLER"} : <input type="text" size="8" id="aller_date" value="{$jour_selectionne_IHM}">
                    </td>
                    <td width="60" align="right">
                        <a href="?jour={$semaine_apres}&avion_id={$avion_id}" class="tooltip_pilote" title="{_T string="RESERVATION.SEM SUIV"}">
                            <img src="picts/next_32.png"/>
                        </a>
                    </td>
                </tr>
            </table>
            <p>
            </p>
            {* FIN ENTETE *}
            <table class="listing">
                {* LISTE DES JOURS AFFICHES DE LA SEMAINE *}
                <thead>
                    <tr>
                        <th></th>
                            {foreach from=$jours item=jour key=jourCode}
                            <th class="center" style="font-weight: normal;">
                                {$jour}
                                {* LIEN POUR RESA DE LA JOURNEE COMPLETE *}
                                {*<a href="?jour={$jourCode}&avion_id={$avion_id}&resa_jour={$jourCode}&resa_heure=&resa_heure_fin=">
                                <img src="picts/resa-avion.png"/>
                                </a>*}
                                <br><small><img src="picts/jour.png"/> {$ephemeride[$jourCode]->lever} &#183;
                                    <img src="picts/nuit.png"/> {$ephemeride[$jourCode]->coucher}</small>
                            </th>
                        {/foreach}
                    </tr>
                </thead>
                {* FIN LISTE DES JOURS AFFICHES DE LA SEMAINE *}
                {* ALGO D'AFFICHAGE DES HEURES RESERVEES, NON RESERVEES, LIBRES, ETC. *}
                <tbody>
                    {foreach from=$heures item=heure}
                        <tr>
                            <th class="center" style="font-weight: normal;">{$heure}</th>
                                {foreach from=$jours item=jour}
                                    {if $reservations[$jour][$heure]->interdit}
                                        {if $reservations[$jour][$heure]->libre}
                                        <td style="background-color: {if $reservations[$jour][$heure]->nuit}{$couleur_interdit_nuit}{else}{$couleur_interdit}{/if}; text-align: center">
                                            {if $reservations[$jour][$heure]->cliquable}
                                                <a onclick="go_reservation('{$jour_selectionne}', {$avion_id}, undefined, '{$reservations[$jour][$heure]->code_jour}', '{$heure}', undefined);" style="cursor: pointer;">
                                                    <img src="picts/resa-avion.png" class="tooltip_pilote" title="{_T string="RESERVATION.RESERVER LE"} {$jour} {_T string="RESERVATION.A PARTIR"} {$heure}"/>
                                                </a>
                                            {/if}
                                        </td>
                                    {else}
                                        {if !$reservations[$jour][$heure]->masquer}
                                            <td style="background-color: {if $reservations[$jour][$heure]->est_resa_club}{$couleur_interdit}{else}{$couleur_reserve}{/if}; text-align: center; vertical-align: top" rowspan="{$reservations[$jour][$heure]->rowspan}">
                                                {if $reservations[$jour][$heure]->cliquable && $reservations[$jour][$heure]->editable}
                                                    <a onclick="go_reservation('{$jour_selectionne}', {$avion_id}, undefined, undefined, undefined, {$reservations[$jour][$heure]->resa_id});" style="cursor: pointer;">
                                                        <img src="picts/copy.png" class="tooltip_pilote" align="right" title="{_T string="RESERVATION.COPIER"}"/>
                                                    </a>
                                                    <a onclick="go_reservation('{$jour_selectionne}', {$avion_id}, '{$reservations[$jour][$heure]->resa_id}', undefined, undefined, undefined);" style="cursor: pointer;">
                                                        <img src="picts/edit.png" class="tooltip_pilote" align="right" title="{_T string="RESERVATION.MODIFIER"}"/>
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
                                                <a onclick="go_reservation('{$jour_selectionne}', {$avion_id}, undefined, '{$reservations[$jour][$heure]->code_jour}', '{$heure}', undefined);" style="cursor: pointer;">
                                                    <img src="picts/resa-avion.png" class="tooltip_pilote" title="{_T string="RESERVATION.RESERVER LE"} {$jour} {_T string="RESERVATION.A PARTIR"} {$heure}"/>
                                                </a>
                                            {/if}
                                        </td>
                                    {else}
                                        {if !$reservations[$jour][$heure]->masquer}
                                            <td style="background-color: {if $reservations[$jour][$heure]->est_resa_club}{$couleur_interdit}{else}{$couleur_reserve}{/if}; text-align: center; vertical-align: top" rowspan="{$reservations[$jour][$heure]->rowspan}">
                                                {if $reservations[$jour][$heure]->cliquable && $reservations[$jour][$heure]->editable}
                                                    <a onclick="go_reservation('{$jour_selectionne}', {$avion_id}, undefined, undefined, undefined, {$reservations[$jour][$heure]->resa_id});" style="cursor: pointer;">
                                                        <img src="picts/copy.png" class="tooltip_pilote" align="right" title="{_T string="RESERVATION.COPIER"}"/>
                                                    </a>
                                                    <a onclick="go_reservation('{$jour_selectionne}', {$avion_id}, {$reservations[$jour][$heure]->resa_id}, undefined, undefined, undefined);" style="cursor: pointer;">
                                                        <img src="picts/edit.png" class="tooltip_pilote" align="right" title="{_T string="RESERVATION.MODIFIER"}"/>
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
        </form>
        <p></p>
        {* LEGENDE *}
        <table>
            <caption class="ui-state-active ui-corner-top"><b>{_T string="RESERVATION.LEGENDE"}</b></caption>
            <tr>
                <td style="background-color: {$couleur_interdit}">
                    &nbsp; &nbsp; &nbsp; &nbsp;
                </td>
                <td>
                    {_T string="RESERVATION.PAS RESA"}
                </td>
            </tr>
            <tr>
                <td style="background-color: {$couleur_interdit_nuit}">
                    &nbsp; &nbsp; &nbsp; &nbsp;
                </td>
                <td>
                    {_T string="RESERVATION.PAS RESA"} {_T string="RESERVATION.NUIT AERO"}
                </td>
            </tr>
            <tr>
                <td style="background-color: {$couleur_libre_clair}">
                    &nbsp; &nbsp; &nbsp; &nbsp;
                </td>
                <td>
                    {_T string="RESERVATION.LIBRE DEPASSE"}
                </td>
            </tr>
            <tr>
                <td style="background-color: {$couleur_libre}">
                    &nbsp; &nbsp; &nbsp; &nbsp;
                </td>
                <td>
                    {_T string="RESERVATION.LIBRE"}
                </td>
            </tr>
            <tr>
                <td style="background-color: {$couleur_libre_nuit}">
                    &nbsp; &nbsp; &nbsp; &nbsp;
                </td>
                <td>
                    {_T string="RESERVATION.LIBRE"} {_T string="RESERVATION.NUIT AERO"}</td>
            </tr>
            <tr>
                <td style="background-color: {$couleur_reserve}">
                    &nbsp; &nbsp; &nbsp; &nbsp;
                </td>
                <td>
                    {_T string="RESERVATION.RESERVE"}
                </td>
            </tr>
        </table>
        {*
        SCRIPT POUR AFFICHER LE CALENDRIER POUR CHOISIR DATE AFFICHAGE SEMAINE
        *}
        <script type="text/javascript">
            $(function () {
                _collapsibleFieldsets();
                $('#aller_date').datepicker({
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
                    onSelect: function (dateText, inst) {
                        dateArray = dateText.split('/');
                        window.location = 'reservation.php?jour=' + dateArray[2] + dateArray[1] + dateArray[0] + '&avion_id={$avion_id}';
                    }
                });
            });
        </script>
    {/if}
    {*
    TABLEAU DE MODIFICATION D'UNE RESERVATION
    *}
    {if $dessine_reservation}
        <form action="reservation.php" method="post" id="form_reservation">
            <input type="hidden" name="avion_id" value="{$resa->id_avion}"/>
            <input type="hidden" name="resa_debut" value="{$resa->heure_debut}"/>
            <input type="hidden" name="resa_jour" value="{$resa_jour}"/>
            <input type="hidden" name="resa_id" value="{$resa->reservation_id}"/>
            <input type="hidden" name="origine" value="{$resa_origine}"/>
            {if $ajax}
                <input type="hidden" name="mode" value="ajax"/>
                <img src="picts/close.png" title="{_T string="AJAX.CLOSE"}" alt="{_T string="AJAX.CLOSE"}" id="button_close"/>
            {/if}
            <div class="bigtable">
                <table width="100%">
                    <caption class="ui-state-active ui-corner-top">{_T string="RESERVATION.DETAIL RESA"}</caption>
                    <tr>
                        <th>{_T string="RESERVATION.RESA AVION"}</th>
                        <td>{$resa_avion->nom} {$resa_avion->type} ({$resa_avion->immatriculation}) {$resa_avion->cout_horaire}</td>
                    </tr>
                    <tr>
                        <th>{_T string="RESERVATION.RESA JOUR"}</th>
                        <td><input type="text" name="resa_jour_debut" id="resa_jour_debut" value="{$resa_jour_debut}" size="8"
                                   title="{_T string="RESERVATION.TITLE RESA JOUR"}"
                                   class="tooltip_pilote"></td>
                    </tr>
                    <tr>
                        <th>{_T string="RESERVATION.RESA HEURE"}</th>
                        <td>
                            <select name="resa_heure_debut" id="resa_heure_debut" title="{_T string="RESERVATION.TITLE HEURE DEBUT"}"
                                    class="tooltip_pilote" onchange="calculDureeVol()">
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
                                        class="tooltip_pilote">
                                    <option value="$$$$$$$$$">{_T string="RESERVATION.CHOIX ADHERENT"}</option>
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
                                    class="tooltip_pilote">
                                <option value="null">{_T string="RESERVATION.CHOIX INSTRUC"}</option>
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
                            class="tooltip_pilote">
                            {foreach from=$resa_heures_fin item=fin_IHM key=fin_key}
                            <option value="{$fin_key}"{if $fin_key eq $resa->heure_fin} selected="selected"{/if}>{$fin_IHM}</option>
                            {/foreach}
                            </select>
                            *}
                            <select name="resa_heure_fin" id="resa_heure_fin" title="{_T string="RESERVATION.TITLE DUREE"}"
                                    class="tooltip_pilote" onchange="calculDureeVol()">
                                {foreach from=$heures item=h}
                                    <option value="{$h}"{if $resa_heure_fin eq $h} selected="selected"{/if}>{$h}</option>
                                {/foreach}
                            </select>
                            {_T string="RESERVATION.SOIT"}
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
                        <td>
                            <input type="text" name="resa_nom" id="resa_nom" size="70" value="{$resa->nom}" required title="{_T string="RESERVATION.TITLE NOM"}" class="tooltip_pilote">
                        </td>
                    </tr>
                    <tr>
                        <th>{_T string="RESERVATION.RESA DESTI"}</th>
                        <td>
                            <input type="text" name="resa_destination" size="70" value="{$resa->destination}" title="{_T string="RESERVATION.TITLE DESTI"}" class="tooltip_pilote">
                        </td>
                    </tr>
                    <tr>
                        <th>{_T string="RESERVATION.RESA EMAIL"}</th>
                        <td>
                            <input type="text" name="resa_email" id="resa_email" size="70" value="{$resa->email_contact}" required title="{_T string="RESERVATION.TITLE EMAIL"}" class="tooltip_pilote">
                        </td>
                    </tr>
                    <tr>
                        <th>{_T string="RESERVATION.RESA PORT"}</th>
                        <td>
                            <input type="text" name="resa_portable" id="resa_portable" size="70" value="{$resa->no_portable}" required title="{_T string="RESERVATION.TITLE PORTABLE"}" class="tooltip_pilote">
                        </td>
                    </tr>
                    <tr>
                        <th>{_T string="RESERVATION.RESA COMMENTS"}</th>
                        <td>
                            <textarea name="resa_commentaires" id="resa_commentaires" cols="70" rows="4" style="font-family: Verdana,Arial,sans-serif; font-size: 0.85em;" title="{_T string="RESERVATION.TITLE COMMENTS"}" class="tooltip_pilote">{$resa->commentaires}</textarea>
                        </td>
                    </tr>
                    {if $login->isStaff() || $login->isAdmin()}
                        <tr>
                            <th>{_T string="RESERVATION.RESA RESA"}</th>
                            <td>
                                <input type="checkbox" name="est_resa_club" id="est_resa_club" value="1"{if $resa->est_reservation_club} checked="checked"{/if} onchange="onReservationClubChange()">
                                <label for="est_resa_club" title="{_T string="RESERVATION.TITLE RESA CLUB"}" class="tooltip_pilote">
                                    {_T string="RESERVATION.TITLE RESA CLUB LABEL"}
                                </label>
                            </td>
                        </tr>
                    {else}
                        <input type="hidden" name="est_resa_club" value="0">
                    {/if}
                </table>
            </div>
            <p>
                {if $erreur_resa}
                <ul style="background: url('../../templates/default/images/error.png') no-repeat scroll 5px 2px rgba(0, 0, 0, 0); border: 2px solid #CC0000; padding: 6px 50px;">
                    {foreach from=$msg_erreur item=msg}
                        <li style="list-style: none;">{$msg}</li>
                        {/foreach}
                </ul>
            {/if}
            </p>
            <div class="button-container" id="button_container">
                {if $resa->reservation_id eq 'null' || $resa->reservation_id eq ''}
                    <input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all" id="reserver" name="reserver" value="{_T string="RESERVATION.RESERVER"}">
                {else}
                    <input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all" id="sauver" name="reserver" value="{_T string="RESERVATION.SAUVER"}">
                    <input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all" id="cloner" name="cloner" value="{_T string="RESERVATION.CLONER"}">
                    <input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all" id="supprimer" name="supprimer" value="{_T string="RESERVATION.SUPPRIMER"}">
                {/if}
                <input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all" id="annuler" name="annuler" value="{_T string="RESERVATION.ANNULER"}" {*onclick="document.location = 'reservation.php?jour={$resa_jour}&avion_id={$resa->id_avion}'; return false;"*}>
            </div>
            <script type="text/javascript">
                {if $ajax}
                $('#annuler').click(function () {
                    close_ajax_pilote();
                    return false;
                });
                $('#button_close').click(close_ajax_pilote);
                $('#reserver').click(function () {
                    ajax_post_reservation('reserver');
                    return false;
                });
                $('#sauver').click(function () {
                    ajax_post_reservation('reserver');
                    return false;
                });
                $('#cloner').click(function () {
                    ajax_post_reservation('cloner');
                    return false;
                });
                $('#supprimer').click(function () {
                    if (confirm('{_T string="RESERVATION.CONFIRM SUPPRESSION"}')) {
                        ajax_post_reservation('supprimer');
                    }
                    return false;
                });
                {else}
                $('#annuler').click(function () {
                    document.location = 'reservation.php?jour={$resa_jour}&avion_id={$resa->id_avion}';
                    return false;
                });
                {/if}
                {*
                DONNE DES INFOS D'UN ADHERENT
                *}
                function renseigneDetailsAdherent() {
                    var ligne = $('#resa_id_adh').val();
                    var tableau = ligne.split('$$$');
                    $('#resa_nom').val(tableau[1]);
                    $('#resa_email').val(tableau[2]);
                    $('#resa_portable').val(tableau[3]);
                }
                {*
                SCRIPT POUR AFFICHER LE CALENDRIER DU DEBUT DE RESERVATION
                *}
                $(function () {
                    _collapsibleFieldsets();
                    $('#resa_jour_debut').datepicker({
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
                    });
                });
                {*
                CALCUL LA DUREE D'UN VOL
                *}
                function calculDureeVol() {
                    var heure_debut = $('#resa_heure_debut').val();
                    var heure_fin = $('#resa_heure_fin').val();
                    var tableau = heure_debut.split(':');
                    var debut = parseInt(tableau[0], 10);
                    if (tableau[1] === '30') {
                        debut += 0.5;
                    }
                    tableau = heure_fin.split(':');
                    var fin = parseInt(tableau[0], 10);
                    if (tableau[1] === '30') {
                        fin += 0.5;
                    }

                    var delta = fin - debut;
                    var nb_h = Math.floor(Math.abs(delta));

                    var complement = '';
                    if (delta - nb_h !== 0) {
                        complement = ' 30min';
                    }

                    if (delta < 0) {
                        $('#calcul_duree_resa').html('<b><font color="red">#Erreur# -' + nb_h + 'h' + complement + '</font></b>');
                    } else {
                        $('#calcul_duree_resa').html('<b><font color="green">' + nb_h + 'h' + complement + '</font></b>');
                    }
                }
                calculDureeVol();
                {*
                SELECTIONNE LA JOURNEE COMPLETE LORSQUE LA CHECKBOX EST COCHEE
                *}
                var oldDebutIndex = 0;
                var oldFinIndex = 0;
                function onFullDayChange() {
                    if ($('#resa_full_day').is(':checked')) {
                        oldDebutIndex = $('#resa_heure_debut option:selected').index();
                        oldFinIndex = $('#resa_heure_fin option:selected').index;
                        $('#resa_heure_debut')[0].selectedIndex = 0;
                        $('#resa_heure_fin')[0].selectedIndex = $('#resa_heure_fin')[0].options.length - 1;
                    } else {
                        $('#resa_heure_debut')[0].selectedIndex = oldDebutIndex;
                        $('#resa_heure_fin')[0].selectedIndex = oldFinIndex;
                    }
                    calculDureeVol();
                }
                {*
                REND OBLIGATOIRE OU NON LE NOM DU PILOTE, SON TEL ET SON EMAIL SELON RESERVATION CLUB
                *}
                function onReservationClubChange() {
                    cbResaClub = document.getElementById('est_resa_club');
                    if (cbResaClub === null || cbResaClub === undefined) {
                        return;
                    }

                    document.getElementById('resa_nom').required = !cbResaClub.checked;
                    document.getElementById('resa_email').required = !cbResaClub.checked;
                    document.getElementById('resa_portable').required = !cbResaClub.checked;
                    document.getElementById('resa_commentaires').required = cbResaClub.checked;

                    document.getElementById('resa_nom').disabled = cbResaClub.checked;
                    document.getElementById('resa_email').disabled = cbResaClub.checked;
                    document.getElementById('resa_portable').disabled = cbResaClub.checked;

                    if (cbResaClub.checked) {
                        document.getElementById('resa_nom').value = '';
                        document.getElementById('resa_email').value = '';
                        document.getElementById('resa_portable').value = '';
                    }

                    cbxIdAdh = document.getElementById('resa_id_adh');
                    if (cbxIdAdh === null || cbxIdAdh === undefined) {
                        return;
                    }
                    if (cbResaClub.checked) {
                        cbxIdAdh.selectedIndex = 0;
                    }
                    cbxIdAdh.disabled = cbResaClub.checked;
                }
                onReservationClubChange();
            </script>
        </form>
    {/if}
</div>