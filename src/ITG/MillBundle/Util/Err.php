<?php

namespace ITG\MillBundle\Util;

/**
 * Class contains err definitions. This class should only be used for generic error codes.<br>
 * Also it is a good practice to namespace your errors using this scheme:<br>
 * <b>YYYXXX</b>, where <b>YYY</b> is bundle number and <b>XXX</b> is error number. 
 * MillBundle has a bundle number of 1, while AppBundle has a bundle number of 0, so errors < 1000 will be from 
 * AppBundle (business login specific) and errors 1000 <= err < 2000 will be MillBundle.<br>
 * <br>
 * Usually errors ranging 0 < err < 100 will be general bundle errors, with 0 being a general undefined bundle error
 */
class Err
{
    /** Generic bundle error */
    const BUNDLE                       = 1000; // 1000 is MillBundle
}