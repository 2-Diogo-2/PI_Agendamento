<?php include 'php/includes/header.php'; ?>

<main>
  <section class="login-section">
    <div class="login-container">
      <h2>Login</h2>
      <form action="php/actions/login.php" method="POST">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Senha</label>
          <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-primary">Entrar</button>
        <div class="form-links">
          <a href="forgot-password.php" class="btn-secondary">Esqueci minha senha</a>
          <a href="register.php" class="btn-secondary">Criar Conta</a>
        </div>
      </form>
    </div>
  </section>
</main>

<?php include 'php/includes/footer.php'; ?>
