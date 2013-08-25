<form action="soldes_pilotes.php" method="get">
    <p>{_T string="SOLDES PILOTES.ACTIF"}
        <input type="radio" name="actif" id="actif_yes" value="yes"{if $actif eq 'yes'} checked="checked"{/if}>
        <label for="actif_yes">{_T string="SOLDES PILOTES.OUI"}</label>
        <input type="radio" name="actif" id="actif_no" value="no"{if $actif eq 'no'} checked="checked"{/if}>
        <label for="actif_no">{_T string="SOLDES PILOTES.NON"}</label>
        <input type="radio" name="actif" id="actif_null" value="null"{if $actif eq 'null'} checked="checked"{/if}>
        <label for="actif_null">{_T string="SOLDES PILOTES.TOUS"}</label>
    </p>
    <p>{_T string="SOLDES PILOTES.SOLDE NEGATIF"}
        <input type="radio" name="solde" id="solde_negatif" value="negatif"{if $solde eq 'negatif'} checked="checked"{/if}>
        <label for="solde_negatif">{_T string="SOLDES PILOTES.NEGATIF"}</label>
        <input type="radio" name="solde" id="solde_zero" value="zero"{if $solde eq 'zero'} checked="checked"{/if}>
        <label for="solde_zero">{_T string="SOLDES PILOTES.ZERO"}</label>
        <input type="radio" name="solde" id="solde_positif" value="positif"{if $solde eq 'positif'} checked="checked"{/if}>
        <label for="solde_positif">{_T string="SOLDES PILOTES.POSITIF"}</label>
        <input type="radio" name="solde" id="solde_all" value="all"{if $solde eq 'all'} checked="checked"{/if}>
        <label for="solde_all">{_T string="SOLDES PILOTES.TOUS"}</label>
    </p>
    <p align="center">
        <input type="submit" id="zoom" name="zoom" value="Afficher">
    </p>
    <table class="listing">
        <thead>
            <tr>
                <th></th>
                <th><a href="?actif={$actif}&negatif={$negatif}&tri=nom&direction={if $tri eq 'nom' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="SOLDES PILOTES.NOM"}</a>{if $tri eq 'nom' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'nom' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                <th><a href="?actif={$actif}&negatif={$negatif}&tri=prenom&direction={if $tri eq 'prenom' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="SOLDES PILOTES.PRENOM"}</a>{if $tri eq 'prenom' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'prenom' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                <th><a href="?actif={$actif}&negatif={$negatif}&tri=pseudo&direction={if $tri eq 'pseudo' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="SOLDES PILOTES.PSEUDO"}</a>{if $tri eq 'pseudo' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'pseudo' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                <th><a href="?actif={$actif}&negatif={$negatif}&tri=email&direction={if $tri eq 'email' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="SOLDES PILOTES.EMAIL"}</a>{if $tri eq 'email' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'email' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                <th><a href="?actif={$actif}&negatif={$negatif}&tri=solde&direction={if $tri eq 'solde' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="SOLDES PILOTES.SOLDE"}</a>{if $tri eq 'solde' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'solde' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$soldes item=solde name=liste}
                <tr>
                    <td class="tbl_line_{if $smarty.foreach.liste.index is even}even{else}odd{/if}"><input type="checkbox" name="member_sel[]" value="{$solde->id_adh}"/></td>
                    <td class="tbl_line_{if $smarty.foreach.liste.index is even}even{else}odd{/if}">{$solde->nom_adh}</td>
                    <td class="tbl_line_{if $smarty.foreach.liste.index is even}even{else}odd{/if}">{$solde->prenom_adh}</td>
                    <td class="tbl_line_{if $smarty.foreach.liste.index is even}even{else}odd{/if}">{$solde->login_adh}</td>
                    <td class="tbl_line_{if $smarty.foreach.liste.index is even}even{else}odd{/if}"><a href="{$solde->email_adh}">{$solde->email_adh}</a></td>
                    <td align="right" class="tbl_line_{if $smarty.foreach.liste.index is even}even{else}odd{/if}">{$solde->solde} €</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <p>
        <a href="javascript:void(0)" id="checkall" onclick="CheckAll()">Tout (dé)cocher</a> 
        | 
        <a href="javascript:void(0)" id="checkinvert" onclick="CheckInvert()">Inverser la sélection</a>
    </p>
    <p>
        <input type="submit" name="sendmail" id="sendmail" value="Envoyer un mailing"/>
    </p>
    <script type="text/javascript">
        var is_checked = true;
    
        function CheckAll() {ldelim}
        var tbl = document.getElementById("listing");
        var numRows = tbl.rows.length;
        for (var i = 1; i < numRows; i++) {ldelim}
        var cells = tbl.rows[i].getElementsByTagName('td');
        var chkbx = cells[0].getElementsByTagName('input')[0];
        chkbx.checked = is_checked;
        {rdelim}
        is_checked = !is_checked;
        {rdelim}

        function CheckInvert() {ldelim}
        var tbl = document.getElementById("listing");
        var numRows = tbl.rows.length;
        for (var i = 1; i < numRows; i++) {ldelim}
        var cells = tbl.rows[i].getElementsByTagName('td');
        var chkbx = cells[0].getElementsByTagName('input')[0];
        chkbx.checked = !chkbx.checked;
        {rdelim}
        {rdelim}
    </script>
</form>