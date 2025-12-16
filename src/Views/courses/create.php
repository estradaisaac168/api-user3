<h2>Desde vista form create course</h2>

<?php if ($success): ?>
  <p class="success"><?= e($success) ?></p>
<?php endif ?>

<form method="POST" action="/users">
  <?= csrf_field() ?>

  <div>
    <label>Nombre: </label>
    <input name="name" value="<?= e($old['name'] ?? '') ?>">
    <?php if (!empty($errors['name'])): ?>
      <small><?= e($errors['name']) ?></small>
    <?php endif ?>
  </div>

  <button type="submit">Guardar</button>
</form>
