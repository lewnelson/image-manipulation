<?php

/**
 * This file is part of the LewisNelson/ImageManipulation package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ImageManipulation;

/**
 * Build a collection of ImageManager objects through build collection.
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class ImageManipulationFactory
{
    /**
     * Creates a collection of ImageManager objects for passed image(s)
     *
     * @param mixed string array $images
     *
     * @return mixed (object)ImageManager array, if array is passed then array is returned
     * with same keys. If string is passed then single object is returned.
     */
    public static function buildCollection($images)
    {
        if(is_array($images)) {
            foreach($images as $index => $image) {
                $file_info = new \SplFileInfo($image);
                $collection[$index] = self::getInstance($file_info);
            }
        } else {
            $file_info = new \SplFileInfo($images);
            $collection = self::getInstance($file_info);
        }
        return $collection;
    }

    /**
     * Instantiate the correct ImageManager class for the image
     *
     * @param SplFileInfo $file_info
     *
     * @return ImageManager instance of class extending ImageManager
     */
    private static function getInstance($file_info)
    {
        $valid_extensions = array(
                'png',
                'jpg',
                'jpeg',
                'gif'
            );

        $extension = strtolower($file_info->getExtension());
        if(!in_array($extension, $valid_extensions)) {
            throw new \Exception('Invalid extension `'.$extension.'` provided for ImageManipulation.');
        }

        if($extension === 'jpg') {
            $extension = 'jpeg';
        }

        $extension = ucfirst($extension);
        $namespace = 'ImageManipulation\\Types\\'.$extension.'Manipulation';
        return new $namespace($file_info);
    }
}

?>