<?php
/**
 * @copyright  Copyright (c) 2009 AITOC, Inc. 
 */

class Aitoc_Aitvbulletin_Model_Observer extends Mage_Core_Model_Abstract 
{
    /**
     * frontend: customer_logout
     *
     * @param Varien_Event_Observer $observer
     */
    public function customerLogout(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('aitvbulletin')->isModuleEnabled()) return;
        
        $forumUserId = $observer->getCustomer()->getAitvbulletinUserId();
        if ($forumUserId)
        {
            $aVars = array(
                'do' => 'logout',
                'userid'  => $forumUserId,
            );
            $forumUrl = Mage::getModel('aitvbulletin_vbulletin/user')->getResource()
                ->getProxyUrl($aVars, $aVars['do'].$aVars['userid']);
            
            Mage::getSingleton('aitvbulletin/session')->setLogoutUrl($forumUrl);
        }
        else 
        {
            Mage::getSingleton('aitvbulletin/session')->setLogoutUrl(null);
        }
    }
    
    /**
     * frontend: customer_load_after
     *
     * @param Varien_Event_Observer $observer
     */
    public function addVbuserInfo(Varien_Event_Observer $observer)
    {
        $customer = $observer->getCustomer();
        /* @var $customer Mage_Customer_Model_Customer */
        $forumUserId = $customer->getAitvbulletinUserId();
        if ($forumUserId AND Mage::helper('aitvbulletin')->isModuleEnabled())
        {
            $vbUser = Mage::getModel('aitvbulletin_vbulletin/user')->load($forumUserId);
            /* @var $vbUser Aitoc_Aitvbulletin_Model_Vbulletin_User */
            if ($vbUser->getId())
            {
                $vbUser->setCustomer($customer);
                $customer->setAitvbulletinUser($vbUser);
            }
            else 
            {
                $customer->setAitvbulletinUserId(0);
            }
        }
    }
    
}