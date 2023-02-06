<?php
include_once 'header.php';
?>
                    <br>
                    <br>
                    <!-- Tile wrapper for content -->
                    <div class="card bg-light m-3 mx-auto col-sm-10 mt-5" style="max-width: 56rem;">
                        <div class="card-header d-flex align-items-center">
                            <h2>Baza herbat</h2>
                            <div class="search-bar search-bar-tea input-group rounded ms-auto align-items-center">
                                <span class="input-group-text border-0" id="search-addon">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="search" id="search_input" class=" rounded" placeholder="Wyszukaj..." aria-controls="table">
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table" width="100%">
                            <thead>
                                <tr>
                                    <th>Nazwa</th>
                                    <th>Składniki</th>
                                    <th>Gatunek</th>
                                    <th>Czas</th>
                                    <th>Temp</th>
                                    <th>Sklep</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if ($result = @$conn->query('SELECT * FROM herbaty')) 
                                {
                                    $count = $result->num_rows;
                                    for ($i = 0; $i < $count; $i++)
                                    {
                                        $wiersz = $result->fetch_assoc();
                                        echo '<tr>';
                                        echo '<td><a href="tea.php?id='.$wiersz['herbata_id'].'">'.$wiersz['nazwa'].'</a></td>';
                                        echo '<td>'.$wiersz['skladniki'].'</td>';
                                        echo '<td>'.$wiersz['gatunek'].'</td>';
                                        echo '<td>' . $wiersz['czas_parzenia'] . ' min.</td>';
                                        echo '<td>'.$wiersz['temperatura'].' ℃ </td>';
                                        echo '<td>'.$wiersz['sklep'].'</td>';
                                        echo '</tr>';
                                    }
                                }
                            ?>
                            </tbody>
                            </table>
                        </div>
                        
                    </div>

                    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script type="text/javascript" src="datatables/datatables.min.js"></script>
                    

<?php
include_once 'footer.php';
?>