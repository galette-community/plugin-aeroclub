{if $enregistre}
<div id="infobox">
<h1>{_T string="LISTE VOLS.ENREGISTRE OK"}</h1>
</div>
{/if}
{if $pas_enregistre}
<div id="warningbox">
<h1>{_T string="LISTE VOLS.PAS ENREGISTRE"}</h1>
</div>
{/if}
<form action="liste_vols.php" method="get">
<input type="hidden" name="tri" value="{$tri}">
<input type="hidden" name="direction" value="{$direction}">
<div class="bigtable">
    <fieldset class="cssform">
        <legend class="ui-state-active ui-corner-top">{_T string="LISTE VOLS.LISTE ANNEES"}</legend>
        <div>
            <p>
                <span class="bline">{_T string="LISTE VOLS.ANNEE"}</span>
                <select name="compte_annee">
                {foreach from=$liste_annees item=an}
                    <option value="{$an}"{if $annee_selectionnee eq $an} selected="selected"{/if}>{$an}</option>
                {/foreach}
                </select>
            </p>
{if $login->isAdmin()}
            <p>
                <span class="bline">{_T string="LISTE VOLS.ADHERENT"}</span>
                <select name="login_adherent">
                {foreach from=$liste_adherents key=pseudo item=adherent}
                    <option value="{$pseudo}"{if $adherent_selectionne eq $pseudo} selected="selected"{/if}>{$adherent}</option>
                {/foreach}
                </select>
            </p>
{/if}
        </div>
    </fieldset>
</div>
<div class="button-container">
    <input type="submit" id="calendrier" value="{_T string="LISTE VOLS.AFFICHER"}">
    <input type="submit" id="pdf" value="{_T string="COMPTE VOL.IMPRIMER"}" onClick="document.location.href='print_liste_vols.php?compte_annee={$annee_selectionnee}&pseudo={$adherent_selectionne}'; return false;">
</div>
<table class="infoline">
    <tr>
        <td class="left">{$nb_resultats} {_T string="LISTE VOLS.RESULTATS"}</td>
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
            <th class="listing"><a href="?tri=date&direction={if $tri eq 'date' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="LISTE VOLS.DATE"}</a>{if $tri eq 'date' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'date' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"><a href="?tri=immat&direction={if $tri eq 'immat' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="LISTE VOLS.IMMAT"}</a>{if $tri eq 'immat' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'immat' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"><a href="?tri=typevol&direction={if $tri eq 'typevol' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="LISTE VOLS.TYPE VOL"}</a>{if $tri eq 'typevol' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'typevol' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"><a href="?tri=aeroport&direction={if $tri eq 'aeroport' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="LISTE VOLS.AEROPORTS"}</a>{if $tri eq 'aeroport' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'aeroport' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"><a href="?tri=passagers&direction={if $tri eq 'passagers' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="LISTE VOLS.PASSAGERS"}</a>{if $tri eq 'passagers' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'passagers' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"><a href="?tri=instructeur&direction={if $tri eq 'instructeur' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="LISTE VOLS.INSTRUCTEUR"}</a>{if $tri eq 'instructeur' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'instructeur' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"><a href="?tri=nbatterr&direction={if $tri eq 'nbatterr' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="LISTE VOLS.NB ATTERRISSAGES"}</a>{if $tri eq 'nbatterr' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'nbatterr' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"><a href="?tri=duree&direction={if $tri eq 'duree' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="LISTE VOLS.DUREE"}</a>{if $tri eq 'duree' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'duree' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            <th class="listing"{if $login->isAdmin()} colspan="2"{/if}><a href="?tri=montant&direction={if $tri eq 'montant' && $direction eq 'asc'}desc{else}asc{/if}&nb_lignes={$nb_lignes}&compte_annee={$annee_selectionnee}{$complement}">{_T string="LISTE VOLS.MONTANT"}</a>{if $tri eq 'montant' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'montant' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$liste_operations item=operation}
        <tr>
            <td class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->date_operation}</td>
            <td class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->immatriculation}</td>
            <td class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->type_vol}</td>
            <td class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->aeroport_depart} / {$operation->aeroport_arrivee}</td>
            <td align="center" class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->nb_passagers}</td>
            <td class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->instructeur}</td>
            <td align="center" class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->nb_atterrissages}</td>
            <td align="right" class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->duree}</td>
            <td align="right" class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}">{$operation->montant}</td>
{if $login->isAdmin()}
            <td align="center" class="tbl_line_{if $operation->numero_ligne % 2 eq 0}even{else}odd{/if}"><a href="new_operation.php?operation_id={$operation->operation_id}&origine=liste_vols"><img src="picts/edit.png" title="Modifier l'opération"></a></td>
{/if}
        </tr>
        {/foreach}
    </tbody>
</table>
<p align="center">{$pagination}</p>
