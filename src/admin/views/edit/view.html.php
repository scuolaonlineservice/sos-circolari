<?php
defined('_JEXEC') or die('Restricted access');

/*
 * Joomla non consente di chiamare le view utilizzando nomi scritti in
 * "camel case", pertanto il nome di questa view è tutto minuscolo
 */

class SosCircolariViewEdit extends JViewLegacy
{
    function display($tpl = null)
    {
        $id = JFactory::getApplication()->input->get->get('id', 0);

        if ($id != 0) {
            $circolare = $this->getModel()->getCircolare($id);

            $this->assignRef("circolare", $circolare);
        }

        $numero = $this->getModel()->getNumero();
        $azioniUtente = $this->getModel()->getAzioniUtente();
        $gruppiDestinatari = $this->getModel()->getGroups();
        $allegati = $this->getModel()->getAllegati($id);

        $this->assignRef ("numero", $numero);
        $this->assignRef ("azioniUtente", $azioniUtente);
        $this->assignRef ("gruppiDestinatari", $gruppiDestinatari);
        $this->assignRef ("allegati", $allegati);

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }

        $this->addToolBar();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        JToolBarHelper::Title('SOS Circolari');
        JToolBarHelper::apply('edit.save');
        JToolBarHelper::cancel('edit.cancel');
    }
}