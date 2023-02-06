<?php
require_once 'autoryzacja.php';
session_start();

function PasswordCheck($string) 
{
    $uppercase = preg_match('@[A-Z]@', $string);
    $lowercase = preg_match('@[a-z]@', $string);
    $number = preg_match('@[0-9]@', $string);
    if (!$uppercase || !$lowercase || !$number || strlen($string) < 8) {
        return false;
    }
    return true;
}


$_SESSION['UserAlreadyExists'] = false;
$_SESSION['badLogin'] = false;
$_SESSION['badPassword'] = false;

$_SESSION['$conn'] = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
or die('Bład połączenia z serwerem: '.mysqli_connect_error());
$conn = $_SESSION['$conn'];
mysqli_query($conn, 'SET NAMES utf8');

//!!!LOGIN
if (isset($_POST['loginUsername'])) 
{
    $login = htmlentities($_POST['loginUsername'], ENT_QUOTES, "UTF-8");
    $password = htmlentities($_POST['loginPassword'], ENT_QUOTES, "UTF-8");
    //check if user with this username is in database
    if ($result = @$conn->query(sprintf('SELECT * FROM uzytkownicy WHERE login="%s";', mysqli_real_escape_string($conn, $login) ) ) ) 
    {
        if ($result->num_rows == 1) 
        {
            //check if password is correct
            $wiersz = $result->fetch_assoc();
            if(password_verify($_POST['loginPassword'], $wiersz['haslo'])) 
            {
                //assigning values from new user to $_SESSION global table
                $_SESSION['user_id'] = $wiersz['user_id'];
                $_SESSION['login'] = $wiersz['login'];
                $_SESSION['imie'] = $wiersz['imie'];
                $_SESSION['nazwisko'] = $wiersz['nazwisko'];
                $_SESSION['czas_zalozenia'] = $wiersz['czas_zalozenia'];
                if ($wiersz['zdjecie'] != NULL) $_SESSION['zdjecie'] = true;
                else $_SESSION['zdjecie'] = false;
                header("Location: dashboard.php");
                $result->free_result();
                $conn->close();
                exit();
            } 
            else 
            {
                $_SESSION['badPassword'] = true;
                echo "Podane hasło jest nieprawidłowe";
                header("Location: index.php");
                $result->free_result();
                $conn->close();
                exit();
            }

        } else {
            $_SESSION['badLogin'] = true;
            echo "Podany login jest niepoprawny lub nie znaleziono uzytkownika.";
            header("Location: index.php");
            $result->free_result();
            $conn->close();
            exit();
        }
    }
}

//!!!REGISTRATION
if (isset($_POST['registerUsername'])) 
{

    $login = htmlentities($_POST['registerUsername'], ENT_QUOTES, "UTF-8");
    $password = htmlentities($_POST['registerPassword'], ENT_QUOTES, "UTF-8");
    $name = htmlentities($_POST['registerName'], ENT_QUOTES, "UTF-8");
    $surname = htmlentities($_POST['registerSurname'], ENT_QUOTES, "UTF-8");
    $repeatPassword = htmlentities($_POST['registerRepeatPassword'], ENT_QUOTES, "UTF-8");

    //check if this username is not taken
    if ($result = @$conn->query(sprintf('SELECT * FROM uzytkownicy WHERE login="%s";', mysqli_real_escape_string($conn, $login) ) ) ) 
    {
        if ($result->num_rows == 0) 
        {
            //check if repeated password is the same as normal
            if($password == $repeatPassword) 
            {
                //hashing password
                $hash = password_hash($password, PASSWORD_ARGON2I);

                //adding user data from form to the users table
                if (@$conn->query(sprintf('INSERT INTO uzytkownicy (login, imie, nazwisko, haslo) VALUES ("%s", "%s", "%s", "%s");', 
                mysqli_real_escape_string($conn, $login),
                mysqli_real_escape_string($conn, $name),
                mysqli_real_escape_string($conn, $surname),
                mysqli_real_escape_string($conn, $hash)
                ))) 
                {
                    if ($result = @$conn->query(sprintf('SELECT * FROM uzytkownicy WHERE login="%s";', mysqli_real_escape_string($conn, $login) ) ) ) 
                    {
                        //assigning values from new user to $_SESSION global table
                        $wiersz = $result->fetch_assoc();
                        $_SESSION['user_id'] = $wiersz['user_id'];
                        $_SESSION['login'] = $wiersz['login'];
                        $_SESSION['imie'] = $wiersz['imie'];
                        $_SESSION['nazwisko'] = $wiersz['nazwisko'];
                        $_SESSION['czas_zalozenia'] = $wiersz['czas_zalozenia'];
                        $_SESSION['zdjecie'] = false;
                        header("Location: dashboard.php");
                        $result->free_result();
                        $conn->close();
                        exit();
                    }
                    
                }
                else
                {
                echo "tych znaków uzywac nie mozesz";
                $_SESSION['invalidRepeatPass'] = true;
                //header("Location: index.php");
                $result->free_result();
                $conn->close();
                exit();
                }
            }
            else 
            {
                $_SESSION['invalidRepeatPass'] = true;
                //header("Location: index.php");
                $result->free_result();
                $conn->close();
                exit();
            }
        } 
        else 
        {
            $_SESSION['UserAlreadyExists'] = true;
            echo "Podany uzytkownik juz znajduje się w bazie";
            //header("Location: index.php");
            $result->free_result();
            $conn->close();
            exit();
        }
    }
}

// !!! Update profile info
if (isset($_POST['update-form'])) 
{
    if (isset($_POST['updateUsername']) && ($_POST['updateUsername'] != NULL)) {
        $login = htmlentities($_POST['updateUsername'], ENT_QUOTES, "UTF-8");
        if ($result = @$conn->query(sprintf('UPDATE uzytkownicy SET login="%s" WHERE user_id="%d";', mysqli_real_escape_string($conn, $login), $_SESSION['user_id'] 
        ))) 
        {
            $_SESSION['login'] = $login;
            $_SESSION['updateUsername'] = true;
        }
        else
        {
            $_SESSION['updateUsername'] = false;
        }
    }
    
    if (isset($_POST['updateName']) && ($_POST['updateName'] != NULL)) {
        $imie = htmlentities($_POST['updateName'], ENT_QUOTES, "UTF-8");
        if ($result = @$conn->query(sprintf('UPDATE uzytkownicy SET imie="%s" WHERE user_id="%d";', mysqli_real_escape_string($conn, $imie), $_SESSION['user_id'] 
        ))) 
        {
            $_SESSION['imie'] = $imie;
            $_SESSION['updateName'] = true;
        }
        else
        {
            $_SESSION['updateName'] = false;
        }
    }

    if (isset($_POST['updateSurname']) && ($_POST['updateSurname'] != NULL)) {
        $nazwisko = htmlentities($_POST['updateSurname'], ENT_QUOTES, "UTF-8");
        if ($result = @$conn->query(sprintf('UPDATE uzytkownicy SET nazwisko="%s" WHERE user_id="%d";', mysqli_real_escape_string($conn, $nazwisko), $_SESSION['user_id'] 
        ))) 
        {
            $_SESSION['nazwisko'] = $nazwisko;
            $_SESSION['updateSurname'] = true;
        }
        else
        {
            $_SESSION['updateSurname'] = false;
        }
    }

    if (isset($_POST['updatePassword']) && ($_POST['updatePassword'] != NULL)) {

        $oldPass = htmlentities($_POST['updateOldPassword'], ENT_QUOTES, "UTF-8");
        $newPass = htmlentities($_POST['updatePassword'], ENT_QUOTES, "UTF-8");
        $newRepeatPass = htmlentities($_POST['updateRepeatPassword'], ENT_QUOTES, "UTF-8");

        if ($result = @$conn->query('SELECT haslo FROM uzytkownicy WHERE user_id="' . $_SESSION['user_id'] . '";', )) {
            $wiersz = $result->fetch_assoc();
            if (password_verify($oldPass, $wiersz['haslo'])) 
            {
                if ($newPass == $newRepeatPass) {
                    $hash = password_hash($newPass, PASSWORD_ARGON2I);
                    if ($result = @$conn->query('UPDATE uzytkownicy SET haslo="'.$hash.'" WHERE user_id="'.$_SESSION['user_id'].'";')) 
                    {
                        $_SESSION['updatePassword'] = true;
                    } else {
                        $_SESSION['updatePassword'] = false;
                        echo "cos nie pyklo";
                    }
                }
                else 
                {
                    $_SESSION['updateRepeatPassword'] = false;
                    echo "hasla nie zgadzaja sie";
                }
            }
            else 
            {
                $_SESSION['updateOldPassword'] = false;
                echo "zle haslo";
            }
        }
    }


    if ($_SERVER['REQUEST_METHOD'] == "POST") 
    {
        if (isset($_FILES['updateAvatar'])) 
        {
            // file name, type, size, temporary name 
            $file_name = $_FILES['updateAvatar']['name'];
            $file_type = $_FILES['updateAvatar']['type'];
            $file_tmp_name = $_FILES['updateAvatar']['tmp_name'];
            $file_size = $_FILES['updateAvatar']['size'];
            
            // target directory 
            $target_dir = '/home/epi/20_chybowski/public_html/BD2/projekt/img/profile_pic/';

            // uploding file 
            if (move_uploaded_file($file_tmp_name, $target_dir . $_SESSION['user_id'])) 
            {
                if (@$conn->query('UPDATE uzytkownicy SET zdjecie = "' . $target_dir . $_SESSION['user_id'] .'" WHERE user_id='.$_SESSION['user_id'].';')) {
                    $_SESSION['updateAvatar'] = true;
                    $_SESSION['zdjecie'] = true;
                } 
                else 
                {
                    $_SESSION['updateAvatar'] = false;
                }
            } 
            else 
            {
                $_SESSION['updateAvatar'] = false;
            }
        } else
        $_SESSION['updateAvatar'] = false;
    }
    header("Location: settings.php");
    exit();
}

if ((!isset($_POST['loginUsername'])) && (!isset($_POST['registerUsername'])) ) {
    header("Location: index.php");
    exit();
} 
?>

