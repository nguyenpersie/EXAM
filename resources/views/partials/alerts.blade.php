{{-- Modern Alert Component --}}
@if(session('success'))
    <div class="alert alert-simple alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="bi bi-x greencross"></i></span>
        </button>
        <i class="start-icon bi bi-check-circle-fill faa-tada animated"></i>
        <strong>Thành công!</strong> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-simple alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="bi bi-x danger"></i></span>
        </button>
        <i class="start-icon bi bi-x-circle-fill faa-pulse animated"></i>
        <strong>Lỗi!</strong> {{ session('error') }}
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-simple alert-warning alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="bi bi-x warning"></i></span>
        </button>
        <i class="start-icon bi bi-exclamation-triangle-fill faa-flash animated"></i>
        <strong>Cảnh báo!</strong> {{ session('warning') }}
    </div>
@endif

@if(session('info'))
    <div class="alert alert-simple alert-info alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="bi bi-x blue-cross"></i></span>
        </button>
        <i class="start-icon bi bi-info-circle-fill faa-shake animated"></i>
        <strong>Thông tin!</strong> {{ session('info') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-simple alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="bi bi-x danger"></i></span>
        </button>
        <i class="start-icon bi bi-x-circle-fill faa-pulse animated"></i>
        <strong>Có lỗi xảy ra!</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif