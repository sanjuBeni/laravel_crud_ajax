@extends('layout.default')

@section('title', 'Users List --')

@section('content')

    <div class="row">

        <div class="col-md-4">
            <h2>User List</h2>
        </div>

        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
        <div class="col-md-2">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#userModal">Add
                User</button>
        </div>

    </div>

    <div class="mt-4" id="userTbl">

    </div>

    {{-- Add User Modal  --}}
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="userForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="email">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter name">
                            <span id="nameErr" style="color:red"></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter email">
                            <span id="emailErr" style="color:red"></span>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password">
                            <span id="passwordErr" style="color:red"></span>
                        </div>

                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile">
                            <span id="mobileErr" style="color:red"></span>
                        </div>

                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <span id="imageErr" style="color:red"></span>
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" placeholder="Address..."></textarea>
                        </div>
                        <span id="serverErr" style="color:red"></span>
                        <button type="submit" id="btnSave" class="btn btn-primary float-right">Save
                            User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- End User Modal --}}

    {{-- Edit User modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
        aria-hidden="true">
    </div>
    {{-- End Edit User Modal --}}

    <script>
        $(document).ready(function() {
            loadUserTbl();

            $("#userForm").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ url('/user_save') }}",
                    type: "POST",
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $(".btnSave").attr("disabled", "disabled");
                        $('#userForm').css("opacity", ".5");
                    },
                    success: function(res) {
                        if (res.status == true) {
                            // $.notify("User data stored successfully.", "success");
                            $("#btnSave").removeAttr("disabled", "disabled");
                            $('#userForm').css("opacity", "");
                            $('#userModal').modal('hide');
                            $("#serverErr").html('');
                            loadUserTbl();
                            $("#userForm")[0].reset();
                        } else {
                            // console.log(res.errors.email[0]);
                            // $.notify("Some Error occure!", "error");
                            if (res.serverError) {
                                $("#serverErr").html('Internal server error...');
                            } else {
                                backendErrorShow(res.errors);
                                $("#serverErr").html('');
                            }
                            $("#btnSave").removeAttr("disabled", "disabled");
                            $('#userForm').css("opacity", "");
                        }
                    }
                });
            });

        });

        function backendErrorShow(errObj) {
            if (errObj.name)
                $('#nameErr').html(errObj.name[0]);
            else
                $('#nameErr').html('');
            if (errObj.email)
                $('#emailErr').html(errObj.email[0]);
            else
                $('#emailErr').html('');
            if (errObj.password)
                $('#passwordErr').html(errObj.password[0]);
            else
                $('#passwordErr').html('');
            if (errObj.mobile)
                $('#mobileErr').html(errObj.mobile[0]);
            else
                $('#mobileErr').html('');
            if (errObj.image)
                $('#imageErr').html(errObj.image[0]);
            else
                $('#imageErr').html('');
        }

        function loadUserTbl() {
            $.ajax({
                url: "{{ url('/user_tbl') }}",
                method: "GET",
                beforeSend: function() {
                    $.LoadingOverlay("show");
                },
                success: function(res) {
                    if (res.status)
                        $("#userTbl").html(res.userTbl);
                    $.LoadingOverlay("hide");
                },
                error: function(res) {
                    $.LoadingOverlay("hide");
                }
            });
        }

        function editUserModal(userId) {
            // console.log(userId);
            $.ajax({
                url: "{{ url('/edit_user') }}",
                method: "GET",
                data: {
                    userId
                },
                // beforeSend: function() {
                //     $.LoadingOverlay("show");
                // },
                success: function(res) {
                    if (res.status) {
                        $("#editModal").html(res.userEditModal);
                        $("#editModal").modal("show");
                        // $.LoadingOverlay("hide");
                    }
                }
            });
        }

        function editFormSubmit() {

            let form = document.querySelector("#editFormData");

            $.ajax({
                url: "{{ url('update_user') }}",
                method: "POST",
                data: new FormData(form),
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {

                }
            });
        }

        function deleteUser(userId) {
            if (confirm("Are you sure want to delete this user.")) {
                $.ajax({
                    url: "{{ url('delete_user') }}",
                    method: "GET",
                    data: {
                        userId
                    },
                    success: function(res) {
                        if (res.status) {
                            console.log(res.message);
                            loadUserTbl();
                        }
                    }
                });
            }
        }
    </script>
@endsection
