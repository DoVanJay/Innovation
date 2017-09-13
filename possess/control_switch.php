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

    function __construct($host, $port)
    {
        $this->sock = fsockopen($host, $port);
        socket_set_timeout($this->sock, 1, 0);
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
            }
        }
    }

}


//使用telnet连接并执行命令
function telnetExeCommand($host, $password, $command)
{

    $telnet = new telnet_control_switch($host, 23);
    echo $telnet->read_till("password: ");
    $telnet->write($password . "\r\n");
    echo $telnet->read_till(":> ");
    $telnet->write("sys\r\n");
    echo $telnet->read_till(":> ");
    foreach ($command as $com) {
        $telnet->write("$com\r\n");
        echo $telnet->read_till(":> ");
    }
    $telnet->close();
}

function readCurrentACL($host, $password, $vlan)
{
    $query_acl = "display current-configuration interface Vlan-interface " . $vlan . "\r\n";
    $telnet = new telnet_control_switch($host, 23);
    $telnet->read_till("password: ");
    $telnet->write($password . "\r\n");
    $telnet->read_till(":> ");
    $telnet->write("sys\r\n");
    $telnet->read_till(":> ");
    $telnet->write($query_acl);
    $result = $telnet->read_till(":> ");
    $telnet->close();
    $results = explode("\r\n", $result);
    return $results[5];
}


//$t1 = microtime(true);
//readCurrentACL("10.0.0.1", "123456", "200");
//test("10.0.0.1", "123456", "200");
//$t2 = microtime(true);
//echo (($t2 - $t1) * 1000) . 'ms';
