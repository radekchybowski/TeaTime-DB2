<?php
include_once 'header.php';

function editTable($conn, $row, $keys)
{
    foreach($keys as $key)
    {
        if (($key == $keys[0]) && ($_GET['table'] != 'uzytkownicy_herbaty') ) {
            echo '<td>' . $row[$key] . '</td>';
            continue;
        }

        echo '<td><input type="text" name="'.$key.'" placeholder="' . $row[$key] . '"></td>';
    }
    echo '<input type="hidden" id="update" name="update" value="'.$_GET['update'].'">';
    echo '<input type="hidden" id="update2" name="update2" value="'.$_GET['update2'].'">';
    echo '<td><input class="btn btn-success" type="submit" name="submit" value="Zapisz"></td>';
    echo '<td><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editItemModal">edytuj</button></td>';
    echo '</tr>

    <!-- Modal edytujący wpis w tabeli-->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edytuj wiersz</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form class="" id="update" action="admin_panel.php?table='.$_GET['table'].'" method="POST">';
    
             foreach($keys as $key)
            {
                if (($key == $keys[0]) && ($_GET['table'] != 'uzytkownicy_herbaty'))
                    continue;
                
                    echo '<div class="form-group my-3">';
                    echo '<label class="form-label" for="'.$key.'">'.$key.'</label>';
                    echo '<textarea rows="1" name="' . $key . '" id="' . $key . '" class="form-control w-100">' . $row[$key] . '</textarea>';
                    echo '</div>';
            }
            echo '<input type="hidden" id="update" name="update" value="'.$_GET['update'].'">';
            echo '<input type="hidden" id="update2" name="update2" value="'.$_GET['update2'].'">';
            
  echo '</div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
            <input type="submit" for="update" "form="update" class="btn btn-primary" value="Zapisz zmiany"></input></form>
            
            
    
      </div>
    </div>
  </div>
</div>';

}

function displayTable($conn, $table)
{

    echo '<div class="card-header d-flex align-items-center">
                <h2>Tabela '.$table.'</h2>';
    if (isset($_SESSION['searchActive']) && ($_SESSION['searchActive'] == true))
        echo '<a href="admin_panel.php?table=' . $table . '&search=false" class="btn btn-warning ms-3">Wyłącz filtrowanie</a>';
          echo '<div class="search-bar input-group rounded d-flex ms-auto align-items-center">
                    <span class="input-group-text border-0" id="search-addon">
                        <i class="bi bi-search"></i>
                    </span>
                    <form action="admin_panel.php" method="POST">
                        <input type="search" id="search_input" name="search_input" class="" style="" placeholder="Wyszukaj..." aria-controls="table">
                        <input type="submit" id="submitSearch" name="submitSearch" class="mx-2 d-inline-block" style="margin: 0px 100px" value="Szukaj" aria-controls="table">
                    </form>
                </div>
                <button type="button" class="btn btn-primary ms-3" style="margin-left: 200px;" data-bs-toggle="modal" data-bs-target="#newItemModal">Nowy wiersz</button>
            </div>
            <div class="card-body table-responsive">
            <form action="admin_panel.php?table='.$_GET['table'].'" method="POST"><tr>   
            <table class="table">
                <thead class="table-light">';

    if (!isset($_SESSION['searchActive']) || ($_SESSION['searchActive'] == false)) 
    {
        $_SESSION['sql_'.$table] = 'SELECT * FROM '.$table.';';
    }
    if ($result = @$conn->query($_SESSION['sql_'.$table])) 
    {
        
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            @$keys = @array_keys($row);
            //wyszukiwanie
            if (isset($_POST['submitSearch']) && ($_POST['submitSearch'] != NULL)) {
                $_SESSION['searchActive'] = true;
                $search = strtolower($_POST['search_input']);

                if ($table == 'herbaty')
                    $_SESSION['sql_herbaty'] = 'SELECT * FROM herbaty WHERE ' . $keys[1] . ' LIKE "%' . $search . '%" OR ' . $keys[2] . ' LIKE "%' . $search . '%" OR ' . $keys[3] . ' LIKE "%' . $search . '%" OR ' . $keys[4] . ' LIKE "%' . $search . '%" OR ' . $keys[5] . ' LIKE "%' . $search . '%" OR ' . $keys[6] . ' LIKE "%' . $search . '%" OR ' . $keys[7] . ' LIKE "%' . $search . '%" OR ' . $keys[8] . ' LIKE "%' . $search . '%" OR ' . $keys[9] . ' LIKE "%' . $search . '%";'; 

                if ($table == 'uzytkownicy')
                    $_SESSION['sql_uzytkownicy'] = 'SELECT * FROM uzytkownicy WHERE ' . $keys[1] . ' LIKE "%' . $search . '%" OR ' . $keys[2] . ' LIKE "%' . $search . '%" OR ' . $keys[3] . ' LIKE "%' . $search . '%" OR ' . $keys[4] . ' LIKE "%' . $search . '%" OR ' . $keys[5] . ' LIKE "%' . $search . '%" OR ' . $keys[6] . ' LIKE "%' . $search . '%"';

                if ($table == 'uzytkownicy_herbaty')
                    $_SESSION['sql_uzytkownicy_herbaty'] = 'SELECT * FROM uzytkownicy_herbaty WHERE ' . $keys[0] . ' LIKE "%' . $search . '%" OR ' . $keys[1] . ' LIKE "%' . $search . '%" OR ' . $keys[2] . ' LIKE "%' . $search . '%" OR ' . $keys[3] . ' LIKE "%' . $search . '%"';

                if ($result = @$conn->query($_SESSION['sql_' . $table . '']))
                    $row = $result->fetch_assoc();
            }
            $_SESSION[$table . '_id'] = $keys[0];
            foreach ($keys as $key) {
                echo '<th><b>' . $key . '</b></th>';
            }

            echo '</thead><tbody>';

            foreach ($result as $row) {
                if (isset($_GET['delete']) && ($_GET['delete'] == $row[$keys[0]]) && ($_GET['table'] == $table) && ($_GET['table'] != 'uzytkownicy_herbaty'))
                    echo '<tr style="background-color: #ffc3bf">';
                elseif (isset($_GET['delete']) && ($_GET['delete'] == $row[$keys[0]]) && ($_GET['table'] == $table) && ($_GET['delete2'] == $row[$keys[1]]))
                    echo '<tr style="background-color: #ffc3bf">';
                else
                    echo '<tr>';
                if(isset($_GET['update']) && ($_GET['table'] == 'uzytkownicy_herbaty') && ($_GET['update'] == $row[$keys[0]]) && ($_GET['update2'] == $row[$keys[1]]) )
                {
                    editTable($conn, $row, $keys);
                    continue;
                }
                elseif (isset($_GET['update']) && ($_GET['update'] == $row[$keys[0]]) && ($_GET['table'] == $table) && ($_GET['table'] != 'uzytkownicy_herbaty') ) {
                    editTable($conn, $row, $keys);
                    continue;
                }

                foreach ($keys as $key) {
                    // if ($key == 'herbata_id')
                    //     continue;
                    if ($key == 'nazwa') {
                        echo '<td><a href="tea.php?id=' . $row[$keys[0]] . '">' . $row[$key] . '</a></td>';
                        continue;
                    }
                    if ($key == 'haslo') {
                        echo '<td>' . mb_substr($row['haslo'], 0, 20) . '...</td>';
                        continue;
                    }

                    if ($key == 'zdjecie') {
                        echo '<td>~/...' . @mb_substr($row['zdjecie'], 46, 50) . '</td>';
                        continue;
                    }
                    echo '<td> ' . $row[$key] . '</td>';
                }

                //przycisk zmień
                echo '<td><a class="btn btn-info" href="admin_panel.php?update=' . $row[$keys[0]] . '&update2=' . $row[$keys[1]] . '&table=' . $table . '">Zmień</a></td>';
                '</tr>';

                //przycisk usuń
                if ($table == 'uzytkownicy_herbaty') {
                    if (isset($_GET['delete2']) && ($_GET['delete'] == $row[$keys[0]]) && ($_GET['delete2'] == $row[$keys[1]]) && ($_GET['table'] == $table))
                        echo '<td><a class="btn btn-danger" href="admin_panel.php?delete=' . $row[$keys[0]] . '&delete2=' . $row[$keys[1]] . '&table=' . $table . '&confirmed=true">Potwierdź</a></td>';
                    else
                        echo '<td><a class="btn btn-warning" href="admin_panel.php?delete=' . $row[$keys[0]] . '&delete2=' . $row[$keys[1]] . '&table=' . $table . '">Usuń</a></td>';
                } elseif (isset($_GET['delete']) && ($_GET['delete'] == $row[$keys[0]]) && ($_GET['table'] == $table))
                    echo '<td><a class="btn btn-danger" href="admin_panel.php?delete=' . $row[$keys[0]] . '&table=' . $table . '&confirmed=true">Potwierdź</a></td>';
                else
                    echo '<td><a class="btn btn-warning" href="admin_panel.php?delete=' . $row[$keys[0]] . '&table=' . $table . '">Usuń</a></td>';

            }
        }
    }

    echo '</tbody>
          </table>
          </form>
    </div>';
}

//sprawdza czy admin
if ($user_id != 1) {
    header("Location: index.php");
    exit();
}

//usuwa przycisk filtrowania wyników
if (isset($_GET['search'])) {
    $_SESSION['searchActive'] = false;
}

//usuwanie wierszy z tabeli
if (isset($_GET['confirmed']) && $_GET['confirmed'] == 'true')
{
    if (($_GET['delete'] == 1) && ($_GET['table'] == 'uzytkownicy'))
        return;
    if($_GET['table'] == 'uzytkownicy_herbaty')
    {
        if($conn->query('DELETE FROM '.$_GET['table'].' WHERE fk_user_id=' . $_GET['delete'] . ' and fk_herbata_id='.$_GET['delete2'].';'))
            echo '<p class="h5 text-center">Rekord został poprawnie usunięty.</p>';
        else 
            echo 'Rekord nie został usunięty z powodu błędu.';
    }
    else
    {
        if($conn->query('DELETE FROM '.$_GET['table'].' WHERE '.$_SESSION[$_GET['table'].'_id'].'=' . $_GET['delete'] . ';'))
            echo '<p class="h5 text-center">Rekord został poprawnie usunięty.</p>';
        else 
            echo 'Rekord nie został usunięty z powodu błędu.'; 
    }
    
}

//dodawanie wierszy do tabeli
if (isset($_GET['new']))
{
    $table = $_GET['new'];
    if($table == 'uzytkownicy')
    {
        $_POST['haslo'] = password_hash($_POST['haslo'], PASSWORD_ARGON2I);
        if ($_POST['czas_zalozenia'] == NULL) $_POST['czas_zalozenia'] = date("Y-m-d H:i:s");         
        if($result = $conn->query('INSERT INTO uzytkownicy (login, imie, nazwisko, haslo, czas_zalozenia, zdjecie) VALUES ("'.$_POST['login'].'", "'.$_POST['imie'].'", "'.$_POST['nazwisko'].'", "'.$_POST['haslo'].'", "'.$_POST['czas_zalozenia'].'", "'.$_POST['zdjecie'].'")'))
            echo '<p class="h5 mt-4 mb-1 text-center">Rekord został poprawnie dodany.</p>';
        else echo '<p class="h5 mt-4 mb-1 text-center">Coś poszło nie tak.</p>';

    }

    if($table == 'herbaty')
    {      
        if($result = $conn->query('INSERT INTO herbaty (nazwa, gatunek, skladniki, czas_parzenia, temperatura, sklep, region, opis, zdjecie) VALUES ("'.$_POST['nazwa'].'", "'.$_POST['gatunek'].'", "'.$_POST['skladniki'].'", "'.$_POST['czas_parzenia'].'", "'.$_POST['temperatura'].'", "'.$_POST['sklep'].'", "'.$_POST['region'].'", "'.$_POST['opis'].'", "'.$_POST['zdjecie'].'");'))
            echo '<p class="h5 mt-4 mb-1 text-center">Rekord został poprawnie dodany.</p>';
        else echo '<p class="h5 mt-4 mb-1 text-center">Coś poszło nie tak.</p>';
    }

    if($table == 'uzytkownicy_herbaty')
    {      
        if($result = $conn->query('INSERT INTO uzytkownicy_herbaty VALUES ("'.$_POST['fk_user_id'].'", "'.$_POST['fk_herbata_id'].'", "'.$_POST['ocena'].'", "'.$_POST['notatki'].'");'))
            echo '<p class="h5 mt-4 mb-1 text-center">Rekord został poprawnie dodany.</p>';
        else echo '<p class="h5 mt-4 mb-1 text-center">Coś poszło nie tak.</p>';
    }
}

//edycja wierszy
if (isset($_POST['update']))
{
    $table = $_GET['table'];
    if($result = $conn->query('SELECT * FROM '.$table.';'))
    {
        $row = $result->fetch_assoc();
        @$keys = @array_keys($row);
        if ($table != 'uzytkownicy_herbaty') {
            foreach ($keys as $key) {
                if ($keys[0] == $key)
                    continue;
                if ($_POST[$key] != NULL) {
                    if ($key == 'haslo') $_POST['haslo'] = password_hash($_POST['haslo'], PASSWORD_ARGON2I);

                    if ($conn->query('UPDATE ' . $table . ' SET ' . $key . ' = "' . $_POST[$key] . '" WHERE ' . $keys[0] . ' = ' . $_POST['update'] . '')) {
                        echo '<p class="h5 mt-4 mb-1 text-center">Rekord został zaktualizowany.</p>';
                    }
                }
            }
        }
        else
        {
            foreach ($keys as $key) {
                if ($_POST[$key] != NULL) {
                    if ($conn->query('UPDATE ' . $table . ' SET ' . $key . ' = "' . $_POST[$key] . '" WHERE ' . $keys[0] . ' = ' . $_POST['update'] . ' AND ' . $keys[1] . ' = ' . $_POST['update2'] . ';')) {
                        echo '<p class="h5 mt-4 mb-1 text-center">Rekord został zaktualizowany.</p>';
                    }
                }
            }
        }

    }
}

?>
<br>
<h1 class="mt-5 text-center">Panel Administratora</h1>
<p class="text-center">Bezpośrednia edycja tabel, mogą być scrollowane horyzontalnie.</p>
<ul class="nav nav-pills mb-3 mx-auto" style="max-width:518px;" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="?table=uzytkownicy"><button class="nav-link <?php if( isset($_GET['table']) && ($_GET['table'] == 'uzytkownicy') ) echo 'active'?>" id="pills-uzytkownicy-tab" data-bs-toggle="pill" data-bs-target="#pills-uzytkownicy" type="button">Uzytkownicy</button></a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="?table=herbaty"><button class="nav-link <?php if( isset($_GET['table']) && ($_GET['table'] == 'herbaty') ) echo 'active'?>" id="pills-herbaty-tab" data-bs-toggle="pill" data-bs-target="#pills-herbaty" type="button">Herbaty</button></a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="?table=uzytkownicy_herbaty"><button class="nav-link <?php if( isset($_GET['table']) && ($_GET['table'] == 'uzytkownicy_herbaty') ) echo 'active'?>" id="pills-uzytkownicy_herbaty-tab" data-bs-toggle="pill" data-bs-target="#pills-uzytkownicy_herbaty" type="button">Uzytkownicy_Herbaty</button></a>
    </li>
</ul>

<?php


?>

<!-- CZĘŚĆ HTML STRONY -->
<!-- Tile wrapper for content -->
<div class="card bg-light m-3 mx-auto col-sm-12 mt-5 mb-" style="max-width: 100rem;">
    
    <div class="tab-content" id="pills-tabContent">

        <div class="tab-pane fade <?php if(@$_GET['table'] == 'uzytkownicy') echo 'show active'?>" id="pills-uzytkownicy" role="tabpanel" aria-labelledby="pills-uzytkownicy-tab" tabindex="0">

            <?php displayTable($conn, 'uzytkownicy'); ?>

        </div>


        <div class="tab-pane fade <?php if(@$_GET['table'] == 'herbaty') echo 'show active'?>" id="pills-herbaty" role="tabpanel" aria-labelledby="pills-herbaty-tab" tabindex="0">

            <?php displayTable($conn, 'herbaty'); ?>

        </div>
        <div class="tab-pane fade <?php if(@$_GET['table'] == 'uzytkownicy_herbaty') echo 'show active'?>" id="pills-uzytkownicy_herbaty" role="tabpanel" aria-labelledby="pills-uzytkownicy_herbaty-tab" tabindex="0">

            <?php displayTable($conn, 'uzytkownicy_herbaty'); ?>

        </div>
    </div>

</div>

<!-- Modal Dodający nowy wiersz do tabeli -->
<div class="modal fade" id="newItemModal" tabindex="-1" aria-labelledby="newItemModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">

        <?php
        $table = $_GET['table'];
        echo '<h5 class="modal-title" id="exampleModalLabel">Dodaj nowy wiersz w tabeli ' . $table . '</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        <form class="" id="new" action="admin_panel.php?new='.$table.'" method="POST">';
        if($result = @$conn->query('SELECT * FROM '.$table.';'))
        {
            $row = $result->fetch_assoc();
            $keys = array_keys($row);

            foreach($keys as $key)
            {
                if (($key == $keys[0]) && ($table != 'uzytkownicy_herbaty'))
                    continue;
                if( ($key == 'czas_zalozenia') || ($key == 'zdjecie') || ($key == 'skladniki') || ($key == 'opis') || ($key == 'notatki') )
                {
                    echo '<div class="form-group my-3">';
                    echo '<label class="form-label" for="'.$key.'">'.$key.'</label>';
                    echo '<textarea rows="1" name="' . $key . '" id="' . $key . '" class="form-control w-100">' . $row[$key] . '</textarea>';
                    echo '</div>';
                    continue;
                }
                echo '<div class="form-group my-3">';
                echo '<label class="form-label" for="'.$key.'">'.$key.'</label>';
                echo '<input class="form-control" type="text" id="'.$key.'" name="'.$key.'" placeholder="' . $row[$key] . '">';
                echo '</div>';
            }
            
        }
        echo '</div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
            <input type="submit" form="new" class="btn btn-primary" value="Zapisz zmiany"></input></form>';
            
            
        ?>
      </div>
    </div>
  </div>
</div>              

<?php
include_once 'footer.php';
?>

