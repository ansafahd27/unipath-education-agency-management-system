CREATE DATABASE IF NOT EXISTS unipath_db;
USE unipath_db;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Hashed Password
    role ENUM('student', 'admin') NOT NULL DEFAULT 'student'
);

CREATE TABLE student_profiles ( 
    profile_id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT NOT NULL, 
    full_name VARCHAR(100), 
    phone VARCHAR(20), 
    date_of_birth DATE, 
    address TEXT, 
    target_destination VARCHAR(50), 
    target_field VARCHAR(50), 
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE 
);

CREATE TABLE universities (
    uni_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo_image VARCHAR(255),
    description TEXT,
    ranking INT,
    website_url VARCHAR(255),
    country_name VARCHAR(50) NOT NULL,
    cost_of_living TEXT,
    created_by INT, 
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    uni_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    level ENUM('Undergraduate', 'Postgraduate', 'Diploma', 'PhD'),
    field_of_study VARCHAR(100),
    duration VARCHAR(50),
    tuition_fee DECIMAL(10, 2),
    created_by INT, 
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (uni_id) REFERENCES universities(uni_id) ON DELETE CASCADE
);

CREATE TABLE appointments (
    appt_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    service_type VARCHAR(100), -- Matches the Service Name
    description TEXT,          -- New column for student notes
    appt_date DATE NOT NULL,
    appt_time TIME NOT NULL,
    mode ENUM('In-person', 'Video', 'Phone') NOT NULL,
    status ENUM('Pending', 'Confirmed', 'Completed', 'Cancelled') DEFAULT 'Pending',
    FOREIGN KEY (student_id) REFERENCES student_profiles(profile_id) ON DELETE CASCADE
);

CREATE TABLE saved_universities (
    save_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    uni_id INT NOT NULL,
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student_profiles(profile_id) ON DELETE CASCADE,
    FOREIGN KEY (uni_id) REFERENCES universities(uni_id) ON DELETE CASCADE
);

CREATE TABLE blog_posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    publisher_id INT, 
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    category VARCHAR(50),
    featured_image VARCHAR(255), 
    published_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (publisher_id) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE success_stories (
    story_id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT NOT NULL,
    student_name VARCHAR(100),
    student_image VARCHAR(255),
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    category VARCHAR(50), 
    published_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT, 
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (university_id) REFERENCES universities(uni_id) ON DELETE CASCADE
);

CREATE TABLE services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,          
    description LONGTEXT NOT NULL,                    
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE inquiries (
    inquiry_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150),
    message TEXT NOT NULL,
    status ENUM('Pending', 'Responded') DEFAULT 'Pending',
    handled_by INT DEFAULT NULL, 
    FOREIGN KEY (handled_by) REFERENCES users(user_id) ON DELETE SET NULL
);