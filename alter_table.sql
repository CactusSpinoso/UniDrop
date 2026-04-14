-- Aggiunge i campi per la conferma account e il recupero password alla tabella users

ALTER TABLE `users`
  ADD COLUMN `isActive` TINYINT(4) NOT NULL DEFAULT 0,
  ADD COLUMN `codiceAttivazione` VARCHAR(6) DEFAULT NULL,
  ADD COLUMN `dataInvioCodiceAttivazione` DATETIME DEFAULT NULL,
  ADD COLUMN `tokenRecupero` VARCHAR(32) DEFAULT NULL,
  ADD COLUMN `dataInvioTokenRecupero` DATETIME DEFAULT NULL;
