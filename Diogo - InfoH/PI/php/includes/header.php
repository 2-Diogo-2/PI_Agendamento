<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma de Agendamento de Shows</title>
    <link rel="stylesheet" href="templates/css/style.css">
</head>

<body>

<header>
    <div class="navbar">
        <div class="logo">
            <a href="index.php" class="logo-title">Agendamento de Shows</a>
        </div>
        <div class="menu-icon" onclick="toggleMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <nav>
            <ul id="nav-links">
                <?php if (isset($_SESSION['id'])): // Verifica se o usuário está logado ?>
                    <li><a href="cadastro.php">Cadastrar</a></li>
                    <li><a href="todas_bandas.php">Bandas</a></li>
                    <li><a href="todos_espacos.php">Espaços</a></li>
                    <li><a href="shows_agendados.php">Shows</a></li>

                    <?php if ($_SESSION['tipo_usuario'] === 'gerente_banda' || $_SESSION['tipo_usuario'] === 'administrador'): ?>
                        <li><a href="minhas_bandas.php">Minhas Bandas</a></li>
                    <?php endif; ?>

                    <?php if ($_SESSION['tipo_usuario'] === 'espaco_apresentacao' || $_SESSION['tipo_usuario'] === 'administrador'): ?>
                        <li><a href="meus_espacos.php">Meus Espaços</a></li>
                    <?php endif; ?>

                    <li>
                        <form method="GET" action="pesquisa.php" style="display:inline;">
                            <input type="text" name="search" placeholder="Pesquisar..." required>
                            <button type="submit" title="Pesquisar"><i class="fa fa-search"></i></button>
                        </form>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-btn"><?php echo $_SESSION['nome']; ?></a>
                        <div class="dropdown-content">
                            <a href="perfil.php">Meu Perfil</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </li>

                    <?php if ($_SESSION['tipo_usuario'] === 'gerente_banda' || $_SESSION['tipo_usuario'] === 'administrador'): ?>
                        <li><a href="cadastro_bandas.php">Cadastrar Banda</a></li>
                    <?php endif; ?>
                    
                    <?php if ($_SESSION['tipo_usuario'] === 'espaco_apresentacao' || $_SESSION['tipo_usuario'] === 'administrador'): ?>
                        <li><a href="cadastro_espacos.php">Cadastrar Espaço</a></li>
                    <?php endif; ?>
                    
                    <li><a href="agendamento.php">Agendar</a></li>
                    <li><a href="gerenciar_solicitacoes.php">Gerenciar Solicitações</a></li>
                    <li><a href="sobre.php">Sobre</a></li>
                <?php else: // Se o usuário não estiver logado ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<script>
    function toggleMenu() {
        const menu = document.getElementById('nav-links');
        menu.classList.toggle('show'); // Adiciona ou remove a classe 'show' dinamicamente
    }
</script>

</body>

</html>