<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 02.11.15
 * Time: 14:17
 */

namespace Spotlight;

/**
 * Class stdSpotlight
 *
 * helper class: extends the EntityBase with an empty toArray() function
 *
 * it can be used like the php stdClass. But has some bsaic methods that are expected.
 *
 * needed for some toArray() functions in other classes if some properties are undefined
 *
 * @package Spotlight
 */
class stdSpotlight extends EntityBase
{
    /**
     * @return array
     */
    public function toArray()
    {
        return array();
    }
}