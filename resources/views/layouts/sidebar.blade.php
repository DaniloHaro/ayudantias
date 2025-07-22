<aside id="sidebar" class="bg-dark text-white vh-100 p-3 position-fixed sidebar-expanded" style="width: 250px; left: 0; transition: all 0.3s; z-index: 1030;">
    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-primary' : '' }}">
                <i class="fas fa-home"></i> <span class="sidebar-text">Inicio</span>
            </a>
            @if(auth()->user()->perfil === 'Admin' || auth()->user()->perfil === 'Estudiante')
                <a href="{{ route('crear-solicitud') }}" class="nav-link text-white {{ request()->routeIs('crear-solicitud') ? 'active bg-primary' : '' }}">
                    <i class="fa-solid fa-file-pen"></i> <span class="sidebar-text">Solicitud</span>
                </a>
                 <a href="{{ route('mis-solicitudes') }}" class="nav-link text-white {{ request()->routeIs('mis-solicitudes') ? 'active bg-primary' : '' }}">
                    <i class="fa-solid fa-list"></i> <span class="sidebar-text">Mis Solicitudes</span>
                </a>
                 <a href="{{ route('solicitudes-coordinador') }}" class="nav-link text-white {{ request()->routeIs('solicitudes-coordinador') ? 'active bg-primary' : '' }}">
                    <i class="fa-solid fa-bookmark"></i> <span class="sidebar-text">Todas las Solicitudes</span>
                </a>
            @endif
            @if(auth()->user()->perfil === 'Docente')
                <a href="{{ route('solicitudes-docente') }}" class="nav-link text-white {{ request()->routeIs('solicitudes-docente') ? 'active bg-primary' : '' }}">
                    <i class="fa-solid fa-bookmark"></i> <span class="sidebar-text">Solicitudes Pendientes</span>
                </a>
                 <a href="{{ route('aceptadas-rechazadas') }}" class="nav-link text-white {{ request()->routeIs('aceptadas-rechazadas') ? 'active bg-primary' : '' }}">
                    <i class="fa-solid fa-bookmark"></i> <span class="sidebar-text">Solicitudes Aceptadas/Rechazas</span>
                </a>
            @endif
            @if(auth()->user()->perfil === 'Coordinador')
                <a href="{{ route('solicitudes-coordinador') }}" class="nav-link text-white {{ request()->routeIs('solicitudes-coordinador') ? 'active bg-primary' : '' }}">
                    <i class="fa-solid fa-bookmark"></i> <span class="sidebar-text">Solicitudes</span>
                </a>
                 {{-- <a href="{{ route('aceptadas-rechazadas') }}" class="nav-link text-white {{ request()->routeIs('aceptadas-rechazadas') ? 'active bg-primary' : '' }}">
                    <i class="fa-solid fa-bookmark"></i> <span class="sidebar-text">Solicitudes Aceptadas/Rechazas</span>
                </a> --}}
            @endif
        </li>
        @if(auth()->user()->perfil === 'Admin')
            <li class="nav-item">
                <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#submenuMantenedor" role="button" aria-expanded="false" aria-controls="submenuMantenedor">
                    <span><i class="fa-solid fa-gear"></i> <span class="sidebar-text"> Mantenedor</span></span>
                    <i class="fas fa-chevron-down sidebar-text"></i>
                </a>
                <div class="collapse ps-3" id="submenuMantenedor">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('usuarios.index') }}" class="nav-link text-white {{ request()->routeIs('usuarios.*') ? 'active bg-primary' : '' }}">
                                <i class="fa-solid fa-users"></i></i> <span class="sidebar-text"> Usuarios</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('carreras.index') }}" class="nav-link text-white {{ request()->routeIs('carreras.*') ? 'active bg-primary' : '' }}">
                                <i class="fa-solid fa-building-columns"></i> <span class="sidebar-text"> Carreras</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permisos.index') }}" class="nav-link text-white {{ request()->routeIs('permisos.*') ? 'active bg-primary' : '' }}">
                                <i class="fa-solid fa-id-card-clip"></i> <span class="sidebar-text"> Permisos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('asignaturas.index') }}" class="nav-link text-white {{ request()->routeIs('asignaturas.*') ? 'active bg-primary' : '' }}">
                               <i class="fa-solid fa-person-chalkboard"></i> <span class="sidebar-text"> Asignaturas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('procesos.index') }}" class="nav-link text-white {{ request()->routeIs('procesos.*') ? 'active bg-primary' : '' }}">
                               <i class="fa-solid fa-bell"></i> <span class="sidebar-text"> Procesos</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
    </ul>
</aside>
