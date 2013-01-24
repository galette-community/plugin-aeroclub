{if $nb_maj > 0}
    <div id="infobox">{$nb_maj} {_T string="DISPO.ELEMENTS SAUVES"}</div>
{/if}
<form action="dispo.php" method="post">
<input type="hidden" name="avion_id" value="{$avion_id}">
<p></p>
<table class="details">
    <caption class="ui-state-active ui-corner-top">{_T string="DISPO.AVION"}</caption>
    <tr>
        <th class="tbl_line_even">{_T string="LISTE AVIONS.NOM"}</th>
        <td class="tbl_line_even">{$avion->nom} ({$avion->nom_court})</td>
    </tr>
    <tr>
        <th class="tbl_line_odd">{_T string="LISTE AVIONS.MARQUE TYPE"}</th>
        <td class="tbl_line_odd">{$avion->marque_type}</td>
    </tr>
    <tr>
        <th class="tbl_line_even">{_T string="LISTE AVIONS.TYPE AERONEF"}</th>
        <td class="tbl_line_even">{$avion->type_aeronef}</td>
    </tr>
    <tr>
        <th class="tbl_line_odd">{_T string="LISTE AVIONS.IMMAT"}</th>
        <td class="tbl_line_odd">{$avion->immatriculation}</td>
    </tr>
</table>
<table class="details">
    <caption class="ui-state-active ui-corner-top">{_T string="DISPO.CAPTION"}</caption>
    <thead>
        <tr>
            <th class="listing" width="5%"></th>
            <th class="listing" width="35%">{_T string="DISPO.DEBUT"}</th>
            <th class="listing" width="35%">{_T string="DISPO.FIN"}</th>
            <th class="listing" width="25%">{_T string="DISPO.SUPPRIMER"}</th>
        </tr>
    </thead>
    <tbody>
{foreach from=$liste_dispos item=dispo key=dispo_id name=liste}
        <tr>
            <td class="tbl_line_{if $smarty.foreach.liste.index is even}even{else}odd{/if}" width="5%">
            </td>
            <td class="tbl_line_{if $smarty.foreach.liste.index is even}even{else}odd{/if}" width="35%">
                <input type="hidden" name="dispos[]" value="{$dispo_id}">
                <input type="text" id="date_debut_{$dispo_id}" name="date_debut_{$dispo_id}" value="{$dispo->date_debut}">
            </td>
            <td class="tbl_line_{if $smarty.foreach.liste.index is even}even{else}odd{/if}" width="35%">
                <input type="text" id="date_fin_{$dispo_id}" name="date_fin_{$dispo_id}" value="{$dispo->date_fin}">
            </td>
            <td class="tbl_line_{if $smarty.foreach.liste.index is even}even{else}odd{/if}" width="25%">
                <input type="checkbox" id="supprimer_{$dispo_id}" name="supprimer_{$dispo_id}" value="supprime">
                <label for="supprimer_{$dispo_id}">{_T string="DISPO.SUPPRIMER LIGNE"}</label>
            </td>
        </tr>
{/foreach}
        <tr>
            <td style="background: #CDF195" width="5%" align="center">
                <img src="picts/plus.png" title="{_T string="DISPO.AJOUTER"}">
            </td>
            <td style="background: #CDF195" width="35%">
                <input type="text" id="date_debut_nouveau" name="date_debut_nouveau">
            </td>
            <td style="background: #CDF195" width="35%">
                <input type="text" id="date_fin_nouveau" name="date_fin_nouveau">
            </td>
            <td style="background: #CDF195" width="25%">
            </td>
        </tr>
    </tbody>
</table>
<p>{_T string="DISPO.EXPLICATION"}</p>
<div class="button-container">
    <input type="submit" id="dispo" name="valider" value="{_T string="DISPO.VALIDER"}">
    <input type="submit" id="annuler" name="retour" value="{_T string="DISPO.RETOUR"}">
</div>
</form>

<script type="text/javascript">

{foreach from=$liste_dispos item=dispo key=dispo_id}
    $('#date_debut_{$dispo_id}').datepicker({ldelim}
        changeMonth: true,
        changeYear: true,
        showOn: 'both',
        buttonImage: '{$template_subdir}images/calendar.png',
        buttonImageOnly: true,
        yearRange: 'c-5:c+5'
    {rdelim});
    $('#date_fin_{$dispo_id}').datepicker({ldelim}
        changeMonth: true,
        changeYear: true,
        showOn: 'both',
        buttonImage: '{$template_subdir}images/calendar.png',
        buttonImageOnly: true,
        yearRange: 'c-5:c+5'
    {rdelim});
{/foreach}

    $('#date_debut_nouveau').datepicker({ldelim}
        changeMonth: true,
        changeYear: true,
        showOn: 'both',
        buttonImage: '{$template_subdir}images/calendar.png',
        buttonImageOnly: true,
        yearRange: 'c-5:c+5'
    {rdelim});
    $('#date_fin_nouveau').datepicker({ldelim}
        changeMonth: true,
        changeYear: true,
        showOn: 'both',
        buttonImage: '{$template_subdir}images/calendar.png',
        buttonImageOnly: true,
        yearRange: 'c-5:c+5'
    {rdelim});

</script>