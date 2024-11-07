-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2024 at 11:17 AM
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
-- Database: `viswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `emailid` varchar(40) NOT NULL,
  `Password` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`emailid`, `Password`) VALUES
('viswa@gmail.com', '111');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `Regno` bigint(14) NOT NULL,
  `core-1` int(3) NOT NULL,
  `core-2` int(3) NOT NULL,
  `core-3` int(3) NOT NULL,
  `elective` int(3) NOT NULL,
  `non_elective` int(3) NOT NULL,
  `behaviour` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`Regno`, `core-1`, `core-2`, `core-3`, `elective`, `non_elective`, `behaviour`) VALUES
(20234012404229, 56, 32, 0, 65, 0, 'VeryGood');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `Regno` bigint(14) NOT NULL,
  `sub_code` int(3) NOT NULL,
  `int_mark` int(3) NOT NULL,
  `ext_mark` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`Regno`, `sub_code`, `int_mark`, `ext_mark`) VALUES
(20234012404229, 123, 10, 55);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffid` int(3) NOT NULL,
  `Password` varchar(25) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Phone_no` bigint(10) NOT NULL,
  `email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffid`, `Password`, `Name`, `Phone_no`, `email`) VALUES
(100, '111', 'Rajesh', 9894766506, 'rajesh@gmail.com'),
(101, '123', 'balu', 9894766506, 'balu@gmail.com'),
(120, 'jjj', 'Viswanathan', 9894766506, 'rajesh@gmail.com'),
(156, 'vnnjgjg', 'Velmurugan', 8482659875, 'vel@gmail.com'),
(15666, '123456789', 'umar', 9894766506, 'vel23@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `Regno` bigint(14) NOT NULL,
  `Name` varchar(25) NOT NULL,
  `DOB` varchar(20) NOT NULL,
  `Class` varchar(10) NOT NULL,
  `Phone_no` varchar(10) NOT NULL,
  `email` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`Regno`, `Name`, `DOB`, `Class`, `Phone_no`, `email`) VALUES
(20234012404226, 'Vicky', '2024-08-07', 'MCA-B', '9894677652', 'vv@gmail.com'),
(20234012404228, 'Vel murugan M', '2024-07-06', 'MCA-B', '9874563215', 'velu@gmail.com'),
(20234012404229, 'Viswanathan S', '2024-07-25', 'MCA-B', '8428152797', 'viswa0274@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `staffid` int(3) NOT NULL,
  `class` varchar(20) NOT NULL,
  `sem` varchar(10) NOT NULL,
  `sub_code` int(5) NOT NULL,
  `sub_type` varchar(30) NOT NULL,
  `sub_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`staffid`, `class`, `sem`, `sub_code`, `sub_type`, `sub_name`) VALUES
(100, 'MCA-A', 'sem-3', 111, 'core-2', 'python'),
(101, 'MCA-B', '2', 301, 'elective', 'softcomputing');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`emailid`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`Regno`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffid`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`Regno`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
