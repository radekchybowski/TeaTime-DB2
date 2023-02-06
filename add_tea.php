<?php
include_once 'header.php';

if( ( isset( $_POST['teaName'] ) ) && ($_POST['teaName'] != NULL) ) 
{
    $teaName = htmlentities($_POST['teaName'], ENT_QUOTES, "UTF-8");
    $teaKind = htmlentities($_POST['teaKind'], ENT_QUOTES, "UTF-8");
    $teaBrewTime = htmlentities($_POST['teaBrewTime'], ENT_QUOTES, "UTF-8");
    $teaTemp = htmlentities($_POST['teaTemp'], ENT_QUOTES, "UTF-8");
    $teaIngr = htmlentities($_POST['teaIngr'], ENT_QUOTES, "UTF-8");
    $teaDesc = htmlentities($_POST['teaDesc'], ENT_QUOTES, "UTF-8");
    $teaVendor = htmlentities($_POST['teaVendor'], ENT_QUOTES, "UTF-8");
    $teaRegion = htmlentities($_POST['teaRegion'], ENT_QUOTES, "UTF-8");
    $teaUserScore = htmlentities($_POST['teaUserScore'], ENT_QUOTES, "UTF-8");
    $teaUserNotes = htmlentities($_POST['teaUserNotes'], ENT_QUOTES, "UTF-8");
    
    

    //check if this item is not in the system
    if ($result = @$conn->query(sprintf('SELECT * FROM herbaty WHERE nazwa="%s";', mysqli_real_escape_string($conn, $teaName)))) 
    {
        if ($result->num_rows == 0) 
        {

            //adding user data from form to the teas table
            if (
                @$conn->query(
                    sprintf(
                        'INSERT INTO herbaty (nazwa, gatunek, skladniki, czas_parzenia, temperatura, sklep, region, opis) VALUES ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s");',
                        mysqli_real_escape_string($conn, $teaName),
                        mysqli_real_escape_string($conn, $teaKind),
                        mysqli_real_escape_string($conn, $teaIngr),
                        mysqli_real_escape_string($conn, $teaBrewTime),
                        mysqli_real_escape_string($conn, $teaTemp),
                        mysqli_real_escape_string($conn, $teaVendor),
                        mysqli_real_escape_string($conn, $teaRegion),
                        mysqli_real_escape_string($conn, $teaDesc)
                    )
                )
            ) 
            {
                if ($result = @$conn->query(sprintf('SELECT * FROM herbaty WHERE nazwa="%s";', mysqli_real_escape_string($conn, $teaName)))) 
                {
                    if ($result->num_rows == 1) 
                    {
                        $wiersz = $result->fetch_assoc();
                        $herbata_id = $wiersz['herbata_id'];
                        if (isset($_FILES['teaPicture'])) 
                        {
                            // file name, type, size, temporary name 
                            $file_name = $_FILES['teaPicture']['name'];
                            $file_type = $_FILES['teaPicture']['type'];
                            $file_tmp_name = $_FILES['teaPicture']['tmp_name'];
                            $file_size = $_FILES['teaPicture']['size'];
                            
                            // target directory 
                            $target_dir = '/home/epi/20_chybowski/public_html/BD2/projekt/img/tea_pics/';

                            // uploding file 
                            if (move_uploaded_file($file_tmp_name, $target_dir . $wiersz['herbata_id'])) 
                            {
                                if (@$conn->query('UPDATE herbaty SET zdjecie = "' . $target_dir .$wiersz['herbata_id'].'" WHERE herbata_id='.$wiersz['herbata_id'].';')) {
                                    echo '<div class="valid-feedback">Zdjecie dodane!</div>';
                                } 
                                else 
                                {
                                    echo '<div class="valid-feedback">blad sql!</div>';
                                }
                            } 
                            else 
                            {
                                echo '<div class="valid-feedback">blad upload!</div>';
                            }
                        }
                        echo '<div class="valid-feedback">Herbata dodana!</div>';
                        
                        if ( ($_POST['teaUserScore'] != NULL) || ($_POST['teaUserNotes'] != NULL) )
                        {
                            if (@$conn->query('INSERT INTO uzytkownicy_herbaty VALUES ("'.$user_id.'", "'.$herbata_id.'", NULL, NULL);')) 
                            {
                                $ocena = NULL;
                                $notatki = NULL;
                                if (($_POST['teaUserScore'] != NULL))
                                    $ocena = $_POST['teaUserScore'];

                                if (($_POST['teaUserScore'] != NULL))
                                    $notatki = $_POST['teaUserNotes'];

                                if (@$conn->query('UPDATE uzytkownicy_herbaty SET ocena = "'.$ocena.'", notatki = "'.$notatki.'" WHERE fk_herbata_id = "'.$herbata_id.'" and fk_user_id = "'.$user_id.'";') ) 
                                {
                                    echo '<div class="h4 text-center">Twoje informacje dodane!</div>';
                                }
                            }
                        }
                    }
                }
            } 

        }
        else
        {
            echo '<div class="invalid-feedback">Herbata o takiej samej nazwie znajduje się juz w bazie!</div>';
        }
    }
}
?> 
                    <br>
                    <br>
                    <!-- Tile wrapper for content -->
                    <div class="card bg-light m-3 mx-auto col-sm-10 mt-5 mb-5" style="max-width: 56rem;">
                        <div class="card-header"><h2>Dodaj nową herbatę</h2></div>
                        <div class="card-body">
                            <form id="addTea" action="add_tea.php" enctype="multipart/form-data" method="POST">
                                <h5 class="card-title">Publiczne</h5>

                                <!-- Name imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaName">Nazwa</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaName" name="teaName" class="form-control" placeholder="Np. Blue Moon">
                                    </div>
                                </div>
            
                                <!-- Kind imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaKind">Gatunek</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaKind" name="teaKind" class="form-control" placeholder="Oddzielone: np. zielona biała smakowa">
                                    </div>
                                </div>
            
                                <!-- Time imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaBrewTime">Czas parzenia</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaBrewTime" name="teaBrewTime" class="form-control" placeholder="Np. 2-4">
                                    </div>
                                </div>

                                <!-- Temperature imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaTemp">Temperatura</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaTemp" name="teaTemp" class="form-control" placeholder="Np. 75">
                                    </div>
                                </div>

                                <!-- Ingredients imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaIngr">Składniki</label>
                                    <div class="col-sm-8">
                                        <textarea cols="40" rows="3" name="teaIngr" id="teaIngr" class="form-control" style="resize: none;" placeholder="Np. China White Tea, Fujian White, kwiat Klitorii, bławatek, aromat."></textarea>
                                    </div>
                                </div>

                                <!-- Description imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaDesc">Opis</label>
                                    <div class="col-sm-8">
                                        <textarea cols="40" rows="6" name="teaDesc" id="teaDesc" class="form-control" style="resize: none;" placeholder="Np. Np. Herbata KING BLUE – Pochodząca z Chin biała herbata nazywana jest królewską nie tylko ze względu na jej właściwości, ale również na historię. Ze względu na swoją unikalność była niezwykle droga i mogli sobie na nią pozwolić jedynie ludzie najbogatsi. Herbata King Blue skierowana jest również do najmłodszych jako napar będący zdrową alternatywą dla  gazowanych napoi ogólnie dostępnych na rynku z niezliczoną ilością polepszaczy smaku, cukrów oraz innych substancji chemicznych. Polecić go można nie tylko dla zdrowia, lecz przede w"></textarea>
                                    </div>
                                </div>

                                <!-- Vendor imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaVendor">Sklep</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaVendor" name="teaVendor" class="form-control" placeholder="Np. eherbata.pl">
                                    </div>
                                </div>

                                <!-- Vendor imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaRegion">Region</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="teaRegion" name="teaRegion" class="form-control" placeholder="Np. Huihan">
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
                                        <select class="form-select" form="addTea" name="teaUserScore" id="teaUserScore">
                                            <option value="0">Nie wybrano</option>
                                            <option value="1">0,5</option>
                                            <option value="2">1</option>
                                            <option value="3">1,5</option>
                                            <option value="4">2</option>
                                            <option value="5">2,5</option>
                                            <option value="6">3</option>
                                            <option value="7">3,5</option>
                                            <option value="8">4</option>
                                            <option value="9">4,5</option>
                                            <option value="10">5</option>
                                        </select>
                                        <!-- <input type="range" min="0" max="10" step="1" value="7" id="teaUserScore" name="teaUserScore" class=""> -->
                                    </div>
                                </div>

                                <!-- User notes imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="teaUserNotes">Twoje notatki</label>
                                    <div class="col-sm-8">
                                        <textarea cols="40" rows="6" name="teaUserNotes" id="teaUserNotes" class="form-control" style="resize: none;" placeholder="Np. Jezu jak ja nienawidze herbaty :)"></textarea>
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