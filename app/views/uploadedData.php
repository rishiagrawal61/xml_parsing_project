<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta name="viewport" content="width=devide-width, initial-scale=1.0">
	<title><?php echo SITENAME."-".$data['title'];?></title>
	<link rel="shortcut icon" href="public/img/favicon.png" type="image/x-icon" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="public/css/uploadedData.css">
</head>
<body>
	<p>No of New Authors: <?php echo count($data['authorsInserted']);?></p>
	<table>
		<tr>
			<th>Id</th>
			<th>Author Name</th>
			<th>Book Name</th>
		</tr>
		<?php if(count($data['authorsInserted']) > 0) {
			foreach ($data['authorsInserted'] as $key => $value) {
				$name = '';
				if(isset($data['namesInserted'][$key]))
					$name = $data['namesInserted'][$key];
		?>  <tr>
				<td><?php echo (intval($key)+1);?></td>
				<td><?php echo $value;?></td>
				<td><?php echo $name;?></td>
			</tr>
		<?php }} else {?>
			<tr>
				<td colspan = '3'>No Records Inserted</td>
			</tr>
		<?php }?>
	</table>
</body>
</html>