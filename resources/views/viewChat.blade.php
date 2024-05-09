<div id="chat-container">
	<p class="fakeLink" style="text-align: left" onclick="loadLocation( 'home' )">Go Back</p>
	<h2>{{$chatrooms{0}->chatName}}</h2>
	<p>{{$chatrooms{0}->chatDescription}}</p>
	<div id="chat-messages">
		@foreach( $chatrooms as $message )
			<div
				class="message"
				{{Session::get('user')->userName == $message->userName ? 'style=text-align:right;' : '' }}
			>
				<div class="message-sender">{{Session::get('user')->userName == $message->userName ? 'Me' : $message->userName}}</div>
				<div class="message-content">{{$message->chatMessageText}}</div>
			</div>
		@endForeach
	</div>
	<div class="chat-input">
		<input type="text" placeholder="Type your message..." id="newMessage" onkeypress="event.key === 'Enter' && addMessage()">
		<button onclick="addMessage();">Send</button>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	function checkForUpdates() {
		// Make an AJAX request to fetch updates
		var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

		$.ajax({
			url: '/check-updates', // Replace with your endpoint to check for updates
			method: 'POST',
			data: {'number': $('#chat-messages').children().length, '_token' : token},
			success: function(response) {
				if( response )
				{
					chatContainer = document.getElementById('chat-messages');
					startScroll = chatContainer.scrollHeight - chatContainer.clientHeight;
					response.reverse();
					$.each(response, function(index, item)
					{
						user = item.userName;
						message = item.chatMessageText;
						message = 
						$('#chat-messages').append(`
							<div class="message"' >
								<div class="message-sender">${user}</div>
								<div class="message-content">${message}</div>
							</div>
						`);
					})
					if( Math.abs( startScroll - chatContainer.scrollTop ) <= 10 )
					{
						chatContainer.scrollTop = chatContainer.scrollHeight;
					}
				}
			},
			error: function() {
				console.error('Failed to fetch updates');
			}
		});
	}

	// Call checkForUpdates function initially to start the process
	checkForUpdates();

	// Schedule checkForUpdates function to run every 5 seconds
	intervalId = setInterval(checkForUpdates, 2000); // 5000 milliseconds = 5 seconds);
</script>