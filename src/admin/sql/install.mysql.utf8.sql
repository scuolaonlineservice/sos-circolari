CREATE TABLE IF NOT EXISTS sos_azioni_utente (
	id INT NOT NULL,
	azione VARCHAR(255) NOT NULL,

	PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sos_circolari (
	id INT NOT NULL AUTO_INCREMENT,
	numero INT UNIQUE,
	oggetto TEXT NOT NULL,
	testo TEXT NOT NULL,
	autore INT NOT NULL,
	bozza TINYINT NOT NULL,
	data_pubblicazione DATE,
--	data_fine_interazione DATE NOT NULL,
	anno_scolastico VARCHAR(10) NOT NULL,
	azioni_utente INT NOT NULL,
	protocollo VARCHAR(255),
	privata TINYINT NOT NULL,
	luogo VARCHAR(255) NOT NULL,

	FOREIGN KEY (autore) REFERENCES j_users (id) ON DELETE CASCADE, -- TODO: sostituire con nome scuola al posto di j_table
	FOREIGN KEY (azioni_utente) REFERENCES sos_azioni_utente (id) ON DELETE CASCADE,
	PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sos_utenti_destinatari (
	id_utente INT NOT NULL,
	id_circolare INT NOT NULL,

	FOREIGN KEY (id_circolare) REFERENCES sos_circolari(id) ON DELETE CASCADE,
	FOREIGN KEY (id_utente) REFERENCES j_users(id) ON DELETE CASCADE, -- TODO: sostituire con nome scuola al posto di j_table
	PRIMARY KEY (id_utente, id_circolare)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sos_gruppi_destinatari (
	id_gruppo INT(10) UNSIGNED NOT NULL,
	id_circolare INT NOT NULL,

	FOREIGN KEY (id_circolare) REFERENCES sos_circolari(id) ON DELETE CASCADE,
	FOREIGN KEY (id_gruppo) REFERENCES j_usergroups(id)  ON DELETE CASCADE, -- TODO: sostituire con nome scuola al posto di j_table
	PRIMARY KEY (id_gruppo, id_circolare)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sos_allegati (
	id INT NOT NULL AUTO_INCREMENT,
	nome TEXT NOT NULL,
	url TEXT NOT NULL,

	PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sos_circolari_allegati (
	id_allegato INT NOT NULL,
	id_circolare INT NOT NULL,

	FOREIGN KEY (id_circolare) REFERENCES sos_circolari(id) ON DELETE CASCADE,
	FOREIGN KEY (id_allegato) REFERENCES sos_allegati(id) ON DELETE CASCADE,
	PRIMARY KEY (id_allegato, id_circolare)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sos_risposte (
	id INT NOT NULL,
	id_circolare INT NOT NULL,
	id_utente INT NOT NULL,
	id_azione_utente INT NOT NULL,
	azione VARCHAR(255) NOT NULL,
	data_risposta DATE NOT NULL,

	FOREIGN KEY (id_circolare) REFERENCES sos_circolari(id),
	FOREIGN KEY (id_azione_utente) REFERENCES sos_azioni_utente(id),
	FOREIGN KEY (id_utente) REFERENCES j_users(id), -- TODO: sostituire con nome scuola al posto di j_table

	PRIMARY KEY (id, id_circolare)
) ENGINE=InnoDB;
