<h1 class="mb-4">Login</h1>

<form action="<?= htmlspecialchars(BASE_URL) ?>/login" method="POST" class="col-md-4">
  <div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" class="form-control" id="username" name="username" required autofocus>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password" required>
  </div>
  <button type="submit" class="btn btn-primary">Login</button>
</form>

<p class="text-muted small mt-3">
  Kredensial sementara (hardcode, belum dari database): <code>admin</code> / <code>admin123</code>
</p>
