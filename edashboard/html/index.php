<style>
.full-width-centered {
  width: 100%;
  max-width: 100%;
  padding: 0 15px;
  /* Add some padding on sides */
}

.centered-content {
  max-width: 1200px;
  /* Or your preferred max width */
  margin: 0 auto;
  /* Center the content */
}
</style>

<?php include("components/header.php"); ?>

<body>
  <!-- Full width wrapper -->
  <div class="full-width-centered">
    <!-- Centered content container -->
    <div class="centered-content">
      <!-- Your content here -->
      <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Stationary & Art Shop Specific Content -->
        <div class="row">
          <div class="col-12">
            <!-- Welcome Card for Art Shop -->
            <div class="card mb-4">
              <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                  <div class="card-body">
                    <h5 class="card-title text-primary">Welcome to Dashboard 🎨</h5>
                    <p class="mb-4">
                      You've completed <span class="fw-bold">72%</span> of today's sales target.
                      Check your new art supplies in stock.
                    </p>
                    <a href="allproducts.php" class="btn btn-sm btn-outline-primary">View Inventory</a>
                  </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                  <div class="card-body pb-0 px-0 px-md-4">
                    <img src="https://t3.ftcdn.net/jpg/02/89/73/52/360_F_289735210_ilL7T0J9EvenaB8gfEnQRMww0WBBpmiO.jpg"
                      height="140" alt="Art Supplies" class="img-fluid" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Art Shop Metrics -->
          <div class="col-md-4 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <div class="avatar flex-shrink-0">
                    <span class="avatar-initial rounded bg-label-primary">
                      <i class="bx bx-paint"></i>
                    </span>
                  </div>
                </div>
                <span class="fw-semibold d-block mb-1">Art Supplies Sold</span>
                <h3 class="card-title mb-2">1,428</h3>
                <small class="text-success fw-semibold">
                  <i class="bx bx-up-arrow-alt"></i> +28.14%
                </small>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <div class="avatar flex-shrink-0">
                    <span class="avatar-initial rounded bg-label-success">
                      <i class="bx bx-notepad"></i>
                    </span>
                  </div>
                </div>
                <span class="fw-semibold d-block mb-1">Stationary Items</span>
                <h3 class="card-title mb-2">3,287</h3>
                <small class="text-success fw-semibold">
                  <i class="bx bx-up-arrow-alt"></i> +18.42%
                </small>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <div class="avatar flex-shrink-0">
                    <span class="avatar-initial rounded bg-label-info">
                      <i class="bx bx-dollar"></i>
                    </span>
                  </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total Revenue</span>
                <h3 class="card-title mb-2">$ 8,457</h3>
                <small class="text-success fw-semibold">
                  <i class="bx bx-up-arrow-alt"></i> +22.63%
                </small>
              </div>
            </div>
          </div>

          <!-- Art Products Section -->
          <div class="col-12 mb-4">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title mb-0">Popular Products</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <div class="card h-100">
                      <img class="card-img-top"
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSf7gYV_tBE-KIVF9Tc-XFL1Lx4_Cjj6oUCFQ&s"
                        alt="Watercolor Set">
                      <div class="card-body">
                        <h5 class="card-title">Watercolor Set</h5>
                        <p class="card-text">24-color professional watercolor palette</p>
                        <span class="fw-bold">$ 500</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card h-100">
                      <img class="card-img-top"
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXGgxv8PwzA6yQsTVZvvcO-15Opu-lrEj54Q&s"
                        alt="Sketchbook">
                      <div class="card-body">
                        <h5 class="card-title">Premium Sketchbook</h5>
                        <p class="card-text">120gsm acid-free paper, 50 sheets</p>
                        <span class="fw-bold">$ 200</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card h-100">
                      <img class="card-img-top"
                        src="https://market-resized.envatousercontent.com/previews/files/299819032/Calligraphy-pen-590x590.jpg?w=590&h=590&cf_fit=crop&crop=top&format=auto&q=85&s=fe8abaa0465eb0061383014c8c134c3549dadecf07b8f50ac396ef438b703dc8"
                        alt="Calligraphy Pen">
                      <div class="card-body">
                        <h5 class="card-title">Calligraphy Pen Set</h5>
                        <p class="card-text">5 nibs with ink and practice guide</p>
                        <span class="fw-bold">$ 100</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include("components/footer.php"); ?>