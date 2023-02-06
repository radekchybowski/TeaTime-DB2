<?php
include_once 'header.php';

if ($user_id != 1) {
    header("Location: index.php");
    exit();
}

function editTable($conn, $row, $keys)
{
    echo '<form action="admin_panel.php?update='.$_GET['update'].'" method="POST"><tr>';
    foreach($keys as $key)
    {
        if (($key == $keys[0]) && ($_GET['table'] != 'uzytkownicy_herbaty') ) {
            echo '<td>' . $row[$key] . '</td>';
            continue;
        }

        if (($key == $keys[0]) && ($_GET['table'] != 'uzytkownicy_herbaty') ) {
            echo '<td>' . $row[$key] . '</td>';
            continue;
        }

        echo '<td><input type="text" name="'.$key.'" placeholder="' . $row[$key] . '"></td>';
        //echo '<textarea cols="30" rows="1" name="' . $key . '" id="' . $key . '" class="" style="resize: none;">' . $row[$key] . '</textarea>';
    }
    echo '<td><input class="btn btn-success" type="submit" name="submit" value="Zapisz"></td>';
    echo '</tr>';
}

function displayTable($conn, $table)
{

    echo '<div class="card-header d-flex align-items-center">
                <h2>Tabela '.$table.'</h2>
                <div class="search-bar input-group rounded ms-auto align-items-center">
                    <span class="input-group-text border-0" id="search-addon">
                        <i class="bi bi-search"></i>
                    </span>
                    <form action="admin_panel.php?table='.$table.'" method="POST">
                        <input type="search" id="search_input" name="search_input" class="" style="margin-right: -88px;" placeholder="Wyszukaj..." aria-controls="table">
                        <input type="submit" id="submitSearch" name="submitSearch" class="mx-2" value="Szukaj" aria-controls="table">
                    </form>
                </div>
                <button type="button" class="btn btn-primary ms-3" style="margin-left: 200px;" data-bs-toggle="modal" data-bs-target="#newItemModal">Nowy wiersz</button>
            </div>
            <div class="card-body table-responsive">    
            <table class="table">
                <thead class="table-light">';

        if (!isset($_SESSION['searchActive'])) $_SESSION['sql_'.$table] = 'SELECT * FROM ' . $table . ';';
    if ($result = @$conn->query($_SESSION['sql_'.$table])) 
    {
            $row = $result->fetch_assoc();
            $keys = array_keys($row);
            //wyszukiwanie
            if (isset($_POST['submitSearch']) && ($_POST['submitSearch'] != NULL) )
            {
                $_SESSION['searchActive'] = true;
                if ($result = @$conn->query($sql)) {
                    $row = $result->fetch_assoc();
                    $keys = array_keys($row);
                    echo "łoooooooo";
                    $search = $_POST['search_input'];

                    if ($_GET['table'] = 'herbaty')
                        $_SESSION['sql_herbaty'] = 'SELECT * FROM herbaty WHERE ' . $keys[1] . ' LIKE "%' . $search . '%" OR ' . $keys[2] . ' LIKE "%' . $search . '%" OR ' . $keys[3] . ' LIKE "%' . $search . '%" OR ' . $keys[4] . ' LIKE "%' . $search . '%" OR ' . $keys[5] . ' LIKE "%' . $search . '%" OR ' . $keys[6] . ' LIKE "%' . $search . '%" OR ' . $keys[7] . ' LIKE "%' . $search . '%"  OR ' . $keys[8] . ' LIKE "%' . $search . '%"  OR ' . $keys[9] . ' LIKE "%' . $search . '%";';
                    
                    if ($_GET['table'] = 'uzytkownicy')
                        $_SESSION['sql_uzytkownicy'] = 'SELECT * FROM uzytkownicy WHERE ' . $keys[1] . ' LIKE "%' . $search . '%" OR ' . $keys[2] . ' LIKE "%' . $search . '%" OR ' . $keys[3] . ' LIKE "%' . $search . '%" OR ' . $keys[4] . ' LIKE "%' . $search . '%" OR ' . $keys[5] . ' LIKE "%' . $search . '%" OR ' . $keys[6] . ' LIKE "%' . $search . '%"';

                    if ($_GET['table'] = 'uzytkownicy_herbaty')
                        $_SESSION['sql_uzytkownicy_herbaty'] = 'SELECT * FROM uzytkownicy WHERE ' . $keys[0] . ' LIKE "%' . $search . '%" OR ' . $keys[1] . ' LIKE "%' . $search . '%" OR ' . $keys[2] . ' LIKE "%' . $search . '%" OR ' . $keys[3] . ' LIKE "%' . $search . '%"';

                    if ($result = @$conn->query($sql))
                        $row = $result->fetch_assoc();
                }
            }
            else
        $_SESSION[$table.'_id'] = $keys[0];
        foreach ($keys as $key) {
            echo '<th><b>' . $key . '</b></th>';
        }

        echo '</thead><tbody>';

        foreach ($result as $row) {
            if (isset($_GET['delete']) && ($_GET['delete'] == $row[$keys[0]]) && ($_GET['table'] == $table) && ($_GET['table'] != 'uzytkownicy_herbaty'))
                echo '<tr style="background-color: #ffc3bf">';
            elseif (isset($_GET['delete']) && ($_GET['delete'] == $row[$keys[0]]) && ($_GET['table'] == $table) && ($_GET['delete2'] == $row[$keys[1]]))
                echo '<tr style="background-color: #ffc3bf">';
            else echo '<tr>';

            if (isset($_GET['update']) && ($_GET['update'] == $row[$keys[0]]) && ($_GET['table'] == $table))
            {
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
                    echo '<td>'.mb_substr($row['haslo'], 0, 20).'...</td>';
                    continue;
                }

                if ($key == 'zdjecie') {
                    echo '<td>~/...'.@mb_substr($row['zdjecie'], 46, 50).'</td>';
                    continue;
                }
                echo '<td> ' . $row[$key] . '</td>';
            }

            //przycisk zmień
            echo '<td><a class="btn btn-info" href="admin_panel.php?update=' . $row[$keys[0]] . '&table='.$table.'">Zmień</a></td>';
                 '</tr>';

            //przycisk usuń
            if($table == 'uzytkownicy_herbaty')
            {
                if(isset($_GET['delete2']) && ($_GET['delete'] == $row[$keys[0]]) && ($_GET['delete2'] == $row[$keys[1]]) && ($_GET['table'] == $table))
                    echo '<td><a class="btn btn-danger" href="admin_panel.php?delete=' . $row[$keys[0]] . '&delete2='.$row[$keys[1]].'&table=' . $table . '&confirmed=true">Potwierdź</a></td>';
                else 
                    echo '<td><a class="btn btn-warning" href="admin_panel.php?delete=' . $row[$keys[0]] . '&delete2='.$row[$keys[1]].'&table='.$table.'">Usuń</a></td>';
            }
            elseif (isset($_GET['delete']) && ($_GET['delete'] == $row[$keys[0]]) && ($_GET['table'] == $table))
                echo '<td><a class="btn btn-danger" href="admin_panel.php?delete=' . $row[$keys[0]] . '&table=' . $table . '&confirmed=true">Potwierdź</a></td>';
            else 
                echo '<td><a class="btn btn-warning" href="admin_panel.php?delete=' . $row[$keys[0]] . '&table='.$table.'">Usuń</a></td>';
            
        }
    }

    echo '</tbody>
          </table>
    </div>';
}

?>

<h1 class="mt-5 text-center">Panel Administratora</h1>
<p class="text-center">Bezpośrednia edycja tabel, mogą być scrollowane horyzontalnie.</p>
<ul class="nav nav-pills mb-3 mx-auto" style="max-width:518px;" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="?table=uzytkownicy"><button class="nav-link <?php if( isset($_GET['table']) && ($_GET['table'] == 'uzytkownicy') ) echo 'active'?>" id="pills-uzytkownicy-tab" data-bs-toggle="pill" data-bs-target="#pills-uzytkonicy" type="button">Uzytkownicy</button></a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="?table=herbaty"><button class="nav-link <?php if( isset($_GET['table']) && ($_GET['table'] == 'herbaty') ) echo 'active'?>" id="pills-herbaty-tab" data-bs-toggle="pill" data-bs-target="#pills-heraty" type="button">Herbaty</button></a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="?table=uzytkownicy_herbaty"><button class="nav-link <?php if( isset($_GET['table']) && ($_GET['table'] == 'uzytkownicy_herbaty') ) echo 'active'?>" id="pills-uzytkownicy_herbaty-tab" data-bs-toggle="pill" data-bs-target="#pills-uzytkownic_herbaty" type="button">Uzytkownicy_Herbaty</button></a>
    </li>
</ul>

<?php
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
?>


<!-- Tile wrapper for content -->
<div class="card bg-light m-3 mx-auto col-sm-12 mt-5" style="max-width: 100rem;">
    
    <div class="tab-content" id="pills-tabContent">

        <div class="tab-pane fade <?php if($_GET['table'] == 'uzytkownicy') echo 'show active'?>" id="pills-uzytkownicy" role="tabpanel" aria-labelledby="pills-uzytkownicy-tab" tabindex="0">

            <?php displayTable($conn, 'uzytkownicy'); ?>

        </div>


        <div class="tab-pane fade <?php if($_GET['table'] == 'herbaty') echo 'show active'?>" id="pills-herbaty" role="tabpanel" aria-labelledby="pills-herbaty-tab" tabindex="0">

            <?php displayTable($conn, 'herbaty'); ?>

        </div>
        <div class="tab-pane fade <?php if($_GET['table'] == 'uzytkownicy_herbaty') echo 'show active'?>" id="pills-uzytkownicy_herbaty" role="tabpanel" aria-labelledby="pills-uzytkownicy_herbaty-tab" tabindex="0">

            <?php displayTable($conn, 'uzytkownicy_herbaty'); ?>

        </div>
    </div>

</div>

<!-- Modal -->
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

        <form class="" name-"new" action="admin_panel.php?new='.$table.'" method="POST">';
        if($result = @$conn->query('SELECT * FROM '.$table.';'))
        {
            $row = $result->fetch_assoc();
            $keys = array_keys($row);

            foreach($keys as $key)
            {
                echo '<label class="form-label" for="'.$key.'">'.$key.'</label>';
                echo '<input class="form-control" type="text" id="'.$key.'" name="'.$key.'" placeholder="' . $row[$key] . '">';
            }
            echo '<input type="submit" form="new" class="btn btn-primary" value="Zapisz zmiany"></input></form>';
        }
        echo '</div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>';
            
            
        ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="datatables/datatables.min.js"></script>               

<?php
include_once 'footer.php';
?>

