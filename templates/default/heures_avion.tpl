<form action="heures_avion.php" method="post" class="tabbed">
    <div id="prefs_tabs">
        <ul>
            <li><a href="#tableau">{_T string="HEURES AVION.TABLEAU"}</a></li>
            <li><a href="#mois12">{_T string="HEURES AVION.MOIS12"}</a></li>
            <li><a href="#selection">{_T string="HEURES AVION.ANNEES"}</a></li>
        </ul>
        <fieldset id="tableau" class="cssform">
            <legend>{_T string="HEURES AVION.TABLEAU LEGENDE"}</legend>
            <table class="listing">
                <thead>
                    <tr>
                        <th class="listing"><a href="?tri=avion&direction={if $tri eq 'avion' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.AVION"}</a>{if $tri eq 'avion' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'avion' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                        <th class="listing"><a href="?tri=an-1&direction={if $tri eq 'an-1' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.ANNEE DERNIERE"} ({$annee_derniere})</a>{if $tri eq 'an-1' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'an-1' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                        <th class="listing"><a href="?tri=an&direction={if $tri eq 'an' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.CETTE ANNEE"} ({$cette_annee})</a>{if $tri eq 'an' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'an' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                        <th class="listing"><a href="?tri=glissant&direction={if $tri eq 'glissant' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.UN AN GLISSANT"} {$an_glissant}</a>{if $tri eq 'glissant' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'glissant' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$avions item=av name=avion}
                        <tr>
                            <td class="tbl_line_{if $smarty.foreach.avion.index is even}even{else}odd{/if}">{$av->avion}</td>
                            <td class="tbl_line_{if $smarty.foreach.avion.index is even}even{else}odd{/if}" align="right">{$av->somme2011}</td>
                            <td class="tbl_line_{if $smarty.foreach.avion.index is even}even{else}odd{/if}" align="right">{$av->somme2012}</td>
                            <td class="tbl_line_{if $smarty.foreach.avion.index is even}even{else}odd{/if}" align="right">{$av->sommeglissant}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </fieldset>
        <fieldset id="mois12" class="cssform">
            <legend>{_T string="HEURES AVION.MOIS12 LEGENDE"}</legend>
            <p style="padding-left: 10px">
                <img src="heures_avion_image.php">
            </p>
            <p style="padding-left: 10px">
                <img src="heures_avion_image_cumul.php">
            </p>
        </fieldset>
        <fieldset id="selection" class="cssform">
            <legend>{_T string="HEURES AVION.ANNEES LEGENDE"}</legend>
            <p style="padding-left: 10px">
                {_T string="HEURES AVION.CHOIX IMMAT"}
                {foreach from=$avions_actifs item=av}
                    <input type="radio" name="immatriculation" id="immat_{$av}" value="{$av}"{if $sel_immat eq $av} checked="checked"{/if}>
                    <label for="immat_{$av}">{$av}</label>
                {/foreach}
                <input type="radio" name="immatriculation" id="immat_tous" value="all"{if $sel_immat eq 'all'} checked="checked"{/if}>
                <label for="immat_tous">Tous</label
            </p>
            <p style="padding-left: 10px">
                {_T string="HEURES AVION.CHOIX ANNEES"}
                {foreach from=$annees item=an}
                    <input type="checkbox" name="sel_annee[]" id="sel_an_{$an}" value="{$an}"{if $selection->$an} checked="checked"{/if}>
                    <label for="sel_an_{$an}">{$an}</label>
                {/foreach}
            </p>
            <center>
                <input type="submit" id="zoom" value="Afficher">
            </center>
            <p style="padding-left: 10px">
                <img src="heures_avion_image_annees.php?{foreach from=$annees item=an}{if $selection->$an}annee[]={$an}&{/if}{/foreach}immat={$sel_immat}">
            </p>
        </fieldset>
    </div>
    <script type="text/javascript">
        $('#prefs_tabs').tabs({$sel_tab});
    </script>
</form>