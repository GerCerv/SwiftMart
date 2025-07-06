<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2><i class="bi bi-basket me-2"></i>My Product List</h2>
    <div class="d-flex gap-2">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-circle me-1"></i> Add Product
        </button>
        <div class="btn-group">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-filter me-1"></i> Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">All Categories</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Vegetables</a></li>
                <li><a class="dropdown-item" href="#">Fruits</a></li>
                <li><a class="dropdown-item" href="#">Meat</a></li>
                <li><a class="dropdown-item" href="#">Fish</a></li>
                <li><a class="dropdown-item" href="#">Spices</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Rest of your products tab content (table, cards, etc.) -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle" id="products-table">
                <!-- Your products table content -->
            </table>
        </div>
        
        <!-- Product Cards (for phones) -->
        <div id="products-cards-body">
            @foreach($products as $product)
            <div class="product-card">
                <!-- Your product card content -->
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Pagination Links -->
<div class="d-flex justify-content-center mt-3" id="pagination-container">
    {{ $products->links('pagination::bootstrap-5') }}
</div>