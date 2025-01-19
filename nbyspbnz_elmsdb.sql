-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2025 at 12:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nbyspbnz_elmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `adid` varchar(50) NOT NULL,
  `FirstName` varchar(150) NOT NULL,
  `LastName` varchar(150) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `EmailId` varchar(120) NOT NULL,
  `Image` varchar(255) NOT NULL DEFAULT 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png',
  `updationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `adid`, `FirstName`, `LastName`, `UserName`, `Password`, `EmailId`, `Image`, `updationDate`) VALUES
(3, 'ad30', 'Tareq', 'Monower', 'Tareq', '0307eb0498c744fb1d336c546d5b33bb', 'tareq21@nbyit.com', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', '2025-01-16 09:07:36');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `empId` int(11) NOT NULL,
  `complaint_title` varchar(255) NOT NULL,
  `complaint` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `empId`, `complaint_title`, `complaint`, `created_at`) VALUES
(11, 6, 'test complaint form', 'test complaint form test complaint form test complaint form', '2025-01-19 11:13:53');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `department_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `subject`, `title`, `description`, `file_path`, `created_at`, `updated_at`, `status`, `department_id`) VALUES
(1, 'Upcoming Workshop', 'Workshop on Team Collaboration', 'We are excited to announce a workshop on team collaboration strategies. All employees are encouraged to attend. Date: March 15, 2024. Time: 10:00 AM - 12:00 PM. Venue: Conference Room A.', 'https://chatgpt.com/c/67873e75-857c-8013-9297-feea5aeb710c', '2025-01-14 19:13:57', '2025-01-19 11:26:14', '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tasklist`
--

CREATE TABLE `tasklist` (
  `id` int(11) NOT NULL,
  `EmpId` int(11) NOT NULL,
  `TaskName` varchar(255) NOT NULL,
  `TaskDescription` varchar(255) NOT NULL,
  `Status` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `Progress` int(3) DEFAULT 0,
  `Notes` text DEFAULT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `CreatedAt` timestamp NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tasklist`
--

INSERT INTO `tasklist` (`id`, `EmpId`, `TaskName`, `TaskDescription`, `Status`, `Progress`, `Notes`, `StartDate`, `EndDate`, `CreatedAt`, `UpdatedAt`) VALUES
(24, 30, 'Demo Task', 'This is a demo task for testing purposes.', '3', 0, NULL, '2025-01-15', '2025-01-20', '2025-01-14 18:00:00', '2025-01-19 06:54:33'),
(25, 6, 'website development', 'Website development work', '2', 0, '', '2025-01-19', '2025-01-23', '2025-01-19 06:39:05', '2025-01-19 07:04:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartments`
--

CREATE TABLE `tbldepartments` (
  `id` int(11) NOT NULL,
  `DepartmentName` varchar(150) DEFAULT NULL,
  `DepartmentShortName` varchar(100) DEFAULT NULL,
  `DepartmentCode` varchar(50) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbldepartments`
--

INSERT INTO `tbldepartments` (`id`, `DepartmentName`, `DepartmentShortName`, `DepartmentCode`, `CreationDate`) VALUES
(1, 'Department of HR', 'HR', 'HR01', '2023-08-31 14:50:20'),
(6, 'Department of Mimics', 'Mimics', 'MI01', '2025-01-12 09:42:09'),
(7, 'Department of 3D', '3D', '3D01', '2025-01-12 09:47:30'),
(8, 'Department of Marketing', 'Marketing', 'MR01', '2025-01-12 09:48:57'),
(9, 'Department of Web Development', 'Web dev', 'WD01', '2025-01-12 09:50:29');

-- --------------------------------------------------------

--
-- Table structure for table `tblemployees`
--

CREATE TABLE `tblemployees` (
  `id` int(11) NOT NULL,
  `EmpId` varchar(100) NOT NULL,
  `FirstName` varchar(150) DEFAULT NULL,
  `LastName` varchar(150) DEFAULT NULL,
  `EmailId` varchar(200) DEFAULT NULL,
  `Password` varchar(180) DEFAULT NULL,
  `Gender` varchar(100) DEFAULT NULL,
  `Dob` varchar(100) DEFAULT NULL,
  `Department` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(200) DEFAULT NULL,
  `Country` varchar(150) DEFAULT NULL,
  `Phonenumber` char(11) DEFAULT NULL,
  `Username` varchar(100) NOT NULL,
  `Image` varchar(255) NOT NULL DEFAULT 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png',
  `Status` int(1) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `AnnualLeave` int(11) DEFAULT NULL,
  `SickLeave` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblemployees`
--

INSERT INTO `tblemployees` (`id`, `EmpId`, `FirstName`, `LastName`, `EmailId`, `Password`, `Gender`, `Dob`, `Department`, `Address`, `City`, `Country`, `Phonenumber`, `Username`, `Image`, `Status`, `RegDate`, `AnnualLeave`, `SickLeave`) VALUES
(6, '30', 'Tareq', 'monower', 'tamimhasancu@gmail.com', '0307eb0498c744fb1d336c546d5b33bb', 'Male', '1 January, 2000', 'Department of Web Development', '488 boro khan bari bokaul bari road', 'chandpur, Bangladesh', 'Bangladesh', '01714270830', 'Tareq123', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-12 09:39:14', 20, 2),
(12, '19', 'AL MUKTADIR', 'AQUIBE', 'amaquibe@nbyit.com', '9964310d569df626a09449a2c9c328b4', 'Male', '29 December, 2001', 'Department of Marketing', 'MALIBAGH', 'DHAKA', 'Bangladesh', '1670966929', 'AQUIBE', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-16 04:59:40', 21, 7);

-- --------------------------------------------------------

--
-- Table structure for table `tblleaves`
--

CREATE TABLE `tblleaves` (
  `id` int(11) NOT NULL,
  `LeaveType` varchar(110) DEFAULT NULL,
  `ToDate` varchar(120) DEFAULT NULL,
  `FromDate` varchar(120) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `AdminRemark` mediumtext DEFAULT NULL,
  `AdminRemarkDate` varchar(120) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `IsRead` int(1) DEFAULT NULL,
  `empid` int(11) DEFAULT NULL,
  `Username` varchar(100) NOT NULL,
  `EmailId` varchar(200) DEFAULT NULL,
  `Phonenumber` char(11) DEFAULT NULL,
  `Duration` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblleaves`
--

INSERT INTO `tblleaves` (`id`, `LeaveType`, `ToDate`, `FromDate`, `Description`, `PostingDate`, `AdminRemark`, `AdminRemarkDate`, `Status`, `IsRead`, `empid`, `Username`, `EmailId`, `Phonenumber`, `Duration`) VALUES
(13, 'Sick Leave', '2025-03-03', '2025-01-01', 'Feeling unwell and need to rest.', '2025-01-13 06:05:53', 'Approved by admin.', '2025-01-13 11:36:27', 1, 1, 6, 'Tareq123', 'tamimhasancu@gmail.com', '1714270830', '2 days'),
(16, 'Annual Leave', '2025-01-16', '2025-01-14', 'i am sick', '2025-01-13 11:02:04', 'approved', '2025-01-14 9:45:06 ', 1, 1, 6, 'Tareq123', 'tamimhasancu@gmail.com', '01714270830', '2 days'),
(17, 'Annual Leave', '2025-01-18', '2025-01-15', 'i need leave', '2025-01-14 04:55:10', 'not approved', '2025-01-14 11:13:48 ', 2, 1, 6, 'Tareq123', 'tamimhasancu@gmail.com', '01714270830', '3 days'),
(18, 'Annual Leave', '2025-01-20', '2025-01-18', 'i need leave', '2025-01-14 05:18:39', 'Rejected', '2025-01-14 11:34:58 ', 2, 1, 6, 'Tareq123', 'tamimhasancu@gmail.com', '01714270830', '2 days'),
(19, 'Sick Leave', '2025-01-18', '2025-01-15', 'i need leave', '2025-01-15 11:54:28', '', '2025-01-16 8:49:19 ', 1, 1, 6, 'Tareq123', 'tamimhasancu@gmail.com', '01714270830', '3 days'),
(20, 'Annual Leave', '2025-01-21', '2025-01-20', 'exam leave', '2025-01-19 04:51:48', '', '2025-01-19 11:25:05', 1, 1, 12, 'AQUIBE', 'amaquibe@nbyit.com', '1670966929', '1 days');

-- --------------------------------------------------------

--
-- Table structure for table `tblleavetype`
--

CREATE TABLE `tblleavetype` (
  `id` int(11) NOT NULL,
  `LeaveType` varchar(200) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblleavetype`
--

INSERT INTO `tblleavetype` (`id`, `LeaveType`, `Description`, `CreationDate`) VALUES
(1, 'Annual Leave', 'Annual Leave', '2023-08-31 14:52:22'),
(3, 'Sick Leave', 'Sick Leave', '2023-08-31 14:53:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empId` (`empId`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasklist`
--
ALTER TABLE `tasklist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblemployees`
--
ALTER TABLE `tblemployees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblleaves`
--
ALTER TABLE `tblleaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UserEmail` (`empid`);

--
-- Indexes for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tasklist`
--
ALTER TABLE `tasklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblemployees`
--
ALTER TABLE `tblemployees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblleaves`
--
ALTER TABLE `tblleaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`empId`) REFERENCES `tblemployees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
