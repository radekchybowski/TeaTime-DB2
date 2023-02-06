<?php
include_once 'header.php';
                  
if(isset($_POST['deleteAccount'])) 
{
    //wyjątek dla admina
    if ($_SESSION['user_id'] == 1)
    {
        echo '<h2 class="text-center mt-5">Konto administratora nie może zostać usunięte, Twoja dola jest nieskończenie długa...</h2>';
        echo '<a class="text-center d-block mx-auto" href="settings.php">Wróć do ustawień</a>';
        exit();

    }
    if ($result = @$conn->query('SELECT * FROM uzytkownicy WHERE user_id = ' . $_SESSION['user_id'] . ';'))
    {
        if($result->num_rows == 1)
        {
            if(@$conn->query('DELETE FROM uzytkownicy WHERE user_id = ' . $_SESSION['user_id'] . ';'))
            {
                //usuwanie zdjęcia (jeśli istnieje)
                $path = '/home/epi/20_chybowski/public_html/BD2/projekt/img/profile_pic/'.$_SESSION['user_id'];
                if(file_exists($path))
                {
                    unlink($path);
                }
                echo 
                '<br><h2 class="text-center mt-5">Twoje konto zostało poprawnie usunięte</h2>
                <h3 class="h4 text-center">Jeśli postanowisz do nas wrócić będziemy na Ciebie czekać :)</h3>
                <a class="text-center d-block mx-auto" href="index.php">Przejdź do strony logowania</a>';
                session_unset();
                exit();
            }
        }
    }
}  

include_once 'footer.php';
?>