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
    if(empty($_POST['name']) || empty($_POST['description'])){
        if(empty($_POST['name'])){
            $nameError = 'Name is required!';
        }
        if(empty($_POST['description'])){
            $descriptionError = 'Descriptionn is required!';
        }

    }else{
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        $stmt = $pdo->prepare("UPDATE categories SET name =:name, description = :description WHERE id='$id'");
        $result = $stmt->execute(
            array(
                ':name' => $name,
                ':description' =>$description
            )
        );
        if($result){
            echo"<script>alert('Successfully Updated');window.location.href='category_list.php';</script>";

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
            <?php 
                $stmt = $pdo->prepare("SELECT * FROM categories WHERE id =".$_GET['id']);
                $stmt->execute();
                $output = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>  
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                    <input type="hidden" name="id" value="<?php echo $output['id']?>">
                    <div class="form-group">
                        <label for="">Name</label><p style="color:red"><?php echo  empty($nameError) ? '' : '*'.$nameError ?></p>
                        <input type="text" name="name" id="" class="form-control" value="<?php echo escape($output['name'])?>">
                    </div>
                    <div class="form-group">
                        <label for="">Description</label><p style="color:red"><?php echo  empty($descriptionError) ? '' : '*'.$descriptionError ?></p>
                        <textarea name="description" id="" cols="30" rows="10" class="form-control"><?php echo escape($output['description'])?></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Update" id="" class="btn btn-primary">
                        <a href="category_list.php" type="button" class="btn btn-warning">Back</a>
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
