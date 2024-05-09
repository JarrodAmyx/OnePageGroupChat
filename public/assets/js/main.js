var add = false;
function loadLocation( $url, $data = {} )
{
	var loader = document.getElementById("overlay");
    loader.style.display = "block";
	
	var token = $('meta[name="csrf-token"]').attr('content');

    // Add the CSRF token to the data object
    $data._token = token;
	$.ajax({
		url: $url,
		method: 'POST',
		data: $data,
		success: function(response) {
			loader.style.display = "none";
			$('#main').html(response);
			if( document.getElementById('chat-messages') !== null )
			{
				chatContainer = document.getElementById('chat-messages');
				chatContainer.scrollTop = chatContainer.scrollHeight;
			}
		},
		error: function() {
			loader.style.display = "none";
			alert('An error occurred. Please try again later.');
		}
	});
	if( typeof intervalId !== 'undefined' )
	{
		clearInterval(intervalId);
	}
}

function addMessage()
{
	add = true;
	var loader = document.getElementById("overlay");
    loader.style.display = "block";
	
	message = $('#newMessage').val().trim();
	if( message.length == 0 )
	{
		loader.style.display = "none";
		alert('Please add message');
		return null;
	}
	
	var token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: 'addMessage',
		method: 'POST',
		data: {'message' : message, '_token' : token},
		success: function(response) {
			$('#chat-messages').append(`
			<div class="message">
				<div class="message-sender">Me</div>
				<div class="message-content">${message}</div>
			</div>`);
			$('#newMessage').val('');
			chatContainer = document.getElementById('chat-messages');
			chatContainer.scrollTop = chatContainer.scrollHeight;
			loader.style.display = "none";
			add = false;
		},
		error: function() {
			loader.style.display = "none";
			alert("Your message was not added, please refresh your browser.");
		}
	});
	
}

window.onload = function()
{
	if( document.getElementById('chat-messages') !== null )
	{
		chatContainer = document.getElementById('chat-messages');
		chatContainer.scrollTop = chatContainer.scrollHeight;
	}
};

// Example usage:
// Call the showLoader function when you want to display the loader
// For example, when performing an AJAX request
// showLoader();
