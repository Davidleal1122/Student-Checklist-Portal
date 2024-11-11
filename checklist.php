<?php
include "connection.php";

// Function to fetch data and store it in an array
function fetchData($connection, $query) {
    $result = mysqli_query($connection, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

// Fetching data and averages for each year and semester
$fetchQueries = [
    'First Year' => [
        'First Semester' => ['data' => 'firstYear_firstSemester', 'total_units_hours' => 'totalUnitHours_firstyear_firstsemester'],
        'Second Semester' => ['data' => 'firstyear_secondsemester', 'total_units_hours' => 'totalUnitHours_firstyear_secondsemester']
    ],
    'Second Year' => [
        'First Semester' => ['data' => 'secondyear_firstsemester', 'total_units_hours' => 'totalUnitHours_secondyear_firstsemester'],
        'Second Semester' => ['data' => 'secondyear_secondsemester', 'total_units_hours' => 'totalUnitHours_secondyear_secondsemester']
    ],
    'Third Year' => [
        'First Semester' => ['data' => 'thirdyear_firstsemester', 'total_units_hours' => 'totalUnitHours_thirdyear_firstsemester'],
        'Second Semester' => ['data' => 'thirdyear_secondsemester', 'total_units_hours' => 'totalUnitHours_thirdyear_secondsemester'],
        'Mid-Year' => ['data' => 'thirdyear_midyear', 'total_units_hours' => 'totalUnitHours_thirdyear_midyear']
    ],
    'Fourth Year' => [
        'First Semester' => ['data' => 'fourthyear_firstsemester', 'total_units_hours' => 'totalUnitHours_fourthyear_firstsemester'],
        'Second Semester' => ['data' => 'fourthyear_secondsemester', 'total_units_hours' => 'totalUnitHours_fourthyear_secondsemester']
    ]
];

$data = [];
foreach ($fetchQueries as $year => $semesters) {
    foreach ($semesters as $semester => $tables) {
        $data[$year][$semester] = fetchData($connection, "SELECT * FROM {$tables['data']}");
        $totalUnitHours[$year][$semester] = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM {$tables['total_units_hours']}"));
    }
}

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
    <title>Checklist</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="stylesheet.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body class="checklist">
<div class="menu-btn">
  <i class="fas fa-bars"></i>
</div>
<div class="side-bar">
  <h1>STUDENT CHECKLIST</h1>
  <div class="student-info">
      <?php foreach ($student_info as $key) { ?>
          <p><strong></strong> <?php echo htmlspecialchars($key['Student Number']); ?></p>
          <p><strong></strong> <?php echo htmlspecialchars($key['Name of Student']); ?></p>
        
      <?php } ?>
  </div>
  <div class="divider"></div>
  <div class="close-btn">
    <i class="fas fa-times"></i>
  </div>
  <div class="menu">
    <div class="item">
      <a class="sub-btn" onclick="showTable('First Year', ['First Semester', 'Second Semester'])">
        <i class="fas fa-table"></i>FIRST YEAR<i class="fas fa-angle-right dropdown"></i>
      </a>
      <div class="sub-menu">
        <a href="#" class="sub-item" onclick="showTable('First Year', 'First Semester')">First Semester</a>
        <a href="#" class="sub-item" onclick="showTable('First Year', 'Second Semester')">Second Semester</a>
      </div>
    </div>
    <div class="item">
        <a class="sub-btn" onclick="showTable('Second Year', ['First Semester', 'Second Semester'])">
        <i class="fas fa-table"></i>SECOND YEAR<i class="fas fa-angle-right dropdown"></i>
      </a>
      <div class="sub-menu">
        <a href="#" class="sub-item" onclick="showTable('Second Year', 'First Semester')">First Semester</a>
        <a href="#" class="sub-item" onclick="showTable('Second Year', 'Second Semester')">Second Semester</a>
      </div>
    </div>
    <div class="item">
        <a class="sub-btn" onclick="showTable('Third Year', ['First Semester', 'Second Semester', 'Mid-Year'])">
        <i class="fas fa-table"></i>THIRD YEAR<i class="fas fa-angle-right dropdown"></i>
      </a>
      <div class="sub-menu">
        <a href="#" class="sub-item" onclick="showTable('Third Year', 'First Semester')">First Semester</a>
        <a href="#" class="sub-item" onclick="showTable('Third Year', 'Second Semester')">Second Semester</a>
        <a href="#" class="sub-item" onclick="showTable('Third Year', 'Mid-Year')">Mid-Year</a>
      </div>
    </div>
    <div class="item">
        <a class="sub-btn" onclick="showTable('Fourth Year', ['First Semester', 'Second Semester'])">
        <i class="fas fa-table"></i>FOURTH YEAR<i class="fas fa-angle-right dropdown"></i>
      </a>
      <div class="sub-menu">
        <a href="#" class="sub-item" onclick="showTable('Fourth Year', 'First Semester')">First Semester</a>
        <a href="#" class="sub-item" onclick="showTable('Fourth Year', 'Second Semester')">Second Semester</a>
      </div>
    </div>
   

    <div class="divider"></div>
  </div>
</div>
<div class="checklist-container" id="mainContainer">
        <div class="headerLabel card text-monospace">
            <h1>CHECKLIST OF COURSES</h1>
        </div>
        <div class="search-container">
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control" placeholder="Year level, Semester, Year taken, Course Code/Title, Grade, Instructor, Status/Remarks ">
                <div class="input-group-append">
                    <button class="btn btn-primary" onclick="performSearch()">Search</button>
                </div>
            </div>
            <div id="errorMessage" class="card text-center text-danger" style="display: none;">
                No matching data found.
            </div>
        </div>
        <div class="content-wrapper">
    <?php foreach ($data as $year => $semesters): ?>
        <?php foreach ($semesters as $semester => $courses): ?>
            <div class='tableContainer'>
                <div class='headerLabel2'>
                    <h2><?php echo $year; ?></h2>
                </div>
                <h3><?php echo $semester; ?></h3>

                <table class='checklistTable table table-striped table-hover'>
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Title</th>
                            <th>Credit Unit Lecture</th>
                            <th>Credit Unit Laboratory</th>
                            <th>Contact Hours Lecture</th>
                            <th>Contact Hours Laboratory</th>
                            <th>Prerequisite</th>
                            <th>Year Level</th>
                            <th>Semester</th>
                            <th>Academic Year</th>
                            <th>Final Grade</th>
                            <th>Instructor</th>
                            <th>Status/Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalCreditsLecture = $totalCreditsLab = $totalHoursLecture = $totalHoursLab = $totalGrades = $gradeCount = 0;
                        ?>
                        <?php foreach ($courses as $course): ?>
                            <?php
                            $totalCreditsLecture += (int)$course['Credit Unit Lecture'];
                            $totalCreditsLab += (int)$course['Credit Unit Laboratory'];
                            $totalHoursLecture += (int)$course['Contact Hours Lecture'];
                            $totalHoursLab += (int)$course['Contact Hours Laboratory'];

                            if (is_numeric($course['Final Grade']) && $course['Final Grade'] !== 'S') {
                                $totalGrades += (float)$course['Final Grade'];
                                $gradeCount++;
                            }
                            ?>
                            <tr>
                                <td><?php echo $course['Course Code']; ?></td>
                                <td><?php echo $course['Course Title']; ?></td>
                                <td><?php echo $course['Credit Unit Lecture']; ?></td>
                                <td><?php echo $course['Credit Unit Laboratory']; ?></td>
                                <td><?php echo $course['Contact Hours Lecture']; ?></td>
                                <td><?php echo $course['Contact Hours Laboratory']; ?></td>
                                <td><?php echo $course['Prerequisite']; ?></td>
                                <td><?php echo $course['Year Level']; ?></td>
                                <td><?php echo $course['Semester']; ?></td>
                                <td><?php echo $course['Academic Year']; ?></td>
                                <td><?php echo $course['Final Grade']; ?></td>
                                <td><?php echo $course['Instructor']; ?></td>
                                <td><?php echo $course['Status/Remarks']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan='2'><strong>Sub-Total:</strong></td>
                            <td><?php echo $totalCreditsLecture; ?></td>
                            <td><?php echo $totalCreditsLab; ?></td>
                            <td><?php echo $totalHoursLecture; ?></td>
                            <td><?php echo $totalHoursLab; ?></td>
                            <td></td>
                            <td colspan='3'><strong>Average: <?php echo $gradeCount > 0 ? number_format($totalGrades / $gradeCount, 2) : 0; ?></strong></td>
                            <td><?php echo $course['Year Level']; ?></td>
                            <td><?php echo $course['Semester']; ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </div>
  

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
<script src="script.js"></script>


</body>
</html>
