<?php

abstract class Aitoc_Aitvbulletin_Model_Notification_Abstract extends Mage_Core_Model_Abstract
{
    const SERVICE_URL = 'http://aitoc.com/api/xmlrpc';
    
    protected $_cacheKey = '';
    protected $_serviceMethod = '';
    
    /**
     * @return Aitoc_Aitvbulletin_Model_Notification_Abstract
     */
    public function loadData()
    {
        if (!$this->_cacheKey OR !$this->_serviceMethod)
        {
            return $this;
        }
        
        $data = Mage::app()->loadCache($this->_cacheKey);
        if ($data)
        {
            $this->setData(unserialize($data));
        }
        else
        {
            $service = Mage::getModel('aitvbulletin/notification_service');
            /* @var $service Aitoc_Aitvbulletin_Model_Notification_Service */
            try
            {
                $service
                    ->setServiceUrl(self::SERVICE_URL)
                    ->connect();
                
                $aData = array();
                
                /**
                 * Check if it's allowed sending usage statistics
                 * @see Aitoc_Aitvbulletin_Model_Config_SourceForum::toOptionArray()
                 */
                if (2 == Mage::getStoreConfig('aitvbulletin/forum/enabled'))
                {
                    $collection = Mage::getModel('customer/customer')->getCollection();
                    /* @var $collection Mage_Customer_Model_Entity_Customer_Collection */
                    $collection->addAttributeToFilter('aitvbulletin_user_id', array('neq' => 0));
                    $aData = array(
                        'host'    => Mage::getBaseUrl(),
                        'version' => Mage::getVersion(),
                        'users'   => $collection->getSize(),
                    );
                }
                $this->setData($service->{$this->_serviceMethod}($aData));
                
                $service->disconnect();
            }
            catch (Exception $e)
            {
                /*
                $this->setData(array(
                    array(
                        'title'   => 'Exception',
                        'content' => 'Exception: '.$e->getMessage(), 
                    )
                ));
                */
                $this->setData(array());
            }
            $this->saveData();
            $this->saveCache($this->getData(), $this->_cacheKey);
        }
        
        return $this;
    }
    
    /**
     * @return Aitoc_Aitvbulletin_Model_Notification_Abstract
     */
    public function saveData()
    {
        return $this;
    }
    
    public function saveCache()
    {
        if ($this->_cacheKey)
        {
            Mage::app()->saveCache(serialize($this->getData()), $this->_cacheKey, array(), 3600*24);
        }
        
        return $this;
    }
    
}