<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/sidenav.css">
    <link rel="stylesheet" href="css/util.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->

        <!-- Page Content  -->
        <div id="content">

            <h2>Item Library</h2><br>
            <div class="row">
                <div class="input-group col-md-3">
                    <input class="form-control py-2" type="search" value="Nama item" id="example-search-input">
                    <span class="input-group-append">
                        <button class="btn btn-outline-primary" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
                <button class="btn btn-primary" data-toggle="modal" id="btn-add">Tambah Item</button>
            </div><br>
            <table class="table table-hover" id="table-item">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once('./util/config.php');

                    $queryAllItems = getDbConnection()->query("SELECT * FROM tbl_item");
                    $no = 0;
                    while ($row = $queryAllItems->fetch()) {
                        $no++;
                        $queryCategory = getDbConnection()->prepare("SELECT name FROM tbl_category WHERE id = :category_id");
                        $queryCategory->execute([
                            'category_id' => $row['category_id']
                        ]);
                        $categoryName = $queryCategory->fetchColumn();
                        echo "<tr>
                                <td>" . $no . "</td>
                                <td>" . $row['name'] . "</td>
                                <td>" . $categoryName . "</td>
                                <td>" . $row['price'] . "</td>
                                <td>" . $row['in_stock'] . "</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ubah Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Nama Item</label>
                                    <input type="text" class="form-control" id="item-name">
                                    <label for="message-text" class="col-form-label">Harga</label>
                                    <input type="number" min="1" step="any" class="form-control" id="item-price">
                                    <label for="message-text" class="col-form-label">Stok</label>
                                    <input type="number" min="1" step="any" class="form-control" id="item-stock">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary">Simpan</button>
                            <button type="button" id="btn-delete" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">
        var buttonDelete = document.getElementById("btn-delete")
        var buttonAdd = document.getElementById("btn-add")
        
        buttonAdd.onclick = function() {
            var modal = $('#exampleModal').modal('show')
            modal.find('.modal-title').text('Tambah Item')
            modal.find('.modal-body #item-name').val('')
            modal.find('.modal-body #item-price').val(0)
            modal.find('.modal-body #item-stock').val(0)

            buttonDelete.style.display = "none"
        }

        function addRowHandlers() {
            var table = document.getElementById("table-item")
            var rows = table.getElementsByTagName("tr")
            for (i = 0; i < rows.length; i++) {
                var currentRow = table.rows[i]
                var createClickHandler =
                    function(row) {
                        return function() {
                            var name = row.getElementsByTagName("td")[1].innerHTML
                            var categoryName = row.getElementsByTagName("td")[2].innerHTML
                            var price = row.getElementsByTagName("td")[3].innerHTML
                            var stock = row.getElementsByTagName("td")[4].innerHTML
                            var modal = $('#exampleModal').modal('show')
                            modal.find('.modal-title').text('Ubah Item')
                            modal.find('.modal-body #item-name').val(name)
                            modal.find('.modal-body #item-price').val(price)
                            modal.find('.modal-body #item-stock').val(stock)

                            buttonDelete.style.display = "block"
                        };
                    };

                currentRow.onclick = createClickHandler(currentRow)
            }
        }
        window.onload = addRowHandlers()
    </script>
</body>

</html>