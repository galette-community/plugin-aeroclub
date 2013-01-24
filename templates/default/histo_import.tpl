<table id="listing">
    <thead>
        <tr>
            <th class="listing">Nom</th>
            <th class="listing">Taille</th>
            <th class="listing">Nb lignes</th>
            <th class="listing">Importé le</th>
            <th class="listing">Exporté le</th>
            <th class="listing">Type</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$historique item=histo name=hist}
            <tr>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}">{$histo->name}</td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}">{$histo->size}</td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}">{$histo->lines}</td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}">{$histo->date}</td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}">{$histo->datemaj}</td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}">{$histo->type}</td>
            </tr>
        {/foreach}
    </tbody>
</table>