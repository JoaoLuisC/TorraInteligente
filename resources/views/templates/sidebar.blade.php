<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    <!--Titulo SideBar-->
    <div class="sidebar-brand">
        <a href="../index.html" class="brand-link">
            <img src="../images/michelangeloTXT.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
        </a>
    </div>

    <!--Conteudo SideBar-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <!--TORRADOR-->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p> Torradores <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview ms-3">

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <img src="{{ asset('images/icons/torrador_de_cafe.png') }}" alt="Torrador de Café"
                                    class="nav-icon" style="width: 1.25em; height: 1.25em; object-fit: contain;">
                                <p>Torradores</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('torradores.adicionar-sensor') }}" class="nav-link">
                                <i class="nav-icon bi bi-plus-circle"></i>
                                <p>Adicionar Sensor</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-gear"></i>
                                <p>Configurar</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <!--Torras-->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p> Torras <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview ms-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <img src="{{ asset('images/icons/torrador_de_cafe.png') }}" alt="Torrador de Café"
                                    class="nav-icon" style="width: 1.25em; height: 1.25em; object-fit: contain;">
                                <p>Torras</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-plus-circle"></i>
                                <p>Iniciar Torra</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-gear"></i>
                                <p>Solicitar Prova</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!--Usuario-->
                <li class="nav-item">
                    <a href="../generate/theme.html" class="nav-link">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>Perfil</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>

</aside>
