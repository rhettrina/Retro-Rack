<?php
include 'product_functions.php';

$products = getAllProducts();

$message = '';
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Products</title>
    <!-- Link to existing styles.css -->
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/admin_products.css">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body>

    <!-- Header -->
    <header class="top-nav" id="admin-header">
        <div class="container">
            <div class="welcome">
                <span id="currentDateTime"></span>
            </div>
            
            <script>
                function updateDateTime() {
                    const now = new Date();
                    const options = { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric', 
                        hour: '2-digit', 
                        minute: '2-digit', 
                        second: '2-digit' 
                    };
                    document.getElementById('currentDateTime').textContent = now.toLocaleString('en-US', options);
                }
            
                updateDateTime();
                setInterval(updateDateTime, 1000); // Update every second
            </script>
            
            <ul class="nav-links">
                <li><a href="#"><i class="fas fa-bell"></i></a></li>
                <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </header>


    <nav class="navigation">
        <div class="nav-center container d-flex">
            <a href="admin_dashboard.html" class="logo">Clothing Store Admin</a>
            <ul class="nav-list d-flex">
                <li class="nav-item">
                    <a href="admin_dashboard.html" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="admin_products.php" class="nav-link active">Products</a>
                </li>
                <li class="nav-item">
                    <a href="admin_orders.html" class="nav-link">Orders</a>
                </li>
                <li class="nav-item">
                    <a href="admin_users.php" class="nav-link">Users</a>
                </li>
                <li class="nav-item">
                    <a href="admin_report.html" class="nav-link">Reports</a>
                </li>
                <li class="nav-item">
                    <a href="admin_settings.php" class="nav-link">Settings</a>
                </li>
            </ul>
            <!-- Hamburger Menu for Mobile -->
            <div class="hamburger">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <section class="section main-admin">
        <div class="container">
            <!-- Title and Add Button -->
            <div class="title d-flex">
                <h1>Products</h1>
                <button id="openModalButton" class="btn">Add New Product</button>
            </div>

            <!-- Display message if any -->
            <?php if ($message): ?>
                <div class="alert success">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Products Table -->
            <div class="admin-products">
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($products)): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                                <td>
                                    <div class="d-flex align-center">
                                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Product Image">
                                        <span><?php echo htmlspecialchars($row['name']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                                <td>₱<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td>
    <!-- Actions for edit and delete -->
    <a href="#" class="btn-action edit" 
       data-id="<?php echo $row['id']; ?>" 
       data-name="<?php echo htmlspecialchars($row['name']); ?>" 
       data-price="<?php echo $row['price']; ?>" 
       data-stock="<?php echo $row['stock']; ?>" 
       data-category="<?php echo htmlspecialchars($row['category']); ?>" 
       data-description="<?php echo htmlspecialchars($row['description']); ?>" 
       data-image="<?php echo htmlspecialchars($row['image_path']); ?>">
       <i class="fas fa-edit"></i>
    </a>
    <a href="#" class="btn-action delete" 
       data-id="<?php echo $row['id']; ?>">
       <i class="fas fa-trash"></i>
    </a>
</td>

                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Add New Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Add New Product</h2>
            <form id="addProductForm" method="POST" enctype="multipart/form-data">
                <label for="productName">Product Name</label>
                <input type="text" id="productName" name="productName" placeholder="Enter product name" required>

                <label for="productPrice">Price</label>
                <input type="number" id="productPrice" name="productPrice" placeholder="Enter product price" required>

                <label for="productStock">Stock Quantity</label>
                <input type="number" id="productStock" name="productStock" placeholder="Enter stock quantity" required>

                <label for="productCategory">Category</label>
                <select id="productCategory" name="productCategory" required>
                    <option value="">Select category</option>
                    <option value="T-Shirts">T-Shirts</option>
                    <option value="Jeans">Jeans</option>
                    <option value="Jackets">Jackets</option>
                    <!-- Add more categories as needed -->
                </select>

                <label for="productImage">Product Image</label>
                <input type="file" id="productImage" name="productImage" accept="image/*" required>

                <label for="productDescription">Description</label>
                <textarea id="productDescription" name="productDescription" placeholder="Enter product description"
                    rows="4" required></textarea>

                <button type="submit" class="btn">Add Product</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Add JavaScript here if needed
        // Get modal element
        const modal = document.getElementById('addProductModal');
        // Get open modal button
        const openModalButton = document.getElementById('openModalButton');
        // Get close button
        const closeButton = document.querySelector('.close-button');

        // Listen for open click
        openModalButton.addEventListener('click', openModal);
        // Listen for close click
        closeButton.addEventListener('click', closeModal);
        // Listen for outside click
        window.addEventListener('click', outsideClick);

        // Function to open modal
        function openModal() {
            modal.style.display = 'block';
        }

        // Function to close modal
        function closeModal() {
            modal.style.display = 'none';
        }

        // Function to close modal if outside click
        function outsideClick(e) {
            if (e.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Handle Add Product form submission
const addProductForm = document.getElementById('addProductForm');
addProductForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(addProductForm);

    // Check if editing or adding
    const isEditing = document.getElementById('productId');
    const endpoint = isEditing ? 'edit_product.php' : 'add_product.php';

    fetch(endpoint, {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                closeModal();
                window.location.reload(); // Reload the page to see updates
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the product.');
        });
});



        // Get all edit buttons
const editButtons = document.querySelectorAll('.btn-action.edit');

// Add click event listeners to edit buttons
editButtons.forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        
        // Populate the modal with the product data
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const price = this.getAttribute('data-price');
        const stock = this.getAttribute('data-stock');
        const category = this.getAttribute('data-category');
        const description = this.getAttribute('data-description');

        document.getElementById('productName').value = name;
        document.getElementById('productPrice').value = price;
        document.getElementById('productStock').value = stock;
        document.getElementById('productCategory').value = category;
        document.getElementById('productDescription').value = description;

        // Add a hidden input field for the product ID
        let hiddenIdField = document.getElementById('productId');
        if (!hiddenIdField) {
            hiddenIdField = document.createElement('input');
            hiddenIdField.type = 'hidden';
            hiddenIdField.id = 'productId';
            hiddenIdField.name = 'productId';
            addProductForm.appendChild(hiddenIdField);
        }
        hiddenIdField.value = id;

        // Open the modal
        openModal();
    });
});


// Add event listener for delete buttons
document.querySelectorAll('.btn-action.delete').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();

        const id = this.getAttribute('data-id');
        if (confirm('Are you sure you want to delete this product?')) {
            fetch('delete_product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the product.');
                });
        }
    });
});


    </script>
</body>

</html>