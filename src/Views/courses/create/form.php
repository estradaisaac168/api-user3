<h1><?= e($title) ?></h1>

<form action="<?= $action ?>" method="post">
  <?= csrf_field() ?>

  <?php if ($course): ?>
    <input type="hidden" name="_method" value="PUT">
  <?php endif ?>

  <input
    type="text"
    name="name"
    value="<?= e($course['name'] ?? old('name')) ?>"
    placeholder="Nombre del curso">

  <!-- <div>
    <label>Nombre: </label>
    <input name="name" value="<?= e($old['name'] ?? '') ?>">
    <?php if (!empty($errors['name'])): ?>
      <small><?= e($errors['name']) ?></small>
    <?php endif ?>
  </div> -->

  <button type="submit">
    <?= $course ? 'Actualizar' : 'Crear' ?>
  </button>
</form>