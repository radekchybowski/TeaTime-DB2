<?php
include_once 'header.php';

$herbata_id = mysqli_real_escape_string($conn, $_GET['id']);

if (isset($_POST['update-form'])) 
{

    if (isset($_POST['teaName']) && ($_POST['teaName'] != NULL)) 
    {
        $teaName = mysqli_real_escape_string($conn, (htmlentities($_POST['teaName'], ENT_QUOTES, "UTF-8")));
        if ($result = @$conn->query(sprintf('UPDATE herbaty SET nazwa="%s" WHERE herbata_id="%s";', $teaName, $herbata_id))) {}
    }

    if (isset($_POST['teaKind']) && ($_POST['teaKind'] != NULL)) 
    {
        $teaKind = mysqli_real_escape_string($conn, (htmlentities($_POST['teaKind'], ENT_QUOTES, "UTF-8")));
        if ($result = @$conn->query(sprintf('UPDATE herbaty SET gatunek="%s" WHERE herbata_id="%s";', $teaKind, $herbata_id))) {}
    }

    if (isset($_POST['teaBrewTime']) && ($_POST['teaBrewTime'] != NULL)) 
    {
        $teaBrewTime = mysqli_real_escape_string($conn, (htmlentities($_POST['teaBrewTime'], ENT_QUOTES, "UTF-8")));
        if ($result = @$conn->query(sprintf('UPDATE herbaty SET czas_parzenia="%s" WHERE herbata_id="%s";', $teaBrewTime, $herbata_id))) {}
    }

    if (isset($_POST['teaTemp']) && ($_POST['teaTemp'] != NULL)) 
    {
        $teaTemp = mysqli_real_escape_string($conn, (htmlentities($_POST['teaTemp'], ENT_QUOTES, "UTF-8")));
        if ($result = @$conn->query(sprintf('UPDATE herbaty SET temperatura="%s" WHERE herbata_id="%s";', $teaTemp, $herbata_id))) {}
    }

    if (isset($_POST['teaIngr']) && ($_POST['teaIngr'] != NULL)) 
    {
        $teaIngr = mysqli_real_escape_string($conn, (htmlentities($_POST['teaIngr'], ENT_QUOTES, "UTF-8")));
        if ($result = @$conn->query(sprintf('UPDATE herbaty SET skladniki="%s" WHERE herbata_id="%s";', $teaIngr, $herbata_id))) {}
    }

    if (isset($_POST['teaDesc']) && ($_POST['teaDesc'] != NULL)) 
    {
        $teaDesc = mysqli_real_escape_string($conn, (htmlentities($_POST['teaDesc'], ENT_QUOTES, "UTF-8")));
        if ($result = @$conn->query(sprintf('UPDATE herbaty SET opis="%s" WHERE herbata_id="%s";', $teaDesc, $herbata_id))) {}
    }

    if (isset($_POST['teaVendor']) && ($_POST['teaVendor'] != NULL)) 
    {
        $teaVendor = mysqli_real_escape_string($conn, (htmlentities($_POST['teaVendor'], ENT_QUOTES, "UTF-8")));
        if ($result = @$conn->query(sprintf('UPDATE herbaty SET sklep="%s" WHERE herbata_id="%s";', $teaVendor, $herbata_id))) {}
    }

    if (isset($_POST['teaRegion']) && ($_POST['teaRegion'] != NULL)) 
    {
        $teaRegion = mysqli_real_escape_string($conn, (htmlentities($_POST['teaRegion'], ENT_QUOTES, "UTF-8")));
        if ($result = @$conn->query(sprintf('UPDATE herbaty SET region="%s" WHERE herbata_id="%s";', $teaRegion, $herbata_id))) {}
    }

    if(isset($_POST['teaUserScore']) || isset($_POST['teaUserNotes']))
    {
        $teaUserScore = mysqli_real_escape_string($conn, (htmlentities($_POST['teaUserScore'], ENT_QUOTES, "UTF-8")));
        $teaUserNotes = mysqli_real_escape_string($conn, (htmlentities($_POST['teaUserNotes'], ENT_QUOTES, "UTF-8")));
    
        if ($result = $conn->query('SELECT * FROM uzytkownicy_herbaty WHERE fk_herbata_id = "'.$herbata_id.'" and fk_user_id = "'.$user_id.'";') )
        {
            if ($result->num_rows == 1)
            {
                if (@$conn->query('UPDATE uzytkownicy_herbaty SET ocena = "'.$_POST['teaUserScore'].'", notatki = "'.$_POST['teaUserNotes'].'" WHERE fk_herbata_id = "'.$herbata_id.'" and fk_user_id = "'.$user_id.'";'))
                {
                    echo '<div class="h4 text-center">Pomyślnie zapisano!</div>';
                }
            }
            else
            {
                if (@$conn->query('INSERT INTO uzytkownicy_herbaty VALUES ("'.$user_id.'", "'.$herbata_id.'", "'.$_POST['teaUserScore'].'", "'.$_POST['teaUserNotes'].'");'))
                {
                    echo '<div class="h4 text-center">Pomyślnie zapisano!</div>';
                }
            }
        }
    } else
        echo 'Coś poszło nie tak...';
}


if ($result = @$conn->query(sprintf('SELECT * FROM herbaty WHERE herbata_id="%s";', $herbata_id))) {
    if ($result->num_rows == 1) 
    {
        $wiersz = $result->fetch_assoc();

        if ($result = @$conn->query(sprintf('SELECT * FROM uzytkownicy_herbaty WHERE fk_herbata_id="%s" and fk_user_id="%s";', $herbata_id, $user_id))) 
        {
            if ($result->num_rows == 1)
            {
                $userWiersz = $result->fetch_assoc();
                $score = [];
                if ($userWiersz['ocena'] == NULL) $score[11] = ' selected="selected"';
                else $score[11] = NULL;
                for ($i = 1; $i < 11; $i++)
                {
                    if ($i == $userWiersz['ocena']) $score[$i] = ' selected="selected"';
                    else $score[$i] = NULL;
                }
                $notes = '>'.$userWiersz['notatki'];
            }
            else
            {
                $score = array('selected="selected"', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
                $notes = ' placeholder="Zapisz tutaj swoje przemyślenia dotyczące tej herbaty...">';
            }
        }
    }
    else
    {
        include "404.php";
        exit();
    }
}


?> 
                    <br>
                    <br>
                    <!-- Tile wrapper for content -->
                    <div class="card bg-light m-3 mx-auto col-sm-10 mt-5 mb-5" style="max-width: 56rem;">
                        <div class="card-header"><h2>Edytuj informacje o herbacie</h2></div>
                        <div class="card-body">
                            <form id="updateTea" action="update_tea.php?id=<?php echo $herbata_id;?>" enctype="multipart/form-data" method="POST">
                                <h5 class="card-title">Publiczne</h5>

                                <!-- Name imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaName">Nazwa</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaName" name="teaName" class="form-control" placeholder="<?php echo $wiersz['nazwa'];?>">
                                    </div>
                                </div>
            
                                <!-- Kind imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaKind">Gatunek</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaKind" name="teaKind" class="form-control" placeholder="<?php echo $wiersz['gatunek'];?>">
                                    </div>
                                </div>
            
                                <!-- Time imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaBrewTime">Czas parzenia</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaBrewTime" name="teaBrewTime" class="form-control" placeholder="<?php echo $wiersz['czas_parzenia'];?>">
                                    </div>
                                </div>

                                <!-- Temperature imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaTemp">Temperatura</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaTemp" name="teaTemp" class="form-control" placeholder="<?php echo $wiersz['temperatura'];?>">
                                    </div>
                                </div>

                                <!-- Ingredients imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaIngr">Składniki</label>
                                    <div class="col-sm-8">
                                        <textarea cols="40" rows="3" name="teaIngr" id="teaIngr" class="form-control" style="resize: none;"><?php echo $wiersz['skladniki'];?></textarea>
                                    </div>
                                </div>

                                <!-- Description imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaDesc">Opis</label>
                                    <div class="col-sm-8">
                                        <textarea cols="40" rows="6" name="teaDesc" id="teaDesc" class="form-control" style="resize: none;"><?php echo $wiersz['opis'];?></textarea>
                                    </div>
                                </div>

                                <!-- Vendor imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaVendor">Sklep</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaVendor" name="teaVendor" class="form-control" placeholder="<?php echo $wiersz['sklep'];?>">
                                    </div>
                                </div>

                                <!-- Region imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaRegion">Region</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaRegion" name="teaRegion" class="form-control" placeholder="<?php echo $wiersz['region'];?>">
                                    </div>
                                </div>

                                <!-- Tea picture -->
                                <div class="form-group row mb-4 mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaPicture">Zdjęcie herbatki</label>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
                                        <input type="file" id="teaPicture" name="teaPicture" class="form-control-file"/>
                                    </div>
                                </div>

                                
                                <h5 class="card-title">Prywatne</h5>

                                <!-- Score imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaUserScore">Ocena</label>
                                    <div class="col-sm-8">
                                    <select class="form-select" form="updateTea" name="teaUserScore" id="teaUserScore" width="150">
                                        <option value="0"<?php echo $score[11];?>>Nie wybrano</option>
                                        <option value="1"<?php echo $score[1];?>>0,5</option>
                                        <option value="2"<?php echo $score[2];?>>1</option>
                                        <option value="3"<?php echo $score[3];?>>1,5</option>
                                        <option value="4"<?php echo $score[4];?>>2</option>
                                        <option value="5"<?php echo $score[5];?>>2,5</option>
                                        <option value="6"<?php echo $score[6];?>>3</option>
                                        <option value="7"<?php echo $score[7];?>>3,5</option>
                                        <option value="8"<?php echo $score[8];?>>4</option>
                                        <option value="9"<?php echo $score[9];?>>4,5</option>
                                        <option value="10"<?php echo $score[10];?>>5</option>
                                    </select>

                                    </div>
                                </div>

                                <!-- User notes imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaUserNotes">Twoje notatki</label>
                                    <div class="col-sm-8">
                                        <textarea cols="40" rows="6" name="teaUserNotes" id="teaUserNotes" class="form-control" style="resize: none;"<?php echo $notes;?></textarea>
                                    </div>
                                </div>
            
                                <!-- Submit button -->
                                <button type="submit" name="update-form" class="btn btn-success mt-4 d-block ms-auto">Potwierdź</button>
                            </form>
                          
                        </div>
                        
                    </div>
<?php
include_once 'footer.php';
?>