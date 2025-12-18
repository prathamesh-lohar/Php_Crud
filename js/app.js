let chart = null;
const API_URL = 'api/products.php';

document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
    setupEventListeners();
    initializeChart();
});

function setupEventListeners() {
    document.getElementById('productForm').addEventListener('submit', handleFormSubmit);
    document.getElementById('resetBtn').addEventListener('click', resetForm);
}

function loadProducts() {
    fetch(`${API_URL}?action=read`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayProducts(data.data);
                updateStatistics(data.data);
                updateChart();
            } else {
                showToast('Error loading products', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error loading products', 'danger');
        });
}

function displayProducts(products) {
    const tableBody = document.getElementById('tableBody');
    
    if (products.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No products found</td></tr>';
        return;
    }
    
    tableBody.innerHTML = products.map(product => `
        <tr>
            <td>${product.id}</td>
            <td>${escapeHtml(product.name)}</td>
            <td>₹${parseFloat(product.price).toFixed(2)}</td>
            <td><span class="badge bg-primary">${escapeHtml(product.category)}</span></td>
            <td>${escapeHtml(product.description.substring(0, 30))}...</td>
            <td>
                <button class="btn btn-sm btn-warning" onclick="editProduct(${product.id})">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})">Delete</button>
            </td>
        </tr>
    `).join('');
}

function updateStatistics(products) {
    const totalProducts = products.length;
    const totalValue = products.reduce((sum, product) => sum + parseFloat(product.price), 0);
    
    document.getElementById('totalProducts').textContent = totalProducts;
    document.getElementById('totalValue').textContent = '₹' + totalValue.toFixed(2);
}

function handleFormSubmit(e) {
    e.preventDefault();
    
    const productId = document.getElementById('productId').value;
    const name = document.getElementById('productName').value;
    const price = document.getElementById('productPrice').value;
    const category = document.getElementById('productCategory').value;
    const description = document.getElementById('productDescription').value;
    
    const data = {
        name: name,
        price: price,
        category: category,
        description: description
    };
    
    const action = productId ? 'update' : 'create';
    if (productId) {
        data.id = productId;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Processing...';
    
    fetch(`${API_URL}?action=${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            resetForm();
            loadProducts();
        } else {
            showToast(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error processing request', 'danger');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
}

function editProduct(id) {
    fetch(`${API_URL}?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const product = data.data;
                document.getElementById('productId').value = product.id;
                document.getElementById('productName').value = product.name;
                document.getElementById('productPrice').value = product.price;
                document.getElementById('productCategory').value = product.category;
                document.getElementById('productDescription').value = product.description;
                
                document.getElementById('submitBtn').textContent = 'Update Product';
                
                document.getElementById('productForm').scrollIntoView({ behavior: 'smooth' });
            } else {
                showToast('Error loading product', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error loading product', 'danger');
        });
}

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch(`${API_URL}?action=delete&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    loadProducts();
                } else {
                    showToast(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error deleting product', 'danger');
            });
    }
}

function resetForm() {
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('submitBtn').textContent = 'Add Product';
}

function initializeChart() {
    const ctx = document.getElementById('categoryChart').getContext('2d');
    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Number of Products',
                data: [],
                backgroundColor: [
                    'rgba(26, 26, 26, 0.8)',
                    'rgba(74, 74, 74, 0.8)',
                    'rgba(107, 114, 128, 0.8)',
                    'rgba(156, 163, 175, 0.8)',
                    'rgba(209, 213, 219, 0.8)',
                    'rgba(26, 26, 26, 0.6)',
                    'rgba(74, 74, 74, 0.6)',
                    'rgba(107, 114, 128, 0.6)'
                ],
                borderColor: [
                    'rgba(26, 26, 26, 1)',
                    'rgba(74, 74, 74, 1)',
                    'rgba(107, 114, 128, 1)',
                    'rgba(156, 163, 175, 1)',
                    'rgba(209, 213, 219, 1)',
                    'rgba(26, 26, 26, 1)',
                    'rgba(74, 74, 74, 1)',
                    'rgba(107, 114, 128, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
}

function updateChart() {
    fetch(`${API_URL}?action=read_by_category`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                const categories = data.data.map(item => item.category);
                const counts = data.data.map(item => item.count);
                
                chart.data.labels = categories;
                chart.data.datasets[0].data = counts;
                chart.update();
            }
        })
        .catch(error => {
            console.error('Error updating chart:', error);
        });
}

function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer');
    const toastId = 'toast-' + Date.now();
    
    const toastHTML = `
        <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-${type} text-white">
                <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}
