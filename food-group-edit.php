<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['food_group_name'])) {
        $valid = 0;
        $error_message .= "Food Group Name can not be empty<br>";
    } else {
		// Duplicate checking
    	// current name that is in the database
    	$statement = $pdo->prepare("SELECT * FROM tbl_food_group WHERE food_group_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$current_food_group_name = $row['food_group_name'];
		}

		$statement = $pdo->prepare("SELECT * FROM tbl_food_group WHERE food_group_name=? and food_group_name!=?");
    	$statement->execute(array($_POST['food_group_name'],$current_food_group_name));
    	$total = $statement->rowCount();							
    	if($total) {
    		$valid = 0;
        	$error_message .= 'Food Group Name already exists<br>';
    	}
    }

    if($valid == 1) {

    	// updating into the database
		$statement = $pdo->prepare("UPDATE tbl_food_group SET food_group_name=? WHERE food_group_id=?");
		$statement->execute(array($_POST['food_group_name'],$_REQUEST['id']));

    	$success_message = 'Food Group is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_food_group WHERE food_group_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Food Group</h1>
	</div>
	<div class="content-header-right">
		<a href="food-group.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php							
foreach ($result as $row) {
	$food_group_name = $row['food_group_name'];
}
?>


<section class="content">

	<div class="row">
		<div class="col-md-12">

			<?php if($error_message): ?>
			<div class="callout callout-danger">
			
			<p>
			<?php echo $error_message; ?>
			</p>
			</div>
			<?php endif; ?>

			<?php if($success_message): ?>
			<div class="callout callout-success">
			
			<p><?php echo $success_message; ?></p>
			</div>
			<?php endif; ?>

			<form class="form-horizontal" action="" method="post">
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Food Group Name <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="food_group_name" value="<?php echo $food_group_name; ?>" autocomplete="off">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Update</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>