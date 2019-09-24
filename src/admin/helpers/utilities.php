<?php
defined('_JEXEC') or die;

class Utilities {
    public static function getAnnoScolastico() {
        $month = idate("m");
        $dacademic_year = idate("d");
        if ($dacademic_year < 31 && $month < 8) {
            return "" . (idate("Y") -1) . "/" . idate("Y");
        } else {
            return "" . idate("Y") . "/" . (idate("Y") + 1 );
        }
    }

    public static function rearrangeArray( $arr ){
        foreach( $arr as $key => $all ){
            foreach( $all as $i => $val ){
                $new[$i][$key] = $val;
            }
        }
        return $new;
    }

    public function flat(array $elems, $key) {
        for ($i = 0; $i < count($elems); $i++) { $flat[$i] = $elems[$i][$key]; }
        return $flat;
    }

    public static function getAzioneIdFromDescription($description) {
        $db	= JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("id")
            ->from("#__com_sos_azioni_utente")
            ->where("azione=" . $db->quote($description));

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result->id;
    }

    public function getNumero() {
        $db	= JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("MAX(numero) as max")
            ->from("#__com_sos_circolari");

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result->max ? $result->max + 1 : 1;
    }

    public static function getDateFromTimestamp($timestamp, $timezone) {
        return gmdate("Y/m/j H:i:s", $timestamp + 3600 * ($timezone + date("I")));
    }

    public function getGroups() {
        $db	= JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("id, title")
            ->from("j_usergroups");

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }
}