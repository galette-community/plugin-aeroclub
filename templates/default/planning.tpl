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
    {if $ajax}
        <script>
            ajax_messages_box_styles();
        </script>
    {/if}
    <form action="planning.php" method="get">
        <table align="center">
            <tr>
                <td width="60">
                    <a href="?jour={$semaine_avant}" title="{_T string="RESERVATION.SEM PREC"}" class="tooltip_pilote">
                        <img src="picts/previous_32.png"/>
                    </a>
                </td>
                <td align="center">
                    {_T string="PLANNING.SEMAINE"} {$numero_semaine} / {$annee}
                    <br/>{$debut_semaine} - {$fin_semaine}
                    <br/>{_T string="PLANNING.ALLER"} : <input type="text" size="8" id="aller_date" value="{$jour_selectionne_IHM}">
                </td>
                <td width="60" align="right"><a href="?jour={$semaine_apres}" title="{_T string="RESERVATION.SEM SUIV"}" class="tooltip_pilote">
                        <img src="picts/next_32.png"/>
                    </a>
                </td>
            </tr>
        </table>
    </form>

    <p></p>

    <table id="listing">
        <caption class="ui-state-active ui-corner-top">{_T string="PLANNING.AVIONS"}</caption>
        <tr>
            {foreach from=$liste_avions item=avion}
                <td style="text-align: center !important">
                    <a href="reservation.php?avion_id={$avion->avion_id}&jour={$jour_selectionne}">
                        <img src="{if $avion->hasPicture()}picture.php?avion_id={$avion->immatriculation}&thumb=1&quick=1{else}picts/avion.png{/if}" class="tooltip_pilote" title="{$avion->tooltip}"/>
                    </a>
                    <br/>{$avion->marque_type} ({$avion->immatriculation})
                    <br/>{$avion->type_aeronef}
                    <br/>#{$avion->avion_id}
                </td>
            {/foreach}
        </tr>
        <tr>
            {foreach from=$liste_avions item=avion}
                {assign var=avion_id value=$avion->avion_id}
                <td>
                    <table align="center" class="listing">
                        <tr>
                            <td></td>
                            {foreach from=$jours item=j key=k}
                                <th width="25" class="center" style="font-weight: normal;"
                                    title="{$tooltip_jours[$k]}" class="tooltip_pilote">
                                    {$j}
                                </th>
                            {/foreach}
                        </tr>
                        {foreach from=$heures item=h name=table_heures}
                            <tr>
                                {if $smarty.foreach.table_heures.index is even}
                                    <th style="font-weight: normal;" rowspan="2">
                                        {$h}
                                    </th>
                                {/if}
                                {foreach from=$jours item=j key=jCode}
                                    {if $planning[$avion_id]->reservations[$jCode][$h]->interdit}
                                        {if $planning[$avion_id]->reservations[$jCode][$h]->libre}
                                            <td style="background-color: {if $planning[$avion_id]->reservations[$jCode][$h]->nuit}{$couleur_interdit_nuit}{else}{$couleur_interdit}{/if}{if $planning[$avion_id]->reservations[$jCode][$h]->cliquable}; cursor:hand; cursor:pointer;" 
                                                onClick="allerReserver('{$avion_id}', '{$jCode}', '{$h}')"
                                                title="{_T string="PLANNING.RESERVER"}" class="tooltip_pilote"{/if}">
                                            </td>
                                        {else}
                                            {if !$planning[$avion_id]->reservations[$jCode][$h]->masquer}
                                                <td style="background-color: {if $planning[$avion_id]->reservations[$jCode][$h]->est_resa_club}{$couleur_interdit}{else}{$couleur_reserve}{/if};" 
                                                    rowspan="{$planning[$avion_id]->reservations[$jCode][$h]->rowspan}"
                                                    title="{$planning[$avion_id]->reservations[$jCode][$h]->infobulle}"
                                                    class="tooltip_pilote">
                                                </td>
                                            {/if}
                                        {/if}
                                    {else}
                                        {if $planning[$avion_id]->reservations[$jCode][$h]->libre}
                                            <td style="background-color: {if $planning[$avion_id]->reservations[$jCode][$h]->nuit}{$couleur_libre_nuit}{elseif $planning[$avion_id]->reservations[$jCode][$h]->cliquable}{$couleur_libre}{else}{$couleur_libre_clair}{/if}; {if $planning[$avion_id]->reservations[$jCode][$h]->cliquable} cursor:hand; cursor:pointer;" 
                                                onClick="allerReserver('{$avion_id}', '{$jCode}', '{$h}')"
                                                title="{_T string="PLANNING.RESERVER"}" class="tooltip_pilote"{else}"{/if} height="8" class="tooltip_pilote">
                                            </td>
                                        {else}
                                            {if !$planning[$avion_id]->reservations[$jCode][$h]->masquer}
                                                <td style="background-color: {if $planning[$avion_id]->reservations[$jCode][$h]->est_resa_club}{$couleur_interdit}{else}{$couleur_reserve}{/if};" 
                                                    rowspan="{$planning[$avion_id]->reservations[$jCode][$h]->rowspan}"
                                                    title="{$planning[$avion_id]->reservations[$jCode][$h]->infobulle}"
                                                    class="tooltip_pilote">
                                                </td>
                                            {/if}
                                        {/if}
                                    {/if}
                                {/foreach}
                            </tr>
                        {/foreach}
                    </table>
                </td>
            {/foreach}
        </tr>
    </table>
    <p></p>
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
                {_T string="RESERVATION.LIBRE"} {_T string="RESERVATION.NUIT AERO"}
            </td>
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

    <script type="text/javascript">
        function allerReserver(avion_id, jour, heure) {
            go_reservation(jour, avion_id, undefined, jour, heure, undefined, 'planning');
            {* document.location.href = 'reservation.php?origine=planning&jour={$jour_selectionne}&avion_id=' + avion_id + '&resa_jour=' + jour + '&resa_heure=' + heure; *}
        }

        $(function() {
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
                onSelect: function(dateText, inst) {
                    dateArray = dateText.split('/');
                    window.location = 'planning.php?jour=' + dateArray[2] + dateArray[1] + dateArray[0];
                }
            });
        });
    </script>
</div>