<?php
require_once 'get_data.php';
$kampus = getKampus();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman AJAX API</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>
    <style>
        .select2-container .select2-selection--single {
            height: 38px;
            font-size: 16px;
            width: 100%;
        }

        .select2-container--default .select2-selection--single {
            padding-top: 5px;
            border-color: #CED4DA;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #51585E;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            margin-top: 5px;
        }
    </style>

    <div id="loading" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <img style="width:150px" src="https://global.discourse-cdn.com/sitepoint/original/3X/e/3/e352b26bbfa8b233050087d6cb32667da3ff809c.gif" alt="Loading...">
        </div>
    </div>

    <div class="container mt-5">
        <h1>User</h1>
        <div class="col-md-12 mt-3">
        <button type="button" class="btn btn-primary mb-3" onclick="tambahData()"><i class="fas fa-plus"></i> Tambah Data</button>
            <table id="tblMaster" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Hobi</th>
                        <th>Kampus</th>
                        <th>Active</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_form">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_formLabel"><span id="judulmodal"></span> User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="form_id">
                    <input type="hidden" id="primarykey" name="primarykey">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hobi" class="col-sm-3 col-form-label">Hobi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="hobi" name="hobi" autocomplete="off" required>
                            </div>
                        </div>
                        <?php if(count($kampus) > 0) {?>
                            <div class="form-group row">
                                <label for="kampus" class="col-sm-3 col-form-label">Kampus</label>
                                <div class="col-sm-9">
                                    <select name="kampus" id="kampus" class="form-control select2" style="width:100%" required>
                                        <option value=""></option>
                                        <?php
                                        foreach ($kampus as $k) {
                                        ?>
                                            <option value="<?= $k['id'] ?>"><?= $k['nama_kampus'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group row">
                            <label for="active" class="col-sm-3 col-form-label">Active</label>
                            <div class="col-sm-9">
                                <select name="active" id="active" class="form-control select2" style="width:100%" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select"
            });

            $('#tblMaster').DataTable({
                "ajax": {
                    "url": "get_users.php",
                    "type": "GET",
                    "dataSrc": ""
                },
                "columns": [
                    { "data": "nama", "class":"dt-center" },
                    { "data": "hobi", "class":"dt-center" },
                    { "data": "nama_kampus", "class":"dt-center" },
                    { "data": "ketaktif", "class":"dt-center" },
                    { "data": "btn", "class":"dt-center" },
                ],
                "error": function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                }
            });

            document.getElementById("form_id").addEventListener("submit", (e) => {
                e.preventDefault()
                validate()
            })
        });

        function tambahData() {
            $("#judulmodal").text("Tambah");
            $('#primarykey').val('');
            $('#nama').val('');
            $('#hobi').val('');
            $('#kampus').val('').change();
            $('#active').val('1').change();
            $('#modal_form').modal('show');
        }

        function edit(d) {
            // console.log(d)
            $("#judulmodal").text("Edit");
            $('#primarykey').val(d.id);
            $('#nama').val(d.nama);
            $('#hobi').val(d.hobi);
            $('#kampus').val(d.id_kampus).change();
            $('#active').val(d.active).change();
            $('#modal_form').modal('show');
        }

        function validate() {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin mengirimkan formulir?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    let form = $('#form_id')[0];
                    let formData = new FormData(form);
                    $("#loading").show();
                    $.ajax({
                        type: "POST",
                        url: "store.php",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $("#loading").hide();
                            Swal.fire(response.head, response.msg, response.status);
                            $('#modal_form').modal('hide');
                            $('#tblMaster').DataTable().ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            $("#loading").hide();
                            Swal.fire('Error', 'Terjadi kesalahan saat mengirim data: ' + error, 'error');
                        }
                    });
                }
            });
        }


    </script>
</body>
</html>
