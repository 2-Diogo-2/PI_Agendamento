<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma de Agendamento de Shows</title>
    <link rel="stylesheet" href="templates/css/style.css">
</head>
<body>
    <header>
        <h1>Plataforma de Agendamento de Shows</h1>
        <nav>
            <div class="menu-icon" onclick="toggleMenu()">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <ul id="nav-links">
                <li><a href="index.php">Início</a></li>
                <li><a href="templates/pages/cadastro.html">Cadastrar-se</a></li>
                <li><a href="templates/pages/login.html">Login</a></li>
                <li><a href="templates/pages/pesquisa.html">Pesquisar Bandas e Espaços</a></li>
                <li><a href="templates/pages/cadastro_bandas.html">Cadastrar Banda</a></li>
                <li><a href="templates/pages/cadastro_espacos.html">Cadastrar Espaço</a></li>
                <li><a href="templates/pages/agendamento.html">Agendar Show</a></li>
                <li><a href="templates/pages/contato.html">Contato</a></li>
                <li><a href="templates/pages/sobre.html">Sobre Nós</a></li>
            </ul>
        </nav>
    </header>

    <!-- Conteúdo da página -->

    <script>
        function toggleMenu() {
            const navLinks = document.getElementById("nav-links");
            // Alterna a visibilidade do menu
            navLinks.style.display = navLinks.style.display === "flex" ? "none" : "flex";
        }
    </script>
</body>
</html>
