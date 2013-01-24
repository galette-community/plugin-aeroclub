{if $login->isAdmin()}
<form action="graphique.php" method="get">
<div class="bigtable">
    <fieldset class="cssform">
        <legend class="ui-state-active ui-corner-top">{_T string="GRAPHIQUE.TABLE TITLE"}</legend>
        <div>
            <p>
                <span class="bline">{_T string="GRAPHIQUE.ADHERENT"}</span>
                <select name="login_adherent">
                {foreach from=$liste_adherents key=pseudo item=adherent}
                    <option value="{$pseudo}"{if $adherent_selectionne eq $pseudo} selected="selected"{/if}>{$adherent}</option>
                {/foreach}
                </select>
            </p>
        </div>
    </fieldset>
</div>
<div class="button-container">
    <input type="submit" id="calendrier" value="{_T string="GRAPHIQUE.AFFICHER"}">
</div>
<p></p>
</form>
{/if}
<center>
    <img src="graph_image.php?pseudo={$adherent_selectionne}" title="{_T string="GRAPHIQUE.IMAGE"}">
</center>
<p></p>
<table>
    <caption class="ui-state-active ui-corner-top"><b>{_T string="GRAPHIQUE.LEGENDE"}</b></caption>
    <tr>
        <td style="background-color: blue; color: white">{_T string="GRAPHIQUE.BLEU1"}</td>
        <td>{_T string="GRAPHIQUE.BLEU2"}</td>
    </tr>
    <tr>
        <td style="background-color: #830092; color: white">{_T string="GRAPHIQUE.VIOLET1"}</td>
        <td>{_T string="GRAPHIQUE.VIOLET2"}</td>
    </tr>
    <tr>
        <td style="background-color: #FFCA59; color: white">{_T string="GRAPHIQUE.ORANGE1"}</td>
        <td>{_T string="GRAPHIQUE.ORANGE2"}</td>
    </tr>
</table>