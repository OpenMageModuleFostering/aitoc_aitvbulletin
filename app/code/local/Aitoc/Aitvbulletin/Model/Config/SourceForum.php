<?php
/**
 * @copyright  Copyright (c) 2009 AITOC, Inc. 
 */

class Aitoc_Aitvbulletin_Model_Config_SourceForum
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 0, 
                'label' => Mage::helper('adminhtml')->__('No'),
            ),
            array(
                'value' => 2, 
                'label' => Mage::helper('adminhtml')->__('Yes') 
                         . Mage::helper('aitvbulletin')->__(' (allow sending usage statistics)'),
            ),
            array(
                'value' => 1, 
                'label' => Mage::helper('adminhtml')->__('Yes') 
                         . Mage::helper('aitvbulletin')->__(' (anonymous usage)')
            ),
        );
    }
}