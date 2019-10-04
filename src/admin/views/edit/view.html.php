<?php
defined('_JEXEC') or die('Restricted access');

use \Joomla\CMS\MVC\View\HtmlView;

class SosCircolariViewEdit extends HtmlView {
    function display($tpl = null) {
        $id = JFactory::getApplication()->input->get->get('id', 0);

        if ($id != 0) {
            $circolare = $this->getModel()->getCircolare($id);

            $this->assignRef("circolare", $circolare);
        }

        $numero = $this->getModel()->getNumero();
        $azioniUtente = $this->getModel()->getAzioniUtente();
        $gruppiDestinatari = $this->getModel()->getGroups();
        $selectedGroups = $this->getModel()->getSelectedGroups($id);
        $allegati = $this->getModel()->getAllegati($id);

        $this->assignRef ("numero", $numero);
        $this->assignRef ("azioniUtente", $azioniUtente);
        $this->assignRef ("gruppiDestinatari", $gruppiDestinatari);
        $this->assignRef ("selectedGroups", $selectedGroups);
        $this->assignRef ("allegati", $allegati);

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }

        $this->addToolBar();

        parent::display($tpl);
    }

    protected function addToolBar() {
        JToolBarHelper::Title('SOS Circolari');
        JToolBarHelper::apply('edit.save');
        JToolBarHelper::cancel('edit.cancel');
    }
}