<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta name="viewport" content="width=devide-width, initial-scale=1.0">
	<title><?php echo SITENAME."-".$data['title'];?></title>
	<link rel="shortcut icon" href="../public/img/favicon.png" type="image/x-icon" /> 
	<h2>Search Books</h2>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<link rel="stylesheet" href="../public/css/searchBookByAuthor.css">
	<script src="../public/javascript/searchBookByAuthor.js"></script>
</head>
<body >
	<form action="#" method="post">
		<h3>Select Author</h3>
		<?php if(count($data['authorListing']) > 0){?>
		<select onchange="getBookDetails()" name="book_name" id="author_id">
			<option value="" selected="selected">Select</option>
		<?php foreach ($data['authorListing'] as $key => $value) {?>
			<option value = '<?php echo $value->id?>'><?php echo $value->author_name?></option>
		<?php }?>
		</select>
		<?php } else {?>
			<h2>No Author Found</h2>
		<?php }?>
	</form>
	<div id="div2"><h2>No of Books Published: 0</h3></div>
	<div id="div1"><h2>No Books Published Yet</h2></div>
</body>
</html>