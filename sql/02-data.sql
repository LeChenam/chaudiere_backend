SET NAMES utf8;
SET time_zone = '+00:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

-- Données de test : utilisateursd
INSERT INTO `user` (id, email, password, est_superadmin) VALUES
('550e8400-e29b-41d4-a716-446655440000', 'admin@lachaudiere.fr', '$2b$12$uvsw08tJnvRDYZk9jbMCg.87xKAiWqwqe1KD/niZ49zXyAul3jUAu', TRUE),
('660e8400-e29b-41d4-a716-446655440000', 'editor@lachaudiere.fr', '$2b$12$nF76ZED0izG0LTKgSrFs5e0eT0rrSm0mXlPpV6VsHx77vp6DqLmEi', FALSE);

-- Données de test : catégories
INSERT INTO `categorie` (id, libelle, description_md) VALUES
(1, 'concert', '### Concerts\n\nÉvénements musicaux en **live** avec artistes et groupes.'),
(2, 'conférence', '### Conférences\n\nRencontres autour de **thématiques culturelles**, sociales ou scientifiques.'),
(3, 'exposition', '### Expositions\n\nPrésentations **artistiques** visuelles dans divers lieux.'),
(4, 'spectacle', '### Spectacles\n\nPerformances **vivantes** : théâtre, danse, humour.');

-- Données de test : événements
INSERT INTO `evenement` (id, titre, description_md, tarif, date_debut, date_fin, horaire, publie, image, categorie_id, cree_par) VALUES
(1, 'Événement culturel 1', 'Description de l''événement *1* en **markdown**.\n\nAvec des détails intéressants.', 'Entrée libre', '2025-06-17', '2025-06-24', NULL, TRUE, NULL, 3, '550e8400-e29b-41d4-a716-446655440000'),
(2, 'Événement culturel 2', 'Description de l''événement *2* en **markdown**.\n\nAvec des détails intéressants.', '5€', '2025-06-19', NULL, '18:00:00', TRUE, NULL, 2, '660e8400-e29b-41d4-a716-446655440000'),
(3, 'Événement culturel 3', 'Description de l''événement *3* en **markdown**.\n\nAvec des détails intéressants.', 'Prix libre', '2025-06-29', NULL, '19:30:00', FALSE, NULL, 3, '550e8400-e29b-41d4-a716-446655440000'),
(4, 'Événement culturel 4', 'Description de l''événement *4* en **markdown**.\n\nAvec des détails intéressants.', '10€', '2025-06-24', NULL, '20:30:00', TRUE, NULL, 2, '660e8400-e29b-41d4-a716-446655440000'),
(5, 'Événement culturel 5', 'Description de l''événement *5* en **markdown**.\n\nAvec des détails intéressants.', 'Gratuit', '2025-06-13', NULL, '20:30:00', TRUE, NULL, 1, '550e8400-e29b-41d4-a716-446655440000'),
(6, 'Événement culturel 6', 'Description de l''événement *6* en **markdown**.\n\nAvec des détails intéressants.', '12€', '2025-06-11', '2025-06-12', '19:30:00', TRUE, NULL, 4, '660e8400-e29b-41d4-a716-446655440000'),
(7, 'Événement culturel 7', 'Description de l''événement *7* en **markdown**.\n\nAvec des détails intéressants.', 'Prix libre', '2025-06-07', NULL, NULL, FALSE, NULL, 3, '550e8400-e29b-41d4-a716-446655440000'),
(8, 'Événement culturel 8', 'Description de l''événement *8* en **markdown**.\n\nAvec des détails intéressants.', 'Gratuit', '2025-06-10', NULL, '20:00:00', TRUE, NULL, 1, '660e8400-e29b-41d4-a716-446655440000'),
(9, 'Événement culturel 9', 'Description de l''événement *9* en **markdown**.\n\nAvec des détails intéressants.', '5€', '2025-06-04', NULL, '18:00:00', TRUE, NULL, 2, '550e8400-e29b-41d4-a716-446655440000'),
(10, 'Événement culturel 10', 'Description de l''événement *10* en **markdown**.\n\nAvec des détails intéressants.', 'Entrée libre', '2025-06-03', '2025-07-06', NULL, FALSE, NULL, 1, '660e8400-e29b-41d4-a716-446655440000'),
(11, 'Événement culturel 11', 'Description de l''événement *11* en **markdown**.\n\nAvec des détails intéressants.', 'Prix libre', '2025-07-08', NULL, '20:30:00', TRUE, NULL, 3, '550e8400-e29b-41d4-a716-446655440000'),
(12, 'Événement culturel 12', 'Description de l''événement *12* en **markdown**.\n\nAvec des détails intéressants.', 'Gratuit', '2025-06-09', '2025-08-10', NULL, FALSE, NULL, 4, '660e8400-e29b-41d4-a716-446655440000'),
(13, 'Événement culturel 13', 'Description de l''événement *13* en **markdown**.\n\nAvec des détails intéressants.', '5€', '2025-07-11', NULL, '19:00:00', TRUE, NULL, 2, '550e8400-e29b-41d4-a716-446655440000'),
(14, 'Événement culturel 14', 'Description de l''événement *14* en **markdown**.\n\nAvec des détails intéressants.', '10€', '2025-11-13', NULL, '20:00:00', TRUE, NULL, 1, '660e8400-e29b-41d4-a716-446655440000'),
(15, 'Événement culturel 15', 'Description de l''événement *15* en **markdown**.\n\nAvec des détails intéressants.', 'Entrée libre', '2025-12-14', NULL, '18:30:00', TRUE, NULL, 4, '550e8400-e29b-41d4-a716-446655440000'),
(16, 'Événement culturel 16', 'Description de l''événement *16* en **markdown**.\n\nAvec des détails intéressants.', '12€', '2025-07-15', NULL, NULL, FALSE, NULL, 1, '660e8400-e29b-41d4-a716-446655440000'),
(17, 'Événement culturel 17', 'Description de l''événement *17* en **markdown**.\n\nAvec des détails intéressants.', 'Gratuit', '2025-07-16', NULL, '19:30:00', TRUE, NULL, 3, '550e8400-e29b-41d4-a716-446655440000'),
(18, 'Événement culturel 18', 'Description de l''événement *18* en **markdown**.\n\nAvec des détails intéressants.', '5€', '2025-08-18', NULL, '20:30:00', TRUE, NULL, 2, '660e8400-e29b-41d4-a716-446655440000'),
(19, 'Événement culturel 19', 'Description de l''événement *19* en **markdown**.\n\nAvec des détails intéressants.', 'Prix libre', '2025-08-20', NULL, '20:00:00', FALSE, NULL, 4, '550e8400-e29b-41d4-a716-446655440000'),
(20, 'Événement culturel 20', 'Description de l''événement *20* en **markdown**.\n\nAvec des détails intéressants.', '10€', '2025-05-21', NULL, NULL, TRUE, NULL, 1, '660e8400-e29b-41d4-a716-446655440000'),
(21, 'Événement culturel 21', 'Description de l''événement *21* en **markdown**.\n\nAvec des détails intéressants.', 'Gratuit', '2025-05-22', NULL, '20:30:00', TRUE, NULL, 3, '550e8400-e29b-41d4-a716-446655440000'),
(22, 'Événement culturel 22', 'Description de l''événement *22* en **markdown**.\n\nAvec des détails intéressants.', '5€', '2025-01-23', NULL, '18:00:00', TRUE, NULL, 2, '660e8400-e29b-41d4-a716-446655440000'),
(23, 'Événement culturel 23', 'Description de l''événement *23* en **markdown**.\n\nAvec des détails intéressants.', 'Entrée libre', '2025-02-25', NULL, NULL, TRUE, NULL, 4, '550e8400-e29b-41d4-a716-446655440000'),
(24, 'Événement culturel 24', 'Description de l''événement *24* en **markdown**.\n\nAvec des détails intéressants.', '12€', '2025-02-26', NULL, '19:00:00', FALSE, NULL, 1, '660e8400-e29b-41d4-a716-446655440000'),
(25, 'Événement culturel 25', 'Description de l''événement *25* en **markdown**.\n\nAvec des détails intéressants.', 'Gratuit', '2025-05-27', NULL, '20:30:00', TRUE, NULL, 3, '550e8400-e29b-41d4-a716-446655440000'),
(26, 'Événement culturel 26', 'Description de l''événement *26* en **markdown**.\n\nAvec des détails intéressants.', 'Prix libre', '2025-05-28', NULL, '19:00:00', TRUE, NULL, 2, '660e8400-e29b-41d4-a716-446655440000'),
(27, 'Événement culturel 27', 'Description de l''événement *27* en **markdown**.\n\nAvec des détails intéressants.', '10€', '2025-03-29', NULL, NULL, FALSE, NULL, 4, '550e8400-e29b-41d4-a716-446655440000'),
(28, 'Événement culturel 28', 'Description de l''événement *28* en **markdown**.\n\nAvec des détails intéressants.', 'Entrée libre', '2025-04-30', NULL, '20:00:00', TRUE, NULL, 1, '660e8400-e29b-41d4-a716-446655440000'),
(29, 'Événement culturel 29', 'Description de l''événement *29* en **markdown**.\n\nAvec des détails intéressants.', 'Gratuit', '2025-06-30', NULL, '19:00:00', TRUE, NULL, 3, '550e8400-e29b-41d4-a716-446655440000'),
(30, 'Événement culturel 30', 'Description de l''événement *30* en **markdown**.\n\nAvec des détails intéressants.', '5€', '2025-06-30', NULL, '21:00:00', TRUE, NULL, 2, '660e8400-e29b-41d4-a716-446655440000');