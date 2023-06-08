<form action="" method="get" style="display:flex;margin-top:auto;margin-bottom:auto;width:100%;padding-top:1%">
    <input id="searchbar" onkeyup="" type="text" name="search" placeholder="rechercher un fichier"
            <?php if (isset($search)){
                    echo ' value="'.$search.'"';
                }?>
        style="width:100%"><button type="submit" class="loupe"><img src="../image/loupe.png" height=30px></button>
</form>