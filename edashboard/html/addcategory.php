<?php
include("components/header.php");
?>

<div class="container-fluid pt-4 px-4">

  <div class="row  bg-light rounded  mx-0">
    <div class="col-md-12">
      <h3>Add a New Category</h3>
      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Category Name</label>
          <input type="text" class="form-control" name="catName" id="exampleInputEmail1" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label">Image</label>
          <input type="file" name="catImage" class="form-control" id="exampleInputPassword1">
        </div>

        <button type="submit" name="addCategory" class="btn btn-primary">Add Category</button>
      </form>


    </div>
  </div>
</div>


<?php
include("components/footer.php");
?>