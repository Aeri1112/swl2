<div id="holder"></div>

<script type="application/javascript">
    (function() {
        console.debug('fufuu')
        function write(message) {
            var pre = document.createElement('pre');
            pre.innerHTML = message;
            document.getElementById('holder').appendChild(pre)
        }
        write('Setting up SSE')
        if ('EventSource' in window) {
            write('SSE available')
            var sse = new EventSource('http://localhost/qyr/rest-api/sse/run');
            sse.onerror = function(e) {
                console.error(e)
                write('SSE Error')
            }
            sse.onopen = function() {
                write('SSE Connection established')
            }
            sse.onclose = function() {
                write('SSE Connection closed')
            }
            sse.onmessage = function(e) {
                console.debug('message received', e);
                write(JSON.stringify(e.data))
            }
        } else {
            write('SSE NOT AVAILABLE')
        }

    })()
</script>
