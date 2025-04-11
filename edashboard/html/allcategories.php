<?php
include("components/header.php");

// Check user type
$user_type = $_SESSION['user_type'] ?? '';

// Handle category deletion (only if the user is not an employee)
if (isset($_POST['deleteCategory'])) {
    if ($user_type !== 'employee') {
        try {
            $category_id = $_POST['category_id'];
            
            // First get the image path to delete the file
            $stmt = $pdo->prepare("SELECT image_path FROM categories WHERE category_id = ?");
            $stmt->execute([$category_id]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($category) {
                // Delete the image file if it exists
                if (!empty($category['image_path']) && file_exists($category['image_path'])) {
                    unlink($category['image_path']);
                }
                
                // Delete the category from database
                $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
                $stmt->execute([$category_id]);
                
                $success = "Category deleted successfully!";
            } 
        } catch (Exception $e) {
            $error = "Error deleting category: " . $e->getMessage();
        }
    } else {
        $error = "You do not have permission to delete categories.";
    }
}
?>

<div class="container-fluid pt-4 px-4">
  <?php if (isset($success)): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <?php if (isset($error)): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <div class="row bg-light rounded mx-0">
    <div class="col-md-12">
      <h3>All Categories</h3>
      <?php if ($user_type === 'employee'): ?>
      <div class="alert alert-warning" role="alert">
        You do not have permission to edit or delete categories. All actions are disabled.
      </div>
      <?php endif; ?>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Image</th>
            <th scope="col">Name</th>
            <th scope="col" colspan="2">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          try {
              $query = $pdo->query("SELECT * FROM categories ORDER BY category_id DESC");
              $rows = $query->fetchAll(PDO::FETCH_ASSOC);
              
              if (empty($rows)) {
                  echo '<tr><td colspan="4" class="text-center">No categories found</td></tr>';
              } else {
                  foreach ($rows as $values) {
                      $imagePath = (!empty($values['image_path']) ? $values['image_path'] : 'images/default-category.png');
                  ?>
          <tr>
            <th scope="row">
              <img src="<?= htmlspecialchars($imagePath) ?>" width="80"
                alt="<?= htmlspecialchars($values['category_name']) ?>">
            </th>
            <td><?= htmlspecialchars($values['category_name']) ?></td>
            <td>
              <a href="edit_category.php?id=<?= $values['category_id'] ?>" class="btn btn-outline-success"
                <?php echo ($user_type === 'employee') ? 'onclick="return false;" style="pointer-events: none;"' : ''; ?>>
                Edit
              </a>
            </td>
            <td>
              <a href="#Delete<?= $values['category_id'] ?>" data-bs-toggle="modal" class="btn btn-outline-danger"
                <?php echo ($user_type === 'employee') ? 'onclick="return false;" style="pointer-events: none;"' : ''; ?>>
                Delete
              </a>
            </td>
          </tr>

          <!-- Delete Category Modal -->
          <div class="modal fade" id="Delete<?= $values['category_id'] ?>" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">Categories Delete</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="category_id" value="<?= $values['category_id'] ?>">
                    <p>Are you sure you want to delete this category?</p>
                    <button type="submit" name="deleteCategory" class="btn btn-primary"
                      <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Delete Category</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <?php
                  }
              }
          } catch (PDOException $e) {
              echo '<tr><td colspan="4" class="text-center text-danger">Error loading categories</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
include("components/footer.php");
?>