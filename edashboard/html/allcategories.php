<?php
include("components/header.php");
?>

<div class="container-fluid pt-4 px-4">

    <div class="row  bg-light rounded  mx-0">
        <div class="col-md-12">
            <h3>All Categories</h3>
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
                                    $query = $pdo->query("select * from categories");
                                    $row  = $query->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($row as$values){
?>
                    <tr>
                        <th scope="row">
                            <img src="<?php echo $catImageAdd.$values['image_path']?>" width="80" alt="" srcset="">
                        </th>
                        <td>
                            <?php echo $values['category_name']?>
                        </td>
                        <td><a href="edit_category.php?id=<?php echo $values['category_id']?>" class="btn btn-outline-success">Edit</a></td>
                        <td><a href="#Delete<?php echo $values['category_id']?>" data-bs-toggle="modal"  class="btn btn-outline-danger">Delete</a></td>

                    </tr>

                    
 <!--Delete category Modal -->
 <div class="modal fade" id="Delete<?php echo $values['category_id']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Categories Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="category_id" value="<?php echo $values['category_id']?>">


                    <button type="submit" name="deleteCategory" class="btn btn-primary">Delete Category</button>
                </form>
            </div>

        </div>
    </div>
</div>

                    <?php


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