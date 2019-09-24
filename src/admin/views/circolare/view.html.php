<?php
defined('_JEXEC') or die('Restricted access');

class SosCircolariViewCircolare extends JViewLegacy
{
    function display($tpl = null) {
        $id = JFactory::getApplication()->input->get->get('id', 0);
        $circolare = $this->getModel()->getCircolare($id);

        $this->assignRef("circolare", $circolare);

        $this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }

        $this->addToolBar();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('SOS Circolari'));
    }
}