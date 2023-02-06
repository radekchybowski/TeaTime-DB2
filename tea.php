<?php
include_once 'header.php';

$herbata_id = mysqli_real_escape_string($conn, $_GET['id']);
if(isset($_POST['teaUserScore']) )
{
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
    
}

if ($result = @$conn->query(sprintf('SELECT * FROM herbaty WHERE herbata_id="%s";', $herbata_id))) 
{
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
                    <?php if ($wiersz['zdjecie'] != NULL) echo '<img src="img/tea_pics/'.$wiersz['herbata_id'].'" class="img-fluid rounded-circle mb-3 shadow-5 mx-auto d-block" style="width: 170px; height: 170px; object-fit: cover;" alt="Avatar" />'?>
                    <!-- Tile wrapper for content -->
                    <div class="card bg-light m-2 mx-auto col-sm-10 my-5" style="max-width: 56rem;">
                    <form id="tea" action="tea.php?id=<?php echo $_GET['id'];?>" method="POST">
                        <div class="card-header">
                            <div class="row">
                                <?php echo '<h2 class="col-8">'.$wiersz['nazwa'].'</h2>'?>
                                <span class="h5 my-auto" style="width: 90px">Ocena:</span>
                                <div class="ms-auto row" style="width: 150px;">
                                    <label class="d-none" for="teaUserScore">Ocena:</label>
                                    <select class="form-select" form="tea" name="teaUserScore" id="teaUserScore" width="150">
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
                        </div>
                        <div class="card-body text-center">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="col-md-12 card p-3 tea-tile-info">
                                        <h3 class="mb-2 mt-1 h5">Gatunek</h3>
                                        <?php if(isset($wiersz['gatunek'])) echo '<p class="h2">'.$wiersz['gatunek'].'</p>'; ?>
                                    </div> 
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-12 card p-3 tea-tile-info">
                                        <h3 class="mb-2 mt-1 h5">Region</h3>
                                        <?php if($wiersz['region'] != '0') echo '<p class="h2">'.$wiersz['region'].'</p>'; ?>
                                    </div> 
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-12 card p-3 tea-tile-info">
                                        <h3 class="mb-2 mt-1 h5">Sklep</h3>
                                        <?php if(isset($wiersz['sklep'])) echo '<p class="h2">'.$wiersz['sklep'].'</p>'; ?>
                                    </div> 
                                </div>

                                <h3 class="mb-2 mt-4 h5 text-start">Skład</h3>
                                <?php if(isset($wiersz['skladniki'])) echo '<p class="text-start mb-4">'.$wiersz['skladniki'].'</p>'; ?>

                                <div class="col-md-6">
                                    <div class="col-md-12 card p-3 tea-tile-info">
                                        <h3 class="mb-2 mt-1 h5">Czas parzenia</h3>
                                        <?php if(isset($wiersz['czas_parzenia'])) echo '<p class="h2">'.$wiersz['czas_parzenia'].' min.</p>'; ?>
                                    </div> 
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-12 card p-3 tea-tile-info">
                                        <h3 class="mb-2 mt-1 h5">Temperatura parzenia</h3>
                                        <?php if(isset($wiersz['temperatura'])) echo '<p class="h2">'.$wiersz['temperatura'].' ℃ </p>'; ?>
                                    </div> 
                                </div>

                                <h3 class="mb-2 mt-4 h5 text-start">Opis</h3>
                                <?php if(isset($wiersz['opis'])) echo '<p class="text-start mb-4">'.$wiersz['opis'].'</p>'; ?>

                                <!-- User notes imput -->
                                <div class="form-group text-start">
                                    <label class="form-label h5 mt-3" for="teaUserNotes">Twoje notatki</label>
                                    <textarea cols="40" rows="6" name="teaUserNotes" id="teaUserNotes" class="form-control" style="resize: none;"<?php echo $notes;?></textarea>
                                </div>
                                
                            </div>
                            <div class="me-auto px-auto">
                                <a href='update_tea.php?id=<?php echo $_GET['id'];?>' class="btn btn-info mt-4 d-inline-block">Edytuj informacje o herbacie</a>
                                <button type="submit" name="tea-form" class="btn btn-success mt-4 d-inline-block ms-auto">Zapisz</button>
                            </div>
                            
                        </div>
                    
                    </form>
                    </div>

<?php
include_once 'footer.php';
?>

