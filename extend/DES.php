<?php
/**
 * 文件的简短描述：无
 *
 * 文件的详细描述：无
 *
 * LICENSE:
 * @author wangzhen 2016/11/14
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
class DES {
    var $key;
    var $iv; //偏移量

    function DES($key, $iv=0)
    {
        $this->key = $key;
        if($iv == 0)
        {
            $this->iv = $key;
        }
        else
        {
            $this->iv = $iv;
        }
    }

    function encrypt($string,$isBase64 = false) {

        /*$ivArray = array(0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF);
        $iv = null;
        foreach ($ivArray as $element)
            $iv .= chr($element);*/
        $size = mcrypt_get_block_size( MCRYPT_DES, MCRYPT_MODE_CBC);
        $string = $this->pkcs5Pad ( $string, $size );

        $data = mcrypt_encrypt(MCRYPT_DES, $this->key, $string, MCRYPT_MODE_CBC, $this->key);
        if($isBase64 == true){
            $data = base64_encode($data);
        }else{
            $data = bin2hex($data);
        }
        return $data;
    }

    //暂时不用，有问题
    function decrypt($string) {

        $ivArray = array(0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF);
        $iv = null;
        foreach ($ivArray as $element)
            $iv .= chr($element);

        $string = base64_decode($string);
        //echo("****");
        //echo($string);
        //echo("****");
        $result =  mcrypt_decrypt(MCRYPT_DES, $this->key, $string, MCRYPT_MODE_CBC, $iv);
        $result = $this->pkcs5Unpad( $result );

        return $result;
    }


    function pkcs5Pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen ( $text ) % $blocksize);
        return $text . str_repeat ( chr ( $pad ), $pad );
    }

    function pkcs5Unpad($text)
    {
        $pad = ord ( $text {strlen ( $text ) - 1} );
        if ($pad > strlen ( $text ))
            return false;
        if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
            return false;
        return substr ( $text, 0, - 1 * $pad );
    }

}