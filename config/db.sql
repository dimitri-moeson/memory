-- MySQL Dump
-- --------------------------------------------------------

--
-- Structure de la table `ladder`
--

CREATE TABLE `ladder` (
  `id` int(11) NOT NULL,
  `timer` int(11) NOT NULL,
  `try` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `date_delete` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour la table `ladder`
--
ALTER TABLE `ladder`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour la table `ladder`
--
ALTER TABLE `ladder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;