-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2025 at 10:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unipath_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appt_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `appt_date` date NOT NULL,
  `appt_time` time NOT NULL,
  `mode` enum('In-person','Video','Phone') NOT NULL,
  `status` enum('Pending','Confirmed','Completed','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `post_id` int(11) NOT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `published_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`post_id`, `publisher_id`, `title`, `content`, `category`, `featured_image`, `published_date`) VALUES
(3, 1, 'Complete Guide to the UK Tier 4 Student Visa', 'Applying for a UK visa can be daunting. This guide breaks down the financial requirements, CAS number importance, and interview tips to help you succeed.', 'Visa Advice', 'uploads/blogs/uk_visa_guide.jpg', '2025-12-20 10:51:04'),
(4, 1, 'Top 5 Cities for International Students in Canada', 'From Toronto to Vancouver, discover which Canadian cities offer the best mix of education quality, lifestyle, and post-study work opportunities.', 'Country Guides', 'uploads/blogs/canada_cities.jpg', '2025-12-20 10:51:04'),
(5, 1, 'How to Score 8.0+ in IELTS: Expert Tips', 'Struggling with the speaking section? Our expert tutors share the secret techniques to boost your band score in just 4 weeks.', 'Test Preparation', 'uploads/blogs/ielts_tips.jpg', '2025-12-20 10:51:04'),
(6, 1, 'Packing Checklist for Your Move Abroad', 'Don’t forget the essentials! Download our ultimate pre-departure checklist covering documents, clothing, electronics, and currency.', 'Student Life', 'uploads/blogs/packing_list.jpg', '2025-12-20 10:51:04'),
(7, 1, 'Part-Time Work Rights in Australia Explained', 'Understanding your work rights is crucial. Learn about the 48-hour fortnightly limit and how to find genuine part-time jobs in Sydney and Melbourne.', 'Career Advice', 'uploads/blogs/aus_work.jpg', '2025-12-20 10:51:04');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `uni_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` enum('Undergraduate','Postgraduate','Diploma','PhD') DEFAULT NULL,
  `field_of_study` varchar(100) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `tuition_fee` decimal(10,2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `uni_id`, `name`, `level`, `field_of_study`, `duration`, `tuition_fee`, `created_by`) VALUES
(1, 1, 'BSc Computer Science', 'Undergraduate', 'IT', '3 Years', 15000.00, 1),
(2, 1, 'MSc Data Science', 'Postgraduate', 'IT', '1 Year', 18000.00, 1),
(3, 2, 'MBA Business Admin', 'Postgraduate', 'Business', '2 Years', 25000.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `inquiry_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('Pending','Responded') DEFAULT 'Pending',
  `handled_by` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`inquiry_id`, `name`, `email`, `subject`, `message`, `status`, `handled_by`) VALUES
(2, 'Ansaf Ahamed', 'ansafahd27@gmail.com', 'Counseling Appoiment Fees', 'I need to know the price details for a counseling session', 'Pending', 1);

-- --------------------------------------------------------

--
-- Table structure for table `saved_universities`
--

CREATE TABLE `saved_universities` (
  `save_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `uni_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `name`, `description`, `created_by`) VALUES
(1, 'University Selection Guidance', 'Expert advice on choosing the right university based on your academic profile and career goals.', 1),
(2, 'Course Recommendation', 'Personalized course matching to align with your interests and job market trends.', 1),
(3, 'Application Assistance', 'Step-by-step support in filling out applications to ensure zero errors.', 1),
(4, 'Scholarship Consultation', 'Guidance on finding and applying for financial aid and scholarships.', 1),
(5, 'Visa Application Support', 'Comprehensive assistance with visa documentation and interview preparation.', 1),
(6, 'Pre-departure Briefing', 'Essential sessions on what to pack, cultural etiquette, and travel tips.', 1),
(7, 'Accommodation Assistance', 'Help in finding safe and affordable student housing near your university.', 1),
(8, 'Test Preparation Guidance', 'Resources and tips for IELTS, TOEFL, GRE, and GMAT exams.', 1),
(9, 'Career Counselling', 'Long-term career planning to help you choose a path with high employability.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_profiles`
--

CREATE TABLE `student_profiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `target_destination` varchar(50) DEFAULT NULL,
  `target_field` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_profiles`
--

INSERT INTO `student_profiles` (`profile_id`, `user_id`, `full_name`, `phone`, `date_of_birth`, `address`, `target_destination`, `target_field`) VALUES
(1, 2, 'Abdul Azeez Ansaf Ahamed', '0704083598', '2003-03-27', 'no 107, ash-suhatha road, kattankudy', 'United States', 'Computer Science');

-- --------------------------------------------------------

--
-- Table structure for table `success_stories`
--

CREATE TABLE `success_stories` (
  `story_id` int(11) NOT NULL,
  `university_id` int(11) NOT NULL,
  `student_name` varchar(100) DEFAULT NULL,
  `student_image` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `published_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `success_stories`
--

INSERT INTO `success_stories` (`story_id`, `university_id`, `student_name`, `student_image`, `title`, `content`, `category`, `published_date`, `created_by`) VALUES
(1, 3, 'Ansaf Ahamed', '../uploads/testimonials/img_6947de681d4134.92242028.jpg', 'Ansaf joins Kelaniya!', 'I am so happy to start my journey in Kelani...', 'Placement', '2025-12-18 11:00:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `uni_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `logo_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ranking` int(11) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `country_name` varchar(50) NOT NULL,
  `cost_of_living` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`uni_id`, `name`, `logo_image`, `description`, `ranking`, `website_url`, `country_name`, `cost_of_living`, `created_by`) VALUES
(1, 'University of Westminster', 'img_69481d168ea2d9.17649187.png', 'Located in the heart of London.', 15, 'www.westminster.ac.uk', 'United Kingdom', '£1,200/month', 1),
(2, 'University of Toronto', 'img_69481cc7221b46.33899785.png', 'Top public research university in Canada.', 5, 'www.utoronto.ca', 'Canada', 'CAD 1,500/month', 1),
(3, 'University of Kelaniya', 'kelaniya.png', 'The University of Kelaniya is a public university located in Sri Lanka, just outside the city of Colombo. It was established in 1978 and has its origins in the historic Vidyalankara Pirivena, founded in 1875. The university offers a wide range of programs across seven faculties, including Commerce & Management Studies, Humanities, Science, and Social Sciences. It is known for its innovative educational approach and has a strong emphasis on research and development. The university has approximately 14,500 full-time undergraduate and 3,500 postgraduate students enrolled in various programs.', 1501, NULL, 'Sri Lanka', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin123@gmail.com', 'admin123', 'admin'),
(2, 'Abdul Azeez Ansaf Ahamed', 'ansafahd27@gmail.com', 'ansaf123', 'student'),
(3, 'rila', 'rila@gmail.com', 'rila123', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appt_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `publisher_id` (`publisher_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `uni_id` (`uni_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`inquiry_id`),
  ADD KEY `handled_by` (`handled_by`);

--
-- Indexes for table `saved_universities`
--
ALTER TABLE `saved_universities`
  ADD PRIMARY KEY (`save_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `uni_id` (`uni_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `success_stories`
--
ALTER TABLE `success_stories`
  ADD PRIMARY KEY (`story_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `university_id` (`university_id`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`uni_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `inquiry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `saved_universities`
--
ALTER TABLE `saved_universities`
  MODIFY `save_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `student_profiles`
--
ALTER TABLE `student_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `success_stories`
--
ALTER TABLE `success_stories`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `uni_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_profiles` (`profile_id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`publisher_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`uni_id`) REFERENCES `universities` (`uni_id`) ON DELETE CASCADE;

--
-- Constraints for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD CONSTRAINT `inquiries_ibfk_1` FOREIGN KEY (`handled_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `saved_universities`
--
ALTER TABLE `saved_universities`
  ADD CONSTRAINT `saved_universities_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_profiles` (`profile_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_universities_ibfk_2` FOREIGN KEY (`uni_id`) REFERENCES `universities` (`uni_id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD CONSTRAINT `student_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `success_stories`
--
ALTER TABLE `success_stories`
  ADD CONSTRAINT `success_stories_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `success_stories_ibfk_2` FOREIGN KEY (`university_id`) REFERENCES `universities` (`uni_id`) ON DELETE CASCADE;

--
-- Constraints for table `universities`
--
ALTER TABLE `universities`
  ADD CONSTRAINT `universities_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
