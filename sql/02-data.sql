SET NAMES utf8;
SET time_zone = '+00:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

-- Données de test : utilisateursd
INSERT INTO `user` (id, email, password, est_superadmin) VALUES
('550e8400-e29b-41d4-a716-446655440000', 'admin@lachaudiere.fr', '$2y$12$JfYO3iSLyZrGN7vh0NToFuESVLEXYxyFAOYLTKoVZgWOl7b6B5wSi', TRUE),
('660e8400-e29b-41d4-a716-446655440000', 'editor@lachaudiere.fr', '$2y$12$JfYO3iSLyZrGN7vh0NToFuESVLEXYxyFAOYLTKoVZgWOl7b6B5wSi', FALSE);

-- Données de test : catégories
INSERT INTO `categorie` (id, libelle, description_md) VALUES
(1, 'Concert', 'Événements musicaux en live.'),
(2, 'Conférence', 'Rencontres autour de thématiques culturelles.'),
(3, 'Exposition', 'Présentations artistiques visuelles.');

-- Données de test : événements
INSERT INTO `evenement` (id, titre, description_md, tarif, date_debut, date_fin, horaire, publie, image_url, categorie_id, cree_par) VALUES
(1, 'Jazz à la Chaudière', 'Un concert de jazz avec les musiciens locaux.', 'Entrée libre', '2025-06-20', NULL, '20:30:00', TRUE, 'rien.jpg', 1, '550e8400-e29b-41d4-a716-446655440000'),
(2, 'Regards sur l’écologie', 'Conférence avec débat sur l’environnement.', '5€', '2025-07-10', NULL, '18:00:00', TRUE, 'rien.jpg', 2, '660e8400-e29b-41d4-a716-446655440000'),
(3, 'Exposition de gravures', 'Artistes locaux exposent leurs œuvres gravées.', 'Gratuit', '2025-06-01', '2025-06-15', NULL, FALSE, 'rien.jpg', 3, '660e8400-e29b-41d4-a716-446655440000');