# 🎓 UniPath – Education Agency Management System

## 📌 Overview

UniPath is a web-based Education Agency Management System designed to streamline student application processing, administrative workflows, and communication between students and agency staff.

This system helps manage student records, applications, and administrative tasks efficiently through a centralized platform.

---

## 🚀 Features

* 👤 User Authentication (Admin / Users)
* 🧾 Student Application Management
* 📊 Admin Dashboard
* 🗂️ Data Management System
* 🔐 Secure Environment Configuration using `.env`
* 🌐 Web-based interface accessible from anywhere

---

## 🛠️ Tech Stack

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Database:** MySQL
* **Version Control:** Git & GitHub

---

## ⚙️ Installation & Setup

### 1. Clone the repository

```bash
git clone https://github.com/ansafahd27/unipath-education-agency-management-system.git
cd unipath-education-agency-management-system
```

---

### 2. Setup Environment Variables

Create a `.env` file:

```env
DB_HOST=your_host
DB_USER=your_username
DB_PASS=your_password
DB_NAME=your_database
```

---

### 3. Setup Database

* Create a MySQL database
* Import the provided `.sql` file

---

### 4. Run the Project

* Place project in `htdocs` (XAMPP) OR deploy to hosting
* Start Apache & MySQL
* Open in browser:

```
http://localhost/project-folder
```

---

## 🔐 Security Notes

* `.env` file is ignored for security reasons
* Use prepared statements to prevent SQL injection
* Passwords should be hashed using `password_hash()`

---

## 📸 Screenshots (Optional)

*Add screenshots of your system here*

---

## 🚧 Future Improvements

* API integration
* Role-based access control enhancements
* Performance optimization
* UI/UX improvements

---

## 👨‍💻 Author

**Ansaf Ahamed**
Undergraduate – Electronics & Computer Science

---

## ⭐ Contributing

Contributions are welcome! Feel free to fork and improve the system.

---

## 📄 License

This project is for educational purposes.
