<?php
/**
 * 实现telnet连接交换机
 *
 */
error_reporting(-1);

class Telnet
{
    var $sock = NULL;

    function telnet($host, $port)
    {
        $this->sock = fsockopen($host, $port);
        socket_set_timeout($this->sock, 2, 0);
    }

    function close()
    {
        if ($this->sock) fclose($this->sock);
        $this->sock = NULL;
    }

    function write($buffer)
    {
        $buffer = str_replace(chr(255), chr(255) . chr(255), $buffer);
        fwrite($this->sock, $buffer);
    }

    function getc()
    {
        return fgetc($this->sock);
    }

    function read_till($what)
    {
        $buf = '';
        while (1) {
            $IAC = chr(255);
            $DONT = chr(254);
            $DO = chr(253);
            $WONT = chr(252);
            $WILL = chr(251);
            $theNULL = chr(0);
            $c = $this->getc();
            if ($c === false) return $buf;
            if ($c == $theNULL) {
                continue;
            }
            if ($c == "1") {
                continue;
            }
            if ($c != $IAC) {
                $buf .= $c;
                if ($what == (substr($buf, strlen($buf) - strlen($what)))) {
                    return $buf;
                } else {
                    continue;
                }
            }
            $c = $this->getc();
            if ($c == $IAC) {
                $buf .= $c;
            } else if (($c == $DO) || ($c == $DONT)) {
                $opt = $this->getc();
                fwrite($this->sock, $IAC . $WONT . $opt);
            } elseif (($c == $WILL) || ($c == $WONT)) {
                $opt = $this->getc();
                fwrite($this->sock, $IAC . $DONT . $opt);
            } else {
            }
        }
    }
}

function exe($order)
{
    $telnet = new telnet("192.168.255.1", 23);
    echo $telnet->read_till("password: ");
    $telnet->write("123456\r\n");
    echo $telnet->read_till(":> ");
    $telnet->write("sys\r\n");
    echo $telnet->read_till(":> ");
    $telnet->write("interface vlan-interface 200\r\n");
    echo $telnet->read_till(":> ");
    $telnet->write("$order\r\n");
    echo $telnet->read_till(":> ");
    echo $telnet->close();
}
//
//exe("undo packet-filter name dmt101_deny_upc inbound");
