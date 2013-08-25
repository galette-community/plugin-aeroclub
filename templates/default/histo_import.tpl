<table class="listing">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Taille</th>
            <th>Nb lignes</th>
            <th>Importé le</th>
            <th>Exporté le</th>
            <th>Type</th>
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