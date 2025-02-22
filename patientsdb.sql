CREATE DATABASE hospital_soap_system;
USE hospital_soap_system;

-- Patients Table
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    sex ENUM('Male', 'Female', 'Other') NOT NULL,
    dob DATE NOT NULL,
    age INT AS (TIMESTAMPDIFF(YEAR, dob, CURRENT_DATE)),
    contact VARCHAR(15) NOT NULL UNIQUE
);

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Doctor', 'Nurse', 'Receptionist', 'Patient') NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contact VARCHAR(15) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    specialty VARCHAR(255) NULL
);

-- Appointments Table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_name VARCHAR(255) NOT NULL,
    appointment_date DATETIME NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('Scheduled', 'Completed', 'Cancelled') DEFAULT 'Scheduled',
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);

-- Medical Records Table
CREATE TABLE IF NOT EXISTS medical_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    subjective TEXT,
    objective TEXT,
    assessment TEXT,
    plan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
);

-- SOAP Notes Table
CREATE TABLE IF NOT EXISTS soap_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    subjective TEXT,
    objective TEXT,
    assessment TEXT,
    plan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);

-- Insert sample data into patients (10 records)
INSERT INTO patients (full_name, sex, dob, contact) VALUES
('Frank Ocean', 'Male', '1987-10-28', '09222654467'),
('Monkey D. Luffy', 'Male', '1997-05-05', '09123456701'),
('Roronoa Zoro', 'Male', '1996-11-11', '09123456702'),
('Nami', 'Female', '1997-07-03', '09123456703'),
('Usopp', 'Male', '1997-04-01', '09123456704'),
('Sanji', 'Male', '1996-03-02', '09123456705'),
('Tony Tony Chopper', 'Male', '2002-12-24', '09123456706'),
('Nico Robin', 'Female', '1988-02-06', '09123456707'),
('Brook', 'Male', '1950-04-03', '09123456708'),
('Jinbei', 'Male', '1980-04-02', '09123456709');

-- Insert sample data into users (10 records)
INSERT INTO users (username, password, role, email, contact) VALUES
('admin01', 'password123', 'Admin', 'admin01@example.com', '09123450001'),
('dr_law', 'docpass321', 'Doctor', 'law@example.com', '09123450002'),
('nurse_nami', 'nami456', 'Nurse', 'nami@example.com', '09123450003'),
('reception_sanji', 'sanji789', 'Receptionist', 'sanji@example.com', '09123450004'),
('patient_luffy', 'luffy000', 'Patient', 'luffy@example.com', '09123450005'),
('dr_chopper', 'chopper123', 'Doctor', 'chopper@example.com', '09123450006'),
('nurse_robin', 'robin456', 'Nurse', 'robin@example.com', '09123450007'),
('reception_brook', 'brook789', 'Receptionist', 'brook@example.com', '09123450008'),
('patient_zoro', 'zoro999', 'Patient', 'zoro@example.com', '09123450009'),
('patient_nami', 'nami888', 'Patient', 'nami@example.com', '09123450010');

-- Insert sample data into appointments (10 records)
INSERT INTO appointments (patient_id, doctor_id, appointment_date, status, notes) VALUES
(2, 2, '2025-02-20 10:00:00', 'Scheduled', 'Routine checkup'),
(3, 2, '2025-02-21 11:30:00', 'Scheduled', 'Follow-up for injury'),
(4, 6, '2025-02-22 14:00:00', 'Completed', 'General consultation'),
(5, 6, '2025-02-23 09:45:00', 'Scheduled', 'Skin allergy checkup'),
(6, 2, '2025-02-24 08:30:00', 'Scheduled', 'Physical therapy session'),
(7, 6, '2025-02-25 10:15:00', 'Scheduled', 'Cold and flu symptoms'),
(8, 2, '2025-02-26 16:00:00', 'Completed', 'Routine pregnancy checkup'),
(9, 6, '2025-02-27 12:00:00', 'Cancelled', 'Cancelled due to patient unavailability'),
(10, 2, '2025-02-28 13:30:00', 'Scheduled', 'Dental checkup'),
(2, 6, '2025-03-01 15:45:00', 'Scheduled', 'Back pain consultation');

-- Insert sample data into medical_records (10 records)
INSERT INTO medical_records (appointment_id, subjective, objective, assessment, plan) VALUES
(1, 'Patient complains of headache and fever.', 'Temperature: 38.2°C', 'Possible viral infection.', 'Prescribed rest and hydration.'),
(2, 'Patient reports persistent shoulder pain.', 'Limited range of motion in left arm.', 'Suspected muscle strain.', 'Advised physiotherapy.'),
(3, 'Routine checkup. No major concerns.', 'Blood pressure: 120/80 mmHg.', 'Patient is in good health.', 'Advised regular exercise and balanced diet.'),
(4, 'Itchy skin and rash for a week.', 'Visible redness and inflammation.', 'Suspected allergic reaction.', 'Advised topical cream.'),
(5, 'Patient has mild fever and sore throat.', 'Temperature: 37.8°C', 'Possible flu.', 'Advised rest and hydration.'),
(6, 'Patient reports frequent headaches.', 'No abnormalities in vital signs.', 'Possible tension headaches.', 'Recommended lifestyle changes.'),
(7, 'Routine prenatal checkup.', 'Fetal heart rate normal.', 'Healthy pregnancy.', 'Scheduled next checkup in four weeks.'),
(8, 'Severe toothache for three days.', 'Swelling in lower jaw.', 'Possible dental infection.', 'Referred to a dentist.'),
(9, 'Back pain after lifting heavy objects.', 'Tenderness in lower back.', 'Possible muscle strain.', 'Advised physical therapy.'),
(10, 'Mild stomach discomfort.', 'No abnormalities detected.', 'Possible indigestion.', 'Advised dietary adjustments.');

-- Insert sample data into soap_notes (1 record)
INSERT INTO soap_notes (patient_id, subjective, objective, assessment, plan)
VALUES 
(1, 'Patient complains of headaches and dizziness.', 
   'Blood pressure: 140/90 mmHg, Heart rate: 88 bpm.', 
   'Hypertension stage 1, needs lifestyle modification.', 
   'Advised dietary changes, follow-up in 2 weeks.');
   
ALTER TABLE users ADD COLUMN specialty VARCHAR(255) NULL;

select * from patients, appointments, medications, medical_records, prescriptions, billing;


ALTER TABLE appointments ADD FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE;
ALTER TABLE appointments ADD FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE;
ALTER TABLE medical_records ADD FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE;
