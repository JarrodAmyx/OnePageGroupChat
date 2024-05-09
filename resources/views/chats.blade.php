<div id="chat-container">
	<a href="/logout" style="float:right">Logout</a>
	<h2>Welcome to the Chatrooms {{Session::get('user')->userName}}!</h2>
	<p>Select any chat room or <span class="fakeLink" onclick="loadLocation( '/create' )">make your own!</span></p>
	<div class="table-container">
		<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Creator</th>
					<th>Number of Messages</th>
				</tr>
			</thead>
			<tbody id="chatroom-table" >
				@foreach( $chatrooms as $chatroom )
					<tr class="fakeLink" onclick="loadLocation( '/viewChat', {'id': {{$chatroom->chatId}}} )">
						<td>{{ $chatroom->chatName }}</td>
						<td>{{ $chatroom->chatDescription }}</td>
						<td>{{ $chatroom->userName }}</td>
						<td>{{ $chatroom->chatCount }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>