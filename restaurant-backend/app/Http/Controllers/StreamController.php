<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StreamController extends Controller
{
    /**
     * The stream source.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return response()->stream(function () {
            while (true) {
                if (connection_aborted()) {
                    break;
                }

                echo "event: ping\n", "data: hello", "\n\n";

                ob_flush();
                flush();
                sleep(5);
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }
}
