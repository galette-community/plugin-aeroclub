<form action="heures_pilote.php" method="get" class="tabbed">
<div id="prefs_tabs">
    <ul>
        <li>
            <a href="#tableau">
                {_T string="HEURES PILOTE.TABLEAU"}
            </a>
        </li>
        <li>
            <a href="#graphique">
                {_T string="HEURES PILOTE.GRAPHIQUE"}
            </a>
        </li>
    </ul>
    <fieldset id="tableau" class="cssform">
        <legend>{_T string="HEURES PILOTE.TABLEAU LEGENDE"}</legend>
        <table class="listing">
            <thead>
                <tr>
                    <th>
                        <a href="?tri=nom&direction={if $tri eq 'nom' && $direction eq 'asc'}desc{else}asc{/if}">
                            {_T string="HEURES.ADHERENT NOM"}
                        </a>
                        {if $tri eq 'nom' && $direction eq 'asc'}
                            <img src="{$template_subdir}images/down.png"/>
                        {elseif $tri eq 'nom' && $direction eq 'desc'}
                            <img src="{$template_subdir}images/up.png"/>
                        {/if}
                    </th>
                    <th>
                        <a href="?tri=prenom&direction={if $tri eq 'prenom' && $direction eq 'asc'}desc{else}asc{/if}">
                            {_T string="HEURES.ADHERENT PRENOM"}
                        </a>
                        {if $tri eq 'prenom' && $direction eq 'asc'}
                            <img src="{$template_subdir}images/down.png"/>
                        {elseif $tri eq 'prenom' && $direction eq 'desc'}
                            <img src="{$template_subdir}images/up.png"/>
                        {/if}
                    </th>
                    <th>
                        <a href="?tri=pseudo&direction={if $tri eq 'pseudo' && $direction eq 'asc'}desc{else}asc{/if}">
                            {_T string="HEURES.ADHERENT PSEUDO"}
                        </a>
                        {if $tri eq 'pseudo' && $direction eq 'asc'}
                            <img src="{$template_subdir}images/down.png"/>
                        {elseif $tri eq 'pseudo' && $direction eq 'desc'}
                            <img src="{$template_subdir}images/up.png"/>
                        {/if}
                    </th>
                    <th>
                        <a href="?tri=an-1&direction={if $tri eq 'an-1' && $direction eq 'asc'}desc{else}asc{/if}">
                            {_T string="HEURES.ANNEE DERNIERE"} ({$annee_derniere})
                        </a>
                        {if $tri eq 'an-1' && $direction eq 'asc'}
                            <img src="{$template_subdir}images/down.png"/>
                        {elseif $tri eq 'an-1' && $direction eq 'desc'}
                            <img src="{$template_subdir}images/up.png"/>
                        {/if}
                    </th>
                    <th>
                        <a href="?tri=an&direction={if $tri eq 'an' && $direction eq 'asc'}desc{else}asc{/if}">
                            {_T string="HEURES.CETTE ANNEE"} ({$cette_annee})
                        </a>
                        {if $tri eq 'an' && $direction eq 'asc'}
                            <img src="{$template_subdir}images/down.png"/>
                        {elseif $tri eq 'an' && $direction eq 'desc'}
                            <img src="{$template_subdir}images/up.png"/>
                        {/if}
                    </th>
                    <th>
                        <a href="?tri=glissant&direction={if $tri eq 'glissant' && $direction eq 'asc'}desc{else}asc{/if}">
                            {_T string="HEURES.UN AN GLISSANT"} {$an_glissant}
                        </a>
                        {if $tri eq 'glissant' && $direction eq 'asc'}
                            <img src="{$template_subdir}images/down.png"/>
                        {elseif $tri eq 'glissant' && $direction eq 'desc'}
                            <img src="{$template_subdir}images/up.png"/>
                        {/if}
                    </th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$adherents item=adh name=adherent}
                    <tr>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}">
                            {$adh->nom}
                        </td>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}">
                            {$adh->prenom}
                        </td>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}">
                            {$adh->pseudo}
                        </td>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}" align="right">
                            {$adh->somme_last_year}
                        </td>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}" align="right">
                            {$adh->somme_this_year}
                        </td>
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}" align="right">
                            {$adh->somme_glissant}
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </fieldset>
    <fieldset id="graphique" class="cssform">
        <legend>{_T string="HEURES PILOTE.GRAPHIQUE LEGENDE"}</legend>
        <p style="padding-left: 10px">
            <img src="heures_pilote_image.php?tri={$tri}&direction={$direction}"/>
        </p>
    </fieldset>
</div>
<script type="text/javascript">
    $('#prefs_tabs').tabs();
</script>
</form>