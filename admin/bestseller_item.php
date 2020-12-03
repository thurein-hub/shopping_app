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


?>

<?php include('header.php')?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Best Seller Item</h3>
              </div>
              <!-- /.card-header -->
              <?php

                $stmt = $pdo->prepare("SELECT * FROM sale_order_detail GROUP BY product_id HAVING SUM(quantity)>7   ORDER BY SUM(quantity) DESC");
                $stmt->execute();
                $result = $stmt->fetchAll();

              ?>
              
              <div class="card-body">
                <table class="table table-bordered" id="d-table">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Product</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if($result){
                    $i=1;
                    foreach($result as $value){
                      $proStmt = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                      $proStmt->execute();
                      $proResult = $proStmt->fetchAll();
                    ?>

                      <tr>
                      <td><?php echo $i ?></td>
                      <td><?php echo escape($proResult[0]['name']) ?></td>
                      </tr>
                    <?php
                    $i++;
                    }
                    }
                    ?>
                  </tbody>
                </table><br>

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

  <script>
      $(document).ready(function() {
        $('#d-table').DataTable();
        } );
  </script>
