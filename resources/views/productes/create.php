<?php
// This view is rendered within the admin layout
// The $title variable is set by the controller

// Use the fully qualified class name for Debug or check if it exists
if (class_exists('\\App\\Core\\Debug')) {
   \App\Core\Debug::log("Loading productes/create.php view");
}

$fields = ['nom', 'descripcio', 'preu', 'estoc', 'categoria', 'imatge', 'detalls'];
$values = escapeArray(formDefaults($fields, $producte ?? null));
$errors = escapeArray(session()->getFlash('errors', []));

// Get valid categories
$categories = \App\Models\Producte::getCategories();

if (class_exists('\\App\\Core\\Debug')) {
   \App\Core\Debug::log("Form fields initialized, errors count: " . count($errors));
   \App\Core\Debug::log("Available categories: " . json_encode($categories));
}
?>

<!-- Page Header with Breadcrumb -->
<div class="row">
   <div class="col-12">
       <div class="d-flex justify-content-between align-items-center mb-4">
           <div>
               <h1 class="h3 mb-0 text-gray-800">
                   <i class="fas fa-plus-circle me-2 text-success"></i>Crear Nou Producte
               </h1>
               <p class="text-muted mb-0">Afegeix un nou producte al catàleg</p>
           </div>
           <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0">
                   <li class="breadcrumb-item">
                       <a href="<?= BASE_URL ?>/admin/" class="text-decoration-none">
                           <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                       </a>
                   </li>
                   <li class="breadcrumb-item">
                       <a href="<?= BASE_URL ?>/admin/products.php" class="text-decoration-none">
                           <i class="fas fa-seedling me-1"></i>Productes
                       </a>
                   </li>
                   <li class="breadcrumb-item active" aria-current="page">Crear</li>
               </ol>
           </nav>
       </div>

       <!-- Main Content Card -->
       <div class="card shadow-sm border-0">
           <div class="card-header bg-white border-bottom">
               <div class="d-flex align-items-center">
                   <div class="avatar bg-success me-3 avatar-sm">
                       <i class="fas fa-seedling text-white"></i>
                   </div>
                   <div>
                       <h5 class="card-title mb-0">Informació del Producte</h5>
                       <small class="text-muted">Completa tots els camps obligatoris</small>
                   </div>
               </div>
           </div>
           
           <div class="card-body">
               <!-- Display errors if they exist -->
               <?php if (!empty($errors)): ?>
                   <div class="alert alert-danger alert-dismissible fade show" role="alert">
                       <i class="fas fa-exclamation-triangle me-2"></i>
                       <strong>Hi ha errors en el formulari:</strong>
                       <ul class="mb-0 mt-2">
                           <?php foreach ($errors as $field => $error): ?>
                               <li><?= htmlspecialchars($error) ?></li>
                           <?php endforeach; ?>
                       </ul>
                       <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                   </div>
               <?php endif; ?>

               <!-- Success message if any -->
               <?php if (session()->hasFlash('success')): ?>
                   <div class="alert alert-success alert-dismissible fade show" role="alert">
                       <i class="fas fa-check-circle me-2"></i>
                       <?= htmlspecialchars(session()->getFlash('success')) ?>
                       <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                   </div>
               <?php endif; ?>

               <!-- Product Creation Form -->
               <form action="<?= BASE_URL ?>/productes/store.php" method="POST" class="needs-validation" novalidate>
                   <!-- CSRF Token -->
                   <input type="hidden" name="csrf_token" value="<?= session()->get('csrf_token') ?>">
                   
                   <div class="row">
                       <!-- Product Name -->
                       <div class="col-md-6 mb-3">
                           <label for="nom" class="form-label">
                               <i class="fas fa-tag me-1"></i>Nom del Producte <span class="text-danger">*</span>
                           </label>
                           <input type="text" 
                                  class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>" 
                                  id="nom" 
                                  name="nom" 
                                  value="<?= $values['nom'] ?? '' ?>" 
                                  required
                                  placeholder="Introdueix el nom del producte">
                           <div class="invalid-feedback">
                               <?= $errors['nom'] ?? 'El nom del producte és obligatori.' ?>
                           </div>
                       </div>

                       <!-- Category -->
                       <div class="col-md-6 mb-3">
                           <label for="categoria" class="form-label">
                               <i class="fas fa-folder me-1"></i>Categoria <span class="text-danger">*</span>
                           </label>
                           <select class="form-select <?= isset($errors['categoria']) ? 'is-invalid' : '' ?>" 
                                   id="categoria" 
                                   name="categoria" 
                                   required>
                               <option value="">Selecciona una categoria</option>
                               <?php foreach ($categories as $category): ?>
                                   <option value="<?= htmlspecialchars($category) ?>" 
                                           <?= ($values['categoria'] ?? '') === $category ? 'selected' : '' ?>>
                                       <?= htmlspecialchars($category) ?>
                                   </option>
                               <?php endforeach; ?>
                           </select>
                           <div class="invalid-feedback">
                               <?= $errors['categoria'] ?? 'La categoria és obligatòria.' ?>
                           </div>
                           <div class="form-text">
                               <i class="fas fa-info-circle me-1"></i>Categories disponibles: <?= implode(', ', $categories) ?>
                           </div>
                       </div>
                   </div>

                   <div class="row">
                       <!-- Price -->
                       <div class="col-md-4 mb-3">
                           <label for="preu" class="form-label">
                               <i class="fas fa-euro-sign me-1"></i>Preu <span class="text-danger">*</span>
                           </label>
                           <div class="input-group">
                               <input type="number" 
                                      class="form-control <?= isset($errors['preu']) ? 'is-invalid' : '' ?>" 
                                      id="preu" 
                                      name="preu" 
                                      value="<?= $values['preu'] ?? '' ?>" 
                                      step="0.01" 
                                      min="0" 
                                      required
                                      placeholder="0.00">
                               <span class="input-group-text">€</span>
                               <div class="invalid-feedback">
                                   <?= $errors['preu'] ?? 'El preu és obligatori.' ?>
                               </div>
                           </div>
                       </div>

                       <!-- Stock -->
                       <div class="col-md-4 mb-3">
                           <label for="estoc" class="form-label">
                               <i class="fas fa-boxes me-1"></i>Estoc <span class="text-danger">*</span>
                           </label>
                           <input type="number" 
                                  class="form-control <?= isset($errors['estoc']) ? 'is-invalid' : '' ?>" 
                                  id="estoc" 
                                  name="estoc" 
                                  value="<?= $values['estoc'] ?? '' ?>" 
                                  min="0" 
                                  required
                                  placeholder="0">
                           <div class="invalid-feedback">
                               <?= $errors['estoc'] ?? 'L\'estoc és obligatori.' ?>
                           </div>
                       </div>

                       <!-- Image -->
                       <div class="col-md-4 mb-3">
                           <label for="imatge" class="form-label">
                               <i class="fas fa-image me-1"></i>Imatge
                           </label>
                           <input type="text" 
                                  class="form-control <?= isset($errors['imatge']) ? 'is-invalid' : '' ?>" 
                                  id="imatge" 
                                  name="imatge" 
                                  value="<?= $values['imatge'] ?? '' ?>"
                                  placeholder="URL de la imatge o nom del fitxer">
                           <div class="form-text">
                               <i class="fas fa-info-circle me-1"></i>Introdueix la URL de la imatge o el nom del fitxer
                           </div>
                           <div class="invalid-feedback">
                               <?= $errors['imatge'] ?? '' ?>
                           </div>
                       </div>
                   </div>

                   <!-- Description -->
                   <div class="mb-3">
                       <label for="descripcio" class="form-label">
                           <i class="fas fa-align-left me-1"></i>Descripció <span class="text-danger">*</span>
                       </label>
                       <textarea class="form-control <?= isset($errors['descripcio']) ? 'is-invalid' : '' ?>" 
                                 id="descripcio" 
                                 name="descripcio" 
                                 rows="3" 
                                 required
                                 placeholder="Descripció breu del producte"><?= $values['descripcio'] ?? '' ?></textarea>
                       <div class="invalid-feedback">
                           <?= $errors['descripcio'] ?? 'La descripció és obligatòria.' ?>
                       </div>
                   </div>

                   <!-- Details -->
                   <div class="mb-4">
                       <label for="detalls" class="form-label">
                           <i class="fas fa-list-ul me-1"></i>Detalls Addicionals
                       </label>
                       <textarea class="form-control <?= isset($errors['detalls']) ? 'is-invalid' : '' ?>" 
                                 id="detalls" 
                                 name="detalls" 
                                 rows="4"
                                 placeholder="Informació detallada sobre el producte, cura, característiques especials, etc."><?= $values['detalls'] ?? '' ?></textarea>
                       <div class="form-text">
                           <i class="fas fa-info-circle me-1"></i>Informació addicional que apareixerà a la pàgina de detall del producte
                       </div>
                       <div class="invalid-feedback">
                           <?= $errors['detalls'] ?? '' ?>
                       </div>
                   </div>

                   <!-- Form Actions -->
                   <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                       <div class="text-muted">
                           <i class="fas fa-info-circle me-1"></i>
                           <small>Els camps marcats amb <span class="text-danger">*</span> són obligatoris</small>
                       </div>
                       <div>
                           <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-outline-secondary me-2">
                               <i class="fas fa-times me-1"></i>Cancel·lar
                           </a>
                           <button type="submit" class="btn btn-success">
                               <i class="fas fa-save me-1"></i>Crear Producte
                           </button>
                       </div>
                   </div>
               </form>
           </div>
       </div>
   </div>
</div>

<!-- Custom JavaScript for form validation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
   console.log('Product creation form loaded');
   
   // Bootstrap form validation
   const forms = document.querySelectorAll('.needs-validation');
   Array.from(forms).forEach(form => {
       form.addEventListener('submit', event => {
           if (!form.checkValidity()) {
               event.preventDefault();
               event.stopPropagation();
           }
           form.classList.add('was-validated');
       }, false);
   });

   // Real-time price formatting
   const preuInput = document.getElementById('preu');
   if (preuInput) {
       preuInput.addEventListener('blur', function() {
           let value = this.value;
           if (value && !isNaN(value)) {
               this.value = parseFloat(value).toFixed(2);
           }
       });
   }

   // Category validation
   const categoriaSelect = document.getElementById('categoria');
   if (categoriaSelect) {
       categoriaSelect.addEventListener('change', function() {
           const validCategories = <?= json_encode($categories) ?>;
           if (this.value && !validCategories.includes(this.value)) {
               this.setCustomValidity('Categoria no vàlida');
           } else {
               this.setCustomValidity('');
           }
       });
   }

   // Image preview functionality
   const imatgeInput = document.getElementById('imatge');
   if (imatgeInput) {
       imatgeInput.addEventListener('blur', function() {
           const url = this.value;
           if (url && (url.startsWith('http') || url.startsWith('/'))) {
               console.log('Image URL provided:', url);
           }
       });
   }
});
</script>

<?php 
// Safe debug logging at the end
if (class_exists('\\App\\Core\\Debug')) {
   \App\Core\Debug::log("productes/create.php view completed");
}
?>
