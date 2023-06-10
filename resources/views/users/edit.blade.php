<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="editFormData" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="userId" value="{{ $userData->id }}">
                <div class="form-group">
                    <label for="email">Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ $userData->name }}" placeholder="Enter name">
                    <span id="nameErr" style="color:red"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ $userData->email }}" placeholder="Enter email">
                    <span id="emailErr" style="color:red"></span>
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <input type="text" class="form-control" id="mobile" name="mobile"
                        value="{{ $userData->userDetails->mobile }}" placeholder="Mobile">
                    <span id="mobileErr" style="color:red"></span>
                </div>

                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <span id="imageErr" style="color:red"></span>

                    @if ($userData->userDetails->image)
                        <img src="{{ url($userData->userDetails->image) }}" alt="user_image" width="100"
                            height="100">
                    @endif
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" placeholder="Address...">{{ $userData->userDetails->address }}</textarea>
                </div>
                <span id="serverErr" style="color:red"></span>
                <button type="button" onclick="editFormSubmit()" class="btn btn-primary float-right">Edit
                    User</button>
            </form>
        </div>
    </div>
</div>
