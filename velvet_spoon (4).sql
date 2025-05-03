-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 03, 2025 at 04:01 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `velvet_spoon`
--

-- --------------------------------------------------------

--
-- Table structure for table `board_recipes`
--

CREATE TABLE `board_recipes` (
  `id` int(11) NOT NULL,
  `board_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `board_recipes`
--

INSERT INTO `board_recipes` (`id`, `board_id`, `recipe_id`, `created_at`) VALUES
(1, 1, 16, '2025-05-01 03:26:01'),
(2, 2, 1, '2025-05-01 03:26:15'),
(3, 2, 3, '2025-05-01 03:26:22'),
(4, 1, 4, '2025-05-01 03:51:11'),
(5, 2, 11, '2025-05-01 17:50:38'),
(6, 1, 11, '2025-05-01 18:15:05'),
(7, 3, 5, '2025-05-01 18:25:32'),
(8, 2, 17, '2025-05-01 22:48:32'),
(9, 4, 16, '2025-05-02 21:28:24'),
(11, 3, 15, '2025-05-02 23:49:29');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `recipe_id`, `user_id`, `content`, `created_at`) VALUES
(1, 16, 11, 'This taste so good', '2025-05-01 14:15:22'),
(2, 16, 2, 'YESSSSSSSSSSSSSSSS!!!!!!', '2025-05-01 14:17:03'),
(3, 3, 1, 'My kids loved these tacos!', '2025-05-01 14:18:45'),
(4, 1, 11, 'purrr', '2025-05-01 03:52:07'),
(5, 12, 11, 'cant wait to try', '2025-05-01 16:48:55'),
(6, 5, 1, 'yup yup yup', '2025-05-01 18:25:39');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT '0.0',
  `rating_count` int(11) DEFAULT '0',
  `prep_time` int(11) NOT NULL COMMENT 'in minutes',
  `cook_time` int(11) NOT NULL COMMENT 'in minutes',
  `servings` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `title`, `image_path`, `rating`, `rating_count`, `prep_time`, `cook_time`, `servings`, `category`, `created_at`) VALUES
(1, 'Harissa Honey Chicken', 'images/harissa-chicken.jpg', '5.0', 218, 15, 30, 4, 'Dinner', '2025-04-30 06:17:12'),
(2, 'Creamy Garlic Parmesan Pasta', 'images/garlic-parmesan-pasta.jpg', '4.8', 187, 10, 15, 4, 'Dinner', '2025-04-30 06:23:30'),
(3, 'Classic Beef Tacos', 'images/beef-tacos.jpg', '4.6', 245, 15, 15, 4, 'Dinner', '2025-04-30 06:23:31'),
(4, 'Classic Banana Bread', 'images/banana-bread.jpg', '4.9', 312, 15, 60, 8, 'Dessert', '2025-04-30 06:23:31'),
(5, 'Authentic Greek Salad', 'images/greek-salad.jpg', '4.7', 178, 15, 0, 4, 'Lunch', '2025-04-30 06:23:31'),
(6, 'Creamy Chicken Alfredo', 'images/chicken-alfredo.jpg', '4.8', 276, 15, 20, 4, 'Dinner', '2025-04-30 06:23:31'),
(7, 'Vegetable Stir Fry', 'images/vegetable-stirfry.jpg', '4.5', 198, 15, 10, 4, 'Vegetarian', '2025-04-30 06:23:31'),
(8, 'Fluffy Buttermilk Pancakes', 'images/buttermilk-pancakes.jpg', '4.8', 287, 10, 15, 4, 'Breakfast', '2025-04-30 06:32:37'),
(9, 'Classic Minestrone Soup', 'images/minestrone.jpg', '4.6', 201, 15, 30, 6, 'Soup', '2025-04-30 06:32:50'),
(10, 'Garlic Butter Shrimp Scampi', 'images/shrimp-scampi.jpg', '4.9', 254, 10, 10, 4, 'Seafood', '2025-04-30 06:33:11'),
(11, 'Hearty Beef Chili', 'images/beef-chili.jpg', '4.7', 189, 20, 60, 6, 'Dinner', '2025-04-30 06:33:22'),
(12, 'Lemon Blueberry Scones', 'images/blueberry-scones.jpg', '4.8', 167, 15, 20, 8, 'Breakfast', '2025-04-30 06:33:33'),
(14, 'Grilled Chicken Caesar Salad', 'images/chicken-caesar.jpg', '4.7', 232, 20, 10, 4, 'Lunch', '2025-04-30 06:33:58'),
(15, 'Creamy Mushroom Risotto', 'images/mushroom-risotto.jpg', '5.0', 79, 15, 30, 4, 'Vegetarian', '2025-04-30 06:34:22'),
(16, 'Honey Glazed Salmon', 'images/honey-salmon.jpg', '5.0', 56, 10, 15, 4, 'Seafood', '2025-04-30 06:35:22'),
(17, 'Classic Lemon Curd Tart', 'images/lemon-tart.jpeg', '4.9', 195, 30, 45, 8, 'Dessert', '2025-04-22 06:50:27'),
(18, 'Southern Pimento Cheese', 'images/pimento-cheese.jpeg', '4.7', 142, 15, 0, 6, 'Appetizer', '2025-04-22 06:50:27'),
(19, 'Berry BLAST Pancakes', 'images/berry-blast.jpeg', '4.8', 178, 7, 15, 4, 'Breakfast', '2025-04-22 06:50:27');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_boards`
--

CREATE TABLE `recipe_boards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `recipe_boards`
--

INSERT INTO `recipe_boards` (`id`, `user_id`, `name`, `created_at`) VALUES
(1, 11, 'lunch', '2025-05-01 03:24:26'),
(2, 11, 'dinner', '2025-05-01 03:25:12'),
(3, 1, 'favorites', '2025-05-01 03:25:45'),
(4, 11, 'idk', '2025-05-02 21:28:10');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_details`
--

CREATE TABLE `recipe_details` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `recipe_details`
--

INSERT INTO `recipe_details` (`id`, `recipe_id`, `description`) VALUES
(1, 1, 'Sweet and spicy chicken with North African harissa paste and honey glaze, roasted to perfection with colorful vegetables.'),
(2, 2, 'Rich and creamy pasta with roasted garlic and freshly grated parmesan cheese, ready in under 30 minutes.'),
(3, 3, 'Seasoned ground beef with all the classic taco toppings in crispy corn tortillas.'),
(4, 4, 'Moist and tender banana bread with walnuts, perfect for using up ripe bananas.'),
(5, 5, 'Fresh and vibrant Mediterranean salad with crisp vegetables, feta, and kalamata olives.'),
(6, 6, 'Rich and creamy fettuccine alfredo with tender chicken breast and fresh parsley.'),
(7, 7, 'Colorful mix of fresh vegetables in a savory garlic-ginger sauce, ready in minutes.'),
(8, 8, 'Light and airy pancakes with crispy edges, perfect for weekend brunches.'),
(9, 9, 'Hearty Italian vegetable soup with beans, pasta and fresh herbs.'),
(10, 10, 'Juicy shrimp in a rich garlic butter sauce with white wine and lemon.'),
(11, 11, 'Classic comfort food with ground beef, beans and warm spices.'),
(12, 12, 'Tender scones bursting with blueberries and bright lemon flavor.'),
(14, 14, 'Classic salad with grilled chicken, crisp romaine and homemade dressing.'),
(15, 15, 'Rich and creamy Italian rice dish with wild mushrooms and parmesan.'),
(16, 16, 'Perfectly cooked salmon fillets with sweet and tangy honey glaze.'),
(17, 17, 'A tangy and sweet dessert with a buttery shortbread crust filled with smooth lemon curd, perfect for any occasion.'),
(18, 18, 'Creamy, tangy, and slightly spicy cheese spread that\'s a Southern classic, perfect for sandwiches or crackers.'),
(19, 19, 'Fluffy buttermilk pancakes loaded with mixed berries and topped with maple syrup for a perfect sweet breakfast.');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `amount` varchar(50) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `ingredient` varchar(255) NOT NULL,
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`id`, `recipe_id`, `amount`, `unit`, `ingredient`, `order`) VALUES
(1, 1, '4', '', 'chicken thighs (bone-in, skin-on)', 1),
(2, 1, '2', 'tbsp', 'harissa paste', 2),
(3, 1, '3', 'tbsp', 'honey', 3),
(4, 1, '2', 'tbsp', 'olive oil', 4),
(5, 1, '3', 'cloves', 'garlic, minced', 5),
(6, 1, '1', 'tsp', 'ground cumin', 6),
(7, 1, '1', 'tsp', 'paprika', 7),
(8, 1, '1', '', 'lemon, juiced', 8),
(9, 1, '1', '', 'red onion, cut into wedges', 9),
(10, 1, '2', '', 'bell peppers, sliced', 10),
(11, 1, NULL, NULL, 'salt and pepper to taste', 11),
(12, 1, '2', 'tbsp', 'fresh cilantro, chopped (for garnish)', 12),
(37, 5, '1', 'large', 'cucumber, diced', 1),
(38, 5, '4', '', 'roma tomatoes, diced', 2),
(39, 5, '1', '', 'red onion, thinly sliced', 3),
(40, 5, '1', '', 'green bell pepper, diced', 4),
(41, 5, '1', 'cup', 'kalamata olives', 5),
(42, 5, '8', 'oz', 'feta cheese, cubed', 6),
(43, 5, '2', 'tbsp', 'extra virgin olive oil', 7),
(44, 5, '1', 'tbsp', 'red wine vinegar', 8),
(45, 5, '1', 'tsp', 'dried oregano', 9),
(46, 5, NULL, NULL, 'salt and pepper to taste', 10),
(47, 6, '12', 'oz', 'fettuccine pasta', 1),
(48, 6, '2', '', 'boneless skinless chicken breasts', 2),
(49, 6, '1/2', 'tsp', 'garlic powder', 3),
(50, 6, NULL, NULL, 'salt and pepper to taste', 4),
(51, 6, '1/2', 'cup', 'butter', 5),
(52, 6, '2', 'cups', 'heavy cream', 6),
(53, 6, '1', 'clove', 'garlic, minced', 7),
(54, 6, '1 1/2', 'cups', 'grated parmesan cheese', 8),
(55, 6, '2', 'tbsp', 'fresh parsley, chopped', 9),
(56, 7, '2', 'tbsp', 'vegetable oil', 1),
(57, 7, '1', '', 'red bell pepper, sliced', 2),
(58, 7, '1', '', 'yellow bell pepper, sliced', 3),
(59, 7, '1', 'cup', 'broccoli florets', 4),
(60, 7, '1', 'cup', 'snow peas', 5),
(61, 7, '1', '', 'carrot, julienned', 6),
(62, 7, '2', 'cloves', 'garlic, minced', 7),
(63, 7, '1', 'tbsp', 'fresh ginger, grated', 8),
(64, 7, '3', 'tbsp', 'soy sauce', 9),
(65, 7, '1', 'tbsp', 'honey', 10),
(66, 7, '1', 'tsp', 'sesame oil', 11),
(67, 7, '1', 'tbsp', 'sesame seeds', 12),
(68, 8, '1 1/2', 'cups', 'all-purpose flour', 1),
(69, 8, '3', 'tbsp', 'sugar', 2),
(70, 8, '1', 'tsp', 'baking powder', 3),
(71, 8, '1/2', 'tsp', 'baking soda', 4),
(72, 8, '1/2', 'tsp', 'salt', 5),
(73, 8, '1 1/4', 'cups', 'buttermilk', 6),
(74, 8, '1', '', 'large egg', 7),
(75, 8, '3', 'tbsp', 'butter, melted', 8),
(76, 8, '1', 'tsp', 'vanilla extract', 9),
(77, 9, '2', 'tbsp', 'olive oil', 1),
(78, 9, '1', '', 'onion, diced', 2),
(79, 9, '2', '', 'carrots, diced', 3),
(80, 9, '2', 'stalks', 'celery, diced', 4),
(81, 9, '3', 'cloves', 'garlic, minced', 5),
(82, 9, '1', 'tsp', 'dried oregano', 6),
(83, 9, '1', 'tsp', 'dried basil', 7),
(84, 9, '1', 'can (28oz)', 'diced tomatoes', 8),
(85, 9, '6', 'cups', 'vegetable broth', 9),
(86, 9, '1', 'can (15oz)', 'kidney beans, drained', 10),
(87, 9, '1', 'cup', 'small pasta (ditalini)', 11),
(88, 9, '2', 'cups', 'chopped spinach', 12),
(89, 9, NULL, NULL, 'salt and pepper to taste', 13),
(90, 9, '1/4', 'cup', 'grated parmesan', 14),
(91, 10, '12', 'oz', 'linguine pasta', 1),
(92, 10, '1 1/2', 'lbs', 'large shrimp, peeled', 2),
(93, 10, '3', 'tbsp', 'butter', 3),
(94, 10, '2', 'tbsp', 'olive oil', 4),
(95, 10, '5', 'cloves', 'garlic, minced', 5),
(96, 10, '1/4', 'tsp', 'red pepper flakes', 6),
(97, 10, '1/2', 'cup', 'white wine', 7),
(98, 10, '1', '', 'lemon, juiced', 8),
(99, 10, '1/4', 'cup', 'parsley, chopped', 9),
(100, 10, NULL, NULL, 'salt and pepper to taste', 10),
(101, 11, '2', 'tbsp', 'olive oil', 1),
(102, 11, '1', '', 'onion, diced', 2),
(103, 11, '1', '', 'green bell pepper, diced', 3),
(104, 11, '2', 'lbs', 'ground beef', 4),
(105, 11, '3', 'cloves', 'garlic, minced', 5),
(106, 11, '2', 'tbsp', 'chili powder', 6),
(107, 11, '1', 'tbsp', 'cumin', 7),
(108, 11, '1', 'tsp', 'oregano', 8),
(109, 11, '1', 'can (28oz)', 'crushed tomatoes', 9),
(110, 11, '1', 'can (15oz)', 'kidney beans, drained', 10),
(111, 11, '1', 'can (15oz)', 'black beans, drained', 11),
(112, 11, '1', 'cup', 'beef broth', 12),
(113, 11, NULL, NULL, 'salt and pepper to taste', 13),
(114, 12, '2', 'cups', 'all-purpose flour', 1),
(115, 12, '1/4', 'cup', 'sugar', 2),
(116, 12, '1', 'tbsp', 'baking powder', 3),
(117, 12, '1/2', 'tsp', 'salt', 4),
(118, 12, '6', 'tbsp', 'cold butter, cubed', 5),
(119, 12, '1', '', 'lemon, zested', 6),
(120, 12, '1/2', 'cup', 'heavy cream', 7),
(121, 12, '1', '', 'large egg', 8),
(122, 12, '1', 'tsp', 'vanilla extract', 9),
(123, 12, '1', 'cup', 'fresh blueberries', 10),
(124, 12, '1', 'tbsp', 'coarse sugar (for topping)', 11),
(125, 14, '2', '', 'boneless chicken breasts', 1),
(126, 14, '1', 'tbsp', 'olive oil', 2),
(127, 14, NULL, NULL, 'salt and pepper', 3),
(128, 14, '1', 'large', 'romaine lettuce, chopped', 4),
(129, 14, '1/2', 'cup', 'grated parmesan', 5),
(130, 14, '1', 'cup', 'croutons', 6),
(131, 14, '1/2', 'cup', 'mayonnaise', 7),
(132, 14, '2', 'tbsp', 'lemon juice', 8),
(133, 14, '2', 'tsp', 'Dijon mustard', 9),
(134, 14, '2', 'cloves', 'garlic, minced', 10),
(135, 14, '2', 'tbsp', 'grated parmesan', 11),
(136, 14, '1', 'tsp', 'Worcestershire sauce', 12),
(137, 14, '1', 'tsp', 'anchovy paste (optional)', 13),
(138, 15, '6', 'cups', 'vegetable broth', 1),
(139, 15, '2', 'tbsp', 'olive oil', 2),
(140, 15, '1', '', 'shallot, diced', 3),
(141, 15, '2', 'cloves', 'garlic, minced', 4),
(142, 15, '1 1/2', 'cups', 'arborio rice', 5),
(143, 15, '1/2', 'cup', 'white wine', 6),
(144, 15, '8', 'oz', 'mixed mushrooms, sliced', 7),
(145, 15, '2', 'tbsp', 'butter', 8),
(146, 15, '1/2', 'cup', 'grated parmesan', 9),
(147, 15, '2', 'tbsp', 'fresh parsley, chopped', 10),
(148, 15, NULL, NULL, 'salt and pepper to taste', 11),
(149, 16, '4', '', 'salmon fillets (6oz each)', 1),
(150, 16, NULL, NULL, 'salt and pepper', 2),
(151, 16, '1/4', 'cup', 'honey', 3),
(152, 16, '2', 'tbsp', 'soy sauce', 4),
(153, 16, '1', 'tbsp', 'lemon juice', 5),
(154, 16, '1', 'tsp', 'Dijon mustard', 6),
(155, 16, '2', 'cloves', 'garlic, minced', 7),
(156, 16, '1', 'tsp', 'ginger, grated', 8),
(157, 16, '1', 'tbsp', 'olive oil', 9),
(158, 16, '1', 'tbsp', 'sesame seeds', 10),
(159, 16, '2', 'tbsp', 'green onions, sliced', 11),
(160, 17, '1 1/2', 'cups', 'all-purpose flour', 1),
(161, 17, '1/4', 'cup', 'powdered sugar', 2),
(162, 17, '1/2', 'cup', 'cold butter, cubed', 3),
(163, 17, '1', '', 'large egg yolk', 4),
(164, 17, '1', 'tbsp', 'ice water', 5),
(165, 17, '4', '', 'large eggs', 6),
(166, 17, '1', 'cup', 'granulated sugar', 7),
(167, 17, '1/2', 'cup', 'fresh lemon juice (about 3 lemons)', 8),
(168, 17, '1', 'tbsp', 'lemon zest', 9),
(169, 17, '1/4', 'cup', 'cold butter, cubed', 10),
(170, 17, NULL, NULL, 'powdered sugar for dusting', 11),
(171, 18, '8', 'oz', 'sharp cheddar cheese, shredded', 1),
(172, 18, '8', 'oz', 'monterey jack cheese, shredded', 2),
(173, 18, '4', 'oz', 'cream cheese, softened', 3),
(174, 18, '1/2', 'cup', 'mayonnaise', 4),
(175, 18, '4', 'oz', 'diced pimentos, drained', 5),
(176, 18, '1/2', 'tsp', 'garlic powder', 6),
(177, 18, '1/2', 'tsp', 'onion powder', 7),
(178, 18, '1/4', 'tsp', 'cayenne pepper', 8),
(179, 18, '1/2', 'tsp', 'hot sauce', 9),
(180, 18, NULL, NULL, 'salt and pepper to taste', 10),
(181, 19, '1 1/2', 'cups', 'all-purpose flour', 1),
(182, 19, '3', 'tbsp', 'granulated sugar', 2),
(183, 19, '1', 'tbsp', 'baking powder', 3),
(184, 19, '1/2', 'tsp', 'baking soda', 4),
(185, 19, '1/2', 'tsp', 'salt', 5),
(186, 19, '1 1/4', 'cups', 'buttermilk', 6),
(187, 19, '1', '', 'large egg', 7),
(188, 19, '3', 'tbsp', 'butter, melted', 8),
(189, 19, '1', 'cup', 'mixed berries (blueberries, raspberries, blackberries)', 9),
(190, 19, NULL, NULL, 'maple syrup for serving', 10),
(191, 19, NULL, NULL, 'additional berries for garnish', 11),
(192, 4, '3', '', 'very ripe bananas (about 1.5 cups mashed)', 1),
(193, 4, '1/3', 'cup', 'melted butter (unsalted)', 2),
(194, 4, '3/4', 'cup', 'granulated sugar', 3),
(195, 4, '1', '', 'large egg, beaten', 4),
(196, 4, '1', 'tsp', 'vanilla extract', 5),
(197, 4, '1', 'tsp', 'baking soda', 6),
(198, 4, '1/4', 'tsp', 'salt', 7),
(199, 4, '1 1/2', 'cups', 'all-purpose flour', 8),
(200, 4, '1/2', 'cup', 'chopped walnuts (optional)', 9),
(201, 4, '1/2', 'cup', 'chocolate chips (optional)', 10),
(202, 2, '12', 'oz', 'fettuccine pasta', 1),
(203, 2, '2', 'tbsp', 'olive oil', 2),
(204, 2, '6', 'cloves', 'garlic, minced', 3),
(205, 2, '1', 'cup', 'heavy cream', 4),
(206, 2, '1', 'cup', 'chicken broth', 5),
(207, 2, '1 1/2', 'cups', 'freshly grated parmesan cheese', 6),
(208, 2, '1/2', 'tsp', 'salt', 7),
(209, 2, '1/4', 'tsp', 'black pepper', 8),
(210, 2, '1/4', 'tsp', 'red pepper flakes (optional)', 9),
(211, 2, '2', 'tbsp', 'fresh parsley, chopped', 10),
(212, 3, '1', 'lb', 'ground beef (80/20)', 1),
(213, 3, '1', '', 'small onion, diced', 2),
(214, 3, '2', 'cloves', 'garlic, minced', 3),
(215, 3, '2', 'tbsp', 'taco seasoning', 4),
(216, 3, '1/2', 'cup', 'water', 5),
(217, 3, '12', '', 'corn tortillas', 6),
(218, 3, '2', 'cups', 'shredded lettuce', 7),
(219, 3, '1', 'cup', 'diced tomatoes', 8),
(220, 3, '1', 'cup', 'shredded cheddar cheese', 9),
(221, 3, '1/2', 'cup', 'sour cream', 10),
(222, 3, '1/4', 'cup', 'chopped cilantro', 11),
(223, 3, '1', '', 'lime, cut into wedges', 12),
(224, 3, NULL, NULL, 'hot sauce (optional)', 13),
(225, 3, '1', 'lb', 'ground beef (80/20)', 1),
(226, 3, '1', '', 'small onion, diced', 2),
(227, 3, '2', 'cloves', 'garlic, minced', 3),
(228, 3, '2', 'tbsp', 'taco seasoning', 4),
(229, 3, '1/2', 'cup', 'water', 5),
(230, 3, '12', '', 'corn tortillas', 6),
(231, 3, '2', 'cups', 'shredded lettuce', 7),
(232, 3, '1', 'cup', 'diced tomatoes', 8),
(233, 3, '1', 'cup', 'shredded cheddar cheese', 9),
(234, 3, '1/2', 'cup', 'sour cream', 10),
(235, 3, '1/4', 'cup', 'chopped cilantro', 11),
(236, 3, '1', '', 'lime, cut into wedges', 12),
(237, 3, NULL, NULL, 'hot sauce (optional)', 13);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_instructions`
--

CREATE TABLE `recipe_instructions` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `step_number` int(11) NOT NULL,
  `instruction` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `recipe_instructions`
--

INSERT INTO `recipe_instructions` (`id`, `recipe_id`, `step_number`, `instruction`) VALUES
(1, 1, 1, 'Preheat oven to 400°F (200°C). Pat chicken thighs dry and season with salt and pepper.'),
(2, 1, 2, 'In a bowl, whisk together harissa paste, honey, olive oil, garlic, cumin, paprika, and lemon juice.'),
(3, 1, 3, 'Toss chicken thighs with 3/4 of the sauce until fully coated. Reserve remaining sauce.'),
(4, 1, 4, 'Arrange chicken in a single layer in a baking dish. Scatter onions and bell peppers around.'),
(5, 1, 5, 'Roast for 25 minutes, then brush with reserved sauce. Continue roasting for 5-10 minutes until chicken reaches 165°F (74°C).'),
(6, 1, 6, 'Garnish with fresh cilantro and serve with roasted vegetables and couscous or rice.'),
(24, 5, 1, 'Combine cucumber, tomatoes, red onion, bell pepper, and olives in large bowl.'),
(25, 5, 2, 'Add feta cheese cubes and gently toss to combine.'),
(26, 5, 3, 'In small bowl, whisk together olive oil, vinegar, oregano, salt and pepper.'),
(27, 5, 4, 'Pour dressing over salad and toss gently to coat.'),
(28, 5, 5, 'Let stand for 10 minutes before serving to allow flavors to meld.'),
(29, 6, 1, 'Cook pasta according to package directions. Drain and set aside.'),
(30, 6, 2, 'Season chicken with garlic powder, salt and pepper. Cook in skillet over medium heat until no longer pink (6-7 min per side). Slice into strips.'),
(31, 6, 3, 'In same skillet, melt butter over medium heat. Add garlic and sauté 30 seconds.'),
(32, 6, 4, 'Pour in heavy cream and bring to simmer. Cook 3-4 minutes until slightly thickened.'),
(33, 6, 5, 'Reduce heat to low and gradually stir in parmesan until melted and smooth.'),
(34, 6, 6, 'Add cooked pasta and chicken to sauce, tossing to coat. Garnish with parsley.'),
(35, 7, 1, 'Heat oil in wok or large skillet over high heat.'),
(36, 7, 2, 'Add bell peppers, broccoli, snow peas, and carrot. Stir fry for 4-5 minutes until crisp-tender.'),
(37, 7, 3, 'Push vegetables to sides and add garlic and ginger to center. Cook 30 seconds until fragrant.'),
(38, 7, 4, 'In small bowl, whisk together soy sauce, honey, and sesame oil. Pour over vegetables.'),
(39, 7, 5, 'Toss everything together and cook 1 minute more. Sprinkle with sesame seeds before serving.'),
(40, 8, 1, 'Whisk dry ingredients together in large bowl.'),
(41, 8, 2, 'In separate bowl, whisk buttermilk, egg, melted butter and vanilla.'),
(42, 8, 3, 'Pour wet ingredients into dry ingredients and stir until just combined (batter will be lumpy).'),
(43, 8, 4, 'Heat griddle over medium heat and lightly grease with butter.'),
(44, 8, 5, 'Pour 1/4 cup batter per pancake. Cook until bubbles form and edges look set (2-3 min).'),
(45, 8, 6, 'Flip and cook other side until golden brown (1-2 min). Serve warm with maple syrup.'),
(46, 9, 1, 'Heat oil in large pot over medium heat. Add onion, carrots and celery. Cook 5 min until softened.'),
(47, 9, 2, 'Add garlic, oregano and basil. Cook 1 min until fragrant.'),
(48, 9, 3, 'Pour in tomatoes and broth. Bring to boil, then reduce heat and simmer 15 min.'),
(49, 9, 4, 'Add beans and pasta. Cook 10 min until pasta is al dente.'),
(50, 9, 5, 'Stir in spinach and cook 2 min until wilted. Season with salt and pepper.'),
(51, 9, 6, 'Serve hot with parmesan cheese on top.'),
(52, 10, 1, 'Cook pasta according to package directions. Drain and set aside.'),
(53, 10, 2, 'Pat shrimp dry and season with salt and pepper.'),
(54, 10, 3, 'Heat butter and oil in large skillet over medium-high. Add shrimp and cook 1-2 min per side until pink. Remove shrimp.'),
(55, 10, 4, 'In same skillet, add garlic and red pepper flakes. Cook 30 sec until fragrant.'),
(56, 10, 5, 'Pour in wine and lemon juice. Simmer 2-3 min until slightly reduced.'),
(57, 10, 6, 'Return shrimp to pan with parsley. Toss with cooked pasta and serve immediately.'),
(58, 11, 1, 'Heat oil in large pot over medium heat. Add onion and bell pepper. Cook 5 min until soft.'),
(59, 11, 2, 'Add ground beef and cook until browned, breaking up chunks. Drain excess fat.'),
(60, 11, 3, 'Stir in garlic and spices. Cook 1 min until fragrant.'),
(61, 11, 4, 'Add tomatoes, beans and broth. Bring to boil then reduce heat to simmer.'),
(62, 11, 5, 'Simmer uncovered for 45-60 min, stirring occasionally. Season with salt and pepper.'),
(63, 11, 6, 'Serve with shredded cheese, sour cream and chopped onions if desired.'),
(64, 12, 1, 'Preheat oven to 400°F (200°C). Line baking sheet with parchment.'),
(65, 12, 2, 'Whisk flour, sugar, baking powder and salt in large bowl.'),
(66, 12, 3, 'Cut in butter with pastry cutter until mixture resembles coarse crumbs. Stir in lemon zest.'),
(67, 12, 4, 'In small bowl, whisk cream, egg and vanilla. Add to flour mixture and stir until just combined.'),
(68, 12, 5, 'Gently fold in blueberries. Turn dough onto floured surface and pat into 8-inch circle.'),
(69, 12, 6, 'Cut into 8 wedges. Transfer to baking sheet, sprinkle with coarse sugar.'),
(70, 12, 7, 'Bake 18-20 min until golden. Cool 5 min before serving.'),
(71, 14, 1, 'Season chicken with salt and pepper. Grill over medium heat 5-6 min per side until cooked through. Slice.'),
(72, 14, 2, 'For dressing: whisk mayo, lemon juice, mustard, garlic, parmesan, Worcestershire and anchovy paste.'),
(73, 14, 3, 'In large bowl, toss lettuce with dressing to coat.'),
(74, 14, 4, 'Top with grilled chicken, croutons and additional parmesan. Serve immediately.'),
(75, 15, 1, 'Heat broth in saucepan and keep warm over low heat.'),
(76, 15, 2, 'In large pot, heat oil over medium. Add shallot and garlic. Cook 2 min until soft.'),
(77, 15, 3, 'Add rice and stir to coat. Toast 1-2 min until translucent around edges.'),
(78, 15, 4, 'Pour in wine and cook until absorbed, stirring constantly.'),
(79, 15, 5, 'Add mushrooms. Begin adding warm broth 1/2 cup at a time, stirring until absorbed before adding more.'),
(80, 15, 6, 'Continue process for 20-25 min until rice is al dente and creamy.'),
(81, 15, 7, 'Remove from heat. Stir in butter, parmesan and parsley. Season with salt and pepper.'),
(82, 16, 1, 'Preheat oven to 400°F (200°C). Line baking sheet with parchment.'),
(83, 16, 2, 'Pat salmon dry and season with salt and pepper. Arrange on baking sheet.'),
(84, 16, 3, 'In small bowl, whisk honey, soy sauce, lemon juice, mustard, garlic and ginger.'),
(85, 16, 4, 'Heat oil in small saucepan. Add glaze mixture and simmer 3-4 min until slightly thickened.'),
(86, 16, 5, 'Brush half of glaze over salmon. Bake 8 min. Brush with remaining glaze and bake 4-5 min more.'),
(87, 16, 6, 'Garnish with sesame seeds and green onions. Serve with rice and vegetables.'),
(88, 17, 1, 'For the crust: Pulse flour and powdered sugar in food processor. Add butter and pulse until mixture resembles coarse crumbs.'),
(89, 17, 2, 'Add egg yolk and ice water. Pulse until dough comes together. Form into disk, wrap in plastic, and chill 1 hour.'),
(90, 17, 3, 'Preheat oven to 375°F (190°C). Roll dough out and fit into 9-inch tart pan. Prick bottom with fork and freeze 15 minutes.'),
(91, 17, 4, 'Line crust with parchment and fill with pie weights. Bake 15 minutes. Remove weights and bake 10 more minutes until golden. Cool.'),
(92, 17, 5, 'For filling: Whisk eggs and sugar in saucepan. Add lemon juice and zest. Cook over medium heat, stirring constantly, until thickened (8-10 min).'),
(93, 17, 6, 'Remove from heat and stir in butter until melted. Strain through fine mesh sieve into prepared crust.'),
(94, 17, 7, 'Chill at least 4 hours until set. Dust with powdered sugar before serving.'),
(95, 18, 1, 'In large bowl, combine both shredded cheeses and cream cheese. Mix until well combined.'),
(96, 18, 2, 'Add mayonnaise, pimentos, garlic powder, onion powder, cayenne, and hot sauce. Stir until creamy.'),
(97, 18, 3, 'Season with salt and pepper to taste. Cover and refrigerate at least 1 hour before serving.'),
(98, 18, 4, 'Serve with crackers, on sandwiches, or as a dip with vegetables.'),
(99, 19, 1, 'In large bowl, whisk together flour, sugar, baking powder, baking soda, and salt.'),
(100, 19, 2, 'In separate bowl, whisk buttermilk, egg, and melted butter. Pour wet ingredients into dry ingredients and stir until just combined (batter will be lumpy).'),
(101, 19, 3, 'Gently fold in mixed berries. Let batter rest 5 minutes.'),
(102, 19, 4, 'Heat griddle or skillet over medium heat and lightly grease with butter or oil.'),
(103, 19, 5, 'Pour 1/4 cup batter per pancake onto griddle. Cook until bubbles form on surface and edges look set (2-3 minutes).'),
(104, 19, 6, 'Flip pancakes and cook other side until golden brown (1-2 minutes).'),
(105, 19, 7, 'Serve warm with maple syrup and additional berries.'),
(106, 4, 1, 'Preheat oven to 350°F (175°C). Grease a 9x5-inch loaf pan.'),
(107, 4, 2, 'In a large bowl, mash bananas with a fork until smooth. Stir in melted butter.'),
(108, 4, 3, 'Mix in sugar, beaten egg, and vanilla extract until well combined.'),
(109, 4, 4, 'Sprinkle baking soda and salt over mixture and stir to incorporate.'),
(110, 4, 5, 'Add flour and mix just until combined (do not overmix). Fold in walnuts or chocolate chips if using.'),
(111, 4, 6, 'Pour batter into prepared loaf pan. Bake for 50-60 minutes until a toothpick inserted comes out clean.'),
(112, 4, 7, 'Cool in pan for 10 minutes, then transfer to a wire rack to cool completely.'),
(113, 2, 1, 'Cook fettuccine according to package directions until al dente. Reserve 1/2 cup pasta water, then drain.'),
(114, 2, 2, 'Heat olive oil in large skillet over medium heat. Add garlic and sauté for 30 seconds until fragrant.'),
(115, 2, 3, 'Pour in heavy cream and chicken broth. Bring to a simmer and cook for 3-4 minutes until slightly reduced.'),
(116, 2, 4, 'Reduce heat to low and gradually whisk in parmesan cheese until melted and smooth.'),
(117, 2, 5, 'Season with salt, black pepper, and red pepper flakes (if using).'),
(118, 2, 6, 'Add drained pasta to sauce, tossing to coat. Add reserved pasta water 1 tbsp at a time if needed to thin sauce.'),
(119, 2, 7, 'Garnish with fresh parsley and serve immediately with extra parmesan.'),
(120, 3, 1, 'Heat large skillet over medium-high heat. Add ground beef and cook until browned, breaking into crumbles (5-6 min). Drain excess fat.'),
(121, 3, 2, 'Add onion and garlic to skillet. Cook 2-3 minutes until softened.'),
(122, 3, 3, 'Stir in taco seasoning and water. Simmer for 3-4 minutes until sauce thickens.'),
(123, 3, 4, 'Warm tortillas: Heat dry skillet over medium heat for 30 sec per side, or wrap in damp paper towels and microwave for 30 sec.'),
(124, 3, 5, 'Assemble tacos: Spoon beef onto tortillas. Top with lettuce, tomatoes, cheese, and other desired toppings.'),
(125, 3, 6, 'Serve immediately with lime wedges and hot sauce on the side.');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ratings`
--

CREATE TABLE `recipe_ratings` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recipe_ratings`
--

INSERT INTO `recipe_ratings` (`id`, `recipe_id`, `user_id`, `rating`, `created_at`) VALUES
(1, 16, 1, 5, '2025-05-02 23:39:52'),
(2, 15, 1, 5, '2025-05-02 23:46:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Emma', 'Johnson', 'emma_j', 'emma.johnson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-14 09:01:40'),
(2, 'Liam', 'Smith', 'liam_s', 'liam.smith@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-14 09:01:40'),
(3, 'Olivia', 'Williams', 'olivia_w', 'olivia.w@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-14 09:01:40'),
(11, 'Tina', 'Abdalla', 'tabdalla1', 'tabdalla12@example.com', '$2y$10$b9T.NqfqQNWHsVSEfnL1teCeYOI1BOkGCLXQqFunLaE6wOyBCw6se', '2025-04-16 08:35:51'),
(12, 'Sophia', 'Brown', 'sophia_b', 'sophia.brown@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-15 14:00:00'),
(13, 'Noah', 'Jones', 'noah_j', 'noah.jones@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-16 15:00:00'),
(14, 'Ava', 'Garcia', 'ava_g', 'ava.garcia@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-17 16:00:00'),
(15, 'William', 'Miller', 'will_m', 'william.miller@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-18 17:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `board_recipes`
--
ALTER TABLE `board_recipes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `board_recipe` (`board_id`,`recipe_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipe_boards`
--
ALTER TABLE `recipe_boards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recipe_details`
--
ALTER TABLE `recipe_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `recipe_instructions`
--
ALTER TABLE `recipe_instructions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `recipe_ratings`
--
ALTER TABLE `recipe_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `recipe_id` (`recipe_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `board_recipes`
--
ALTER TABLE `board_recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `recipe_boards`
--
ALTER TABLE `recipe_boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `recipe_details`
--
ALTER TABLE `recipe_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT for table `recipe_instructions`
--
ALTER TABLE `recipe_instructions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `recipe_ratings`
--
ALTER TABLE `recipe_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `board_recipes`
--
ALTER TABLE `board_recipes`
  ADD CONSTRAINT `board_recipes_ibfk_1` FOREIGN KEY (`board_id`) REFERENCES `recipe_boards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `board_recipes_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recipe_boards`
--
ALTER TABLE `recipe_boards`
  ADD CONSTRAINT `recipe_boards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recipe_details`
--
ALTER TABLE `recipe_details`
  ADD CONSTRAINT `recipe_details_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recipe_instructions`
--
ALTER TABLE `recipe_instructions`
  ADD CONSTRAINT `recipe_instructions_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recipe_ratings`
--
ALTER TABLE `recipe_ratings`
  ADD CONSTRAINT `recipe_ratings_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`),
  ADD CONSTRAINT `recipe_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
