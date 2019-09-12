<?php
defined('_JEXEC') or die('Restricted access');

/*
 * Joomla non consente di chiamare le view utilizzando nomi scritti in
 * "camel case", pertanto il nome di questa view è tutto minuscolo
 */

class SosCircolariViewNewCircolare extends JViewLegacy
{
    function display($tpl = null)
    {
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }

        $this->addToolBar();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        //JToolBarHelper::title(JText::_('SOS Circolari'));
        //JToolBarHelper::custom('newcircolare.test');
        JToolBarHelper::Title('SOS Circolari');
        JToolBarHelper::apply('newcircolare.save');
        JToolBarHelper::save('circolari.saveAndClose');
        JToolBarHelper::save2new('circolari.saveAndNew');
        JToolBarHelper::divider();
        JToolBarHelper::divider();
        JToolBarHelper::cancel('circolari.goToHome');

        if (isset($_SESSION["Circolare"])){
            switch ($_SESSION["Circolare"]){
                case 'Bozza':
                    JFactory::getApplication()->enqueueMessage('Bozza salvata con successo');
                    break;
                case 'Pubblicata':
                    JFactory::getApplication()->enqueueMessage('Circolare pubblicata con successo');
                    break;
            }
            unset($_SESSION["Circolare"]);
        }
        if (isset($_SESSION["ErrorPublish"])){
            JError::raiseWarning( 100, $_SESSION["ErrorPublish"]);
            unset($_SESSION["ErrorPublish"]);
        }
    }
}