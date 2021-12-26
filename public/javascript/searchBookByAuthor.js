$('document').ready(function(){
			$('#author_id').val('');
		});
		function getBookDetails(){
			if(document.getElementById('author_id').value == ""){
				alert("Please select correct value for Author");return 
			}
			$.ajax({
				url: "../DisplayController/getBookDetails",
				type: 'POST',
				dataType: 'json',
				data: {author_id:document.getElementById('author_id').value},
				success: function(result){
		      		if(result.status == 0){
		      			swal(result.message);
		      			return;
		      		} else {
		      			var tableString = "<table><tr><th>Id</th><th>Book Name</th></tr>";
		      			var newString = '';
		      			if(result.data.booksListing.length == 0){
		      				tableString+="<tr><td colspan = '2'>No Records Found</td></tr></table>"; 
		      				newString+= '<p> No of books Published: '+result.data.booksListing.length+'</p>';
		      			} else {
		      				newString+= '<p> No of books Published: '+result.data.booksListing.length+'</p>';
		      				var key = 0;
		      				result.data.booksListing.forEach((entry) => {
		      					tableString+="<tr><td>"+(key+1)+"</td><td>"+(entry.book_name)+"</td></tr>";
		      					key++;
		      				});
		      				tableString+="</table>";
		      			}
		      			$('#div1').html(tableString);
		      			$('#div2').html(newString);
		      		}
		    }});
		}