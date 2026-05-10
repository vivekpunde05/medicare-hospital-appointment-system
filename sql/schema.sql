-- MediCare Appointment System Database Schema
-- Doctors table
CREATE TABLE doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    availability VARCHAR(255) NOT NULL,
    image_path VARCHAR(255)
);

-- Appointments table
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    doctor VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    time_slot VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Patients table (optional auth)
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample doctor seed data
INSERT INTO doctors (name, specialization, availability, image_path) VALUES
    ('Dr. Aryan Mhatre',    'General Physician', 'Mon-Fri 9am-5pm',        'images/doctors/dr-mhatre.svg'),
    ('Dr. Rohit Dongre',    'Dentist',           'Mon-Wed-Fri 10am-6pm',   'images/doctors/dr-dongre.svg'),
    ('Dr. Shrutesh Wadibhasme',    'Cardiologist','Tue-Thu 8am-4pm',       'images/doctors/dr-shrutesh.svg'),
    ('Dr. Rahul Kokate ',   'General Physician', 'Mon-Sat 9am-1pm',        'images/doctors/dr-kokate.svg'),
    ('Dr. Vinay Todkar', 'Dentist',           'Tue-Thu-Sat 11am-7pm',      'images/doctors/dr-todkar.svg');

-- Sample doctor seed data

INSERT INTO doctors (name, specialization, availability, image_path) VALUES
    ('Dr. Aryan Mhatre',    'General Physician', 'Mon-Fri 9am-5pm',        'images/doctors/dr-mhatre.svg'),
    ('Dr. Rohit Dongre',    'Dentist',           'Mon-Wed-Fri 10am-6pm',   'images/doctors/dr-dongre.svg'),
    ('Dr. Shrutesh Wadibhasme',    'Cardiologist','Tue-Thu 8am-4pm',       'images/doctors/dr-shrutesh.svg'),
    ('Dr. Rahul Kokate',   'General Physician', 'Mon-Sat 9am-1pm',         'images/doctors/dr-kokate.svg'),
    ('Dr. Vinay Todkar', 'Dentist',           'Tue-Thu-Sat 11am-7pm',      'images/doctors/dr-todkar.svg');
