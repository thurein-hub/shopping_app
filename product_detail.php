
<?php include('header.php') ?>
<?php
  require 'config/config.php';
  
  $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['product_id']);
  $stmt->execute();
  $detailResult = $stmt->fetchALL();
  
?>
<!--================Single Product Area =================-->
<div class="product_image_area" style="padding-top: 0px !important;">
  <div class="container">
    <div class="row s_product_inner">
      <div class="col-lg-6">
          <div class="single-prd-item">
            <img class="img-fluid" src="admin/images/<?php echo escape($detailResult[0]['image']) ?>" alt="" 
                        style="height: 350px; margin-top: 73px; margin-left:100px;">
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1">
        <div class="s_product_text">
          <?php
            $catestmt = $pdo->prepare("SELECT * FROM categories WHERE id=".$detailResult[0]['category_id']);
            $catestmt->execute();
            $cateResult = $catestmt->fetchAll();
          ?>
          <h3><?php echo escape($detailResult[0]['name']) ?></h3>
          <h2><?php echo escape($detailResult[0]['price']) ?></h2>
          <ul class="list">
            <li><a class="active" href="#"><span>Category</span> : <?php echo escape($cateResult[0]['name']) ?></a></li>
            <li><a href="#"><span>Availibility</span> : In Stock</a></li>
          </ul>
          <p><?php echo escape($detailResult[0]['description']) ?></p>

          <form action="addtocart.php" method="post">
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
            <input type="hidden" name="id" value="<?php  echo escape($detailResult[0]['id']) ?>">
            <div class="product_count">
              <label for="qty">Quantity:</label>
              <input type="text" name="qty" id="sst" maxlength="12" value="<?php echo escape($detailResult[0]['quantity']) ?>" title="Quantity:" class="input-text qty">
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
              class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
              class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
            </div>
            <div class="card_area d-flex align-items-center">
              <button class="primary-btn" style="border:1px;">Add to Cart</button>
              <a class="primary-btn" href="index.php">Back</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div><br>
<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<?php include('footer.php');?>
