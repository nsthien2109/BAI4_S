<?php
require_once('../ket_noi.php');

// Get category ID
if (!isset($category_id)) {
    $category_id = filter_input(INPUT_GET, 'category_id',
            FILTER_VALIDATE_INT);
    if ($category_id == NULL || $category_id == FALSE) {
        $category_id = 1;
    }
}

// Get name for selected category
$queryCategory = 'SELECT * FROM categories
                  WHERE categoryID = :category_id';
$statement1 = $db->prepare($queryCategory);
$statement1->bindValue(':category_id', $category_id);
$statement1->execute();
$category = $statement1->fetch();
$category_name = $category['categoryName'];
$statement1->closeCursor();


// Get all categories
$query = 'SELECT * FROM categories
                       ORDER BY categoryID';
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll();
$statement->closeCursor();

// Get products for selected category
$queryProducts = 'SELECT * FROM products
                  WHERE categoryID = :category_id
                  ORDER BY productID';
$statement3 = $db->prepare($queryProducts);
$statement3->bindValue(':category_id', $category_id);
$statement3->execute();
$products = $statement3->fetchAll();
$statement3->closeCursor();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Product</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="./public/css/main.css">
<script type="text/javascript" src="./public/js/main.js" ></script>
</head>
<body>
<div class="container-xl">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-6">
						<h2>My <b>Laptop Shop</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Employee</span></a>
						<a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Delete</span></a>
					</div>
				</div>
			</div>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>
              <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Category
                <span class="caret"></span></button>
                <ul class="dropdown-menu">
            <?php foreach ($categories as $category) : ?>
            <li><a href=".?category_id=<?php echo $category['categoryID']; ?>"><?php echo $category['categoryName']; ?></a></li>
            <?php endforeach; ?>
                </ul>
              </div>
						</th>
						<th>Code</th>
						<th>Name</th>
						<th>Price</th>
						<th>Actions</th>
					</tr>
				</thead>

				<tbody>
          <?php foreach ($products as $product) : ?>
					<tr>
            <td></td>
						<td><?php echo $product['productCode']; ?></td>
						<td><?php echo $product['productName']; ?></td>
						<td><?php echo $product['listPrice']; ?></td>
            <td style="display : flex"><form action="delete_product.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['productID']; ?>" class="edit" data-toggle="modal">
                <input type="hidden" name="category_id" value="<?php echo $product['categoryID']; ?>" class="edit" data-toggle="modal">
                <input type="image" src="../images/buttons/delete.png" name="" value="Delete" class="delete">&emsp;
                <a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit" style="width:16px">&#xE254;</i></a>
            </form></td>
					</tr>
          <?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- Add Modal HTML -->
<div id="addEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="add_product.php" id="add_product_form" method="post">
				<div class="modal-header">
					<h4 class="modal-title">Add Product</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
          <div class="form-group">
            <label>CategoryID</label>
            <select name="category_id">
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category['categoryID']; ?>">
                                <?php echo $category['categoryName']; ?>
                            </option>
                        <?php endforeach; ?>
                        </select>
          </div>
					<div class="form-group">
						<label>Code</label>
						<input type="text" class="form-control" name="code" required>
					</div>
					<div class="form-group">
						<label>Name</label>
						<input type="text" class="form-control" name="name" required>
					</div>
					<div class="form-group">
						<label>listPrice</label>
						<input type="text" class="form-control" name="price" required>
					</div>
				</div>
				<div class="modal-footer">
          <input type="submit" class="btn btn-success" value="Add Product">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Edit Modal HTML -->
<div id="editEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="edit_product.php" method="post">

				<div class="modal-header">
					<h4 class="modal-title">Edit Product</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
        <div class="modal-body">
          <div class="form-group">
            <label>ProductID</label>
            <select name="product_id1">
                        <?php foreach ($products as $product) : ?>
                            <option value="<?php echo $product['productID']; ?>">
                                <?php echo $product['productID']; ?>
                            </option>
                        <?php endforeach; ?>
                        </select>
          </div>
					<div class="form-group">
						<label>Code</label>
						<input type="text" class="form-control" name="code1" required>
					</div>
					<div class="form-group">
						<label>Name</label>
						<input type="text" class="form-control" name="name1" required>
					</div>
					<div class="form-group">
						<label>listPrice</label>
						<input type="text" class="form-control" name="price1" required>
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-info" value="Save">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Delete Modal HTML -->
<div id="deleteEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form>
				<div class="modal-header">
					<h4 class="modal-title">Delete Employee</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete these Records?</p>
					<p class="text-warning"><small>This action cannot be undone.</small></p>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<input type="submit" class="btn btn-danger" value="Delete">
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>
