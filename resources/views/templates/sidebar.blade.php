<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    <!--Titulo SideBar-->
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link" style="color: white; text-decoration: none;">
            <!-- <img src="../images/michelangeloTXT.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" /> -->
            <h2 style="color: white;">MICHELANGELO</h2>
        </a>
    </div>

    <!--Conteudo SideBar-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                @if(Auth::user()->tipo === 'Produtor')
                    <!--DASHBOARD-->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link ajax-link">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!--TORRADOR-->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-fire"></i>
                            <p> Torradores <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview ms-3">
                            <li class="nav-item">
                                <a href="{{ route('torradores.index') }}" class="nav-link ajax-link">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>Torradores</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('torradores.adicionar-sensor') }}" class="nav-link ajax-link">
                                    <i class="nav-icon fas fa-plus-circle"></i>
                                    <p>Adicionar Sensor</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link ajax-link">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>Configurar</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!--Torras-->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-coffee"></i>
                            <p> Torras <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview ms-3">
                            <li class="nav-item">
                                <a href="{{ route('torras.index') }}" class="nav-link ajax-link">
                                    <i class="nav-icon fas fa-list-ul"></i>
                                    <p>Minhas Torras</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('torras.iniciar') }}" class="nav-link ajax-link">
                                    <i class="nav-icon fas fa-play-circle"></i>
                                    <p>Configurar Torra</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('torras.monitoramento') }}" class="nav-link">
                                    <i class="nav-icon fas fa-chart-line"></i>
                                    <p>Monitoramento</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('torras.solicitar-avaliacao') }}" class="nav-link">
                                    <i class="nav-icon fas fa-file-alt"></i>
                                    <p>Solicitar Prova</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->tipo === 'Analista')
                    <!--DASHBOARD-->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link ajax-link">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!--Análises-->
                    <li class="nav-item">
                        <a href="{{ route('analise.pendentes') }}" class="nav-link ajax-link">
                            <i class="nav-icon bi bi-clipboard-check"></i>
                            <p>Análises Pendentes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('analise.historico') }}" class="nav-link ajax-link">
                            <i class="nav-icon bi bi-clock-history"></i>
                            <p>Histórico de Análises</p>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->tipo === 'Administrador')
                    <!--Dashboard Admin-->
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link ajax-link">
                            <i class="nav-icon bi bi-graph-up"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <!--Gerenciar Usuários-->
                    <li class="nav-item">
                        <a href="{{ route('admin.usuarios') }}" class="nav-link ajax-link">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Gerenciar Usuários</p>
                        </a>
                    </li>
                @endif

                <!--Usuario (Comum para todos)-->
                <li class="nav-item">
                    <a href="{{ route('perfil') }}" class="nav-link ajax-link">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>Perfil</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>

</aside>
