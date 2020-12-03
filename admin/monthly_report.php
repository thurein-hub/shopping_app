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
                <h3 class="card-title">Monthly Report</h3>
              </div>
              <!-- /.card-header -->
              <?php
                $currentDate = date('Y-m-d');
                $fromDate = date('Y-m-d H:i:s',strtotime($currentDate.'+1 day'));
                $toDate = date('Y-m-d H:i:s',strtotime($currentDate.'-2 month'));
                $stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE order_date<:fromdate AND order_date>=:todate  ORDER BY id DESC");
                $stmt->execute(
                    array(
                        ':fromdate'=>$fromDate,
                        ':todate'=>$toDate
                    )
                );
                $result = $stmt->fetchAll();

              ?>
              
              <div class="card-body">
                <table class="table table-bordered" id="d-table">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User</th>
                      <th>Total Amount</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if($result){
                    $i=1;
                    foreach($result as $value){
                      $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                      $userStmt->execute();
                      $userResult = $userStmt->fetchAll();
                    ?>

                      <tr>
                      <td><?php echo $i ?></td>
                      <td><?php echo escape($userResult[0]['name']) ?></td>
                      <td><?php echo escape($value['total_price']) ?></td>
                      <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))) ?></td>
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
