<!-- Orders Management Content - No HTML structure, content only -->
<div class="container-fluid py-4">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-shopping-cart me-2" style="color: #4daa57;"></i>Gestió de Comandes
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/" style="color: #754668;">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Comandes</li>
                </ol>
            </nav>
        </div>
        <div>
            <button class="btn btn-outline-success me-2" id="exportOrdersBtn">
                <i class="fas fa-file-export me-1"></i> Exportar
            </button>
            <button class="btn btn-outline-primary" id="printReportBtn">
                <i class="fas fa-print me-1"></i> Informe
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #4daa57;">
                                Total Comandes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalOrders ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x" style="color: #4daa57; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Comandes Pendents
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pendingOrders ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #4daa57;">
                                Comandes Completades
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $completedOrders ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x" style="color: #4daa57; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #754668;">
                                Ingressos Totals
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($totalRevenue, 2) ?> €</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x" style="color: #754668; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
            <h6 class="m-0 font-weight-bold" style="color: #754668;">
                <i class="fas fa-list me-2"></i>Llistat de Comandes
            </h6>
            <div>
                <div class="input-group">
                    <input type="text" id="orderSearch" class="form-control" placeholder="Cercar comandes..." style="max-width: 200px;">
                    <select class="form-select" id="statusFilter" style="max-width: 150px;">
                        <option value="all">Tots els estats</option>
                        <option value="pendent">Pendents</option>
                        <option value="completada">Completades</option>
                        <option value="cancel·lada">Cancel·lades</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="ordersTable">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="px-3 py-3">ID</th>
                            <th class="px-3 py-3">Client</th>
                            <th class="px-3 py-3">Data</th>
                            <th class="px-3 py-3">Import</th>
                            <th class="px-3 py-3">Estat</th>
                            <th class="px-3 py-3">Productes</th>
                            <th class="px-3 py-3">Accions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <?php 
                                $client = \App\Models\Client::find($order->client_id);
                                $clientName = $client ? $client->nom : 'Client desconegut';
                                
                                // Determine status class
                                $statusClass = 'bg-success';
                                $statusText = 'Completada';
                                
                                if ($order->estat === 'pendent') {
                                    $statusClass = 'bg-warning text-dark';
                                    $statusText = 'Pendent';
                                } elseif ($order->estat === 'cancel·lada') {
                                    $statusClass = 'bg-danger';
                                    $statusText = 'Cancel·lada';
                                }
                            ?>
                            <tr data-status="<?= $order->estat ?>" class="border-bottom">
                                <td class="px-3 py-3"><?= $order->id ?></td>
                                <td class="px-3 py-3">
                                    <div class="fw-bold"><?= htmlspecialchars($clientName) ?></div>
                                </td>
                                <td class="px-3 py-3"><?= date('d/m/Y', strtotime($order->data_comanda)) ?></td>
                                <td class="px-3 py-3">
                                    <span class="fw-bold"><?= number_format($order->import_total, 2) ?> €</span>
                                </td>
                                <td class="px-3 py-3">
                                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td class="px-3 py-3">
                                    <button class="btn btn-sm btn-outline-info view-products" 
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewProductsModal"
                                            data-order-id="<?= $order->id ?>"
                                            title="Veure productes">
                                        <i class="fas fa-box"></i> <?= $order->productCount ?? 0 ?>
                                    </button>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-success update-status" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#updateStatusModal"
                                                data-order-id="<?= $order->id ?>"
                                                data-order-status="<?= $order->estat ?>"
                                                data-bs-toggle="tooltip"
                                                title="Actualitzar estat">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary view-order" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewOrderModal"
                                                data-order-id="<?= $order->id ?>"
                                                data-bs-toggle="tooltip"
                                                title="Veure detalls">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-order" 
                                                data-order-id="<?= $order->id ?>"
                                                data-client-name="<?= htmlspecialchars($clientName) ?>"
                                                data-bs-toggle="tooltip"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Monthly Sales Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #f8f9fa;">
                    <h6 class="m-0 font-weight-bold" style="color: #754668;">
                        <i class="fas fa-chart-line me-2"></i>Vendes Mensuals
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlySalesChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #f8f9fa;">
                    <h6 class="m-0 font-weight-bold" style="color: #754668;">
                        <i class="fas fa-chart-pie me-2"></i>Distribució d'Estats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="orderStatusChart" style="height: 250px;"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="me-2">
                            <i class="fas fa-circle text-warning"></i> Pendents
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle" style="color: #4daa57;"></i> Completades
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-danger"></i> Cancel·lades
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Order Modal -->
<div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #4daa57; color: white;">
                <h5 class="modal-title" id="viewOrderModalLabel">
                    <i class="fas fa-eye me-2"></i>Detalls de la Comanda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3" id="orderLoadingSpinner">
                    <div class="spinner-border" style="color: #4daa57;" role="status">
                        <span class="visually-hidden">Carregant...</span>
                    </div>
                    <p class="mt-2">Carregant detalls de la comanda...</p>
                </div>
                <div id="orderDetails" style="display: none;">
                    <!-- Order details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tancar</button>
                <button type="button" class="btn btn-success" id="printOrderBtn">
                    <i class="fas fa-print me-1"></i> Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Products Modal -->
<div class="modal fade" id="viewProductsModal" tabindex="-1" aria-labelledby="viewProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #754668; color: white;">
                <h5 class="modal-title" id="viewProductsModalLabel">
                    <i class="fas fa-box me-2"></i>Productes de la Comanda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3" id="productsLoadingSpinner">
                    <div class="spinner-border" style="color: #754668;" role="status">
                        <span class="visually-hidden">Carregant...</span>
                    </div>
                    <p class="mt-2">Carregant productes...</p>
                </div>
                <div id="orderProducts" style="display: none;">
                    <!-- Products will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tancar</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #4daa57; color: white;">
                <h5 class="modal-title" id="updateStatusModalLabel">
                    <i class="fas fa-sync-alt me-2"></i>Actualitzar Estat
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    <input type="hidden" id="statusOrderId" name="orderId">
                    <div class="mb-3">
                        <label for="orderStatus" class="form-label">Nou Estat</label>
                        <select class="form-select" id="orderStatus" name="status">
                            <option value="pendent">Pendent</option>
                            <option value="completada">Completada</option>
                            <option value="cancel·lada">Cancel·lada</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
                <button type="button" class="btn btn-success" id="saveStatusBtn">
                    <i class="fas fa-save me-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Order Modal -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteOrderModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminació
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                </div>
                <p class="text-center">Estàs segur que vols eliminar la comanda del client <strong id="deleteOrderClient"></strong>?</p>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Atenció:</strong> Aquesta acció no es pot desfer.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
                <a href="#" id="confirmDeleteOrderBtn" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i>Eliminar
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // View order details
    const viewOrderModal = document.getElementById('viewOrderModal');
    if (viewOrderModal) {
        viewOrderModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const orderId = button.getAttribute('data-order-id');
            
            document.getElementById('orderLoadingSpinner').style.display = 'block';
            document.getElementById('orderDetails').style.display = 'none';
            
            // Simulate loading order details (would be an AJAX call in production)
            setTimeout(function() {
                document.getElementById('orderLoadingSpinner').style.display = 'none';
                document.getElementById('orderDetails').style.display = 'block';
                
                // Mock data - in production this would come from the server
                document.getElementById('orderDetails').innerHTML = `
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 style="color: #754668;">Informació de la Comanda</h5>
                            <p><strong>ID:</strong> ${orderId}</p>
                            <p><strong>Data:</strong> 15/05/2023</p>
                            <p><strong>Estat:</strong> <span class="badge bg-warning text-dark">Pendent</span></p>
                            <p><strong>Import Total:</strong> 125,75 €</p>
                        </div>
                        <div class="col-md-6">
                            <h5 style="color: #754668;">Informació del Client</h5>
                            <p><strong>Nom:</strong> Joan Garcia</p>
                            <p><strong>Email:</strong> joan.garcia@example.com</p>
                            <p><strong>Telèfon:</strong> 666 777 888</p>
                            <p><strong>Adreça:</strong> Carrer Principal 123, Barcelona</p>
                        </div>
                    </div>
                    <h5 style="color: #754668;">Productes</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>Producte</th>
                                    <th>Preu</th>
                                    <th>Quantitat</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Gerani</td>
                                    <td>12,50 €</td>
                                    <td>3</td>
                                    <td>37,50 €</td>
                                </tr>
                                <tr>
                                    <td>Terra Universal</td>
                                    <td>8,25 €</td>
                                    <td>2</td>
                                    <td>16,50 €</td>
                                </tr>
                                <tr>
                                    <td>Begonia</td>
                                    <td>15,95 €</td>
                                    <td>4</td>
                                    <td>63,80 €</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Subtotal:</th>
                                    <td>117,80 €</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">IVA (21%):</th>
                                    <td>24,74 €</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <td><strong>142,54 €</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
            }, 1000);
        });
    }
    
    // View order products
    const viewProductsModal = document.getElementById('viewProductsModal');
    if (viewProductsModal) {
        viewProductsModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const orderId = button.getAttribute('data-order-id');
            
            document.getElementById('productsLoadingSpinner').style.display = 'block';
            document.getElementById('orderProducts').style.display = 'none';
            
            // Simulate loading products (would be an AJAX call in production)
            setTimeout(function() {
                document.getElementById('productsLoadingSpinner').style.display = 'none';
                document.getElementById('orderProducts').style.display = 'block';
                
                // Mock data - in production this would come from the server
                document.getElementById('orderProducts').innerHTML = `
                    <div class="table-responsive">
                        <table class="table">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>Imatge</th>
                                    <th>Producte</th>
                                    <th>Quantitat</th>
                                    <th>Preu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><img src="${BASE_URL}/images/gerani.jpg" class="img-thumbnail" style="max-width: 50px;"></td>
                                    <td>Gerani</td>
                                    <td>3</td>
                                    <td>12,50 €</td>
                                </tr>
                                <tr>
                                    <td><img src="${BASE_URL}/images/terra_universal.jpg" class="img-thumbnail" style="max-width: 50px;"></td>
                                    <td>Terra Universal</td>
                                    <td>2</td>
                                    <td>8,25 €</td>
                                </tr>
                                <tr>
                                    <td><img src="${BASE_URL}/images/begonia.jpg" class="img-thumbnail" style="max-width: 50px;"></td>
                                    <td>Begonia</td>
                                    <td>4</td>
                                    <td>15,95 €</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `;
            }, 800);
        });
    }
    
    // Update status modal
    const updateStatusModal = document.getElementById('updateStatusModal');
    if (updateStatusModal) {
        updateStatusModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const orderId = button.getAttribute('data-order-id');
            const currentStatus = button.getAttribute('data-order-status');
            
            document.getElementById('statusOrderId').value = orderId;
            document.getElementById('orderStatus').value = currentStatus;
        });
    }
    
    // Save status button
    const saveStatusBtn = document.getElementById('saveStatusBtn');
    if (saveStatusBtn) {
        saveStatusBtn.addEventListener('click', function() {
            const orderId = document.getElementById('statusOrderId').value;
            const newStatus = document.getElementById('orderStatus').value;
            
            // Here you would normally make an AJAX request to update the status
            // For demonstration, we'll just show an alert
            alert(`Estat actualitzat per a la comanda ID ${orderId}: ${newStatus}`);
            
            // Close the modal
            const modal = bootstrap.Modal.getInstance(updateStatusModal);
            modal.hide();
            
            // Reload the page to show updated status
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    }
    
    // Delete order
    const deleteButtons = document.querySelectorAll('.delete-order');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const clientName = this.getAttribute('data-client-name');
            
            document.getElementById('deleteOrderClient').textContent = clientName;
            document.getElementById('confirmDeleteOrderBtn').href = `${BASE_URL}/comandes/destroy.php?id=${orderId}`;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteOrderModal'));
            deleteModal.show();
        });
    });
    
    // Order search functionality
    const orderSearch = document.getElementById('orderSearch');
    if (orderSearch) {
        orderSearch.addEventListener('keyup', function() {
            filterOrders();
        });
    }
    
    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            filterOrders();
        });
    }
    
    function filterOrders() {
        const searchValue = orderSearch.value.toLowerCase();
        const statusValue = statusFilter.value;
        const rows = document.querySelectorAll('#ordersTable tbody tr');
        
        rows.forEach(row => {
            const clientName = row.cells[1].textContent.toLowerCase();
            const orderStatus = row.getAttribute('data-status');
            
            const matchesSearch = clientName.includes(searchValue);
            const matchesStatus = statusValue === 'all' || orderStatus === statusValue;
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Print order button
    const printOrderBtn = document.getElementById('printOrderBtn');
    if (printOrderBtn) {
        printOrderBtn.addEventListener('click', function() {
            const content = document.getElementById('orderDetails').innerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Detalls de la Comanda</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                        <style>
                            body { padding: 20px; }
                            @media print {
                                .no-print { display: none; }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h1>Detalls de la Comanda</h1>
                                <img src="${BASE_URL}/images/logo.png" alt="Logo" style="height: 60px;">
                            </div>
                            ${content}
                            <div class="mt-4 no-print">
                                <button class="btn btn-primary" onclick="window.print()">Imprimir</button>
                                <button class="btn btn-secondary" onclick="window.close()">Tancar</button>
                            </div>
                        </div>
                    </body>
                </html>
            `);
            printWindow.document.close();
        });
    }
    
    // Export orders button
    const exportOrdersBtn = document.getElementById('exportOrdersBtn');
    if (exportOrdersBtn) {
        exportOrdersBtn.addEventListener('click', function() {
            alert('Funcionalitat d\'exportació en desenvolupament');
        });
    }
    
    // Print report button
    const printReportBtn = document.getElementById('printReportBtn');
    if (printReportBtn) {
        printReportBtn.addEventListener('click', function() {
            alert('Funcionalitat d\'informe en desenvolupament');
        });
    }
    
    // Monthly sales chart
    const monthlySalesCtx = document.getElementById('monthlySalesChart');
    if (monthlySalesCtx) {
        new Chart(monthlySalesCtx, {
            type: 'line',
            data: {
                labels: ['Gen', 'Feb', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Des'],
                datasets: [{
                    label: 'Vendes (€)',
                    data: [1200, 1900, 3000, 2500, 2800, 3500, 4000, 3800, 4200, 4500, 5000, 6000],
                    backgroundColor: 'rgba(77, 170, 87, 0.2)',
                    borderColor: 'rgba(77, 170, 87, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(77, 170, 87, 1)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' €';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    // Order status chart
    const orderStatusCtx = document.getElementById('orderStatusChart');
    if (orderStatusCtx) {
        new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pendents', 'Completades', 'Cancel·lades'],
                datasets: [{
                    data: [<?= $pendingOrders ?>, <?= $completedOrders ?>, <?= $cancelledOrders ?>],
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(77, 170, 87, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 193, 7, 1)',
                        'rgba(77, 170, 87, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    }
});
</script>
