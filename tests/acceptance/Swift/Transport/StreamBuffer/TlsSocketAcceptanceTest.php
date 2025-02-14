<?php

require_once __DIR__.'/AbstractStreamBufferAcceptanceTest.php';

class Swift_Transport_StreamBuffer_TlsSocketAcceptanceTest extends Swift_Transport_StreamBuffer_AbstractStreamBufferAcceptanceTest
{
    protected function setUp(): void
    {
        $streams = stream_get_transports();
        if (!\in_array('tls', $streams)) {
            $this->markTestSkipped(
                'TLS is not configured for your system.  It is not possible to run this test'
            );
        }
        if (!\defined('SWIFT_TLS_HOST')) {
            $this->markTestSkipped(
                'Cannot run test without a TLS enabled SMTP host to connect to (define '.
                'SWIFT_TLS_HOST in tests/acceptance.conf.php if you wish to run this test)'
            );
        }
        if (\PHP_VERSION_ID < 70200) {
            $this->markTestSkipped(
                'Tests fail on PHP 7.1 and below.'
            );
        }
        parent::setUp();
    }

    protected function initializeBuffer()
    {
        $parts = explode(':', SWIFT_TLS_HOST);
        $host = $parts[0];
        $port = $parts[1] ?? 25;

        $this->buffer->initialize([
            'type' => Swift_Transport_IoBuffer::TYPE_SOCKET,
            'host' => $host,
            'port' => $port,
            'protocol' => 'tls',
            'blocking' => 1,
            'timeout' => 15,
            ]);
    }
}
