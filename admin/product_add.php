<?php
    session_start();
    require '../config/config.php';
    require '../config/common.php';

    
    if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
        header('location: login.php');
      
    }
    if($_SESSION['role'] != 1){
        header('location: login.php');
    }

    if($_POST){
        if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) 
           || empty($_POST['quantity']) || empty($_POST['price']) || empty($_FILES['image'])){

            if(empty($_POST['name'])){
                $nameError = 'Name is required!';
            }

            if(empty($_POST['description'])){
                $descriptionError = 'Description is required!';
            }

            if(empty($_POST['cateory'])){
                $cateError = 'Category is required!';
            }

            if(empty($_POST['quantity'])){
                $qtyError = 'Quantity is required!';
            }elseif(is_numeric($_POST['quantity']) != 1){
                $qtyError = 'Quantity must be a number!';
            }

            if(empty($_POST['price'])){
                $priceError = 'Price is required!';
            }elseif(is_numeric($_POST['price']) != 1){
                $priceError = 'Price must be a number!';
            }

            if(empty($_FILES['image']['name'])){
                $imageError = 'Image is required!';
            }

           }else{
               $file = 'images/'.$_FILES['image']['name'];
               $imageType = pathinfo($file, PATHINFO_EXTENSION);
               if($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png' && $imageType != 'gif'){
                echo"<script>alert('Image must be jpg,jpeg,png,gif!');window.location.href = 'product_add.php';</script>";
               }else{
                   $name = $_POST['name'];
                   $description = $_POST['description'];
                   $category = $_POST['category'];
                   $quantity = $_POST['quantity'];
                   $price = $_POST['price'];
                   $image = $_FILES['image']['name'];
                   move_uploaded_file($_FILES['image']['tmp_name'], $file);

                   $stmt = $pdo->prepare("INSERT INTO products(name,description,category_id,quantity,price,image) VALUES(:name,:description,
                                          :category,:quantity,:price,:image)");
                   $result=$stmt->execute(
                            array(
                                ':name'=>$name,
                                ':description'=>$description,
                                ':category'=>$category,
                                ':quantity'=>$quantity,
                                ':price'=>$price,
                                ':image'=>$image
                                )
                    );
                    if($result){
                        echo"<script>alert('Successfully added');window.location.href='index.php';</script>";
                    }

               }

           }
    }


?>

<?php include('header.php')?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
            <div class="card-body">
                <form action="product_add.php" method="post" enctype="multipart/form-data">
                    <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                    <div class="form-group">
                        <label for="">Name</label><p style="color:red"><?php echo  empty($nameError) ? '' : '*'.$nameError ?></p>
                        <input type="text" name="name" id="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Description</label><p style="color:red"><?php echo  empty($descriptionError) ? '' : '*'.$descriptionError ?></p>
                        <textarea name="description" id="" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    <?php
                        $catStmt = $pdo->prepare("SELECT * FROM categories");
                        $catStmt->execute();
                        $catResult = $catStmt->fetchAll();

                    ?>
                    <div class="form-group">
                        <label for="">Category</label><p style="color:red"><?php echo  empty($cateError) ? '' : '*'.$cateError ?></p>
                        <select name="category" id="" class="form-control">
                            <option value="">SELECT CATEGORY</option>
                            <?php
                            foreach($catResult as $catValue){
                            ?>
                            <option value="<?php echo $catValue['id'] ?>"><?php echo $catValue['name'] ?></option>
                            <?php
                            }?>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Quantity</label><p style="color:red"><?php echo  empty($qtyError) ? '' : '*'.$qtyError ?></p>
                        <input type="number" name="quantity" id="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Price</label><p style="color:red"><?php echo  empty($priceError) ? '' : '*'.$priceError ?></p>
                        <input type="number" name="price" id="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Image</label><p style="color:red"><?php echo  empty($imageError) ? '' : '*'.$imageError ?></p>
                        <input type="file" name="image" id="" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="ADD" id="" class="btn btn-primary">
                        <a href="index.php" type="button" class="btn btn-warning">Back</a>
                    </div>
                </form>
            </div>
              
            </div>
            <!-- /.card -->

            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php include('footer.html')?>