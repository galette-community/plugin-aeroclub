{if $nb_operations > 0}
    <div id="infobox">
        <h1>{$nb_operations} {_T string="RAPPROCHEMENT.OPERATION OK"}</h1>
    </div>
{/if}
<form action="rapprochement.php" method="post">
<table id="listing">
    <thead>
        <tr>
            <th class="listing center"><b>{_T string="RAPPROCHEMENT.ACTION"}</b></th>
            <th class="listing center"><b>{_T string="RAPPROCHEMENT.RESERVATION"}</b></th>
            <th class="listing center" colspan="4"><b>{_T string="RAPPROCHEMENT.OPERATION"}</b></th>
        </tr>
    </thead>
    <tbody>
{foreach from=$liste_rapprochements item=resa key=resa_id name=rapproch}
        <tr>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" rowspan="6" align="center">
                <input type="hidden" name="resas[]" value="{$resa_id}">
                <input type="checkbox" id="rapproche_{$resa_id}" name="rapproche_{$resa_id}" value="1">
                <br/><label for="rapproche_{$resa_id}">{_T string="RAPPROCHEMENT.RAPPROCHER"}</label>
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" rowspan="6">
                <img src="picts/calendar.png"> {$resa->heure_debut_IHM}
                <br/><img src="picts/membre.png"> {$resa->nom}
                <br/><img src="picts/mobile-phone.png"> {$resa->no_portable}
{if $resa->destination ne ''}
                <br/><img src="picts/destination.png"> {$resa->destination}
{/if}
{if $resa->commentaires ne ''}
                <br/><img src="picts/comment.png"> <i>{$resa->commentaires}</i>
{/if}
{if $resa->instructeur ne ''}
                <br/><img src="picts/instructeur.png"> {$resa->instructeur}
{/if}
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" align="right">
                {_T string="RAPPROCHEMENT.IMPUTER"}
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" colspan="3">
                <select name="id_adherent_{$resa_id}">
{foreach from=$liste_adherents item=adherent}
                    <option value="{$adherent->id_adh}"{if $resa->adherent_id eq $adherent->id_adh} selected="selected"{/if}>{$adherent->nom_adh} {$adherent->prenom_adh} ({$adherent->pseudo_adh})</option>
{/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" align="right">
                {_T string="RAPPROCHEMENT.AVION"}
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}">
                <select name="avion_{$resa_id}" id="avion_{$resa_id}" onChange="metAJourLibelle({$resa_id})">
{foreach from=$liste_avions item=avion key=id_avion}
                    <option value="{$avion->immatriculation}"{if $id_avion eq $resa->id_avion} selected="selected"{/if}>{$avion->nom} {$avion->immatriculation}</option>
{/foreach}
                </select>
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" align="right">  
                {_T string="RAPPROCHEMENT.INSTRUCTEUR"}
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}">
                <select name="instructeur_{$resa_id}">
                    <option value="*">*</option>
{foreach from=$liste_instructeurs item=instructeur}
                    <option value="{$instructeur->code}"{if $resa->id_instructeur eq $instructeur->instructeur_id} selected="selected"{/if}>{$instructeur->nom} ({$instructeur->code})</option>
{/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" align="right">
                {_T string="RAPPROCHEMENT.TYPE VOL"}
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}">
                <select name="type_vol_{$resa_id}">
{foreach from=$liste_type_vols item=type_vol}
                    <option value="{$type_vol}">{$type_vol}</option>
{/foreach}
                    <option value="-null-">{_T string="RAPPROCHEMENT.AUTRE"}</option>
                </select>
                <br/>Ou <input type="text" name="type_vol_text_{$resa_id}" size="10">
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" align="right">
                {_T string="RAPPROCHEMENT.ATTERRISSAGES"}
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}">
                <input type="text" name="atterissages_{$resa_id}" size="2" value="1">
            </td>
        </tr>
        <tr>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" align="right">
                {_T string="RAPPROCHEMENT.DEP ARR"}
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}">
                <input type="text" name="depart_{$resa_id}" size="4" value="{$depart}">
                <img src="picts/destination.png">
                <input type="text" name="arrivee_{$resa_id}" size="4" value="{$resa->destination}">
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" align="right">
                {_T string="RAPPROCHEMENT.PASSAGERS"}
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}">
                <input type="text" name="passagers_{$resa_id}" size="2" value="0">
            </td>
        </tr>
        <tr>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" align="right">
                {_T string="RAPPROCHEMENT.MONTANT"}
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}">
                <input type="text" name="montant_{$resa_id}" id="montant_{$resa_id}" size="6" value="-0.00">
                EUR
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" align="right">
                {_T string="RAPPROCHEMENT.DUREE"}
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}">
                <input type="text" name="heure_{$resa_id}" id="heure_{$resa_id}" size="1" value="{$resa->heure}">
                h
                <input type="text" name="minute_{$resa_id}" id="minute_{$resa_id}" size="2" value="{$resa->minute}">
                min
            </td>
        </tr>
        <tr>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" align="right">
                {_T string="RAPPROCHEMENT.LIBELLE"}
            </td>
            <td class="tbl_line_{if $smarty.foreach.rapproch.index is odd}odd{else}even{/if}" colspan="3">
                <input type="text" name="libelle_{$resa_id}" id="libelle_{$resa_id}" size="60" value="{$resa->libelle}">
                <a href="javascript:void(0)" onClick="metAJourLibelle({$resa_id})"><img src="picts/calculate.gif" title="Calculer le montant à partir du coût horaire et de la durée"></a>
            </td>
        </tr>
{if !$smarty.foreach.rapproch.last}
        <tr>
            <td colspan="6">
                <hr style="color: #717171; height: 1px;" width="75%"/>
            </td>
        </tr>
{/if}
{/foreach}
    </tbody>
</table>
<p></p>
<div class="button-container">
    <input type="submit" id="sauver" name="sauver" value="{_T string="RAPPROCHEMENT.ENREGISTRER"}">
</div>
</form>
<script>
function metAJourLibelle(resa_id){ldelim}
    var heure = document.getElementById('heure_' + resa_id).value;
    var minute = document.getElementById('minute_' + resa_id).value;
    var i = document.getElementById('libelle_' + resa_id).value.indexOf('*');
    var montant = document.getElementById('libelle_' + resa_id).value.substring(i + 2, document.getElementById('libelle_' + resa_id).value.length - 4);
    
    document.getElementById('libelle_' + resa_id).value =
        document.getElementById('avion_' + resa_id).options[document.getElementById('avion_' + resa_id).selectedIndex].text
        + ' - '
        + (heure < 10 ? '0' : '') + heure
        + ':' + (minute < 10 ? '0' : '') + minute
        + ' * ' + montant + ' EUR';

    var prix_minute = montant.replace(',', '.') / 60.0;
    var duree = 60.0 * heure + 1.0 * minute;
    document.getElementById('montant_' + resa_id).value = 
        '-' + (prix_minute * duree).toFixed(2);

    if(!document.getElementById('rapproche_' + resa_id).checked) {ldelim}
        document.getElementById('rapproche_' + resa_id).checked = true;
    {rdelim}
{rdelim}

</script>
