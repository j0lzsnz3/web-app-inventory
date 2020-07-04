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
                    <input class="form-control py-2" type="search" onkeyup="searchItem()" placeholder="Masukkan nama item" id="search-input">
                    <span class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" onclick="searchItem()">
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
                        <th scope="col">ID</th>
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

                    if (!empty($queryAllItems)) {
                        while ($row  = $queryAllItems->fetch()) {
                            $no++;
                            $queryCategory = getDbConnection()->prepare("SELECT name FROM tbl_category WHERE id = :category_id");
                            $queryCategory->execute([
                                'category_id' => $row['category_id']
                            ]);
                            $categoryName = $queryCategory->fetchColumn();
                            echo "<tr>
                                    <td>" . $no . "</td>
                                    <td>" . $row['id'] . "</td>
                                    <td>" . $row['name'] . "</td>
                                    <td>" . $categoryName . "</td>
                                    <td>" . $row['price'] . "</td>
                                    <td>" . $row['in_stock'] . "</td>
                                </tr>";
                        }
                    } else {
                        echo "Data kosong";
                    }
                    ?>
                </tbody>
            </table>

            <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="itemModalLabel">Ubah Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="./controller/save_item.php" method="POST" name="input">
                                <div class="form-group">
                                    <label for="item-id" class="col-form-label" id="item-id-label">ID</label>
                                    <input type="text" class="form-control" id="item-id" name="item_id">
                                    <label for="recipient-name" class="col-form-label">Nama Item</label>
                                    <input type="text" class="form-control" id="item-name" name="item_name">
                                    <label class="col-form-label">Kategori</label>
                                    <div class="dropdown show" id="dropdown-category">
                                        <select class="custom-select" id="combo-category" name="selected_category">
                                            <?php
                                            require_once('./util/config.php');
                                            $queryCategories = getDbConnection()->query("SELECT * FROM tbl_category");
                                            $data = $queryCategories->fetchAll();
                                            foreach ($data as $row) :
                                            ?>
                                                <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <label for="message-text" class="col-form-label">Harga</label>
                                    <input type="number" min="1" step="any" class="form-control" id="item-price" name="item_price">
                                    <label for="message-text" class="col-form-label">Stok</label>
                                    <input type="number" min="1" step="any" class="form-control" id="item-stock" name="item_stock">
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary" formaction="./controller/save_item.php">Simpan</button>
                                    <button type="submit" id="btn-delete" formaction="./controller/delete_item.php" class="btn btn-danger">Hapus</button>
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
        var inputId = document.getElementById("item-id")
        var inputIdLabel = document.getElementById("item-id-label")
        var buttonDelete = document.getElementById("btn-delete")
        var buttonAdd = document.getElementById("btn-add")
        var comboCategory = document.getElementById("combo-category")

        buttonAdd.onclick = function() {
            var modal = $('#itemModal').modal('show')
            modal.find('.modal-title').text('Tambah Item')
            modal.find('.modal-body #item-id').val('')
            modal.find('.modal-body #item-name').val('')
            modal.find('.modal-body #item-price').val('')
            modal.find('.modal-body #item-stock').val('')



            buttonDelete.style.display = "none"
            inputId.style.display = "none"
            inputIdLabel.style.display = "none"
        }

        function addRowHandlers() {
            var table = document.getElementById("table-item")
            var rows = table.getElementsByTagName("tr")
            for (i = 0; i < rows.length; i++) {
                var currentRow = table.rows[i]
                var createClickHandler =
                    function(row) {
                        return function() {
                            var id = row.getElementsByTagName("td")[1].innerHTML
                            var name = row.getElementsByTagName("td")[2].innerHTML
                            var categoryName = row.getElementsByTagName("td")[3].innerHTML
                            var price = row.getElementsByTagName("td")[4].innerHTML
                            var stock = row.getElementsByTagName("td")[5].innerHTML
                            var modal = $('#itemModal').modal('show')
                            modal.find('.modal-title').text('Ubah Item')
                            modal.find('.modal-body #item-id').val(id)
                            modal.find('.modal-body #item-name').val(name)
                            modal.find('.modal-body #item-price').val(price)
                            modal.find('.modal-body #item-stock').val(stock)

                            buttonDelete.style.display = "block"
                            inputId.style.display = "block"
                            inputId.readOnly = true
                            inputIdLabel.style.display = "block"
                            dropdownCategory.innerText = categoryName
                        };
                    };

                currentRow.onclick = createClickHandler(currentRow)
            }
        }
        window.onload = addRowHandlers()

        function searchItem() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("search-input")
            filter = input.value.toUpperCase()
            table = document.getElementById("table-item")
            tr = table.getElementsByTagName("tr")
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[2]
                if (td) {
                    txtValue = td.textContent || td.innerText
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = ""
                    } else {
                        tr[i].style.display = "none"
                    }
                }
            }
        }

        function setSelectedCategory(categoryId, categoryName) {
            let element = document.getElementById("#combo-category");
            element.value = categoryId
            element.innerHTML = categoryName
        }
    </script>
</body>

</html>