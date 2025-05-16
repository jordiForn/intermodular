<div class="container mt-4">
    <div class="jumbotron bg-light p-5 rounded">
        <h1 class="display-4">Benvinguts a Jardineria</h1>
        <p class="lead">La teva botiga online de jardineria amb els millors productes per al teu jardí.</p>
        <hr class="my-4">
        <p>Descobreix la nostra àmplia selecció de plantes, eines i productes per a la cura del teu jardí.</p>
        <a class="btn btn-primary btn-lg" href="<?= BASE_URL ?>/productes/index.php" role="button">Veure productes</a>
    </div>

    <h2 class="mt-5 mb-4">Productes destacats</h2>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="<?= imageUrl('alfabrega.jpg', 300, 200) ?>" class="card-img-top" alt="Alfabrega" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">Plantes aromàtiques</h5>
                    <p class="card-text">Descobreix la nostra selecció de plantes aromàtiques per a la teva cuina i jardí.</p>
                    <a href="<?= BASE_URL ?>/productes/category.php?categoria=Plantes+i+llavors" class="btn btn-outline-primary">Veure més</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="<?= imageUrl('tomaca.jpg', 300, 200) ?>" class="card-img-top" alt="Tomaca" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">Hortalisses</h5>
                    <p class="card-text">Cultiva les teves pròpies hortalisses ecològiques amb les nostres llavors i plantes.</p>
                    <a href="<?= BASE_URL ?>/productes/category.php?categoria=Plantes+i+llavors" class="btn btn-outline-primary">Veure més</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="<?= imageUrl('terra_universal.jpg', 300, 200) ?>" class="card-img-top" alt="Terra Universal" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">Terra i adobs</h5>
                    <p class="card-text">Tot el que necessites per a nodrir i cuidar les teves plantes de manera natural.</p>
                    <a href="<?= BASE_URL ?>/productes/category.php?categoria=Terra+i+adobs" class="btn btn-outline-primary">Veure més</a>
                </div>
            </div>
        </div>
    </div>
    
    <h2 class="mt-5 mb-4">Categories</h2>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body d-flex">
                    <img src="<?= imageUrl('timó.jpg', 150, 150) ?>" alt="Plantes i llavors" class="me-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <div>
                        <h3 class="card-title">Plantes i llavors</h3>
                        <p class="card-text">Àmplia selecció de plantes aromàtiques, flors, hortalisses i llavors per al teu jardí.</p>
                        <a href="<?= BASE_URL ?>/productes/category.php?categoria=Plantes+i+llavors" class="btn btn-primary">Explorar</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body d-flex">
                    <img src="<?= imageUrl('terra.jpg', 150, 150) ?>" alt="Terra i adobs" class="me-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <div>
                        <h3 class="card-title">Terra i adobs</h3>
                        <p class="card-text">Substrats, adobs i compost de qualitat per a garantir el creixement òptim de les teves plantes.</p>
                        <a href="<?= BASE_URL ?>/productes/category.php?categoria=Terra+i+adobs" class="btn btn-primary">Explorar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-md-6">
            <h3>Sobre nosaltres</h3>
            <p>Som una empresa familiar dedicada a la jardineria des de fa més de 20 anys. El nostre objectiu és oferir productes de qualitat i assessorament personalitzat per a que el teu jardí llueixi el millor possible.</p>
            <p>Tots els nostres productes són seleccionats curosament per garantir la seva qualitat i respecte pel medi ambient.</p>
        </div>
        <div class="col-md-6">
            <h3>Contacta'ns</h3>
            <p><i class="fas fa-map-marker-alt"></i> Carrer Principal, 123, 08001 Barcelona</p>
            <p><i class="fas fa-phone"></i> 93 123 45 67</p>
            <p><i class="fas fa-envelope"></i> info@jardineria.com</p>
            <a href="<?= BASE_URL ?>/contact/show-form.php" class="btn btn-success">Formulari de contacte</a>
        </div>
    </div>
</div>
