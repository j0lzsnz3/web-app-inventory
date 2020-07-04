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

            <h2>Kategori</h2><br>
            <div class="row">
                <div class="input-group col-md-3">
                    <input class="form-control py-2" type="search" value="Nama kategori" id="example-search-input">
                    <span class="input-group-append">
                        <button class="btn btn-outline-primary" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
                <button class="btn btn-primary" data-toggle="modal" id="btn-add">Tambah Kategori</button>
            </div><br>
            <table class="table table-hover" id="table-category">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">ID</th>
                        <th scope="col">Nama Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once('./util/config.php');

                    $queryAllCategories = getDbConnection()->query("SELECT * FROM tbl_category");
                    $no = 0;

                    // TODO: add logic to handle if there is no data here

                    while ($row = $queryAllCategories->fetch()) {
                        $no++;
                        echo "<tr>
                                <td>" . $no . "</td>
                                <td>" . $row['id'] . "</td>
                                <td>" . $row['name'] . "</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ubah Kategori</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="./controller/save_category.php" method="POST" name="input">
                                <div class="form-group">
                                    <label for="category-id" class="col-form-label" id="category-id-label">ID</label>
                                    <input type="text" class="form-control" id="category-id" name="category_id">
                                    <label for="recipient-name" class="col-form-label">Nama Kategori</label>
                                    <input type="text" class="form-control" id="category-name" name="category_name">
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary" formaction="./controller/save_category.php">Simpan</button>
                                    <button type="submit" id="btn-delete" formaction="./controller/delete_category.php" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
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
        var inputId = document.getElementById("category-id")
        var inputIdLabel = document.getElementById("category-id-label")
        var buttonDelete = document.getElementById("btn-delete")
        var buttonAdd = document.getElementById("btn-add")

        buttonAdd.onclick = function() {
            var modal = $('#exampleModal').modal('show')
            modal.find('.modal-title').text('Tambah Kategori')
            modal.find('.modal-body #category-id').val('')
            modal.find('.modal-body #category-name').val('')

            buttonDelete.style.display = "none"
            inputId.style.display = "none"
            inputIdLabel.style.display = "none"
        }

        function addRowHandlers() {
            var table = document.getElementById("table-category")
            var rows = table.getElementsByTagName("tr")
            for (i = 0; i < rows.length; i++) {
                var currentRow = table.rows[i]
                var createClickHandler =
                    function(row) {
                        return function() {
                            var id = row.getElementsByTagName("td")[1].innerHTML
                            var name = row.getElementsByTagName("td")[2].innerHTML
                            var modal = $('#exampleModal').modal('show')
                            modal.find('.modal-title').text('Ubah Kategori')
                            modal.find('.modal-body #category-id').val(id)
                            modal.find('.modal-body #category-name').val(name)

                            buttonDelete.style.display = "block"
                            inputId.style.display = "block"
                            inputIdLabel.style.display = "block"
                            dropdownCategory.innerText = categoryName
                        };
                    };

                currentRow.onclick = createClickHandler(currentRow)
            }
        }
        window.onload = addRowHandlers()

        $('#dropdown-category').change(function() {
            // if ($(this).val() == "New") {
            //     $("#new_value").show();
            // } else {
            //     $("#new_value").hide();
            // }
            alert($(this).val())
        });
    </script>
</body>

</html>