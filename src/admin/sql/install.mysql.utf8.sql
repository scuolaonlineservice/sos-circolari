CREATE TABLE IF NOT EXISTS `#__com_sos_azioni_utente` (
	id INT NOT NULL,
	azione VARCHAR(255) NOT NULL,

	PRIMARY KEY (id)
) ENGINE=InnoDB;

INSERT INTO `#__com_sos_azioni_utente`
VALUES (1, "Nessuna");

INSERT INTO `#__com_sos_azioni_utente`
VALUES (2, "Presa visione");

INSERT INTO `#__com_sos_azioni_utente`
VALUES (3, "Adesione, Non adesione");

INSERT INTO `#__com_sos_azioni_utente`
VALUES (4, "Presa visione, Adesione, Non adesione");

CREATE TABLE IF NOT EXISTS `#__com_sos_circolari` (
	id INT NOT NULL AUTO_INCREMENT,
	numero INT,
	oggetto TEXT NOT NULL,
	testo TEXT NOT NULL,
	autore INT NOT NULL,
	bozza TINYINT NOT NULL,
	data_pubblicazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	data_fine_interazione DATE,
	anno_scolastico VARCHAR(10) NOT NULL,
	azioni_utente INT NOT NULL,
	protocollo VARCHAR(255),
	privata TINYINT NOT NULL,
	luogo VARCHAR(255) NOT NULL,

	FOREIGN KEY (autore) REFERENCES `#__users` (id) ON DELETE CASCADE,
	FOREIGN KEY (azioni_utente) REFERENCES `#__com_sos_azioni_utente` (id) ON DELETE CASCADE,
	PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `#__com_sos_utenti_destinatari` (
	id_utente INT NOT NULL,
	id_circolare INT NOT NULL,

	FOREIGN KEY (id_circolare) REFERENCES `#__com_sos_circolari` (id) ON DELETE CASCADE,
	PRIMARY KEY (id_utente, id_circolare)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `#__com_sos_gruppi_destinatari` (
	id_gruppo INT(10) UNSIGNED NOT NULL,
	id_circolare INT NOT NULL,

	FOREIGN KEY (id_circolare) REFERENCES `#__com_sos_circolari` (id) ON DELETE CASCADE,
	PRIMARY KEY (id_gruppo, id_circolare)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `#__com_sos_allegati` (
	id INT NOT NULL AUTO_INCREMENT,
	id_circolare INT NOT NULL,
	nome TEXT NOT NULL,

	PRIMARY KEY (id),
	FOREIGN KEY (id_circolare) REFERENCES `#__com_sos_circolari` (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `#__com_sos_circolari_risposte` (
	id INT NOT NULL,
	id_circolare INT NOT NULL,
	id_utente INT NOT NULL,
	id_azione_utente INT NOT NULL,
	azione VARCHAR(255) NOT NULL,
	data_risposta TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,

	FOREIGN KEY (id_circolare) REFERENCES `#__com_sos_circolari` (id),
	FOREIGN KEY (id_azione_utente) REFERENCES `#__com_sos_azioni_utente` (id),

	PRIMARY KEY (id, id_circolare)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `#__com_sos_circolari_gruppi_esclusi` (
	id INT(10) UNSIGNED NOT NULL PRIMARY KEY,
	FOREIGN KEY (id) REFERENCES `#__usergroups` (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `#__com_sos_configurazioni` (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	chiave VARCHAR(255) NOT NULL,
	valore VARCHAR (255) NOT NULL
) ENGINE=InnoDB;