<!-- Radosław Chybowski -->
<!DOCTYPE html>
<html lang="pl">
  <head>
     <meta charset="utf-8">
     <title>TeaTime | Logowanie</title>
     <meta name="author" content="Radosław Chybowski">
     <meta http-equiv="X-Ua-Compatible" content="IE=edge,chrome=1">
     <meta name="viewport" content="width=device-width, initial-scale=1">

     <!-- Favicon-->
     <link rel="icon" type="image/x-icon" href="img/icon-coffee.svg" />

     <!-- CSS -->
     <link rel="stylesheet" href="css/bootstrap.css">
     <link rel="stylesheet" href="css/mdb.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css"/>
     <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
    <?php
        session_start();
        if (isset($_SESSION['user_id'])) {
        header("Location: dashboard.php");
        exit();
    }
    ?>
  <section class="h-100 gradient-form" style="background-color: #eee;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-xl-10">
        <div class="card rounded-3 text-black">
          <div class="row g-0" >
            <div class="col-lg-6">
              <div class="card-body p-md-5 mx-md-4">

                <div class="text-center my-3">
                <div class="sidebar-heading text-center">
                  <img class="img-fluid me-2" width=50px src="img/icon-coffee.svg" alt="Site logo">
                  <h2 class="d-inline-block" style="vertical-align: sub;">TeaTime</h2>
                  <p class="">Najlepsza strona z herbatą, naprawdę.</p>
                </div>
                </div>

                <!-- Pills nav -->
                <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="tab-login" data-mdb-toggle="pill" href="#pills-login" role="tab"
                    aria-controls="pills-login" aria-selected="true">Logowanie</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="tab-register" data-mdb-toggle="pill" href="#pills-register" role="tab"
                    aria-controls="pills-register" aria-selected="false">Rejestracja</a>
                </li>
                </ul>
                <!-- Pills nav -->

                <!-- Pills content -->
                <div class="tab-content">
                <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                    <form class="needs-validation" action="logowanie.php" method="POST">

                    <!-- login input -->
                    <div class="form-outline mb-4 was-validated">
                        <input type="text" id="loginUsername" name="loginUsername" class="form-control" required autofocus/>
                        <label class="form-label" for="loginUsername">Nazwa użytkownika</label>
                        <?php
                            if( isset($_SESSION['badLogin']) && ($_SESSION['badLogin'] == true) )
                            echo '<div class="invalid-feedback mb-1">Podany login jest niepoprawny lub nie znaleziono uzytkownika</div>';
                        ?>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4 was-validated">
                        <input type="password" id="loginPassword" name="loginPassword" class="form-control" minlength="5" required/>
                        <label class="form-label" for="loginPassword">Hasło</label>
                        <?php
                            if( isset($_SESSION['badPassword']) && ($_SESSION['badPassword'] == true) )
                            echo '<div class="invalid-feedback mb-1">Podane hasło jest nieprawidłowe</div>';
                        ?>
                    </div>

                    <!-- 2 column grid layout -->
                    <div class="row mb-4">
                        <div class="col-md-6 d-flex justify-content-center">
                        <!-- Checkbox -->
                        <div class="form-check mb-3 mb-md-0 was-validated">
                            <input class="form-check-input" type="checkbox" value="" id="loginCheck" checked />
                            <label class="form-check-label" for="loginCheck"> Zapamiętaj mnie </label>
                        </div>
                        </div>

                        <div class="col-md-6 d-flex justify-content-center">
                        <!-- Simple link -->
                        <a href="#!"data-bs-toggle="modal" data-bs-target="#resetPasswordtModal">Reset hasła...</a>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mb-4">Zaloguj się</button>

                    </form>
                </div>
                <div class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
                    <form class="needs-validation" action="logowanie.php" method="POST">

                    <!-- Name input -->
                    <div class="form-outline mb-4 d-inline-block me-1" style="width: 48%">
                        <input type="text" id="registerName" name="registerName" class="form-control" optional/>
                        <label class="form-label" for="registerName">Imię</label>
                    </div>

                    <!-- Surname input -->
                    <div class="form-outline mb-4 d-inline-block ms-1" style="width: 48%">
                        <input type="text" id="registerSurname" name="registerSurname" class="form-control" />
                        <label class="form-label" for="registerSurname">Nazwisko</label>
                    </div>

                    <!-- Username input -->
                    <div class="form-outline mb-4 was-validated">
                        <input type="text" id="registerUsername" name="registerUsername" class="form-control" />
                        <label class="form-label" for="registerUsername" required autofocus>Nazwa użytkownika</label>
                        <?php
                            if(isset($_SESSION['UserAlreadyExists']) && ($_SESSION['UserAlreadyExists'] == true) )
                            echo '<div class="invalid-feedback mb-1">Podany uzytkownik juz znajduje się w bazie</div>';
                        ?>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4 was-validated">
                        <input type="password" id="registerPassword" name="registerPassword" class="form-control" />
                        <label class="form-label" for="registerPassword" required>Hasło</label>
                    </div>

                    <!-- Repeat Password input -->
                    <div class="form-outline mb-4 was-validated">
                        <input type="password" id="registerRepeatPassword" name="registerRepeatPassword" class="form-control" />
                        <label class="form-label" for="registerRepeatPassword">Powtórz hasło</label>
                        <?php
                            if(isset($_SESSION['invalidRepeatPass']) && ($_SESSION['invalidRepeatPass'] == true) )
                            echo '<div class="invalid-feedback mb-1">Podane hasła są rózne</div>';
                        ?>
                    </div>

                    <!-- Checkbox -->
                    <div class="form-check d-flex justify-content-center mb-4 was-validated">
                        <input class="form-check-input me-2" type="checkbox" value="" id="registerCheck" checked
                        aria-describedby="registerCheckHelpText" />
                        <label class="form-check-label" for="registerCheck">
                        Oddaję duszę diabłu
                        </label>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mb-3">Zarejestruj</button>
                    </form>
                </div>
                </div>
                <!-- Pills content -->

                

              </div>
            </div>
            
            <div class="col-lg-6 d-flex div-bg-image">
              <img class="col-lg-12 d-flex div-bg-image" style="width: 100%; object-fit: cover; background-size: cover; background-repeat: no-repeat; background-position: center;" src="img/bg-tea.jpg" alt="Zdjęcie przedstawia kubek pełny herbaty.">
              <!-- <div class="mask text-light d-flex justify-content-center flex-column text-center text-white px-3 py-4 p-md-5 mx-md-4" style="background-color: rgba(0, 0, 0, 0.5)">
                <h4 class="mb-4">Herbata to coś więcej niz napój</h4>
                <p class="small mb-0">Herbata to styl zycia, powołanie, sposób na zmianę siebie i świata wokół siebie. Pijąc herbatę mozesz poznać wyjątkowych ludzi, zbudować relacje na całe zycie, spotkać swoją pierwszą miłość. Mozesz się poparzyć i powiedzieć kolegom ze uprawiasz sporty ekstremalne. Herbata jest cudowna.</p>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
  
<div class="modal fade" id="resetPasswordtModal" tabindex="-1" aria-labelledby="resetPasswordtModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Reset hasła</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              W celu zresetowania hasła skontaktuj się z Administratorem strony.
          </div>
        </div>
    </div>
</div>

  <script src="js/bootstrap.js"></script>
  <script src="js/mdb.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.js"></script>
  </body>
</html>


