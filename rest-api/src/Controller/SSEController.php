<?php


namespace App\Controller;

use Cake\Controller\Controller;

error_reporting(0);

class SSEController extends Controller
{
    public  $layout = 'default';
    private $rounds = 100;

    /**
     * Gibt den Inhalt im Buffer aus, bis nix mehr drin ist.
     */
    protected function flushHandler()
    {
        do {
            $flushed = @ob_end_flush();
        } while ($flushed);
        @ob_flush();
        @flush();
    }

    /**
     * Sendet Daten an den Client
     * @param mixed $payload daten die per json encode gepackt werden.
     * @param null  $event
     * @param false $baseEncoded
     * @return $this
     */
    protected function flush($payload, $event = null, $baseEncoded = false)
    {
        $payload = $baseEncoded ? base64_encode($payload) : $payload;
        $msg     = '';
        if ($event) {
            $msg .= "event: $event\n";
        }
        if (!is_string($payload)) {
            $payload = json_encode($payload);
        }
        $msg .= "data: $payload\n";
        echo str_pad($msg, 4 * 1024 - 2) . "\n\n";
        $this->flushHandler();
        return $this;
    }

    public function intro()
    {
        // Das hier ist die landing page fuer SSE
        // Enpoint ist /sse/intro
    }

    public function run()
    {
        $this->layout = false; // layout deaktivieren
        ini_set('zlib.output_compression', 0); // kompression sicherheitshalber deaktvieren
        ini_set('max_input_time', 0); // laufzeit auf unendlich, sicherheitshalber
        gc_enable(); // garbage collector... naja, vermutlich nicht so wichtig
        ob_end_clean(); // output buffer sicherheitshalber entsorgen
        // header festlegen
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');
        header('Content-Encoding: none');
        @ob_end_flush(); // und header senden
        do {
            // 100 mal die Zeit ausgeben
            $this->flush(['time' => time()]);
            --$this->rounds;
            // und dabei zwischen 1 und 30 Sekunden warten, bevor man wieder etwas sendet.
            sleep(random_int(1, 30));
        } while ($this->rounds > 0);
        // final den letzten inhalt rausgeben, vermutlich leer.
        @ob_end_flush();
    }

    /**
     * Mit Redis wuerde das so aus sehen.
     *
     * $pubSub->subscribe('channel-1', 'channel-2', ...); // Die channel angeben, denen man lauschen will
     * $loopNum = 0;
     * while (true) { // dauerschleife
     *      $streams = [$predis->getConnection()->getResource()]; // streams denen man lauscht
     *      $n = null;
     *      $timeout = 30;
     *      try {
     *          if (stream_select($streams, $n, $n, $timeout) > 0) { // das hier blockiert bis was durchkommt
     *              $message = $pubSub->current(); // und hier kriegt man dann die nachricht als stdClass
     *              $loopNum++;
     *              // nun hier die empfangene Nachricht bearbeiten und dann per $this->flush() weitergeben,
     *              // derjenige das auch empfangen soll.
     *
     *          }
     *          if ($loopNum >= 1000) { // das hier auch nur fuer performance, kann aber sein, dass kaum was ausmacht.
     *              $loopNum = 0;
     *              gc_collect_cycles();
     *          }
     *      } catch (Throwable $e) {}
     * }
     */
}
