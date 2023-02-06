<?php
include_once 'header.php';
?>
                    <br>
                    <br>
                    <!-- Tile wrapper for content -->
                    <div class="card bg-light m-3 mx-auto col-sm-10 mt-5" style="max-width: 56rem;">
                        <div class="card-header"><h2>Ustawienia</h2></div>
                        <div class="card-body">
                            <form action="logowanie.php" enctype="multipart/form-data" method="POST">
                                <h5 class="card-title">Zmień dane</h5>

                                <!-- Name imput -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="updateName">Imię</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="updateName" name="updateName" class="form-control" 
                                        <?php
                                        echo 'placeholder="'.$imie.'"'
                                        ?>/>
                                    </div>
                                </div>
            
                                <!-- Surname input -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="updateSurname">Nazwisko</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="updateSurname" name="updateSurname" class="form-control"
                                        <?php
                                        echo 'placeholder="'.$nazwisko.'"'
                                        ?>/>
                                    </div>
                                </div>
            
                                <!-- Username input -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="updateUsername">Nazwa użytkownika</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="updateUsername" name="updateUsername" class="form-control"
                                        <?php
                                        echo 'placeholder="'.$login.'"'
                                        ?>/>
                                    </div>
                                </div>

                                <!-- New profile picture -->
                                <div class="form-group row mb-4 mb-2">
                                    <label class="col-sm-4 col-form-label" for="updateAvatar">Zdjęcie profilowe</label>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="300000"/>
                                        <input type="file" id="updateAvatar" name="updateAvatar" class="form-control-file"/>
                                    </div>
                                </div>
                                
                                <h5 class="card-title">Zmień hasło</h5>

                                <!-- Old Password -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="updateOldPassword">Stare hasło</label>
                                    <div class="col-sm-8">
                                        <input type="password" id="updateOldPassword" name="updateOldPassword" class="form-control"/>
                                    </div>
                                </div>

                                <!-- Password input -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="updatePassword">Nowe hasło</label>
                                    <div class="col-sm-8">
                                        <input type="password" id="updatePassword" name="updatePassword" class="form-control"/>
                                    </div>
                                </div>
            
                                <!-- Repeat Password input -->
                                <div class="form-group row mb-2">
                                    <label class="col-sm-4 col-form-label" for="updateRepeatPassword">Powtórz nowe hasło</label>
                                    <div class="col-sm-8">
                                        <input type="password" id="updateRepeatPassword" name="updateRepeatPassword" class="form-control"/>
                                    </div>
                                </div>
            
                                <!-- Submit button -->
                                <button type="submit" name="update-form" class="btn btn-success mt-4 d-block ms-auto">Potwierdź</button>
                            </form>

                            
                          
                          
                        </div>
                        
                    </div>
                    <div class="row">
                        <span class="text-center mx-auto">
                            <?php
                                $earlier = new DateTime($czas_zalozenia);
                                $later = new DateTime(date("Y-m-d"));
                                
                                $difference = $later->diff($earlier)->format("%a");
                                echo 'Twoje konto zostało założone '.$difference.' dni temu.';
                            ?></span>
                            <button type="button" name="deleteAccount" class="btn bg-warning text-light mt-3 col-1 mx-auto d-block" style="min-width:250px" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Usuń konto i wszystkie dane</button>
                    </div>
                    
                    <!-- Popup - usunięcie konta -->
                    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Usunięcie konta</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Czy na pewno chcesz usunąć swoje konto, a razem z nim wszystkie zapisane oceny, kolekcje oraz notatki?
                                <br><br>
                                Ta operacja jest nieodwracalna.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Wróć</button>
                                <form id="deleteAccount" action="deleteAccount.php" method="POST">
                                    <button type="submit" name="deleteAccount" id="deleteAccount" class="btn btn-danger">Usuń konto</button>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>

<?php
include_once 'footer.php';
?>