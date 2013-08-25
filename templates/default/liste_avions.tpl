{if $enregistre}
<div id="infobox">
<h1>{_T string="LISTE AVIONS.ENREGISTRE OK"}</h1>
</div>
{/if}
{if $pas_enregistre}
<div id="warningbox">
<h1>{_T string="LISTE AVIONS.PAS ENREGISTRE"}</h1>
</div>
{/if}
{if $supprime}
<div id="errorbox">
<h1>{_T string="LISTE AVIONS.SUPPRIME"}</h1>
</div>
{/if}
<p>
{$nb_resultats} {_T string="LISTE AVIONS.RESULTATS"}
</p>
<script>
function confirmerSuppression(nom, avion_id) {ldelim}
    if (confirm('{_T string="LISTE AVIONS.CONFIRM SUPPR"}' + nom)) {ldelim}
        window.location = 'supprimer_avion.php?avion_id=' + avion_id;
    {rdelim}
    return false;
{rdelim}
</script>
<table class="listing">
    <thead>
        <tr>
            <th><a href="?tri=nom&direction={if $tri eq 'nom' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="LISTE AVIONS.NOM"}</a>{if $tri eq 'nom' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'nom' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th><a href="?tri=marque_type&direction={if $tri eq 'marque_type' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="LISTE AVIONS.MARQUE TYPE"}</a>{if $tri eq 'marque_type' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'marque_type' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th><a href="?tri=type_aeronef&direction={if $tri eq 'type_aeronef' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="LISTE AVIONS.TYPE AERONEF"}</a>{if $tri eq 'type_aeronef' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'type_aeronef' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th><a href="?tri=immatriculation&direction={if $tri eq 'immatriculation' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="LISTE AVIONS.IMMAT"}</a>{if $tri eq 'immatriculation' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'immatriculation' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th><a href="?tri=couleur&direction={if $tri eq 'couleur' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="LISTE AVIONS.COULEUR"}</a>{if $tri eq 'couleur' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'couleur' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th><a href="?tri=nb_places&direction={if $tri eq 'nb_places' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="LISTE AVIONS.NB PLACES"}</a>{if $tri eq 'nb_places' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'nb_places' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th>{_T string="LISTE AVIONS.DC"}</th>
            <th>{_T string="LISTE AVIONS.REMORQUEUR"}</th>
            <th>{_T string="LISTE AVIONS.RESERVABLE"}</th>
            <th colspan="2">{_T string="LISTE AVIONS.DATES"}</th>
            <th colspan="2"><a href="?tri=cout_horaire&direction={if $tri eq 'cout_horaire' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="LISTE AVIONS.COUT"}</a>{if $tri eq 'cout_horaire' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'cout_horaire' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
        </tr>
    </thead>
    <tbody>
    {foreach from=$liste_avions item=avion}
        <tr>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}">{$avion->nom} ({$avion->nom_court})</td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}">{$avion->marque_type}</td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}">{$avion->type_aeronef}</td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}">{$avion->immatriculation}</td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}">{$avion->couleur}</td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}">{$avion->nb_places}</td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}" align="center">{if $avion->DC_autorisee}<img src="picts/check.png">{/if}</td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}" align="center">{if $avion->est_remorqueur}<img src="picts/check.png">{/if}</td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}" align="center">{if $avion->est_reservable}<img src="picts/check.png">{/if}</td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}">
                <a href="dispo.php?avion_id={$avion->avion_id}"><img src="picts/dispos.png" title="Modifier dates de disponibilité"></a>
            </td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}">
                {$avion->date_debut} » {$avion->date_fin}
            </td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}">{$avion->cout_horaire}</td>
            <td class="tbl_line_{if $avion->numero_ligne % 2 eq 0}even{else}odd{/if}" width="40">
                <a href="modifier_avion.php?avion_id={$avion->avion_id}"><img src="picts/edit.png" title="Modifier" border="0"></a>
                <a href="javascript:void(0)"><img src="picts/delete.png" title="Supprimer" border="0" onClick="confirmerSuppression('{$avion->nom} {$avion->type} ({$avion->immatriculation})', '{$avion->avion_id}')"></a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
<p align="center">{$pagination}</p>
<form action="modifier_avion.php?avion_id=new" method="get">
<div class="button-container">
    <input type="submit" id="ajout_avion" value="{_T string="LISTE AVIONS.AJOUTER"}">
</div>
</form>