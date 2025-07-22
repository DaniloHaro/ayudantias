<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-3 mb-0 fixed-top">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <button id="toggleSidebarFromNavbar" class="btn btn-sm btn-outline-light me-3">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-shield-alt fa-lg text-primary me-2"></i>
            <span class="navbar-brand mb-0 h5">BCA - FACSO </span>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            @if(auth()->user()->perfil === 'Admin')
                <form action="{{ route('simular.usuario') }}" method="POST" class="d-flex align-items-center">
                    @csrf
                    <div class="input-group input-group-sm">
                        {{-- <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-user-secret"></i>
                        </span> --}}
                        <select id="usuario-select" name="usuario_id" class="form-select" onchange="this.form.submit()">
                            <option selected disabled>Simular usuario...</option>
                            @foreach(\App\Models\Usuario::whereHas('permisoActivo', function($query) {
                                $query->where('estado', '=', '1');
                            })->get() as $u)
                                <option value="{{ $u->rut }}">
                                    {{ $u->nombres }} {{ $u->paterno }} {{ $u->materno }}  ({{ $u->rut }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            @endif
            @if(session()->has('usuario_real_id'))
                <form action="{{ route('volver.usuario') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fas fa-user-shield me-1"></i> Volver
                    </button>
                </form>
            @endif
            <span class="text-white small me-2">
                Bienvenido, <strong>{{ auth()->user()->nombres ?? 'Invitado' }}</strong>
            </span>
            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#perfilModal">
                <i class="fas fa-user-alt"></i> Mi Perfil
            </button>
            <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-light">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
        </div>
    </div>
</nav>