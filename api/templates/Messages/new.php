
        <script src = "https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>
        <link href="/style.css" rel="stylesheet"/>
        <title>Live Group Chat</title>

        <main role="main">
            <div id="chat-window">
                <div id="output"></div>
                <div id="feedback"></div>
            </div>
        </main>

            <footer class="footer">
                <input id="handle" type="text" placeholder="Speaker"/>
                <input id="message" type="text" placeholder="Message"/>
                <button id="send" class="btn btn-danger btn-block btn-lg">Send</button>
            </footer>



        <?= $this->Html->script('chat') ?>
