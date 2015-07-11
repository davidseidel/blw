<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2006 by David Seidel. All rights reserved.
 *
 * To contact the author write to {@link mailto:seidel.david@googlemail.com David Seidel}
 * The latest version of BlueWonder can be obtained from: {@link http://www.bluewonder-framework.de/}
 *
 * @author David Seidel <seidel.david@googlemail.com>
 * @package blw.lib.PSP
 */

/**
 * this interface marks its implementing class as a storage for values
 */
interface BLW_PSP_ValueHolder {
    /**
     * returns the stored value
     *
     */
    public function getStoredValue(); 
    /**
     * sets the stored value
     *
     * @param mixed $value
     */
    public function setStoredValue($value);
}
?>