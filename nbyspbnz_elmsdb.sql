-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 05, 2025 at 10:41 PM
-- Server version: 10.6.20-MariaDB-cll-lve
-- PHP Version: 8.3.15

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
(4, 'ad01', 'Md. Babul', 'Hossain', 'Babul123', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'bablu.pm@gmail.com', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', '2025-01-20 05:07:39'),
(6, 'ad02', 'Aeysha', 'Khanom Urmi\r\n', 'Urmi123', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'hr@nbyit.com', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', '2025-01-22 09:08:27'),
(7, 'ad03', 'Abdul', 'Kaium', 'Abdul_Kaium', 'e560e7cf33235e0d46bf235c6cd67bb8', 'akaium85@gmail.com', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', '2025-01-27 06:19:57');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `tblemployeelogs`
--

CREATE TABLE `tblemployeelogs` (
  `id` int(11) NOT NULL,
  `EmpId` int(11) NOT NULL,
  `LogDate` date NOT NULL DEFAULT curdate(),
  `LoginTime` time DEFAULT NULL,
  `LogoutTime` time DEFAULT NULL,
  `Timezone` varchar(50) NOT NULL DEFAULT 'UTC+6 BST'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblemployeelogs`
--

INSERT INTO `tblemployeelogs` (`id`, `EmpId`, `LogDate`, `LoginTime`, `LogoutTime`, `Timezone`) VALUES
(6, 49, '2025-01-23', '10:26:31', NULL, 'UTC+6 BST'),
(7, 43, '2025-01-23', '10:29:18', '10:29:28', 'UTC+6 BST'),
(10, 6, '2025-01-27', '02:23:42', '02:23:50', 'UTC+6 BST'),
(13, 41, '2025-01-27', '02:36:27', '02:36:33', 'UTC+6 BST'),
(14, 12, '2025-01-27', '03:03:59', NULL, 'UTC+6 BST'),
(15, 6, '2025-01-28', '10:07:31', NULL, 'UTC+6 BST'),
(16, 6, '2025-01-29', '10:08:11', '05:54:36', 'UTC+6 BST'),
(17, 6, '2025-01-30', '10:12:15', NULL, 'UTC+6 BST'),
(18, 39, '2025-02-04', '12:29:43', NULL, 'UTC+6 BST'),
(19, 40, '2025-02-04', '12:40:42', NULL, 'UTC+6 BST');

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
  `AnnualLeave` int(11) NOT NULL DEFAULT 0 CHECK (`AnnualLeave` BETWEEN 0 AND 1000),
  `SickLeave` int(11) NOT NULL DEFAULT 0 CHECK (`SickLeave` BETWEEN 0 AND 1000)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblemployees`
--

INSERT INTO `tblemployees` (`id`, `EmpId`, `FirstName`, `LastName`, `EmailId`, `Password`, `Gender`, `Dob`, `Department`, `Address`, `City`, `Country`, `Phonenumber`, `Username`, `Image`, `Status`, `RegDate`, `AnnualLeave`, `SickLeave`) VALUES
(6, 'nby30', 'Tareq', 'monower', 'tareq@nbyit.com', '0307eb0498c744fb1d336c546d5b33bb', 'Male', '1 January, 2000', 'Department of Web Development', '488 boro khan bari bokaul bari road', 'chandpur, Bangladesh', 'Bangladesh', '01714270830', 'Tareq123', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-12 09:39:14', 22, 7),
(12, 'nby19', 'AL MUKTADIR', 'AQUIBE', 'amaquibe@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', '29 December, 2001', 'Department of Marketing', 'MALIBAGH', 'DHAKA', 'Bangladesh', '1670966929', 'AQUIBE', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-16 04:59:40', 22, 7),
(32, 'nby07', 'Sheikh Md. Sanjidul', 'Hoque Sanjid', 'sanjid@nbyit.com', 'd8a3f50e1a7e064bb974faab94f180a9', 'Male', '17/12/2000', 'Department of Mimics', 'Kabilpur, House:SheikhBari, Senbagh, Noakhali', 'Noakhali', 'Bangladesh', '01580929401', 'sanjid', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(33, 'nby08', 'MD. Wadud', 'Rahman', 'wadud@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', 'ZIRANI BAZAR, BKSP, ASULIA, SAVAR, DHAKA', 'Dhaka', 'Bangladesh', '1787758812', 'Wadud', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(34, 'nby09', 'MD. Mursalin', NULL, 'mursalin@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', 'C/O: Abul Hossen, Sowdagor Bari, Vill: Khondakarabad, P.O: Wazedia, P.S: Bayezid Bostami, Dist.: Chattogram.', 'Chattogram', 'Bangladesh', '1878791035', 'mursalin', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(35, 'nby10', 'Shamim', 'Uddin', 'shamim@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', 'Village: Gullakhali, P.O : Surjamukhi, P.S :Hatiya, Noakhali.', 'Noakhali', 'Bangladesh', '1787758812', 'shamim', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(36, 'nby11', 'MD. Abu', 'Zahed', 'zahed@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', 'Vill: Nekmorad, P.O: Nekmorad, P.S: Ranisankail, Dist: Thakurgaon', 'Thakurgaon', 'Bangladesh', '1870044821', 'zahed', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(37, 'nby12', 'MD. Shahidul', 'Islam', 'shahidul@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', '18/24 ,East Badda,Koborsthan road, Badda,Dhaka', 'Dhaka', 'Bangladesh', '1914446848', 'shahidul', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(38, 'nby13', 'Mst.', 'Afsana', 'afsana@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Female', NULL, 'Department of Mimics', 'Vill: Lalbag Club Mor, Post: Lalbag Club Mor, Kotwali, Dinajpur.', 'Dinajpur', 'Bangladesh', '1719498657', 'afsana', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(39, 'nby14', 'Md. Khaza Mainuddin', 'Akram', 'akram@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', 'Vill: Palishara, P.S: Hazigong, Chandpur', 'Chandpur', 'Bangladesh', '1758132122', 'akram', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(40, 'nby15', 'Robiul', 'Islam', 'robiul@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', 'Village: Bacharigram, Post: Aledabpur, Post code: 6500, Naogoan Sadar, Naogaon', 'Naogaon', 'Bangladesh', '1739467922', 'robiul', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 21, 7),
(41, 'nby18', 'Raqibul', 'Mia', 'raqib@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', '30-06-1998', 'Department of Marketing', 'Sultanpur, Khalilpur, Rajbari Sarad, Rajbari', 'Faridpur', 'Bangladesh', '01768044211', 'Raqib4you', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(42, 'nby20', 'Md. Daniel', 'Araphat', 'araphat@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', '1 January, 2000', 'Department of Mimics', 'Housing colony, Santahar, Bogra, Rajshahi', 'Rajshahi', 'Bangladesh', '1744847794', 'daniel', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(43, 'nby21', 'Kamrul', 'Hasan', 'kamrul@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', 'Vill: Choto hajipur, Khutupara, Post: Bishnupur, Thana: Badarganj, Dist: Rangpur', NULL, 'Bangladesh', '1303651447', 'kamrul_hasan', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(44, 'nby22', 'Abdullah Al Maruf', 'Sojib', 'maruf@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', 'Holding: Purton bazar, Village: Purton bazar Birampur, Post: Birampur, Birampur Powrashoba, Dinajpur', 'Dinajpur', 'Bangladesh', '1761119773', 'maruf', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(45, 'nby24', 'Md. Mehedi', 'Hasan', 'mehedi@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of 3D', '11, Mahadinagor, Kamrangirchor, Dhaka.', 'Dhaka', 'Bangladesh', '1751072034', 'mehedi', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(46, 'nby25', 'Md. Foaz', 'Ahmed', 'foaz@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of 3D', '115/1 Fulbaria Road, Mymensingh', 'Mymensingh', 'Bangladesh', '1682944177', 'foaz', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(47, 'nby26', 'MD. Biplob', 'Hosen', 'biplob@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', 'Purbo goalpara, Thakurgaon sadar, Thakurgaon', 'Thakurgaon', 'Bangladesh', NULL, 'biplob', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(48, 'nby27', 'Adal Faiyaz', 'Ahmed', 'faiyaz@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', '11 Segun Bagicha, Dhaka -1205', 'Dhaka', 'Bangladesh', '1723427891', 'faiyaz', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(49, 'nby28', 'Abu', 'Hanif', 'hanif@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of 3D', 'Khejurbagh, South Keraniganj, Dhaka-1310', 'Dhaka', 'Bangladesh', '1953304333', 'hanif', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7),
(50, 'nby29', 'Md Al-', 'Amin', 'alamin@nbyit.com', 'e1d0cc48bbd022ba68ce8d3d457e9afb', 'Male', NULL, 'Department of Mimics', 'V-sare egaro rosi lopti Kandi, P/O-Matbor Chor, P/S-Shibchor, Madaripur', 'Madaripur', 'Bangladesh', '1642318835', 'alamin', 'https://nbyit.com/wp-content/uploads/2019/05/cropped-n-logo-1.png', 1, '2025-01-22 09:11:22', 22, 7);

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
(24, 'Annual Leave', '2025-02-09', '2025-02-09', '1 Day Leave', '2025-01-28 10:30:34', '', '2025-02-04 16:36:53', 1, 1, 32, 'sanjid', 'sanjid@nbyit.com', '01580929401', '0 days'),
(25, 'Annual Leave', '2025-02-10', '2025-02-09', 'i Want to go home thats why i need 1 day leave.', '2025-02-04 06:28:53', '', '2025-02-04 16:37:20', 1, 1, 40, 'robiul', 'robiul@nbyit.com', '1739467922', '1 days'),
(27, 'Sick Leave', '2025-02-04', '2025-02-03', 'i was sick', '2025-02-05 11:09:00', NULL, NULL, 0, 0, 38, 'afsana', 'afsana@nbyit.com', '1719498657', '1 days');

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
-- Indexes for table `tblemployeelogs`
--
ALTER TABLE `tblemployeelogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_log` (`EmpId`,`LogDate`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT for table `tblemployeelogs`
--
ALTER TABLE `tblemployeelogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tblemployees`
--
ALTER TABLE `tblemployees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tblleaves`
--
ALTER TABLE `tblleaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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

--
-- Constraints for table `tblemployeelogs`
--
ALTER TABLE `tblemployeelogs`
  ADD CONSTRAINT `tblemployeelogs_ibfk_1` FOREIGN KEY (`EmpId`) REFERENCES `tblemployees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
