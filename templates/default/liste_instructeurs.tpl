{if $enregistre}
<div id="infobox">
<h1>{_T string="LISTE INSTRUCTEURS.ENREGISTRE OK"}</h1>
</div>
{/if}
{if $pas_enregistre}
<div id="warningbox">
<h1>{_T string="LISTE INSTRUCTEURS.PAS ENREGISTRE"}</h1>
</div>
{/if}
{if $supprime}
<div id="errorbox">
<h1>{_T string="LISTE INSTRUCTEURS.SUPPRIME"}</h1>
</div>
{/if}
<p>
{$nb_resultats} {_T string="LISTE INSTRUCTEURS.RESULTATS"}
</p>
<script>
function confirmerSuppression(nom, instructeur_id) {ldelim}
    if (confirm('{_T string="LISTE INSTRUCTEURS.CONFIRM SUPPR"}' + nom)) {ldelim}
        window.location = 'supprimer_instructeur.php?instructeur_id=' + instructeur_id;
    {rdelim}
    return false;
{rdelim}
</script>
<table id="listing">
    <thead>
        <tr>
            <th class="listing"><a href="?tri=code&direction={if $tri eq 'code' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="LISTE INSTRUCTEURS.CODE"}</a>{if $tri eq 'code' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'code' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"><a href="?tri=nom&direction={if $tri eq 'nom' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="LISTE INSTRUCTEURS.NOM"}</a>{if $tri eq 'nom' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'nom' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing" colspan="2"><a href="?tri=code_adherent&direction={if $tri eq 'code_adherent' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="LISTE INSTRUCTEURS.CODE ADHERENT"}</a>{if $tri eq 'code_adherent' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'code_adherent' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
        </tr>
        </tr>
    </thead>
    <tbody>
    {foreach from=$liste_instructeurs item=instructeur}
        <tr>
            <td class="tbl_line_{if $instructeur->numero_ligne is even}even{else}odd{/if}">{$instructeur->code}</td>
            <td class="tbl_line_{if $instructeur->numero_ligne is even}even{else}odd{/if}">{$instructeur->nom}</td>
            <td class="tbl_line_{if $instructeur->numero_ligne is even}even{else}odd{/if}">{if $instructeur->adherent_id != null}<a href="{$galette_base_path}voir_adherent.php?id_adh={$instructeur->adherent_id}">{$instructeur->code_adherent}</a>{/if}</td>
            <td class="tbl_line_{if $instructeur->numero_ligne is even}even{else}odd{/if}" width="40">
                <a href="modifier_instructeur.php?instructeur_id={$instructeur->instructeur_id}"><img src="picts/edit.png" title="Modifier" border="0"></a>
                <a href="javascript:void(0)"><img src="picts/delete.png" title="Supprimer" border="0" onClick="confirmerSuppression('{$instructeur->code} {$instructeur->nom}', '{$instructeur->instructeur_id}')"></a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
<p align="center">{$pagination}</p>
<form action="modifier_instructeur.php?instructeur_id=new" method="get">
<div class="button-container">
    <input type="submit" id="ajout_instructeur" value="{_T string="LISTE INSTRUCTEURS.AJOUTER"}">
</div>
</form>