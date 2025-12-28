@extends('front.layout.app')
@section('main')
    <section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="card border-0 shadow mb-4 p-3">
                    <div class="s-body text-center mt-3">
                        <img
    src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('assets/images/default-avatar.png') }}"
    alt="avatar"
    class="rounded-circle img-fluid"
    style="width: 150px;"
>
                        <h5 class="mt-3 pb-0" >{{$user->name}}</h5>
                        <p class="text-muted mb-1 fs-6" id="user_d">{{ $user->designation ?? 'Add your designation' }}</p>
                        <div class="d-flex justify-content-center mb-2">
                            <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" class="btn btn-primary">Change Profile Picture</button>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-lg-9">
                <div class="card border-0 shadow mb-4">
                    <form action="" id="formUpdateProfile" name="formUpdateProfile">
                    <div class="card-body  p-4">
                        <h3 class="fs-4 mb-1">My Profile</h3>
                        <div class="mb-4">
                            <label for="" class="mb-2">Name*</label>
                            <input name="name" type="text" placeholder="Enter Name" class="form-control" value="{{$user->name}}">
                            <div class="text-danger small error-name"></div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="mb-2">Email*</label>
                            <input name="email" type="text" placeholder="Enter Email" class="form-control" value="{{$user->email}}">
                            <div class="text-danger small error-email"></div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="mb-2">Designation*</label>
                            <input name="designation" type="text" placeholder="Designation" class="form-control" value="{{$user->designation}}">
                            <div class="text-danger small error-designation"></div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="mb-2">Mobile*</label>
                            <input name="mobile" type="text" placeholder="Mobile" class="form-control" value="{{$user->mobile}}">
                            <div class="text-danger small error-mobile"></div>
                        </div>                        
                    </div>
                    <div class="card-footer  p-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>

                    </form>

                </div>

                <div class="card border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h3 class="fs-4 mb-1">Change Password</h3>
                        <div class="mb-4">
                            <label for="" class="mb-2">Old Password*</label>
                            <input type="password" placeholder="Old Password" class="form-control">
                        </div>
                        <div class="mb-4">
                            <label for="" class="mb-2">New Password*</label>
                            <input type="password" placeholder="New Password" class="form-control">
                        </div>
                        <div class="mb-4">
                            <label for="" class="mb-2">Confirm Password*</label>
                            <input type="password" placeholder="Confirm Password" class="form-control">
                        </div>                        
                    </div>
                    <div class="card-footer  p-4">
                        <button type="button" class="btn btn-primary">Update</button>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJS')
<script>
document.getElementById('formUpdateProfile')
.addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    fetch('/account/profile/update', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        const data = await response.json();

        // ❌ validation failed
        if (!response.ok) {
            showFieldErrors(data.errors);
            return;
        }

        // ✅ SUCCESS

        alert(data.message);

        form.name.value        = data.user.name;
        form.email.value       = data.user.email;
        form.designation.value = data.user.designation ?? '';
        form.mobile.value      = data.user.mobile ?? '';
        document.getElementById('user_d').innerText = data.user.designation ?? 'Add your designation';
    })
    .catch(error => console.error(error));
});

function showFieldErrors(errors) {
    for (let field in errors) {
        const errorDiv = document.querySelector('.error-' + field);
        if (errorDiv) {
            errorDiv.innerText = errors[field][0];
        }
    }
}

document.querySelectorAll('#formUpdateProfile input')
.forEach(input => {
    input.addEventListener('input', function () {
        const errorDiv = document.querySelector('.error-' + this.name);
        if (errorDiv) {
            errorDiv.innerText = '';
        }
    });
});


</script>
@endsection