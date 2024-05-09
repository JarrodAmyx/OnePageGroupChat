<div id="chat-container">
    <p class="fakeLink" style="text-align: left" onclick="loadLocation( 'home' )">Go Back</p>
	<h2>Create Chatroom</h2>
    <form id="create-chatroom-form" method="post" action="/create-chat">
        @csrf
        <label for="chat-name">Chat Name:</label><br>
        <input type="text" id="chat-name" name="chatName" required><br>
        
        <label for="description">Description:</label><br>
        <input type="text" id="description" name="description" required></input><br>
        
        <label for="first-message">First Message:</label><br>
        <input type="text" id="first-message" name="firstMessage" rows="4" required></input><br>
        
        <button type="submit">Create Chatroom</button>
    </form>
</div>
