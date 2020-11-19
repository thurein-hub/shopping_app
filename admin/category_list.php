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

  if(!empty($_POST['search'])){
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
  }else{
    if(empty($_GET['pageno'])){
      unset($_COOKIE['search']); 
      setcookie('search', null, -1, '/');
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
              <div class="card-header">
                <h3 class="card-title">Category listings</h3>
              </div>
              <!-- /.card-header -->
              <?php
                if(!empty($_GET['pageno'])){
                  $pageno = $_GET['pageno'];
                }else{
                  $pageno = 1;
                }
                $numOfrecord = 5;
                $offset = ($pageno-1) * $numOfrecord;

                if(empty($_POST['search']) && empty($_COOKIE['search'])){

                  $stmt =$pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
                  $stmt->execute();
                  $rawResult = $stmt->fetchAll();
                  $total_pages = ceil(count($rawResult)/$numOfrecord);  
  
                  $stmt =$pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset,$numOfrecord");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
  

                }else{
                  $search = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
                  $stmt =$pdo->prepare("SELECT * FROM categories  WHERE name LIKE '%$search%' ORDER BY id DESC");
                  $stmt->execute();
                  $rawResult = $stmt->fetchAll();
                  $total_pages =  ceil(count($rawResult)/$numOfrecord);  
  
                  $stmt =$pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfrecord");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
  

                }

              ?>
              
              <div class="card-body">
                <div>
                  <a href="category_add.php" type="button" class="btn btn-success">New Category</a>
                </div><br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">ID</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if($result){
                    $i=1;
                    foreach($result as $value){ 
                    ?>
                      <tr>
                      <td><?php echo $i ?></td>
                      <td><?php echo escape($value['name']) ?></td>
                      <td><?php echo escape($value['description']) ?></td>
                      <td>
                        <div class="btn-group">
                          <div class="container">
                            <a href="category_edit.php?id=<?php echo $value['id']?>" type="button" class="btn btn-warning">Edit</a>
                          </div>
                          <div class="container">
                            <a href="category_delete.php?id=<?php echo $value['id']?>" type="button" class="btn btn-danger" onclick="return confirm('Are you sure to delete?');">Delete</a>
                          </div>
                        </div>
                      </td>
                      </tr>
                    <?php
                    $i++;
                    }
                    }
                    ?>
                  </tbody>
                </table><br>
                <nav aria-label="Page navigation example" style="float:right">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item <?php if($pageno <= 1){echo'disabled';}?>">
                    <a class="page-link" href="<?php if($pageno <= 1){echo "#";}else{ echo "?pageno=".($pageno-1);}?>">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno;?></a></li>
                    <li class="page-item <?php if($pageno >= $total_pages){echo'disabled';}?>">
                    <a class="page-link" href="<?php if($pageno >= $total_pages){echo "#";}else{ echo "?pageno=".($pageno+1);}?>">Next</a></li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages;?>">Last</a></li>
                  </ul>

                </nav>
              </div>
              <!-- /.card-body -->
              
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

