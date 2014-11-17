<form action="modifier_avion.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="avion_id" value="{$avion->avion_id}">
<div class="bigtable">
    <fieldset class="cssform">
        <legend class="ui-state-active ui-corner-top">{_T string="MODIFIER AVION.TITRE"}</legend>
        <div>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.NOM"}</span>
                <input type="text" name="nom" value="{$avion->nom}" required>
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.NOM COURT"}</span>
                <input type="text" name="nom_court" value="{$avion->nom_court}">
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.MARQUE TYPE"}</span>
                <input type="text" name="marque_type" value="{$avion->marque_type}" required>
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.TYPE AERONEF"}</span>
                <input type="text" name="type_aeronef" value="{$avion->type_aeronef}" required>
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.IMMAT"}</span>
                <input type="text" name="immatriculation" value="{$avion->immatriculation}" required>
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.COULEUR"}</span>
                <input type="text" name="couleur" value="{$avion->couleur}">
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.PLACES"}</span>
                <input type="text" name="nb_places" value="{$avion->nb_places}">
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.COUT"}</span>
                <input type="text" name="cout_horaire" value="{$avion->cout_horaire}">
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.DC"}</span>
                <input type="checkbox" name="DC_autorisee" value="1"{if $avion->DC_autorisee} checked{/if}>
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.REMORQUEUR"}</span>
                <input type="checkbox" name="est_remorqueur" value="1"{if $avion->est_remorqueur} checked{/if}>
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.RESERVABLE"}</span>
                <input type="checkbox" name="est_reservable" value="1"{if $avion->est_reservable} checked{/if}>
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.REMQS"}</span>
                <textarea name="remarques" style="font-family: Verdana,Arial,sans-serif; font-size: 0.85em;">{$avion->remarques}</textarea>
            </p>
            <p>
                <span class="bline">{_T string="MODIFIER AVION.PHOTO"}</span>
                <img src="picture.php?avion_id={$avion->avion_id}" class="picture" width="{$avion->picture->getOptimalWidth()}" height="{$avion->picture->getOptimalHeight()}" /><br/>
{if $detail_picture ne ''}
            {$detail_picture}</br>
{/if}
{if $avion->hasPicture()}
            <input type="checkbox" name="del_photo" id="del_photo" value="1"/><span class="labelalign"><label for="del_photo">{_T string="MODIFIER AVION.SUPPR PHOTO"}</label></span><br/>
{/if}
            <input class="labelalign" type="file" name="photo"/>
            </p>
{if $avion->hasPicture()}
            <p>
            <span class="bline">{_T string="MODIFIER AVION.MINIATURE"}</span>
            <img src="picture.php?avion_id={$avion->avion_id}&thumb=1" class="picture" />
            </p>
{/if}
        </div>
    </fieldset>
</div>
<div class="button-container">
    <input type="submit" id="sauver" name="modifier" value="{_T string="MODIFIER AVION.ENREGISTRER"}">
    <input type="submit" id="annuler" name="annuler" value="{_T string="MODIFIER AVION.ANNULER"}" onclick="document.location = 'liste_avions.php?msg=pas_enregistre'">
</div>
</form>