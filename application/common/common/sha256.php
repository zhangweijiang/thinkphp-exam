<?php
/*
 * SHA-256 Implementation for PHP
 *
 * Author: Perry McGee (pmcgee@nanolink.ca)
 *
 * Copyright (C) 2006,2007,2008 Nanolink Solutions
 *
 * Date: December 13th, 2007
 * Updated: May 10th, 2008
 *
 *  THIS SOFTWARE IS PROVIDED BY AUTHOR(S) AND CONTRIBUTORS ``AS IS'' AND
 *  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 *  IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 *  ARE DISCLAIMED.  IN NO EVENT SHALL AUTHOR(S) OR CONTRIBUTORS BE LIABLE
 *  FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 *  DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 *  OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 *  HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 *  LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 *  OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 *  SUCH DAMAGE.
 *
 * Include: require_once("[path/]sha256.inc.php");
 *   Usage: $shaStr = sha256(string $str[, bool ignore_function_check = false]);
 *
 *   - ignore_function_check will skip the check to use php's hash() function.
 *
 *
 * Reference: http://csrc.nist.gov/groups/ST/toolkit/secure_hashing.html
 *
 * PHP 5.1.2+ have hash() built in by default: $hashstr = hash("sha256", $string)
 *
 * 2008-05-10: Moved all helper functions into a class.  API access unchanged.
 */
if (!defined('__PHP_SHA256_NANO_'))
{
    define('__PHP_SHA256_NANO_', true);

class shaHelper
{
    function shaHelper()
    {
        // nothing to construct here...
    }

    // Do the SHA-256 Padding routine (make input a multiple of 512 bits)
    function char_pad($str)
    {
        $tmpStr = $str;

        $l = strlen($tmpStr)*8;     // # of bits from input string

        $tmpStr .= "\x80";          // append the "1" bit followed by 7 0's

        $k = (512 - (($l + 8 + 64) % 512)) / 8;   // # of 0 bytes to append
        $k += 4;    // PHP String's will never exceed (2^31)-1, so 1st 32bits of
                    // the 64-bit value representing $l can be all 0's

        for ($x = 0; $x < $k; $x++)
            $tmpStr .= "\0";

        // append the last 32-bits representing the # of bits from input string ($l)
        $tmpStr .= chr((($l>>24) & 0xFF));
        $tmpStr .= chr((($l>>16) & 0xFF));
        $tmpStr .= chr((($l>>8) & 0xFF));
        $tmpStr .= chr(($l & 0xFF));

        return $tmpStr;
    }

    // Here are the bitwise and custom functions as defined in FIPS180-2 Standard
    function addmod2n($x, $y, $n = 4294967296)      // Z = (X + Y) mod 2^32
    {
        $mask = 0x80000000;

        if ($x < 0)
        {
            $x &= 0x7FFFFFFF;
            $x = (float)$x + $mask;
        }

        if ($y < 0)
        {
            $y &= 0x7FFFFFFF;
            $y = (float)$y + $mask;
        }

        $r = $x + $y;

        if ($r >= $n)
        {
            while ($r >= $n)
                $r -= $n;
        }

        return (int)$r;
    }

    // Logical bitwise right shift (PHP default is arithmetic shift)
    function SHR($x, $n)        // x >> n
    {
        if ($n >= 32)       // impose some limits to keep it 32-bit
            return (int)0;

        if ($n <= 0)
            return (int)$x;

        $mask = 0x40000000;

        if ($x < 0)
        {
            $x &= 0x7FFFFFFF;
            $mask = $mask >> ($n-1);
            return ($x >> $n) | $mask;
        }

        return (int)$x >> (int)$n;
    }

    function ROTR($x, $n) { return (int)($this->SHR($x, $n) | ($x << (32-$n))); }
    function Ch($x, $y, $z) { return ($x & $y) ^ ((~$x) & $z); }
    function Maj($x, $y, $z) { return ($x & $y) ^ ($x & $z) ^ ($y & $z); }
    function Sigma0($x) { return (int) ($this->ROTR($x, 2)^$this->ROTR($x, 13)^$this->ROTR($x, 22)); }
    function Sigma1($x) { return (int) ($this->ROTR($x, 6)^$this->ROTR($x, 11)^$this->ROTR($x, 25)); }
    function sigma_0($x) { return (int) ($this->ROTR($x, 7)^$this->ROTR($x, 18)^$this->SHR($x, 3)); }
    function sigma_1($x) { return (int) ($this->ROTR($x, 17)^$this->ROTR($x, 19)^$this->SHR($x, 10)); }

    /*
     * Custom functions to provide PHP support
     */
    // split a byte-string into integer array values
    function int_split($input)
    {
        $l = strlen($input);

        if ($l <= 0)        // right...
            return (int)0;

        if (($l % 4) != 0)  // invalid input
            return false;

        for ($i = 0; $i < $l; $i += 4)
        {
            $int_build  = (ord($input[$i]) << 24);
            $int_build += (ord($input[$i+1]) << 16);
            $int_build += (ord($input[$i+2]) << 8);
            $int_build += (ord($input[$i+3]));

            $result[] = $int_build;
        }

        return $result;
    }
}

    // Compatability with older versions of PHP < 5
    if (!function_exists('str_split'))
    {
        function str_split($string, $split_length = 1)
        {
            $sign = (($split_length < 0) ? -1 : 1);
            $strlen = strlen($string);
            $split_length = abs($split_length);

            if (($split_length == 0) || ($strlen == 0))
            {
                $result = false;
            }
            elseif ($split_length >= $strlen)
            {
                $result[] = $string;
            }
            else
            {
                $length = $split_length;

                for ($i = 0; $i < $strlen; $i++)
                {
                    $i = (($sign < 0) ? $i + $length : $i);
                    $result[] = substr($string, $sign*$i, $length);
                    $i--;
                    $i = (($sign < 0) ? $i : $i + $length);

                    if (($i + $split_length) > ($strlen))
                    {
                        $length = $strlen - ($i + 1);
                    }
                    else
                    {
                        $length = $split_length;
                    }
                }
            }

            return $result;
        }
    }


    /*
     * Main routine called from an application using this include.
     *
     * General usage:
     *   require_once(sha256.inc.php);
     *   $hashstr = sha256("abc");
     *
     * Note:
     * PHP Strings are limitd to (2^31)-1, so it is not worth it to
     * check for input strings > 2^64 as the FIPS180-2 defines.
     */
    function sha256($str, $ig_func = false)
    {
        unset($binStr);     // binary representation of input string
        unset($hexStr);     // 256-bit message digest in readable hex format

        // check for php 5.1.2's internal sha256 function, ignore if ig_func is true
        if ($ig_func == false)
            if (function_exists("hash"))
                return hash("sha256", $str, false);

        /*
         * Use PHP Implementation of SHA-256 if no other library is available
         * - This method is much slower, but adds an additional level of fault tolerance
         */

        $sh = new shaHelper();

        // SHA-256 Constants
        // sequence of sixty-four constant 32-bit words representing the first thirty-two bits
        // of the fractional parts of the cube roots of the first sixtyfour prime numbers.
        $K = array((int)0x428a2f98, (int)0x71374491, (int)0xb5c0fbcf, (int)0xe9b5dba5,
                   (int)0x3956c25b, (int)0x59f111f1, (int)0x923f82a4, (int)0xab1c5ed5,
                   (int)0xd807aa98, (int)0x12835b01, (int)0x243185be, (int)0x550c7dc3,
                   (int)0x72be5d74, (int)0x80deb1fe, (int)0x9bdc06a7, (int)0xc19bf174,
                   (int)0xe49b69c1, (int)0xefbe4786, (int)0x0fc19dc6, (int)0x240ca1cc,
                   (int)0x2de92c6f, (int)0x4a7484aa, (int)0x5cb0a9dc, (int)0x76f988da,
                   (int)0x983e5152, (int)0xa831c66d, (int)0xb00327c8, (int)0xbf597fc7,
                   (int)0xc6e00bf3, (int)0xd5a79147, (int)0x06ca6351, (int)0x14292967,
                   (int)0x27b70a85, (int)0x2e1b2138, (int)0x4d2c6dfc, (int)0x53380d13,
                   (int)0x650a7354, (int)0x766a0abb, (int)0x81c2c92e, (int)0x92722c85,
                   (int)0xa2bfe8a1, (int)0xa81a664b, (int)0xc24b8b70, (int)0xc76c51a3,
                   (int)0xd192e819, (int)0xd6990624, (int)0xf40e3585, (int)0x106aa070,
                   (int)0x19a4c116, (int)0x1e376c08, (int)0x2748774c, (int)0x34b0bcb5,
                   (int)0x391c0cb3, (int)0x4ed8aa4a, (int)0x5b9cca4f, (int)0x682e6ff3,
                   (int)0x748f82ee, (int)0x78a5636f, (int)0x84c87814, (int)0x8cc70208,
                   (int)0x90befffa, (int)0xa4506ceb, (int)0xbef9a3f7, (int)0xc67178f2);

        // Pre-processing: Padding the string
        $binStr = $sh->char_pad($str);

        // Parsing the Padded Message (Break into N 512-bit blocks)
        $M = str_split($binStr, 64);

        // Set the initial hash values
        $h[0] = (int)0x6a09e667;
        $h[1] = (int)0xbb67ae85;
        $h[2] = (int)0x3c6ef372;
        $h[3] = (int)0xa54ff53a;
        $h[4] = (int)0x510e527f;
        $h[5] = (int)0x9b05688c;
        $h[6] = (int)0x1f83d9ab;
        $h[7] = (int)0x5be0cd19;

        // loop through message blocks and compute hash. ( For i=1 to N : )
        for ($i = 0; $i < count($M); $i++)
        {
            // Break input block into 16 32-bit words (message schedule prep)
            $MI = $sh->int_split($M[$i]);

            // Initialize working variables
            $_a = (int)$h[0];
            $_b = (int)$h[1];
            $_c = (int)$h[2];
            $_d = (int)$h[3];
            $_e = (int)$h[4];
            $_f = (int)$h[5];
            $_g = (int)$h[6];
            $_h = (int)$h[7];
            unset($_s0);
            unset($_s1);
            unset($_T1);
            unset($_T2);
            $W = array();

            // Compute the hash and update
            for ($t = 0; $t < 16; $t++)
            {
                // Prepare the first 16 message schedule values as we loop
                $W[$t] = $MI[$t];

                // Compute hash
                $_T1 = $sh->addmod2n($sh->addmod2n($sh->addmod2n($sh->addmod2n($_h, $sh->Sigma1($_e)), $sh->Ch($_e, $_f, $_g)), $K[$t]), $W[$t]);
                $_T2 = $sh->addmod2n($sh->Sigma0($_a), $sh->Maj($_a, $_b, $_c));

                // Update working variables
                $_h = $_g; $_g = $_f; $_f = $_e; $_e = $sh->addmod2n($_d, $_T1);
                $_d = $_c; $_c = $_b; $_b = $_a; $_a = $sh->addmod2n($_T1, $_T2);
            }

            for (; $t < 64; $t++)
            {
                // Continue building the message schedule as we loop
                $_s0 = $W[($t+1)&0x0F];
                $_s0 = $sh->sigma_0($_s0);
                $_s1 = $W[($t+14)&0x0F];
                $_s1 = $sh->sigma_1($_s1);

                $W[$t&0xF] = $sh->addmod2n($sh->addmod2n($sh->addmod2n($W[$t&0xF], $_s0), $_s1), $W[($t+9)&0x0F]);

                // Compute hash
                $_T1 = $sh->addmod2n($sh->addmod2n($sh->addmod2n($sh->addmod2n($_h, $sh->Sigma1($_e)), $sh->Ch($_e, $_f, $_g)), $K[$t]), $W[$t&0xF]);
                $_T2 = $sh->addmod2n($sh->Sigma0($_a), $sh->Maj($_a, $_b, $_c));

                // Update working variables
                $_h = $_g; $_g = $_f; $_f = $_e; $_e = $sh->addmod2n($_d, $_T1);
                $_d = $_c; $_c = $_b; $_b = $_a; $_a = $sh->addmod2n($_T1, $_T2);
            }

            $h[0] = $sh->addmod2n($h[0], $_a);
            $h[1] = $sh->addmod2n($h[1], $_b);
            $h[2] = $sh->addmod2n($h[2], $_c);
            $h[3] = $sh->addmod2n($h[3], $_d);
            $h[4] = $sh->addmod2n($h[4], $_e);
            $h[5] = $sh->addmod2n($h[5], $_f);
            $h[6] = $sh->addmod2n($h[6], $_g);
            $h[7] = $sh->addmod2n($h[7], $_h);
        }

        // Convert the 32-bit words into human readable hexadecimal format.
        $hexStr = sprintf("%08x%08x%08x%08x%08x%08x%08x%08x", $h[0], $h[1], $h[2], $h[3], $h[4], $h[5], $h[6], $h[7]);

        return $hexStr;
    }

} // __PHP_SHA256_NANO_

?>