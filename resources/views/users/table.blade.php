<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Profile</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Mobile</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($userData as $user)
            <tr>
                <th scope="row">1</th>
                <td><img src="{{ url($user->userDetails->image) }}" alt="" width="80" height="80"></td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->userDetails->mobile }}</td>
                <td>
                    <a href="javascript:void(0)" onclick="editUserModal({{ $user->id }})" title="Edit Data"><i
                            class="fa fa-edit" style="font-size:30px"></i></a>
                    <a href="javascript:void(0)" onclick="deleteUser({{ $user->id }})" class="text-danger"
                        title="Delete Data"><i class="fa fa-remove" style="font-size:30px"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
