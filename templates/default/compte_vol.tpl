{if $enregistre}
<div id="infobox">
<h1>{_T string="COMPTE VOL.ENREGISTRE OK"}</h1>
</div>
{/if}
{if $pas_enregistre}
<div id="warningbox">
<h1>{_T string="COMPTE VOL.PAS ENREGISTRE"}</h1>
</div>
{/if}
<form action="compte_vol.php" method="get">
<input type="hidden" name="tri" value="{$tri}">
<input type="hidden" name="direction" value="{$direction}">
<div class="bigtable">
    <fieldset class="cssform">
        <legend class="ui-state-active ui-corner-top">{_T string="COMPTE VOL.LISTE ANNEES"}</legend>
        <div>
            <p>
                <span class="bline">{_T string="COMPTE VOL.ANNEE"}</span>
                <select name="compte_annee">
                {foreach from=$liste_annees item=an}
                    <option value="{$an}"{if $annee_selectionnee eq $an} selected="selected"{/if}>{$an}</option>
                {/foreach}
                </select>
            </p>
{if $login->isAdmin()}
            <p>
                <span class="bline">{_T string="COMPTE VOL.ADHERENT"}</span>
                <select name="login_adherent">
                {foreach from=$liste_adherents item=adherent}
                    <option value="{$adherent->pseudo_adh}"{if $adherent_selectionne eq $adherent->pseudo_adh} selected="selected"{/if}>{$adherent->nom_adh} {$adherent->prenom_adh} ({$adherent->pseudo_adh})</option>
                {/foreach}
                </select>
            </p>
{/if}
        </div>
    </fieldset>
</div>
<div class="button-container">
    <input type="submit" id="calendrier" value="{_T string="COMPTE VOL.AFFICHER"}">
    <input type="submit" id="pdf" value="{_T string="COMPTE VOL.IMPRIMER"}" onClick="document.location.href='print_compte_vol.php?compte_annee={$annee_selectionnee}&pseudo={$adherent_selectionne}'; return false;">
</div>
<table class="infoline">
    <tr>
        <td class="left">{$nb_resultats} {_T string="COMPTE VOL.RESULTATS"}</td>
        <td class="right">{_T string="COMPTE VOL.NB LIGNES"}
            <select name="nb_lignes">
             {foreach from=$liste_nb_lignes item=nombre}
                <option value="{$nombre}"{if $nb_lignes eq $nombre} selected{/if}>{$nombre}</option>
             {/foreach}
            </select>
        </td>
    </tr>
</table>
</form>
<table id="listing">
    <thead>
        <tr>
            <th class="listing"><a href="?tri=date&direction={if $tri eq 'date' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="COMPTE VOL.DATE"}</a>{if $tri eq 'date' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'date' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"><a href="?tri=type&direction={if $tri eq 'type' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="COMPTE VOL.TYPE"}</a>{if $tri eq 'type' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'type' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"><a href="?tri=libelle&direction={if $tri eq 'libelle' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="COMPTE VOL.LIBELLE"}</a>{if $tri eq 'libelle' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'libelle' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"><a href="?tri=duree&direction={if $tri eq 'duree' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="COMPTE VOL.DUREE"}</a>{if $tri eq 'duree' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'duree' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"{if $login->isAdmin()} colspan="2"{/if}><a href="?tri=montant&direction={if $tri eq 'montant' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="COMPTE VOL.MONTANT"}</a>{if $tri eq 'montant' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'montant' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$liste_operations item=operation}
        <tr>
            <td class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->date_operation}</td>
            <td class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->type_operation}</td>
            <td class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->libelle_operation}</td>
            <td align="right" class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->duree}</td>
            <td align="right" class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}"><font color="{if $operation->montant < 0}red{else}green{/if}">{$operation->montant}</font></td>
{if $login->isAdmin()}
            <td align="center" class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}"><a href="new_operation.php?operation_id={$operation->operation_id}&origine=compte_vol"><img src="picts/edit.png" title="Modifier l'opération"></a></td>
{/if}
        </tr>
        {/foreach}
    </tbody>
</table>
<p align="center">{$pagination}</p>
<div class="bigtable">
    <table class="details">
        <caption class="ui-state-active ui-corner-top">{_T string="COMPTE VOL.TOTAUX"}</caption>
        <tr>
            <th>{_T string="COMPTE VOL.TOTAL VOL"} :</th>
            <td><b>{$total_vols}</b></td>
        </tr>
        <tr>
            <th>{_T string="COMPTE VOL.SOLDE"}{$date_jour} :</th>
            <td><b><font color="{if $solde < 0}red{else}green{/if}">{$solde}</font></b></td>
        </tr>
    </table>
</div>
