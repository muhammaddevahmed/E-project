<?php
include('connection.php');
$catImageAdd = "";
$proImageAdd = "";
// addcategory
if(isset($_POST['addCategory'])){
$catName = $_POST['catName'];
$catImageName = $_FILES['catImage']['name'];
$fileObject = $_FILES['catImage']['tmp_name'];
$directory = 'images/categories/'.$catImageName;
$extension = pathinfo($catImageName, PATHINFO_EXTENSION);
if($extension == "jpg" || $extension == "jpeg" || $extension == "png" || $extension == "webp"){
if(move_uploaded_file($fileObject,$directory)){
    $query = $pdo ->prepare("insert into categories(category_name,image_path)values(:pn,:pi)");
        $query->bindParam("pn",$catName);
        $query->bindParam("pi",$catImageName);
        $query->execute();
        echo "<script>
    alert('inserted');
    </script>";

}else{
    echo "<script>
    alert('invaild file address');
    </script>";
}
}else{
    echo "<script>
    alert('invaild file extension');
    </script>";
}
}

// delete category
if(isset($_POST['deleteCategory'])){
    $category_id = $_POST['category_id'];
    $query = $pdo ->prepare("delete from categories where category_id = :cid");
    $query->bindParam("cid",$category_id);
    $query->execute();
    echo "<script>
        alert('category Deleted');
        </script>
    ";
}

//delete product
if(isset($_POST['deleteProduct'])){
    // $productId = 
    $product_id = $_POST['product_id'];
    $query = $pdo ->prepare("delete from products where product_id = :pid");
    $query->bindParam(":pid", $product_id);
    $query->execute();
    echo "<script>
    alert('Product Deleted');
    </script>";
}

?>