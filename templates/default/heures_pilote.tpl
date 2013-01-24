<form action="heures_pilote.php" method="get" class="tabbed">
<div id="prefs_tabs">
    <ul>
        <li><a href="#tableau">{_T string="HEURES PILOTE.TABLEAU"}</a></li>
        <li><a href="#graphique">{_T string="HEURES PILOTE.GRAPHIQUE"}</a></li>
    </ul>
    <fieldset id="tableau" class="cssform">
        <legend>{_T string="HEURES PILOTE.TABLEAU LEGENDE"}</legend>
{*
        NE SERT PAS A CAUSE NOUVELLE PAGE DES TYPES DE VOLS
        <p style="padding-left: 10px">
        Types de vols :
{foreach from=$type_vols item=tvol}
        <input type="radio" id="radio_{$tvol}" name="type_vol" value="{$tvol}"{if $tvol eq $type_vol} checked="checked"{/if}>
        <label for="radio_{$tvol}">{$tvol}</label>
{/foreach}
        <input type="radio" id="radio_all" name="type_vol" value="all"{if $type_vol eq 'all'} checked="checked"{/if}>
        <label for="radio_all">Tous</label>
        </p>
        <center>
            <input type="submit" id="zoom" value="Afficher">
        </center>
*}
        <table class="listing">
            <thead>
                <tr>
                    <th class="listing"><a href="?tri=nom&direction={if $tri eq 'nom' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.ADHERENT NOM"}</a>{if $tri eq 'nom' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'nom' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                    <th class="listing"><a href="?tri=prenom&direction={if $tri eq 'prenom' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.ADHERENT PRENOM"}</a>{if $tri eq 'prenom' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'prenom' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                    <th class="listing"><a href="?tri=pseudo&direction={if $tri eq 'pseudo' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.ADHERENT PSEUDO"}</a>{if $tri eq 'pseudo' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'pseudo' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                    <th class="listing"><a href="?tri=an-1&direction={if $tri eq 'an-1' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.ANNEE DERNIERE"} ({$annee_derniere})</a>{if $tri eq 'an-1' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'an-1' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                    <th class="listing"><a href="?tri=an&direction={if $tri eq 'an' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.CETTE ANNEE"} ({$cette_annee})</a>{if $tri eq 'an' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'an' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                    <th class="listing"><a href="?tri=glissant&direction={if $tri eq 'glissant' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.UN AN GLISSANT"} {$an_glissant}</a>{if $tri eq 'glissant' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'glissant' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$adherents item=adh name=adherent}
                    <tr>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}">{$adh->nom}</td>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}">{$adh->prenom}</td>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}">{$adh->pseudo}</td>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}" align="right">{$adh->somme2011}</td>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}" align="right">{$adh->somme2012}</td>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}" align="right">{$adh->sommeglissant}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </fieldset>
    <fieldset id="graphique" class="cssform">
        <legend>{_T string="HEURES PILOTE.GRAPHIQUE LEGENDE"}</legend>
        <p style="padding-left: 10px">
            <img src="heures_pilote_image.php?tri={$tri}&direction={$direction}">
        </p>
    </fieldset>
</div>
<script type="text/javascript">
    $('#prefs_tabs').tabs();
</script>
</form>