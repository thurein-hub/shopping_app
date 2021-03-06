<?php 

	if (!empty($_POST['search'])) {
	setcookie('search',$_POST['search'], time() + (86400 * 30), "/");
	}else{
	if (empty($_GET['pageno'])) {
		unset($_COOKIE['search']); 
		setcookie('search', null, -1, '/'); 
	}
	}

?>
<?php include('header.php') ?>
	<?php
		require 'config/config.php';
		if(!empty($_GET['pageno'])){
			$pageno = $_GET['pageno'];
			}else{
			$pageno = 1;
			}
			$numOfrecords = 5;
			$offest = ($pageno-1)*$numOfrecords;

		if(empty($_POST['search']) && empty($_COOKIE['search'])){

			if(!empty($_GET['category_id'])){
				
				$cateId = $_GET['category_id'];
				$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id=$cateId AND quantity>0 ORDER BY id DESC");
				$stmt->execute();
				$rawResult = $stmt->fetchAll();
				$total_pages = ceil(count($rawResult)/$numOfrecords);
	
				$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id=$cateId AND quantity>0 ORDER BY id DESC LIMIT $offest,$numOfrecords");
				$stmt->execute();
				$result = $stmt->fetchAll();	
			}else{
				$stmt = $pdo->prepare("SELECT * FROM products WHERE quantity>0 ORDER BY id DESC");
				$stmt->execute();
				$rawResult = $stmt->fetchAll();
				$total_pages = ceil(count($rawResult)/$numOfrecords);
	
				$stmt = $pdo->prepare("SELECT * FROM products WHERE  quantity>0 ORDER BY id DESC LIMIT $offest,$numOfrecords");
				$stmt->execute();
				$result = $stmt->fetchAll();
			}



		}else{
			$search = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
			$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$search%' AND quantity>0 ORDER BY id DESC");
			$stmt->execute();
			$rawResult = $stmt->fetchAll();

			$total_pages = ceil(count($rawResult)/$numOfrecords);

			$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$search%' AND quantity>0 ORDER BY id DESC LIMIT $offest,$numOfrecords");
			$stmt->execute();
			$result = $stmt->fetchAll();

		}


	?>
	<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Browse Categories</div>
					<ul class="main-categories">
						<li class="main-nav-list">
						<?php 
							$catestmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
							$catestmt->execute();
							$cateresult= $catestmt->fetchALL();
						?>
						<?php
							foreach($cateresult as $value){?>
							<a  href="index.php?category_id=<?php echo $value['id']?>"><?php echo $value['name']?></a>
						<?php
						}
						?>
						</li>
					</ul>
					</div>
				</div>
	<div class="col-xl-9 col-lg-8 col-md-7">
				<!-- Start Filter Bar -->

	<div class="filter-bar d-flex flex-wrap align-items-center">
		<div class="pagination">
			<?php if(!empty($_GET['category_id'])) :?>
				<a href="?pageno=1&category_id=<?php echo $_GET['category_id']?>" class="active">First</a>
				<a <?php if($pageno <= 1){echo'disabled';}?>
				href="<?php if($pageno <= 1){echo "#";}else{ echo "?pageno=".($pageno-1)."&category_id=".$_GET['category_id'];}?>" class="prev-arrow">
				<i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
				<a href="#" class="active"><?php echo $pageno?></a>
				<a <?php if($pageno >= $total_pages){echo'disabled';}?> 
				href="<?php if($pageno >= $total_pages){echo "#";}else{ echo "?pageno=".($pageno+1)."&category_id=".$_GET['category_id'];}?>" class="next-arrow">
				<i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
				<a href="?pageno=<?php echo $total_pages;?>&category_id=<?php echo $_GET['category_id'] ?>" class="active">Last</a>
			<?php else: ?>
				<a href="?pageno=1" class="active">First</a>
				<a <?php if($pageno <= 1){echo'disabled';}?>
				href="<?php if($pageno <= 1){echo "#";}else{ echo "?pageno=".($pageno-1);}?>" class="prev-arrow">
				<i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
				<a href="#" class="active"><?php echo $pageno?></a>
				<a <?php if($pageno >= $total_pages){echo'disabled';}?> 
				href="<?php if($pageno >= $total_pages){echo "#";}else{ echo "?pageno=".($pageno+1);}?>" class="next-arrow">
				<i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
				<a href="?pageno=<?php echo $total_pages;?>" class="active">Last</a>
			<?php endif; ?>					
		</div>
	</div>

	<!-- End Filter Bar -->
	<!-- Start Best Seller -->
	<section class="lattest-product-area pb-40 category-list">
		<div class="row">
			<!-- single product -->
			
			<?php
				if($result){
				foreach($result as $item){
			?>
				<div class="col-lg-4 col-md-6">
					<div class="single-product">
						<a href="product_detail.php?product_id=<?php echo escape($item['id']) ?>"><img class="img-fluid" src="admin/images/<?php echo escape($item['image'])?>" alt="" style="height:200px"></a>
						<div class="product-details">
							<h6><?php echo escape($item['name'])?></h6>
							<h6><?php echo escape($item['price'])?></h6>
							<div class="prd-bottom">
								<form action="addtocart.php" method="post">
									<input type="hidden" name="_token" value="<?php echo $_SESSION['_token']?>">
									<input type="hidden" name="id" value="<?php echo $item['id']?>">
									<input type="hidden" name="qty" value="1">
									<div class="social-info">
										<button type="submit" style="display:contents">
											<span class="ti-bag"></span>
											<p class="hover-text" style="left:20px;">add to bag</p>
										</button>
									</div>
									<a href="product_detail.php?product_id=<?php echo escape($item['id']) ?>" class="social-info">
										<span class="lnr lnr-move"></span>
										<p class="hover-text">view more</p>
									</a>	
								</form>
							</div>
						</div>
					</div>
				</div>
			<?php
				}
				}
			?>
			

			
			<!-- single product -->
		</div>
	</section>
	<!-- End Best Seller -->
<?php include('footer.php');?>
