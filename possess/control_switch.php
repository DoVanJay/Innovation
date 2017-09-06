<?php
/**
 * 连接控制交换机
 *
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/6/2017
 * Time: 9:05 AM
 */
error_reporting(-1);

//telnet连接交换机
class telnet_control_switch
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
//使用telnet连接并执行命令
function telnetExeCommand($host, $password, $command)
{
    $telnet = new telnet($host, 23);
    echo $telnet->read_till("password: ");
    $telnet->write($password);
    echo $telnet->read_till(":> ");
    $telnet->write("sys\r\n");
    echo $telnet->read_till(":> ");
    $telnet->write("interface vlan-interface 200\r\n");
    echo $telnet->read_till(":> ");
    $telnet->write("$command\r\n");
    echo $telnet->read_till(":> ");
    echo $telnet->close();
}
//
//exeCommand("undo packet-filter name dmt101_deny_upc inbound");
