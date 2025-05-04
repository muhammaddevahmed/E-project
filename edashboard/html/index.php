<?php
include("components/header.php");
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Crafty Corner - Dashboard</title>
  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Font Awesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
  <style>
  /* Custom Animations */
  .fade-in {
    animation: fadeIn 1s ease-in;
  }

  @keyframes fadeIn {
    0% {
      opacity: 0;
      transform: translateY(20px);
    }

    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .hover-scale {
    transition: transform 0.3s ease;
  }

  .hover-scale:hover {
    transform: scale(1.05);
  }

  /* Glassmorphism Effect */
  .glass {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
  }

  /* Scrollbar Styling */
  ::-webkit-scrollbar {
    width: 8px;
  }

  ::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  ::-webkit-scrollbar-thumb {
    background: #7fad39;
    border-radius: 4px;
  }

  /* Ensure product images display fully */
  .product-image {
    width: 100%;
    max-height: 192px;
    /* Equivalent to h-48 for consistency */
    object-fit: contain;
    /* Scale image to fit without cropping */
    object-position: center;
    /* Center the image */
  }
  </style>
</head>

<body class="bg-gray-100 font-sans">
  <!-- Full Width Wrapper -->
  <div class="w-full py-6 px-4 sm:px-6 lg:px-8">
    <!-- Centered Content Container -->
    <div class="max-w-7xl mx-auto">
      <!-- Welcome Section -->
      <div class="mb-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden glass fade-in">
          <div class="flex flex-col md:flex-row">
            <div class="p-6 md:w-2/3">
              <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome to Your Dashboard 🎨</h2>
              <p class="text-gray-600 mb-4">
                You've achieved <span class="font-bold text-green-600">72%</span> of today's sales target.
                Explore new art supplies in stock and track your category performance.
              </p>
              <a href="allproducts.php"
                class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500 transition">View
                Inventory</a>
            </div>
            <div class="md:w-1/3 p-6 flex items-center justify-center">
              <img src="https://t3.ftcdn.net/jpg/02/89/73/52/360_F_289735210_ilL7T0J9EvenaB8gfEnQRMww0WBBpmiO.jpg"
                alt="Art Supplies" class="h-32 object-cover rounded fade-in" />
            </div>
          </div>
        </div>
      </div>

      <!-- Category Revenue Section (Now directly after Welcome Section) -->
      <div class="bg-white shadow-lg rounded-lg p-6 mb-8 glass fade-in">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Revenue by Category</h3>
        <div id="category-revenue-container">
          <!-- Dynamic content loaded via AJAX -->
        </div>
        <canvas id="categoryRevenueChart" class="mt-6"></canvas>
      </div>

      <!-- Popular Products Section -->
      <div class="bg-white shadow-lg rounded-lg p-6 glass fade-in">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Popular Products</h3>
        <?php
        require_once 'php/connection.php';
        try {
            $query = "
                SELECT 
                    p.product_id,
                    p.product_name,
                    p.price,
                    p.image_path
                FROM products p
                LEFT JOIN reviews r ON p.product_id = r.product_id
                GROUP BY p.product_id
                HAVING AVG(r.rating) >= 3
                ORDER BY AVG(r.rating) DESC
                LIMIT 3
            ";
            $stmt = $pdo->query($query);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $products = [];
            echo '<p class="text-red-500">Error fetching products: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <?php if (empty($products)): ?>
          <p class="text-gray-600 col-span-3">No products with 3+ star ratings found.</p>
          <?php else: ?>
          <?php foreach ($products as $product): ?>
          <div class="bg-gray-50 rounded-lg overflow-hidden shadow hover-scale">
            <img src="<?php echo htmlspecialchars($product['image_path']); ?>"
              alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image">
            <div class="p-4">
              <h5 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($product['product_name']); ?></h5>
              <span class="font-bold text-green-600">Rs <?php echo number_format($product['price'], 2); ?></span>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <?php include("components/footer.php"); ?>

  <script>
  // Fetch category revenue data and update chart
  async function fetchCategoryRevenue() {
    try {
      const response = await fetch('fetch_category_revenue.php');
      const data = await response.json();

      // Update revenue container
      const container = document.getElementById('category-revenue-container');
      container.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              ${data.map(category => `
                <div class="bg-gray-50 p-4 rounded-lg shadow flex items-center">
                  <span class="text-2xl text-green-600 mr-4"><i class="${category.icon}"></i></span>
                  <div>
                    <h4 class="font-bold text-gray-800">${category.category_name}</h4>
                    <p class="text-gray-600">Revenue: Rs ${category.revenue.toLocaleString()}</p>
                    <p class="text-gray-600">Orders: ${category.order_count}</p>
                  </div>
                </div>
              `).join('')}
            </div>
            <div class="bg-teal-50 p-4 rounded-lg shadow">
              <h4 class="font-bold text-gray-800">Top Category</h4>
              <p class="text-gray-600">${data[0]?.category_name || 'N/A'}: Rs ${data[0]?.revenue.toLocaleString() || '0'}</p>
            </div>
          `;

      // Update chart
      const ctx = document.getElementById('categoryRevenueChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.map(item => item.category_name),
          datasets: [{
            label: 'Revenue (Rs)',
            data: data.map(item => item.revenue),
            backgroundColor: ['#7fad39', '#4ade80', '#2dd4bf', '#60a5fa', '#f87171'],
            borderColor: ['#5f8a2b', '#38a169', '#1ebeb4', '#3b82f6', '#ef4444'],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: context => `Rs ${context.parsed.y.toLocaleString()}`
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: value => `Rs ${value.toLocaleString()}`
              }
            }
          }
        }
      });
    } catch (error) {
      console.error('Error fetching category revenue:', error);
      document.getElementById('category-revenue-container').innerHTML =
        '<p class="text-red-500">Error loading category data. Please try again later.</p>';
    }
  }

  // Initial fetch
  fetchCategoryRevenue();
  </script>