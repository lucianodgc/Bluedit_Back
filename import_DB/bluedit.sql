-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-06-2026 a las 15:12:19
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bluedit`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `creation_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `creation_date`) VALUES
(1, 2, 1, 'awdawd', '2026-06-17 00:37:59'),
(2, 2, 1, 'bkjbkbnhjk', '2026-06-17 00:38:43'),
(3, 1, 1, 'ssefsef', '2026-06-17 16:49:31'),
(4, 4, 1, 'sefsef', '2026-06-17 16:50:01'),
(6, 4, 1, 'awdawd', '2026-06-17 17:01:31'),
(7, 2, 1, 'sefefs', '2026-06-17 17:02:21'),
(9, 7, NULL, 'dgsrger', '2026-06-19 20:54:40'),
(12, 3, 1, 'hola', '2026-06-22 13:35:59'),
(13, 7, 1, 'hola', '2026-06-22 13:40:48'),
(24, 6, 1, 'holaaa', '2026-06-22 14:12:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` enum('text','multimedia') NOT NULL,
  `votes_count` int(11) DEFAULT 0,
  `comments_count` int(11) DEFAULT 0,
  `creation_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `content`, `type`, `votes_count`, `comments_count`, `creation_date`) VALUES
(1, 1, 'Bienvenido a Bluedit', 'Este es un post de prueba.', 'text', 0, 1, '2026-06-17 00:37:41'),
(2, 1, 'Reglas del sitio', 'Sé amable con los demás.', 'text', 0, 3, '2026-06-17 00:37:41'),
(3, 2, 'Aprendiendo Angular', 'Angular es increíble!', 'text', 1, 1, '2026-06-17 00:37:41'),
(4, 1, 'sefsef', 'sefsefsefsef', 'text', 0, 2, '2026-06-17 16:49:55'),
(6, NULL, 'Hola nuevo post', 'sdsdawsdawd', 'text', 0, 1, '2026-06-19 20:54:06'),
(7, NULL, 'video', 'uploads/post_6a35906119bae_1781895265.mp4', 'multimedia', -1, 2, '2026-06-19 20:54:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `avatar_url`, `created_at`, `location`, `birth_date`, `gender`) VALUES
(1, 'admin', 'admin@bluedit.com', '$2y$10$PzFUvaAKiiBH8FliQTCQ3uqHXUXNiNKA0k08Hx6.VWgoWC4oQg.7C', 'uploads/avatar_1_1782159608.jpg', '2026-06-17', 'Argentina', '1212-12-12', 'male'),
(2, 'juan_dev', 'juan@test.com', '$2y$10$qm.wspaqFjmugrzzd0yFrOEPqKu84/qI2tlYzU/do6UR8RzGjMKau', NULL, '2026-06-17', NULL, NULL, NULL),
(5, 'xd1', 'admin1@bluedit.com', '$2y$10$3xJNFPr2zZ01kHAgFnldXOZSx3g3CBdvC0vsXio/DiYpy/GiNzXgG', NULL, '2026-06-19', NULL, NULL, NULL),
(6, 'admin2', 'admin2@bluedit.com', '$2y$10$3ZSsMT6FlgJpsfJXpdFvneoi/iU5m/peXB2Ok/wmi6k8UlsHv7c3m', NULL, '2026-06-24', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) NOT NULL,
  `vote_type` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `votes`
--

INSERT INTO `votes` (`id`, `user_id`, `post_id`, `vote_type`) VALUES
(1, 1, 3, 1),
(5, 1, 7, -1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comments_posts` (`post_id`),
  ADD KEY `fk_comments_users` (`user_id`);

--
-- Indices de la tabla `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_posts_users` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`post_id`) USING BTREE,
  ADD KEY `fk_votes_posts` (`post_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `fk_votes_posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_votes_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
