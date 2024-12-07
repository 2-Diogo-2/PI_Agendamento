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
                <a href="index.php" class="logo-title">Plataforma de Agendamento de Shows</a>
            </div>
            <div class="menu-icon" onclick="toggleMenu()">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <nav>
                <ul id="nav-links">
                    <li><a href="index.php">Início</a></li>
                    <li><a href="cadastro.php">Cadastrar</a></li>
                    <?php if (!isset($_SESSION['id'])): ?>
                        <li><a href="login.php">Login</a></li>
                    <?php else: ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-btn"><?php echo $_SESSION['nome']; ?></a>
                            <div class="dropdown-content">
                                <a href="perfil.php">Meu Perfil</a>
                                <a href="logout.php">Logout</a>
                            </div>
                        </li>
                    <?php endif; ?>
                    <li><a href="pesquisa.php">Pesquisar</a></li>
                    <li><a href="cadastro_bandas.php">Cadastrar Banda</a></li>
                    <li><a href="cadastro_espacos.php">Cadastrar Espaço</a></li>
                    <li><a href="agendamento.php">Agendar</a></li>
                    <li><a href="contato.php">Contato</a></li>
                    <li><a href="sobre.php">Sobre Nós</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <script>
        function toggleMenu() {
            const navLinks = document.getElementById("nav-links");
            navLinks.style.display = navLinks.style.display === "flex" ? "none" : "flex";
        }
    </script>
</body>

</html>