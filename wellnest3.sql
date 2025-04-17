-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 15, 2025 at 09:51 PM
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
-- Database: `wellnest3`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `PatientID` int(11) DEFAULT NULL,
  `DoctorID` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `reason` text NOT NULL,
  `status` enum('Pending','Confirmed','Done') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `PatientID`, `DoctorID`, `date`, `time`, `reason`, `status`) VALUES
(30, 5, 7, '2025-03-21', '10:00:00', 'Consultation', 'Confirmed'),
(31, 4, 8, '2025-03-25', '14:00:00', 'Follow-up', 'Confirmed'),
(32, 6, 9, '2025-03-13', '09:30:00', 'Check-up', 'Done'),
(33, 5, 10, '2025-03-11', '10:00:00', 'Consultation', 'Confirmed'),
(35, 4, 7, '2025-03-20', '10:00:00', 'Consultation', 'Confirmed'),
(36, 5, 8, '2025-03-22', '14:00:00', 'Follow-up', 'Done'),
(38, 4, 10, '2025-03-19', '10:00:00', 'Consultation', 'Confirmed'),
(39, 5, 11, '2025-03-22', '07:00:00', 'Follow-up', 'Done'),
(40, 5, 7, '2025-03-12', '10:00:00', 'Consultation', 'Confirmed'),
(41, 6, 8, '2025-03-10', '12:00:00', 'Follow-up', 'Done'),
(42, 6, 9, '2025-03-09', '09:30:00', 'Check-up', 'Done'),
(43, 4, 10, '2025-03-01', '07:00:00', 'Consultation', 'Confirmed'),
(45, 5, 10, '2025-03-21', '10:00:00', 'Consultation', 'Done'),
(46, 4, 10, '2025-04-08', '14:19:00', 'follow-up', 'Pending'),
(47, 7, 8, '2025-04-11', '23:30:00', 'Check-up', 'Pending'),
(48, 8, 11, '2025-04-25', '23:43:00', 'check up', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `uniqueFileName` varchar(255) NOT NULL,
  `SpecialityID` int(11) DEFAULT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `firstName`, `lastName`, `uniqueFileName`, `SpecialityID`, `emailAddress`, `password`) VALUES
(7, 'Saud', 'Mohammed', '67dc6527281d7_doctor.png', 1, 'saud_mohammed@example.com', '$2y$10$qg4ztomG4Zbjs8avldeMjeLYLnnOWEew1IHsv2GgXEgRb9kzgEj86'),
(8, 'Walaa', 'Saif', '67dc6acf1cb43_doctor3.jpeg', 1, 'walaa_saif@example.com', '$2y$10$M6sg2pesAJxULhDVo7NF4el6alZCn5z6ckRG5uqPZUwfYHQ/IFriq'),
(9, 'Lana', 'Mohammed', '67dc6d8c92dbc_doctor3.jpeg', 1, 'lana_mohammed@example.com', '$2y$10$XLvOpWfF/sQL2vGiZ8SrmOr0tLLi6A1HJ0m961o4t3LT0jaianZdO'),
(10, 'Hatoun', 'Ibrahim', '67dc6e190afa1_doctor3.jpeg', 4, 'hatoun_ibrahim@example.com', '$2y$10$R8nq2LrGSTPoz8y.Y/ZEFemidIVp4tq2L4H1jjkoml5FrknpNiROO'),
(11, 'Maha', 'Albaker', '67dc6e500f654_doctor3.jpeg', 2, 'maha_albaker@example.com', '$2y$10$BowHf7BAydC.qGmRJndt1Ob7vmEpB6vpvh9qTNbz0Zk9Rf2u9MELC'),
(12, 'Danah', 'Ali', '67dc7c737ed20_doctor2.jpeg', 3, 'danah_ali@example.com', '$2y$10$MtZ11buAC3Xtd1kPNB.MHOLVX6rtH3bGIavaCQcbhvLz8KRkPOSaS');

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

CREATE TABLE `medication` (
  `id` int(11) NOT NULL,
  `MedicationName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medication`
--

INSERT INTO `medication` (`id`, `MedicationName`) VALUES
(1, 'Paracetamol'),
(2, 'Ibuprofen'),
(3, 'Fluoxteine');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `Gender` enum('Male','Female','Other') NOT NULL,
  `DoB` date NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `firstName`, `lastName`, `Gender`, `DoB`, `emailAddress`, `password`) VALUES
(4, 'Ola', 'Samer', 'Female', '2006-02-08', 'ola_samer@exampl.com', '$2y$10$yRffHrzzWomNebwmDb6hGuTHzi.RvckM8pLgjLngXkziRY/9H6Xzy'),
(5, 'Ali', 'Kareem', 'Male', '2016-02-16', 'ali_kareem@exampl.com', '$2y$10$zOiNeokuMCHSv6pv137.9O2ayOWBLfiDmI4zsN.d9dtUgDPXTs1sO'),
(6, 'Wesam', 'Talal', 'Male', '2005-03-16', 'wesam_talal@exampl.com', '$2y$10$vqJoQZ5.r1CJD6umM1YcEud633iHzxGynaYUE5TBvPjFAm38Yowda'),
(7, 'Sami', 'Wesam', 'Male', '1998-07-04', 'Sami_Wesam@example.com', '$2y$10$cEfp57UiwjAY3qbSCNOqquuJDGSHp.93Xg6wzrVwgYjN5Kwc1l20q'),
(8, 'Naz', 'Nouil', 'Female', '1996-05-06', 'Naz222@gmail.com', '$2y$10$BxJBYbARkFfyHvdN5rjQ5u8xv6kftCLdRlvYkMfbLlm2GuqlOH/4W');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `id` int(11) NOT NULL,
  `AppointmentID` int(11) DEFAULT NULL,
  `MedicationID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`id`, `AppointmentID`, `MedicationID`) VALUES
(1, 41, 1),
(2, 41, 1),
(3, 41, 2),
(4, 41, 1),
(5, 41, 2),
(6, 36, 2),
(7, 36, 3),
(8, 36, 1),
(9, 36, 2);

-- --------------------------------------------------------

--
-- Table structure for table `speciality`
--

CREATE TABLE `speciality` (
  `id` int(11) NOT NULL,
  `speciality` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `speciality`
--

INSERT INTO `speciality` (`id`, `speciality`) VALUES
(1, 'Dental'),
(2, 'Dermatology'),
(3, 'Ophthalmology'),
(4, 'Psychology');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `DoctorID` (`DoctorID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`),
  ADD KEY `SpecialityID` (`SpecialityID`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `AppointmentID` (`AppointmentID`),
  ADD KEY `MedicationID` (`MedicationID`);

--
-- Indexes for table `speciality`
--
ALTER TABLE `speciality`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `speciality`
--
ALTER TABLE `speciality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`DoctorID`) REFERENCES `doctor` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`SpecialityID`) REFERENCES `speciality` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`AppointmentID`) REFERENCES `appointment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`MedicationID`) REFERENCES `medication` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
