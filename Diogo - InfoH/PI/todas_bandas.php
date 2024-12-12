<?php
session_start();
require_once 'php/includes/db_connect.php';

$db = new Database();
$stmt = $db->prepare("SELECT * FROM bandas");
$stmt->execute();
$result = $stmt->get_result();
$bandas = $result->fetch_all(MYSQLI_ASSOC);
?>

<?php include 'php/includes/header.php'; ?>

<main>




    <section class="form-container">
        
        <h2>Todas as Bandas</h2>
        <form method="GET" action="">
    <input type="text" name="search" placeholder="Pesquisar...">
    <select name="filter">
        <option value="nome">Por Nome</option>
        <option value="espaco">Por Espaço</option>
        <option value="banda">Por Banda</option>
        <option value="cidade">Por Cidade</option>
    </select>
    <button type="submit">Pesquisar</button>
</form>
        <table>
            <thead>
                <tr>
                    <th>Nome da Banda</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bandas as $banda): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($banda['nome_banda']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include 'php/includes/footer.php'; ?>