<?php
include "connection.php";

// Fetch student information
$selectStudent = "SELECT * FROM student_info";
$query = mysqli_query($connection, $selectStudent);

$student_info = array();
while ($row = mysqli_fetch_assoc($query)) {
    $student_info[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="stylesheet.css"> 
</head>
<body>
 
  <!-- Header -->
  <div class = "checklist-container">
        <div class = "header">
            <img src = "images/cvsu_logo.png" alt = "CVSU Logo">
            <h3>Republic of the Philippines</h3>
            <h3>CAVITE STATE UNIVERSITY</h3>
            <h3>Bacoor City Campus</h3>
        </div>  
        <div class = "header-program">
            <h3>Bachelor of Science in Computer Science</h3>
            <h3>(Program)</h3>
        </div>

        <div class="studentTable card mx-4 my-4">
           
           <!-- Student Table -->
           <table class="studentTable table table-striped table-hover">
               <thead>
                   <tr>
                       <th scope="col">Student Number</th>
                       <th scope="col">Name of Student</th>
                       <th scope="col">Student Email</th>
                       <th scope="col">Date of Admission</th>
                       <th scope="col">Contact Number</th>
                       <th scope="col">Address</th>
                   </tr>
               </thead>
               <tbody>
                   <?php foreach ($student_info as $key) { ?>
                       <tr>
                           <td><?php echo htmlspecialchars($key['Student Number']); ?></td>
                           <td><?php echo htmlspecialchars($key['Name of Student']); ?></td>
                           <td><?php echo htmlspecialchars($key['Student Email']); ?></td>
                           <td><?php echo htmlspecialchars($key['Date of Admission']); ?></td>
                           <td><?php echo htmlspecialchars($key['Contact Number']); ?></td>
                           <td><?php echo htmlspecialchars($key['Address']); ?></td>
                       </tr>
                   <?php } ?>
               </tbody>
           </table>
           <a href="checklist.php" class="viewButton btn btn-primary">View</a>
       </div>

</body>
</html>
