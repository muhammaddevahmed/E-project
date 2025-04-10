<?php
include("components/header.php");
?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded mx-0">
        <div class="col-md-12">
            <h3>All Products</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Category</th>
                        <th scope="col">Warranty</th>
                        <th scope="col">Created At</th>
                        <th scope="col" colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $pdo->query("SELECT `products`.*, `categories`.`category_name`
                    FROM `products` 
                    INNER JOIN `categories` ON `products`.`category_id` = `categories`.`category_id`;");
                    $row = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach($row as $values) {
                    ?>
                    <tr>
                        <td><?php echo $values['product_id'] ?></td>
                        <td>
                            <img src="<?php echo $proImageAdd.$values['image_path']?>" width="80" alt="">
                        </td>
                        <td><?php echo $values['product_name'] ?></td>
                        <td><?php echo number_format($values['price'], 2) ?></td>
                        <td><?php echo $values['stock_quantity'] ?></td>
                        <td><?php echo $values['category_name'] ?></td>
                        <td><?php echo $values['warranty_period'] ?> months</td>
                        <td><?php echo date('Y-m-d', strtotime($values['created_at'])) ?></td>
                        <td>
                            <a href="editproduct.php?id=<?php echo $values['product_id']; ?>" class="btn btn-outline-success">Edit</a>
                        </td>
                        <td>
                            <a href="#Delete<?php echo $values['product_id']?>" data-bs-toggle="modal" class="btn btn-outline-danger">Delete</a>
                        </td>
                    </tr>

                    <!-- Delete Product Modal -->
                    <div class="modal fade" id="Delete<?php echo $values['product_id']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Delete Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this product?</p>
                                    <form method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="product_id" value="<?php echo $values['product_id']?>">
                                        <button type="submit" name="deleteProduct" class="btn btn-danger">Confirm Delete</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include("components/footer.php");
?>